<?php

namespace Tests\Feature;

use App\Mail\BackupCompleted;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Process;
use Tests\TestCase;

class RunBackupCommandTest extends TestCase
{
    private string $backupDir;

    protected function setUp(): void
    {
        parent::setUp();

        $this->backupDir = storage_path('app/backups');
        File::ensureDirectoryExists($this->backupDir);

        // Clean slate: remove any leftover backups from previous test
        // runs or manual `php artisan backup:run` calls, so pruning
        // assertions below aren't thrown off by unrelated files.
        $this->clearBackupDir();
    }

    protected function tearDown(): void
    {
        $this->clearBackupDir();

        parent::tearDown();
    }

    private function clearBackupDir(): void
    {
        collect(File::files($this->backupDir))
            ->filter(fn ($file) => str_ends_with($file->getFilename(), '.zip'))
            ->each(fn ($file) => File::delete($file->getRealPath()));
    }

    /**
     * Intercepts the pg_dump call so the test doesn't depend on a real
     * PostgreSQL client being installed, and writes a small placeholder
     * file at the path pg_dump would have written to, so the zip step
     * downstream has something real to archive.
     */
    private function fakePgDump(): void
    {
        Process::fake(function ($process) {
            foreach ($process->command as $arg) {
                if (is_string($arg) && str_starts_with($arg, '--file=')) {
                    file_put_contents(substr($arg, 7), "-- fake dump for testing\n");
                }
            }

            return Process::result(exitCode: 0);
        });
    }

    public function test_command_creates_a_zip_archive_and_emails_it(): void
    {
        $this->fakePgDump();
        Mail::fake();

        $this->artisan('backup:run')->assertExitCode(0);

        $zips = collect(File::files($this->backupDir))
            ->filter(fn ($file) => str_ends_with($file->getFilename(), '.zip'));

        $this->assertCount(1, $zips);

        Mail::assertSent(BackupCompleted::class);
    }

    public function test_command_fails_gracefully_when_pg_dump_errors(): void
    {
        Process::fake(fn () => Process::result(exitCode: 1, errorOutput: 'connection refused'));
        Mail::fake();

        $this->artisan('backup:run')->assertExitCode(1);

        $zips = collect(File::files($this->backupDir))
            ->filter(fn ($file) => str_ends_with($file->getFilename(), '.zip'));

        $this->assertCount(0, $zips);
        Mail::assertNotSent(BackupCompleted::class);
    }

    public function test_old_backups_beyond_retention_limit_are_pruned(): void
    {
        // Seed 9 fake old backups (retention limit is 7), each with a
        // distinct modification time so the "oldest first" pruning
        // order is deterministic.
        for ($i = 0; $i < 9; $i++) {
            $path = "{$this->backupDir}/backup-fake-{$i}.zip";
            file_put_contents($path, 'fake zip content');
            touch($path, now()->subDays(9 - $i)->timestamp);
        }

        $this->fakePgDump();
        Mail::fake();

        $this->artisan('backup:run')->assertExitCode(0);

        $zips = collect(File::files($this->backupDir))
            ->filter(fn ($file) => str_ends_with($file->getFilename(), '.zip'));

        // 9 seeded fakes + 1 real one just created by this run = 10
        // total before pruning; only the 7 most recent survive.
        $this->assertCount(7, $zips);
    }
}

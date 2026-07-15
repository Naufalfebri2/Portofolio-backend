<?php

namespace App\Console\Commands;

use App\Mail\BackupCompleted;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Process;
use ZipArchive;

class RunBackup extends Command
{
    protected $signature = 'backup:run';

    protected $description = 'Backup the database and uploaded files, then email the archive.';

    /**
     * Number of past backups to keep on disk. Older ones are pruned
     * automatically so storage doesn't grow unbounded over time.
     */
    private const KEEP_LAST = 7;

    public function handle(): int
    {
        $this->info('Starting backup...');

        $timestamp = now()->format('Y-m-d_His');
        $backupDir = storage_path('app/backups');
        $tempDir = storage_path("app/backups/tmp_{$timestamp}");
        $zipPath = "{$backupDir}/backup-{$timestamp}.zip";

        File::ensureDirectoryExists($backupDir);
        File::ensureDirectoryExists($tempDir);

        try {
            $sqlDumpPath = $this->dumpDatabase($tempDir, $timestamp);

            if (! $sqlDumpPath) {
                $this->error('Database dump failed. Aborting backup.');

                return self::FAILURE;
            }

            $this->info('Database dumped successfully.');

            $this->createZipArchive($zipPath, $sqlDumpPath);
            $this->info("Archive created: {$zipPath}");

            $sizeInMb = round(filesize($zipPath) / 1024 / 1024, 2);

            $this->sendBackupEmail($zipPath, $sizeInMb);
            $this->info('Backup emailed successfully.');

            $this->pruneOldBackups($backupDir);
            $this->info('Old backups pruned.');
        } finally {
            File::deleteDirectory($tempDir);
        }

        $this->info('Backup completed.');

        return self::SUCCESS;
    }

    /**
     * Dump the PostgreSQL database using pg_dump. Credentials are passed
     * via the PGPASSWORD environment variable rather than the command
     * line, so they never appear in `ps aux` process listings or shell
     * history on the server.
     */
    private function dumpDatabase(string $tempDir, string $timestamp): ?string
    {
        $connection = config('database.connections.pgsql');
        $dumpPath = "{$tempDir}/database-{$timestamp}.sql";

        $result = Process::env(['PGPASSWORD' => $connection['password']])
            ->run([
                'pg_dump',
                '--host=' . $connection['host'],
                '--port=' . $connection['port'],
                '--username=' . $connection['username'],
                '--format=plain',
                '--no-owner',
                '--no-privileges',
                '--file=' . $dumpPath,
                $connection['database'],
            ]);

        if ($result->failed()) {
            $this->error('pg_dump error: ' . $result->errorOutput());

            return null;
        }

        return $dumpPath;
    }

    /**
     * Bundle the SQL dump together with every file under the public
     * storage disk (profile photos, project thumbnails/gallery images,
     * resume PDF) into a single zip archive.
     */
    private function createZipArchive(string $zipPath, string $sqlDumpPath): void
    {
        $zip = new ZipArchive();
        $zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE);

        $zip->addFile($sqlDumpPath, 'database.sql');

        $storagePublicPath = storage_path('app/public');

        if (File::isDirectory($storagePublicPath)) {
            $files = File::allFiles($storagePublicPath);

            foreach ($files as $file) {
                $relativePath = 'storage/' . $file->getRelativePathname();
                $zip->addFile($file->getRealPath(), $relativePath);
            }
        }

        $zip->close();
    }

    private function sendBackupEmail(string $zipPath, float $sizeInMb): void
    {
        Mail::to(config('mail.from.address'))
            ->send(new BackupCompleted($zipPath, $sizeInMb));
    }

    /**
     * Keep only the most recent KEEP_LAST backups on disk, deleting
     * anything older so the server doesn't slowly fill up with
     * daily archives over months of running unattended.
     */
    private function pruneOldBackups(string $backupDir): void
    {
        $backups = collect(File::files($backupDir))
            ->filter(fn ($file) => str_ends_with($file->getFilename(), '.zip'))
            ->sortByDesc(fn ($file) => $file->getMTime())
            ->values();

        $backups->slice(self::KEEP_LAST)->each(
            fn ($file) => File::delete($file->getRealPath())
        );
    }
}

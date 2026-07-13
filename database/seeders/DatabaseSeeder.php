<?php

namespace Database\Seeders;

use App\Models\Profile;
use App\Models\User;
use Database\Seeders\ProjectSeeder;
use Database\Seeders\TechnologySeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Naufal Febriansyah',
            'email' => 'naufalfebriansyah9034@gmail.com',
            'password' => bcrypt('naufal455667'),
            'is_admin' => true,
        ]);

        Profile::create([
            'name' => 'Naufal Febriansyah',
            'bio' => '6th-semester Information Systems student (working-class program) at Universitas Pamulang with a focus on backend development. Experienced in building multi-role SaaS systems using Laravel and PostgreSQL, as well as mobile apps with Flutter and Clean Architecture. Currently seeking an internship opportunity as a Backend Developer or Software Engineer.',
            'phone' => '+6281385230785',
            'email' => 'naufalfebriansyah9043@gmail.com',
            'github_url' => 'https://github.com/Naufalfebri2',
            'linkedin_url' => 'https://www.linkedin.com/in/naufal-febriansyah-7b75b31b5',
            'instagram_url' => 'https://www.instagram.com/febriansyahnaufal/?hl=en',
            'resume_path' => 'resume/CV_NAUFAL_FEBRIANSYAH.pdf',
        ]);

        $this->call([
            TechnologySeeder::class,
            ProjectSeeder::class,
        ]);
    }
}

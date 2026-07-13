<?php

namespace Database\Seeders;

use App\Models\Technology;
use Illuminate\Database\Seeder;

class TechnologySeeder extends Seeder
{
    public function run(): void
    {
        $technologies = [
            'Laravel 12',
            'PHP 8.3',
            'Laravel Sanctum',
            'PostgreSQL',
            'Eloquent ORM',
            'RESTful API',
            'Flutter',
            'BLoC',
            'Clean Architecture',
            'WebSocket',
            'HTML5',
            'CSS3',
            'JavaScript',
            'Tailwind CSS',
            'Livewire',
        ];

        foreach ($technologies as $tech) {
            Technology::firstOrCreate(['name' => $tech]);
        }
    }
}

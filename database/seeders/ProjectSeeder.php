<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\Technology;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    public function run(): void
    {
        // 1. KLolain
        $KLolain = Project::create([
            'title' => 'KLolain — Business Suite',
            'description' => 'SaaS invoice and point-of-sale platform for Indonesian SMEs. Multi-role (Owner, Admin, Super Admin) with Analytics, Product Moderation, Ticketing, User Management, and automated backup system modules.',
            'type' => 'team',
            'role' => 'Backend Developer',
            'repo_url' => 'https://github.com/ReAct22/kelolain_backend.git',
            'demo_url' => null,
            'thumbnail' => 'projects/klolain/Thumbnail.png',
            'is_featured' => true,
            'order' => 1,
        ]);
        $KLolain->technologies()->attach(
            Technology::whereIn('name', ['Laravel 12', 'PHP 8.3', 'Laravel Sanctum', 'PostgreSQL', 'Eloquent ORM', 'RESTful API'])->pluck('id')
        );
        $KLolain->images()->createMany([
            ['image_path' => 'projects/klolain/Dashbord.png', 'caption' => 'Seller Dashboard', 'order' => 1],
            ['image_path' => 'projects/klolain/HALAMAN PRODUK.png', 'caption' => 'Product Page', 'order' => 2],
            ['image_path' => 'projects/klolain/Invoice Kelolain (Pending).png', 'caption' => 'Pending Invoice', 'order' => 3],
            ['image_path' => 'projects/klolain/Invoice Kelolain (Lunas).png', 'caption' => 'Paid Invoice', 'order' => 4],
            ['image_path' => 'projects/klolain/Owner.png', 'caption' => 'Owner/Super Admin Dashboard', 'order' => 5],
            ['image_path' => 'projects/klolain/Tiketin.png', 'caption' => 'Ticketing Module', 'order' => 6],
            ['image_path' => 'projects/klolain/User.png', 'caption' => 'User Management', 'order' => 7],
        ]);

        // 2. Employee Attendance App
        $absensi = Project::create([
            'title' => 'Employee Attendance App',
            'description' => 'Flutter-based employee attendance app with Clean Architecture (Presentation/Domain/Data), BLoC state management, GPS & selfie verification, and real-time notifications via WebSocket.',
            'type' => 'team',
            'role' => 'Front-End Developer',
            'repo_url' => 'https://github.com/Naufalfebri2/absensi-karyawan-app.git',
            'demo_url' => null,
            'thumbnail' => 'projects/attendance-app/Home.jpeg',
            'is_featured' => true,
            'order' => 2,
        ]);
        $absensi->technologies()->attach(
            Technology::whereIn('name', ['Flutter', 'BLoC', 'Clean Architecture', 'WebSocket'])->pluck('id')
        );
        $absensi->images()->createMany([
            ['image_path' => 'projects/attendance-app/Login.jpeg', 'caption' => 'Login Page', 'order' => 1],
            ['image_path' => 'projects/attendance-app/Calendar.jpeg', 'caption' => 'Attendance Calendar', 'order' => 2],
            ['image_path' => 'projects/attendance-app/Attendance.jpeg', 'caption' => 'Attendance History', 'order' => 3],
            ['image_path' => 'projects/attendance-app/Leave.jpeg', 'caption' => 'Leave Request', 'order' => 4],
            ['image_path' => 'projects/attendance-app/Profile.jpeg', 'caption' => 'Profile', 'order' => 5],
        ]);

        // 3. HvnCake's
        $hvncake = Project::create([
            'title' => "HvnCake's",
            'description' => 'F&B landing page for a cake and donut shop, featuring a shopping cart and automated checkout via the WhatsApp API.',
            'type' => 'solo',
            'role' => null,
            'repo_url' => 'https://github.com/Naufalfebri2/hvn-Cake.git',
            'demo_url' => null,
            'thumbnail' => 'projects/hvncakes/Home.jpeg',
            'is_featured' => false,
            'order' => 3,
        ]);
        $hvncake->technologies()->attach(
            Technology::whereIn('name', ['HTML5', 'CSS3', 'JavaScript'])->pluck('id')
        );
        $hvncake->images()->createMany([
            ['image_path' => 'projects/hvncakes/Tentang Kami.jpeg', 'caption' => 'About Us', 'order' => 1],
            ['image_path' => 'projects/hvncakes/Menu.jpeg', 'caption' => 'Menu', 'order' => 2],
            ['image_path' => 'projects/hvncakes/Menu Donuts.jpeg', 'caption' => 'Donuts Menu', 'order' => 3],
            ['image_path' => 'projects/hvncakes/Produk Kami.jpeg', 'caption' => 'Our Products', 'order' => 4],
            ['image_path' => 'projects/hvncakes/Kontak Kami.jpeg', 'caption' => 'Contact Us', 'order' => 5],
            ['image_path' => 'projects/hvncakes/Checkout.jpeg', 'caption' => 'Checkout', 'order' => 6],
            ['image_path' => 'projects/hvncakes/Click WA.jpeg', 'caption' => 'Checkout via WhatsApp', 'order' => 7],
        ]);

        // 4. KopiKita
        $kopikita = Project::create([
            'title' => 'KopiKita',
            'description' => 'Coffee shop landing page with separate drink and food menus, featuring a shopping cart and automated checkout via the WhatsApp API.',
            'type' => 'solo',
            'role' => null,
            'repo_url' => 'https://github.com/Naufalfebri2/Kopi-Kita-Bersama.git',
            'demo_url' => null,
            'thumbnail' => 'projects/kopikita/Home.jpeg',
            'is_featured' => false,
            'order' => 4,
        ]);
        $kopikita->technologies()->attach(
            Technology::whereIn('name', ['HTML5', 'CSS3', 'JavaScript'])->pluck('id')
        );
        $kopikita->images()->createMany([
            ['image_path' => 'projects/kopikita/Tentang Kami.jpeg', 'caption' => 'About Us', 'order' => 1],
            ['image_path' => 'projects/kopikita/Menu.jpeg', 'caption' => 'Menu', 'order' => 2],
            ['image_path' => 'projects/kopikita/Produk Keunggulan.jpeg', 'caption' => 'Featured Products', 'order' => 3],
            ['image_path' => 'projects/kopikita/Kontak Kami.jpeg', 'caption' => 'Contact Us', 'order' => 4],
            ['image_path' => 'projects/kopikita/Checkout.jpeg', 'caption' => 'Checkout', 'order' => 5],
        ]);
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\UserSeeder;
use Database\Seeders\ServiceSeeder;
use Database\Seeders\CategorySeeder;
use Database\Seeders\PriceSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Urutan wajib agar foreign key tidak error
        $this->call([
            UserSeeder::class,      // pertama, tidak ada FK
            CategorySeeder::class,  // kedua
            ServiceSeeder::class,   // ketiga, FK ke kategori
            PriceSeeder::class,     // terakhir, FK ke layanan
        ]);
    }
}

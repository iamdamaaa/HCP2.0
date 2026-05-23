<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Category::firstOrCreate(
            ['name' => 'Shoes'],
            [
                'id'          => Str::uuid()->toString(),
                'slug'        => 'shoes',
                'description' => 'Category for shoe cleaning services',
                'active'      => true,
            ]
        );

        Category::firstOrCreate(
            ['name' => 'Clothes'],
            [
                'id'          => Str::uuid()->toString(),
                'slug'        => 'clothes',
                'description' => 'Category for clothes cleaning services',
                'active'      => true,
            ]
        );
    }
}

<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Service;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $shoesCategory = Category::where('name', 'Shoes')->first();
        $clothesCategory = Category::where('name', 'Clothes')->first();

        if ($shoesCategory) {
            Service::firstOrCreate(
                ['name' => 'White Shoes Cleaning'],
                [
                    'id'          => Str::uuid()->toString(),
                    'category_id' => $shoesCategory->id,
                    'slug'        => 'white-shoes-cleaning',
                ]
            );

            Service::firstOrCreate(
                ['name' => 'Colored Shoes Cleaning'],
                [
                    'id'          => Str::uuid()->toString(),
                    'category_id' => $shoesCategory->id,
                    'slug'        => 'colored-shoes-cleaning',
                ]
            );
        }

        if ($clothesCategory) {
            Service::firstOrCreate(
                ['name' => 'Wash by Weight'],
                [
                    'id'          => Str::uuid()->toString(),
                    'category_id' => $clothesCategory->id,
                    'slug'        => 'wash-by-weight',
                ]
            );

            Service::firstOrCreate(
                ['name' => 'Wash by Piece'],
                [
                    'id'          => Str::uuid()->toString(),
                    'category_id' => $clothesCategory->id,
                    'slug'        => 'wash-by-piece',
                ]
            );

            Service::firstOrCreate(
                ['name' => 'Ironing by Weight'],
                [
                    'id'          => Str::uuid()->toString(),
                    'category_id' => $clothesCategory->id,
                    'slug'        => 'ironing-by-weight',
                ]
            );
        }
    }
}

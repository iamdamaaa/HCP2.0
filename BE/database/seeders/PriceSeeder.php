<?php

namespace Database\Seeders;

use App\Models\Service;
use App\Models\Price;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PriceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Data struktur harga per layanan
        $priceStructure = [
            'White Shoes Cleaning' => [
                '1_day' => 35000,
                '2_days' => 28000,
                '3_days' => 22000,
            ],
            'Colored Shoes Cleaning' => [
                '1_day' => 30000,
                '2_days' => 25000,
                '3_days' => 20000,
            ],
            'Wash by Weight' => [
                '1_day' => 7000,
                '2_days' => 6000,
                '3_days' => 5000,
            ],
            'Wash by Piece' => [
                '1_day' => 15000,
                '2_days' => 12000,
                '3_days' => 10000,
            ],
            'Ironing by Weight' => [
                '1_day' => 5000,
                '2_days' => 4000,
                '3_days' => 3000,
            ],
        ];

        foreach ($priceStructure as $serviceName => $prices) {
            $service = Service::where('name', $serviceName)->first();
            
            if ($service) {
                foreach ($prices as $duration => $price) {
                    Price::firstOrCreate(
                        [
                            'service_id' => $service->id,
                            'duration_type' => $duration,
                        ],
                        [
                            'id'     => Str::uuid()->toString(),
                            'price'  => $price,
                            'active' => true,
                        ]
                    );
                }
            }
        }
    }
}

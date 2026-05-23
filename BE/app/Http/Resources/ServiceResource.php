<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Resource untuk memformat data response Layanan.
 */
class ServiceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'       => $this->id,
            'name'     => $this->name,
            'unit'   => $this->unit,
            'is_active'    => $this->is_active,
            'category' => [
                'id'   => $this->category->id ?? null,
                'name' => $this->category->name ?? null,
            ],
            // Harga diformat dari relasi prices jika diload
            'prices'    => $this->whenLoaded('prices', function () {
                return $this->prices->map(function ($price) {
                    return [
                        'id'     => $price->id,
                        'duration' => $price->duration_type,
                        'price'  => $price->price,
                        'is_active'  => $price->active,
                    ];
                });
            }),
        ];
    }
}

<?php

namespace App\Http\Resources\Employee;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Resource untuk memformat data response Order khusus untuk Karyawan.
 * Menampilkan alamat pickup, delivery, dan status dengan jelas.
 */
class EmployeeOrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'               => $this->id,
            'order_number'     => $this->order_number,
            'status'           => $this->status,
            'channel'          => $this->channel,
            'pickup_address'   => $this->pickup_address,
            'delivery_address' => $this->delivery_address,
            'user'             => $this->whenLoaded('user', function () {
                return [
                    'id'    => $this->user->id,
                    'name'  => $this->user->name,
                    'phone' => $this->user->phone,
                ];
            }),
            'items'            => $this->whenLoaded('items'),
        ];
    }
}

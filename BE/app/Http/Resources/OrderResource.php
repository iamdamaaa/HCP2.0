<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Resource untuk memformat data response Order secara umum (misal untuk pelanggan).
 */
class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'order_number'   => $this->order_number,
            'status'      => $this->status,
            'channel'     => $this->channel,
            'total_price' => $this->total_price,
            'discount'      => $this->discount,
            'final_price' => $this->final_price,
            'created_at'  => $this->created_at,
            'user'        => $this->whenLoaded('user', function () {
                return [
                    'id'    => $this->user->id,
                    'name'  => $this->user->name,
                    'phone' => $this->user->phone,
                ];
            }),
            'items'       => $this->whenLoaded('items'),
        ];
    }
}

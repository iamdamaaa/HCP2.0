<?php

namespace App\Http\Requests\Customer;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Validasi untuk pembuatan pesanan baru oleh pelanggan.
 */
class StoreOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->role === 'customer';
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'service_id'       => ['required', 'string', 'exists:services,id'],
            'price_id'         => ['required', 'string', 'exists:prices,id'],
            'same_location'    => ['boolean'],
            'pickup_address'   => ['required_if:same_location,false', 'string', 'nullable'],
            'delivery_address' => ['required_if:same_location,false', 'string', 'nullable'],
            'channel'          => ['required', 'in:website,whatsapp,mitra'],
        ];
    }
}

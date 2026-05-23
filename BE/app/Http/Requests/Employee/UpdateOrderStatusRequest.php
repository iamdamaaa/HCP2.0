<?php

namespace App\Http\Requests\Employee;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Validasi untuk update status pesanan oleh karyawan.
 */
class UpdateOrderStatusRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->role === 'employee';
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'status' => ['required', 'string', 'in:pickUp,process,delivery,done'],
        ];
    }
}

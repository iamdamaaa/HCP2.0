<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Validasi untuk pembuatan atau update layanan oleh Admin.
 */
class StoreServiceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Pastikan hanya admin yang bisa, bisa juga dicek lewat middleware
        return auth()->check() && auth()->user()->role === 'admin';
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name'        => ['required', 'string', 'max:255'],
            'category_id' => ['required', 'string', 'exists:categories,id'],
            'unit'        => ['required', 'string', 'max:50'],
            'is_active'   => ['boolean'],
        ];
    }
}

<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateServiceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // Ambil model dari route binding, lalu ignore record ini saat cek unique
        $service = $this->route('service');

        return [
            'category_id' => ['required', 'uuid', 'exists:categories,id'],
            'name'        => ['required', 'string', 'max:100', Rule::unique('services', 'name')->ignoreModel($service)],
            'unit'        => ['required', 'string', 'max:50'],
            'is_active'   => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'category_id.required' => 'Kategori wajib dipilih.',
            'category_id.uuid'     => 'Format ID kategori tidak valid.',
            'category_id.exists'   => 'Kategori yang dipilih tidak ditemukan.',
            'name.required'        => 'Nama layanan wajib diisi.',
            'name.unique'          => 'Nama layanan sudah digunakan oleh layanan lain.',
            'unit.required'        => 'Satuan layanan wajib diisi.',
            'is_active.boolean'    => 'Status aktif harus berupa true atau false.',
        ];
    }

    protected function prepareForValidation(): void
    {
        // Kalau is_active tidak dikirim → jangan override nilai yang ada
        // Berbeda dengan Store — saat update, tidak ada perubahan = biarkan
    }
}
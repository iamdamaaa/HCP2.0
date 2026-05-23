<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Form Request untuk membuat Service (Layanan) baru.
 *
 * CATATAN UNTUK JUNIOR:
 * - category_id: 'uuid' memastikan format UUID valid sebelum dicek ke DB.
 *   Urutan rule penting: uuid dulu, baru exists — supaya pesan error lebih tepat.
 * - Slug tidak diinput user, digenerate otomatis di Controller dari 'name'.
 */
class StoreServiceRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // Pastikan category_id berformat UUID dan ada di tabel categories
            'category_id' => ['required', 'uuid', 'exists:categories,id'],
            'name'        => ['required', 'string', 'max:100', 'unique:services,name'],
            // unit: satuan layanan, contoh: "kg", "pasang", "item"
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

            'name.required' => 'Nama layanan wajib diisi.',
            'name.string'   => 'Nama layanan harus berupa teks.',
            'name.max'      => 'Nama layanan maksimal 100 karakter.',
            'name.unique'   => 'Nama layanan sudah digunakan.',

            'unit.required' => 'Satuan layanan wajib diisi.',
            'unit.string'   => 'Satuan layanan harus berupa teks.',
            'unit.max'      => 'Satuan layanan maksimal 50 karakter.',

            'is_active.boolean' => 'Status aktif harus berupa true atau false.',
        ];
    }
}

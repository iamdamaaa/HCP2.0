<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Form Request untuk membuat Kategori baru.
 *
 * CATATAN UNTUK JUNIOR:
 * - Slug tidak ada di sini karena slug digenerate OTOMATIS di Controller dari field 'name'.
 * - messages() berguna agar pesan error lebih ramah dan dalam Bahasa Indonesia.
 */
class StoreCategoryRequest extends FormRequest
{
    /**
     * Menentukan apakah user diizinkan membuat request ini.
     * return true → validasi hanya bergantung pada middleware (role:admin),
     * bukan authorize() di sini.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Aturan validasi untuk setiap field input.
     */
    public function rules(): array
    {
        return [
            // 'name' harus unik di tabel categories kolom 'name'
            'name'        => ['required', 'string', 'max:100', 'unique:categories,name'],
            'description' => ['nullable', 'string', 'max:255'],
            // 'boolean' menerima true/false/1/0/"true"/"false"/"1"/"0"
            'active'      => ['nullable', 'boolean'],
        ];
    }

    /**
     * Pesan error kustom dalam Bahasa Indonesia.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Nama kategori wajib diisi.',
            'name.string'   => 'Nama kategori harus berupa teks.',
            'name.max'      => 'Nama kategori maksimal 100 karakter.',
            'name.unique'   => 'Nama kategori sudah digunakan.',

            'description.string' => 'Deskripsi harus berupa teks.',
            'description.max'    => 'Deskripsi maksimal 255 karakter.',

            'active.boolean' => 'Status aktif harus berupa true atau false.',
        ];
    }
}

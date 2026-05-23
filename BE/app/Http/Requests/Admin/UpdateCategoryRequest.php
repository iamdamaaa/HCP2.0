<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Form Request untuk mengupdate Kategori yang sudah ada.
 *
 * CATATAN UNTUK JUNIOR:
 * Perbedaan utama dengan StoreCategoryRequest ada di validasi unique 'name':
 *
 * unique:categories,name,{id},id
 *  ↑ tabel      ↑ kolom  ↑ nilai ID yg dikecualikan  ↑ nama kolom ID-nya
 *
 * Kenapa perlu dikecualikan? Karena saat update, kita tidak mau validasi unique
 * menolak nama milik kategori itu sendiri. Misal kategori "Sepatu" di-update
 * tapi namanya tetap "Sepatu" → harusnya lolos.
 *
 * $this->route('category') → mengambil segment ID dari URL: /admin/categories/{category}
 */
class UpdateCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // Ambil ID dari parameter route, misal: /admin/categories/{category}
        $id = $this->route('category');

        return [
            // Ignore ID milik dirinya sendiri saat cek unique
            'name'        => ['required', 'string', 'max:100', "unique:categories,name,{$id},id"],
            'description' => ['nullable', 'string', 'max:255'],
            'active'      => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama kategori wajib diisi.',
            'name.string'   => 'Nama kategori harus berupa teks.',
            'name.max'      => 'Nama kategori maksimal 100 karakter.',
            'name.unique'   => 'Nama kategori sudah digunakan oleh kategori lain.',

            'description.string' => 'Deskripsi harus berupa teks.',
            'description.max'    => 'Deskripsi maksimal 255 karakter.',

            'active.boolean' => 'Status aktif harus berupa true atau false.',
        ];
    }
}

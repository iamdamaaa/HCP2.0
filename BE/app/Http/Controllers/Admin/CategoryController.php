<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreCategoryRequest;
use App\Http\Requests\Admin\UpdateCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Traits\HasApiResponse;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    use HasApiResponse;

    // =========================================================================
    // INDEX — tampilkan semua kategori
    // =========================================================================
    public function index()
    {
        $categories = Category::withCount('services')
                              ->latest()
                              ->get();

        return $this->successResponse(
            CategoryResource::collection($categories),
            'Data kategori berhasil diambil'
        );
    }

    // =========================================================================
    // STORE — buat kategori baru
    // =========================================================================
    public function store(StoreCategoryRequest $request)
    {
        $data = $request->validated();

        // Generate slug otomatis dari name, cek bentrok
        $data['slug'] = $this->generateSlug($data['name']);

        $category = Category::create($data);

        return $this->successResponse(
            new CategoryResource($category->loadCount('services')),
            'Kategori berhasil ditambahkan',
            201
        );
    }

    // =========================================================================
    // SHOW — detail satu kategori
    // =========================================================================
    public function show(Category $category)
    {
        // Load relasi services beserta prices tiap service
        $category->load('services.prices');

        return $this->successResponse(
            new CategoryResource($category),
            'Detail kategori berhasil diambil'
        );
    }

    // =========================================================================
    // UPDATE — edit kategori
    // =========================================================================
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $data = $request->validated();

        // Regenerate slug dari nama baru, kecualikan ID dirinya sendiri
        $data['slug'] = $this->generateSlug($data['name'], $category->id);

        $category->update($data);

        return $this->successResponse(
            new CategoryResource($category->loadCount('services')),
            'Kategori berhasil diperbarui'
        );
    }

    // =========================================================================
    // DESTROY — hapus kategori
    // =========================================================================
    public function destroy(Category $category)
    {
        // Tolak kalau masih ada service di bawah kategori ini
        if ($category->services()->exists()) {
            return $this->errorResponse(
                'Kategori tidak dapat dihapus karena masih memiliki layanan aktif',
                422
            );
        }

        $category->delete();

        return $this->successResponse(null, 'Kategori berhasil dihapus');
    }

    // =========================================================================
    // PRIVATE — generate slug unik
    // =========================================================================
    private function generateSlug(string $name, ?string $exceptId = null): string
    {
        $slug = Str::slug($name);
        $original = $slug;
        $counter = 1;

        // Cek apakah slug sudah ada di database
        while (
            Category::where('slug', $slug)
                    ->when($exceptId, fn($q) => $q->where('id', '!=', $exceptId))
                    ->exists()
        ) {
            // Kalau bentrok → tambah suffix angka
            $slug = "{$original}-{$counter}";
            $counter++;
        }

        return $slug;
    }
}
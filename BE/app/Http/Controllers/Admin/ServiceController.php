<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreServiceRequest;
use App\Http\Requests\Admin\UpdateServiceRequest;
use App\Http\Resources\ServiceResource;
use App\Models\Service;
use App\Traits\HasApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ServiceController extends Controller
{
    use HasApiResponse;

    // =========================================================================
    // INDEX — tampilkan semua layanan
    // =========================================================================
    public function index(Request $request)
    {
        $services = Service::with(['category', 'prices'])
            // Filter by category_id kalau ada query param
            // contoh: GET /api/admin/services?category_id=uuid
            ->when(
                $request->category_id,
                fn($q) => $q->where('category_id', $request->category_id)
            )
            ->latest()
            ->get();

        return $this->successResponse(
            ServiceResource::collection($services),
            'Data layanan berhasil diambil'
        );
    }

    // =========================================================================
    // STORE — buat layanan baru
    // =========================================================================
    public function store(StoreServiceRequest $request)
    {
        $data = $request->validated();

        // Generate slug otomatis dari name
        $data['slug'] = $this->generateSlug($data['name']);

        $service = Service::create($data);

        return $this->successResponse(
            new ServiceResource($service->load(['category', 'prices'])),
            'Layanan berhasil ditambahkan',
            201
        );
    }

    // =========================================================================
    // SHOW — detail satu layanan
    // Route Model Binding: Laravel otomatis cari Service by ID dari URL
    // Kalau tidak ditemukan → otomatis 404, tidak perlu cek manual
    // =========================================================================
    public function show(Service $service)
    {
        $service->load(['category', 'prices']);

        return $this->successResponse(
            new ServiceResource($service),
            'Detail layanan berhasil diambil'
        );
    }

    // =========================================================================
    // UPDATE — edit layanan
    // =========================================================================
    public function update(UpdateServiceRequest $request, Service $service)
    {
        $data = $request->validated();

        // Regenerate slug dari nama baru, kecualikan ID dirinya sendiri
        $data['slug'] = $this->generateSlug($data['name'], $service->id);

        $service->update($data);

        return $this->successResponse(
            new ServiceResource($service->load(['category', 'prices'])),
            'Layanan berhasil diperbarui'
        );
    }

    // =========================================================================
    // DESTROY — hapus layanan
    // =========================================================================
    public function destroy(Service $service)
    {
        // Cek apakah service masih dipakai di order_items
        if ($service->orderItems()->exists()) {
            return $this->errorResponse(
                'Layanan tidak dapat dihapus karena masih digunakan dalam pesanan',
                422
            );
        }

        $service->delete();

        return $this->successResponse(null, 'Layanan berhasil dihapus');
    }

    // =========================================================================
    // PRIVATE — generate slug unik
    // =========================================================================
    private function generateSlug(string $name, ?string $exceptId = null): string
    {
        $slug = Str::slug($name);
        $original = $slug;
        $counter = 1;

        while (
            Service::where('slug', $slug)
                   ->when($exceptId, fn($q) => $q->where('id', '!=', $exceptId))
                   ->exists()
        ) {
            $slug = "{$original}-{$counter}";
            $counter++;
        }

        return $slug;
    }
}
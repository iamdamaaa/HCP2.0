<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreServiceRequest;
use App\Http\Resources\ServiceResource;
use App\Models\Service;
use App\Traits\HasApiResponse;
use Illuminate\Http\Request;

/**
 * Controller Admin untuk mengelola Layanan Laundry.
 */
class ServiceController extends Controller
{
    use HasApiResponse;

    /**
     * Menampilkan daftar semua layanan beserta kategori dan harga.
     */
    public function index()
    {
        // Mengambil semua service (layanan) beserta relasi category (kategori) dan prices (harga)
        $services = Service::with(['category', 'prices'])->get();

        return $this->successResponse(
            ServiceResource::collection($services),
            'Data layanan berhasil diambil'
        );
    }

    /**
     * Menyimpan data layanan baru.
     */
    public function store(StoreServiceRequest $request)
    {
        $data = $request->validated();

        $service = Service::create([
            'name'        => $data['name'],
            'category_id' => $data['category_id'],
            'unit'        => $data['unit'] ?? null,
            'is_active'   => $data['is_active'] ?? true,
        ]);

        return $this->successResponse(
            new ServiceResource($service->load(['category', 'prices'])),
            'Layanan berhasil ditambahkan',
            201
        );
    }

    /**
     * Menampilkan detail satu layanan.
     */
    public function show(string $id)
    {
        $service = Service::with(['category', 'prices'])->find($id);

        if (!$service) {
            return $this->errorResponse('Layanan tidak ditemukan', 404);
        }

        return $this->successResponse(
            new ServiceResource($service),
            'Detail layanan berhasil diambil'
        );
    }

    /**
     * Memperbarui data layanan.
     * Menggunakan tipe HTTP Method PUT/PATCH.
     */
    public function update(StoreServiceRequest $request, string $id)
    {
        $service = Service::find($id);

        if (!$service) {
            return $this->errorResponse('Layanan tidak ditemukan', 404);
        }

        $data = $request->validated();
        $service->update([
            'name'        => $data['name'],
            'category_id' => $data['category_id'],
            'unit'        => $data['unit'] ?? null,
            'is_active'   => $data['is_active'] ?? true,
        ]);

        return $this->successResponse(
            new ServiceResource($service->load(['category', 'prices'])),
            'Layanan berhasil diperbarui'
        );
    }

    /**
     * Menghapus layanan (Hanya hapus jika tidak ada relasi penting yang restrict).
     */
    public function destroy(string $id)
    {
        $service = Service::find($id);

        if (!$service) {
            return $this->errorResponse('Layanan tidak ditemukan', 404);
        }

        try {
            $service->delete();
            return $this->successResponse(null, 'Layanan berhasil dihapus');
        } catch (\Exception $e) {
            // Bisa jadi gagal karena constrain relation restrict
            return $this->errorResponse('Layanan gagal dihapus, mungkin sedang digunakan', 400);
        }
    }
}

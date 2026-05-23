<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Http\Requests\Employee\UpdateOrderStatusRequest;
use App\Http\Resources\Employee\EmployeeOrderResource;
use App\Models\Order;
use App\Traits\HasApiResponse;
use Illuminate\Http\Request;

/**
 * Controller Karyawan untuk mengelola pesanan yang di-assign kepadanya.
 */
class OrderController extends Controller
{
    use HasApiResponse;

    /**
     * Menampilkan daftar pesanan yang di-assign ke Karyawan yang sedang login.
     */
    public function index(Request $request)
    {
        // Hanya ambil order dimana employee_id sama dengan id karyawan yang login
        $orders = Order::where('employee_id', $request->user()->id)
            ->with(['user', 'items']) // Load relasi pelanggan dan item
            ->latest()
            ->get();

        return $this->successResponse(
            EmployeeOrderResource::collection($orders),
            'Daftar pesanan karyawan berhasil diambil'
        );
    }

    /**
     * Menampilkan detail pesanan yang di-assign ke karyawan bersangkutan.
     */
    public function show(Request $request, string $id)
    {
        $order = Order::with(['user', 'items'])
            ->where('id', $id)
            ->where('employee_id', $request->user()->id)
            ->first();

        if (!$order) {
            return $this->errorResponse('Pesanan tidak ditemukan atau bukan assign Anda', 404);
        }

        return $this->successResponse(
            new EmployeeOrderResource($order),
            'Detail pesanan berhasil diambil'
        );
    }

    /**
     * Update status pesanan secara bertahap (dijemput -> diproses -> diantar -> selesai).
     * Tidak bisa membatalkan pesanan.
     */
    public function updateStatus(UpdateOrderStatusRequest $request, string $id)
    {
        $order = Order::where('id', $id)
            ->where('employee_id', $request->user()->id)
            ->first();

        if (!$order) {
            return $this->errorResponse('Pesanan tidak ditemukan atau bukan assign Anda', 404);
        }

        $data = $request->validated();
        
        // Logika tambahan: memastikan status maju secara logis bisa ditambahkan di sini,
        // misalnya memastikan bahwa pesanan yang "selesai" tidak bisa mundur ke "dijemput".
        // Untuk saat ini, kita langsung update sesuai request validasi in:pickUp,process,delivery,done.

        $order->update([
            'status' => $data['status']
        ]);

        // Opsional: Rekam ke StatusLog jika diperlukan
        // $order->statusLogs()->create([ ... ]);

        return $this->successResponse(
            new EmployeeOrderResource($order->load(['user', 'items'])),
            'Status pesanan berhasil diperbarui menjadi ' . $data['status']
        );
    }
}

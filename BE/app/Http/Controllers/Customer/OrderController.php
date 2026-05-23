<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\StoreOrderRequest;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Models\Price;
use App\Traits\HasApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

/**
 * Controller Pelanggan untuk mengelola Pesanan.
 */
class OrderController extends Controller
{
    use HasApiResponse;

    /**
     * Menampilkan daftar pesanan MILIK pelanggan yang sedang login.
     */
    public function index(Request $request)
    {
        // Hanya ambil order yang user_id nya = id user login
        $orders = Order::where('user_id', $request->user()->id)
            ->with(['items.service', 'items.price']) // Relasi untuk mendapatkan detail item
            ->latest()
            ->get();

        return $this->successResponse(
            OrderResource::collection($orders),
            'Daftar pesanan berhasil diambil'
        );
    }

    /**
     * Membuat pesanan baru oleh pelanggan.
     */
    public function store(StoreOrderRequest $request)
    {
        $data = $request->validated();
        $user = $request->user();

        // Cari harga berdasarkan ID yang dipilih
        $price = Price::find($data['price_id']);
        if (!$price) {
            return $this->errorResponse('Harga/Layanan tidak valid', 400);
        }

        // Logic kalkulasi total harga sederhana
        // Jika ada qty (asumsi 1 untuk contoh ini), final_price bisa berbeda.
        $qty = 1; 
        $subtotal = $price->price * $qty;
        
        // Simpan Order
        $order = Order::create([
            'user_id'          => $user->id,
            'order_number'        => 'ORD-' . strtoupper(Str::random(8)),
            'status'           => 'waiting', // Default status awal
            'channel'          => $data['channel'],
            'same_location'    => $data['same_location'] ?? false,
            'pickup_address'   => $data['pickup_address'] ?? null,
            'delivery_address' => $data['delivery_address'] ?? null,
            'total_price'      => $subtotal,
            'discount'           => 0,
            'final_price'      => $subtotal, // tanpa diskon saat ini
        ]);

        // Simpan OrderItem (Relasi many-to-many atau one-to-many item order)
        // Table OrderItem pastikan sudah dipersiapkan sebelumnya
        $order->items()->create([
            'service_id' => $data['service_id'],
            'price_id'   => $data['price_id'],
            'qty'        => $qty,
            'subtotal'   => $subtotal,
        ]);

        return $this->successResponse(
            new OrderResource($order->load(['items', 'user'])),
            'Pesanan berhasil dibuat',
            201
        );
    }

    /**
     * Menampilkan detail pesanan (Hanya jika order ini milik pelanggan yang login).
     */
    public function show(Request $request, string $id)
    {
        $order = Order::with(['items.service', 'items.price', 'user'])
            ->where('id', $id)
            ->where('user_id', $request->user()->id)
            ->first();

        if (!$order) {
            return $this->errorResponse('Pesanan tidak ditemukan atau Anda tidak memiliki akses', 404);
        }

        return $this->successResponse(
            new OrderResource($order),
            'Detail pesanan berhasil diambil'
        );
    }

    /**
     * Membatalkan pesanan.
     * Hanya bisa dilakukan jika pesanan masih berstatus 'waiting'.
     */
    public function cancel(Request $request, string $id)
    {
        $order = Order::where('id', $id)
            ->where('user_id', $request->user()->id)
            ->first();

        if (!$order) {
            return $this->errorResponse('Pesanan tidak ditemukan atau Anda tidak memiliki akses', 404);
        }

        // Cek status pesanan apakah masih bisa dibatalkan menggunakan helper isCancellable()
        // Atau manual: if ($order->status !== 'waiting')
        if (!$order->isCancellable()) {
            return $this->errorResponse('Pesanan sudah tidak bisa dibatalkan karena statusnya: ' . $order->status, 400);
        }

        $order->update(['status' => 'cancel']);

        return $this->successResponse(
            new OrderResource($order),
            'Pesanan berhasil dibatalkan'
        );
    }
}

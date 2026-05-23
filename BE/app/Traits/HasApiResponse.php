<?php

namespace App\Traits;

/**
 * Trait HasApiResponse
 *
 * Menyediakan format JSON response yang konsisten di seluruh aplikasi.
 * Semua controller wajib menggunakan trait ini agar response seragam.
 *
 * Format standar:
 * {
 *   "success": true/false,
 *   "message": "...",
 *   "data": {...} atau null
 * }
 */
trait HasApiResponse
{
    /**
     * Response sukses dengan data.
     *
     * @param  mixed   $data        Data yang dikembalikan (array, resource, collection, dll)
     * @param  string  $message     Pesan untuk client
     * @param  int     $statusCode  HTTP status code (default 200)
     */
    protected function successResponse($data = null, string $message = 'Berhasil', int $statusCode = 200)
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data'    => $data,
        ], $statusCode);
    }

    /**
     * Response error umum.
     *
     * @param  string  $message     Pesan error untuk client
     * @param  int     $statusCode  HTTP status code (default 400)
     */
    protected function errorResponse(string $message = 'Terjadi kesalahan', int $statusCode = 400)
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'data'    => null,
        ], $statusCode);
    }

    /**
     * Response khusus error validasi.
     * Mengembalikan detail field mana saja yang gagal validasi.
     *
     * @param  mixed  $errors  Error bag dari validator (MessageBag atau array)
     */
    protected function validationErrorResponse($errors)
    {
        return response()->json([
            'success' => false,
            'message' => 'Validasi gagal',
            'data'    => $errors,
        ], 422);
    }
}

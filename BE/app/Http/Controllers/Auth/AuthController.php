<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\SendOtpRequest;
use App\Http\Requests\Auth\VerifyOtpRequest;
use App\Models\OtpCode;
use App\Models\User;
use App\Traits\HasApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * Controller untuk menangani Autentikasi menggunakan OTP.
 */
class AuthController extends Controller
{
    use HasApiResponse;

    /**
     * Kirim OTP ke nomor HP (Simulasi / Integrasi Provider).
     * 
     * @param SendOtpRequest $request
     */
    public function sendOtp(SendOtpRequest $request)
    {
        $phone = $request->validated('phone');

        // TODO: Integrasi dengan layanan pengirim SMS/WA (contoh: Twilio, Watzap, dll)
        // Untuk sekarang kita generate random 6 digit
        $otp = rand(100000, 999999);

        // Simpan OTP ke database
        OtpCode::create([
            'phone'      => $phone,
            'otp_code'   => $otp,
            'expired_at' => now()->addMinutes(5),
            'is_used'    => false,
        ]);

        return $this->successResponse(null, 'Kode OTP berhasil dikirim ke nomor HP Anda');
    }

    /**
     * Verifikasi kode OTP dan berikan token Sanctum jika berhasil.
     * 
     * @param VerifyOtpRequest $request
     */
    public function verifyOtp(VerifyOtpRequest $request)
    {
        $data = $request->validated();

        $otpRecord = OtpCode::where('phone', $data['phone'])
            ->where('otp_code', $data['otp_code'])
            ->where('is_used', false)
            ->first();

        if (!$otpRecord) {
            return $this->errorResponse('Kode OTP tidak valid atau salah', 400);
        }

        if ($otpRecord->isExpired()) {
            return $this->errorResponse('Kode OTP sudah kadaluarsa', 400);
        }

        // Tandai OTP sudah digunakan
        $otpRecord->update(['is_used' => true]);

        // Cari atau buat user baru jika belum terdaftar (misal otomatis jadi pelanggan)
        $user = User::firstOrCreate(
            ['phone' => $data['phone']],
            [
                'name' => 'User ' . $data['phone'],
                'role' => 'pelanggan',
                'is_verified' => true
            ]
        );

        // Hapus token lama jika perlu, lalu buat token baru
        $user->tokens()->delete();
        $token = $user->createToken('auth_token')->plainTextToken;

        return $this->successResponse([
            'token' => $token,
            'user'  => [
                'id'    => $user->id,
                'name'  => $user->name,
                'phone' => $user->phone,
                'role'  => $user->role,
            ]
        ], 'Login berhasil');
    }

    /**
     * Mendaftar akun baru (Register) dengan email/phone dan password.
     */
    public function register(RegisterRequest $request)
    {
        $data = $request->validated();

        $user = User::create([
            'id'          => Str::uuid()->toString(),
            'name'        => $data['name'],
            'phone'       => $data['phone'],
            'email'       => $data['email'],
            'password'    => Hash::make($data['password']),
            'role'        => 'pelanggan',
            'is_verified' => false, // bisa di-set true atau nunggu verifikasi OTP/Email
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return $this->successResponse([
            'token' => $token,
            'user'  => [
                'id'    => $user->id,
                'name'  => $user->name,
                'phone' => $user->phone,
                'email' => $user->email,
                'role'  => $user->role,
            ]
        ], 'Registrasi berhasil', 201);
    }

    /**
     * Login menggunakan email atau nomor telepon dan password.
     */
    public function login(LoginRequest $request)
    {
        $data = $request->validated();

        // Cari user berdasarkan email ATAU phone
        $user = User::where('email', $data['email_or_phone'])
                    ->orWhere('phone', $data['email_or_phone'])
                    ->first();

        if (!$user || !Hash::check($data['password'], $user->password)) {
            return $this->errorResponse('Kredensial tidak cocok', 401);
        }

        // Hapus token lama jika perlu, lalu buat token baru
        $user->tokens()->delete();
        $token = $user->createToken('auth_token')->plainTextToken;

        return $this->successResponse([
            'token' => $token,
            'user'  => [
                'id'    => $user->id,
                'name'  => $user->name,
                'phone' => $user->phone,
                'email' => $user->email,
                'role'  => $user->role,
            ]
        ], 'Login berhasil');
    }

    /**
     * Logout pengguna dengan menghapus token saat ini.
     * 
     * @param Request $request
     */
    public function logout(Request $request)
    {
        // Hapus token yang sedang digunakan untuk request ini
        $request->user()->currentAccessToken()->delete();

        return $this->successResponse(null, 'Logout berhasil');
    }
}

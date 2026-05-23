<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('performance_logs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignUuid('order_id')->nullable()->constrained('orders')->onDelete('set null');
            $table->decimal('rating', 3, 2)->nullable()->comment('Rating 0.00 - 5.00');
            $table->text('feedback')->nullable();
            $table->enum('kategori', ['kebersihan', 'ketepatan_waktu', 'komunikasi', 'hasil_kerja'])->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('performance_logs');
    }
};

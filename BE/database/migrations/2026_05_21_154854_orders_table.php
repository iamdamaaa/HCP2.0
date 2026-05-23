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
        Schema::create('orders', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignUuid('partner_id')->nullable()->constrained('partners')->onDelete('set null');
            $table->foreignUuid('employee_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignUuid('coupon_id')->nullable()->constrained('coupons')->onDelete('set null');
            $table->string('no_orders')->unique();
            $table->enum('status', [
                'waiting',
                'pickUp',
                'process',
                'delivery',
                'done',
                'cancel',
            ])->default('waiting');
            $table->enum('channel', [
                'website',
                'whatsapp',
                'mitra',
            ])->default('website');
            $table->string('pickup_address')->nullable();
            $table->string('delivery_address')->nullable();
            $table->boolean('same_location')->default(false);
            $table->integer('poin_digunakan')->default(0);
            $table->integer('poin_didapat')->default(0);
            $table->decimal('total_harga', 12, 2)->default(0);
            $table->decimal('diskon', 12, 2)->default(0);
            $table->decimal('harga_akhir', 12, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};

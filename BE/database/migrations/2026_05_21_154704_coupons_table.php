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
        Schema::create('coupons', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('code')->unique();
            $table->enum('type', ['percent', 'nominal'])->default('percent');
            $table->decimal('discount_value', 12, 2);
            $table->integer('quota_limit');
            $table->integer('quota_used')->default(0);
            $table->timestamp('started_at')->nullable();
            $table->timestamp('expired_at')->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};

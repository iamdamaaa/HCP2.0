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
        Schema::create('affiliate_sessions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('partner_id')->constrained('partners')->onDelete('cascade');
            $table->string('unique_code')->unique();
            $table->timestamp('start')->nullable();
            $table->timestamp('end')->nullable();
            $table->boolean('active')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('affiliate_sessions');
    }
};

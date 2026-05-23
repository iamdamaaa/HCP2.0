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
        Schema::create('prices', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('service_id')->constrained('services')->onDelete('restrict');
            $table->enum('duration_type', ['4_hours', '8_hours', '12_hours', '1_day', '2_days', '3_days']);
            $table->decimal('price', 12, 2);
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prices');
    }
};

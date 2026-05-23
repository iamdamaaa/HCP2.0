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
        Schema::create('partners', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->constrained('users')->onDelete('cascade');
            $table->enum('type', ['shop', 'boarding_house', 'affiliate']);
            $table->string('name');
            $table->string('address');
            $table->boolean('pick_up_delivery_same')->default(false);
            $table->decimal('commission_percent', 5, 2)->default(0);
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('partners');
    }
};

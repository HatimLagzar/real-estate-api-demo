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
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->enum('property_type', ['residential', 'commercial', 'land']);
            $table->json('features')->nullable(); // array of strings, e.g. ["pool", "garage"]
            $table->decimal('price', 12, 2)->nullable();
            $table->decimal('taxes', 12, 2)->nullable();
            $table->decimal('income', 12, 2)->nullable();
            $table->decimal('expenditure', 12, 2)->nullable();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('properties');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('umkm_products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('owner_name');
            $table->string('category'); // Kuliner, Fashion, Kerajinan, Pertanian/Perkebunan, Minuman
            $table->string('district'); // Kraksaan, Paiton, Sukapura, Gading, etc.
            $table->text('description');
            $table->decimal('price', 12, 2)->default(0);
            $table->string('price_unit')->default('pcs');
            $table->string('phone')->nullable();
            $table->string('image')->nullable();
            $table->string('website_url')->nullable();
            $table->boolean('is_featured')->default(true);
            $table->boolean('is_verified')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('umkm_products');
    }
};

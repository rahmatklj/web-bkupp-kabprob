<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('market_prices', function (Blueprint $table) {
            $table->id();
            $table->string('commodity_name'); // Beras Medium, Minyak Goreng, Gula Pasir, Cabai Rawit, Daging Sapi, Daging Ayam, Bawang Merah
            $table->string('unit'); // kg, liter, bungkus
            $table->decimal('price_today', 10, 2);
            $table->decimal('price_yesterday', 10, 2)->nullable();
            $table->string('status')->default('stabil'); // naik, turun, stabil
            $table->string('market_location')->default('Pasar Kraksaan & Semampir');
            $table->date('updated_date');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('market_prices');
    }
};

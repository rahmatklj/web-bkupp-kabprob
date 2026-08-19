<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('category'); // Koperasi, Usaha Mikro, Perdagangan, Perindustrian, Metrologi
            $table->string('icon')->default('fa-handshake');
            $table->text('summary');
            $table->longText('requirements')->nullable();
            $table->longText('procedure')->nullable();
            $table->string('service_time')->default('1-3 Hari Kerja');
            $table->string('cost')->default('Gratis (Rp 0)');
            $table->string('external_url')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};

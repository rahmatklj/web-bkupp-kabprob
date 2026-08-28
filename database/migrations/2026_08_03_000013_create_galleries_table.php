<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('galleries')) {
            Schema::create('galleries', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->string('type')->default('image'); // 'image' or 'video'
                $table->string('category')->default('Dokumentasi Kegiatan');
                $table->longText('file_path')->nullable();
                $table->string('youtube_url')->nullable();
                $table->text('caption')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('galleries');
    }
};

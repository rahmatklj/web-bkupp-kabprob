<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('org_members', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('position');
            $table->string('type')->default('personel'); // personel, kelompok_fungsional
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->string('photo')->nullable();
            $table->integer('order')->default(1);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('org_members');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('services', 'location')) {
            Schema::table('services', function (Blueprint $table) {
                $table->string('location')->nullable()->default('Loket MPP Kraksaan')->after('service_time');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('services', 'location')) {
            Schema::table('services', function (Blueprint $table) {
                $table->dropColumn('location');
            });
        }
    }
};

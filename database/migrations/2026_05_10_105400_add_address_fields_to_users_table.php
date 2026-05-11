<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('sitio')->nullable()->after('mobile');
            $table->string('barangay')->nullable()->after('sitio');
            $table->string('city')->nullable()->after('barangay');
            $table->string('landmark')->nullable()->after('city');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['sitio', 'barangay', 'city', 'landmark']);
        });
    }
};

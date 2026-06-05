<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('guru', function (Blueprint $table) {
            // Menambahkan field nik setelah kolom nama
            $table->string('nik', 16)->unique()->after('nama')->nullable();
        });
    }

    public function down()
    {
        Schema::table('guru', function (Blueprint $table) {
            $table->dropColumn('nik');
        });
    }
};

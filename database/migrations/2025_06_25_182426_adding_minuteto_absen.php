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
        Schema::table('absensi', function (Blueprint $table) {
            $table->date('tanggal')->nullable();
            $table->integer('menit')->default(0);
            $table->string('deskripsi')->default('masuk');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('absensi', function (Blueprint $table) {
            $table->dropColumn('menit');
            $table->dropColumn('tanggal');
            $table->dropColumn('deskripsi');
        });
    }
};

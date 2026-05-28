<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('absensi', function (Blueprint $table) {
            // 1. Hapus foreign key dan kolom lama yang tidak digunakan
            // Catatan: Jika database kamu menggunakan SQLite, dropForeign harus dipisah atau dilewati.
            // Di MySQL/PostgreSQL, ini wajib agar tidak error saat menghapus kolom.
            if (Schema::hasColumn('absensi', 'jabatan_id')) {
                $table->dropForeign(['jabatan_id']); // Hapus relasi foreign key dulu
                $table->dropColumn('jabatan_id');    // Baru hapus kolomnya
            }

            if (Schema::hasColumn('absensi', 'menit')) {
                $table->dropColumn('menit');
            }

            if (Schema::hasColumn('absensi', 'deskripsi')) {
                $table->dropColumn('deskripsi');
            }

            // 2. Tambahkan kolom-kolom baru untuk kebutuhan payroll gajimu
            $table->string('jam_masuk', 10)->nullable()->after('tanggal');
            $table->string('jam_pulang', 10)->nullable()->after('jam_masuk');
            $table->integer('jml_jam_kerja')->default(0)->after('jam_pulang');
            $table->string('status_kehadiran', 50)->after('jml_jam_kerja');
            $table->integer('potongan_jam')->default(0)->after('status_kehadiran');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('absensi', function (Blueprint $table) {
            // Logika rollback: Kembalikan struktur ke bentuk semula jika migrasi dibatalkan

            // Hapus kolom baru
            $table->dropColumn(['jam_masuk', 'jam_pulang', 'jml_jam_kerja', 'status_kehadiran', 'potongan_jam']);

            // Kembalikan kolom lama
            $table->foreignId('jabatan_id')->nullable()->constrained('jabatans');
            $table->integer('menit')->nullable();
            $table->string('deskripsi')->nullable();
        });
    }
};

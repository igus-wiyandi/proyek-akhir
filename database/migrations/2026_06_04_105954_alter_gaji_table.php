<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('gaji', function (Blueprint $table) {
            // Hapus kolom lama yang tidak relevan
            $table->dropColumn(['nama', 'nominal']);

            if (Schema::hasColumn('gaji', 'jabatan_id')) {
                // Hapus foreign key jika ada sebelum drop kolom
                $table->dropForeign(['jabatan_id']);
                $table->dropColumn('jabatan_id');
            }

            // Tambahkan kolom baru standar Payroll
            $table->foreignId('guru_id')->after('id')->constrained('guru')->onDelete('cascade');
            $table->date('periode_mulai')->after('guru_id');
            $table->date('periode_akhir')->after('periode_mulai');

            $table->decimal('total_jam_standar', 5, 2)->default(53.20); // Standar 1 bulan
            $table->integer('total_potongan_jam')->default(0);
            $table->decimal('total_jam_bersih', 5, 2)->default(0);

            $table->integer('tarif_per_jam')->default(45000);
            $table->integer('tunjangan_tambahan')->default(0);
            $table->integer('total_gaji_bersih')->default(0);

            // Tipe Data JSON untuk menyimpan rincian mingguan sebagai riwayat permanen
            $table->json('rincian_mingguan')->nullable();

            $table->string('status_pembayaran', 50)->default('Belum Dibayar');
        });
    }

    public function down()
    {
        Schema::table('gaji', function (Blueprint $table) {
            // Rollback logika
            $table->dropForeign(['guru_id']);
            $table->dropColumn([
                'guru_id',
                'periode_mulai',
                'periode_akhir',
                'total_jam_standar',
                'total_potongan_jam',
                'total_jam_bersih',
                'tarif_per_jam',
                'tunjangan_tambahan',
                'total_gaji_bersih',
                'rincian_mingguan',
                'status_pembayaran'
            ]);

            $table->string('nama')->nullable();
            $table->integer('nominal')->nullable();
            $table->integer('jabatan_id')->nullable();
        });
    }
};

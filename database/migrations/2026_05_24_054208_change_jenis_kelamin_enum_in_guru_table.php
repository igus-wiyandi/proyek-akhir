<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        \DB::statement("ALTER TABLE guru MODIFY jenis_kelamin ENUM('Pria', 'Wanita') NOT NULL");
    }

    public function down(): void
    {
        \DB::statement("ALTER TABLE guru MODIFY jenis_kelamin ENUM('Laki-laki', 'Perempuan') NOT NULL");
    }
};

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
        Schema::create('status12', function (Blueprint $table) {
            $table->id();
            $table->enum('status', ['tersedia', 'tidak tersedia'])->default('tersedia');
            $table->unsignedBigInteger('guru_id');
            $table->unsignedBigInteger('mapel12_id');
            $table->timestamps();
            $table->foreign('guru_id')->references('id')->on('guru')->onDelete('cascade');
            $table->foreign('mapel12_id')->references('id')->on('mapel12')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('status12');
    }
};

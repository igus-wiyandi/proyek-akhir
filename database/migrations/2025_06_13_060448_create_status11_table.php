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
        Schema::create('status11', function (Blueprint $table) {
            $table->id();
            $table->enum('status', ['tersedia', 'tidak tersedia'])->default('tersedia');
            $table->unsignedBigInteger('guru_id');
            $table->unsignedBigInteger('mapel11_id');
            $table->timestamps();
            $table->foreign('mapel11_id')->references('id')->on('mapel')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('status11');
    }
};

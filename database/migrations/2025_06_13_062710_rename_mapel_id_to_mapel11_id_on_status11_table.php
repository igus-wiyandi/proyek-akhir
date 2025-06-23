<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('status11', function (Blueprint $table) {
            $table->renameColumn('mapel_id', 'mapel11_id');
        });
    }

    public function down(): void
    {
        Schema::table('status11', function (Blueprint $table) {
            $table->renameColumn('mapel11_id', 'mapel_id');
        });
    }
};

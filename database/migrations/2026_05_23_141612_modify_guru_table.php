<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('guru', function (Blueprint $table) {
            $table->dropColumn(['email', 'password']);
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade')->after('id');
            $table->string('nik', 16)->nullable()->unique()->after('nama');
        });
    }

    public function down(): void
    {
        Schema::table('guru', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn(['user_id', 'nik']);
            $table->string('email')->after('nama');
            $table->string('password')->after('email');
        });
    }
};

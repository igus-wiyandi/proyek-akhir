<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('admin')->insert([
            'nama' => 'Administrator',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('rahasia123'), // Password akan di-hash agar aman
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}

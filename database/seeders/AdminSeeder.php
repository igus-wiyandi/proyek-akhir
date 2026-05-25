<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Admin;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // Buat akun di tabel users
        $user = User::create([
            'name'     => 'Administrator',
            'email'    => 'admin@sekolah.sch.id',
            'password' => Hash::make('password123'),
            'role'     => 'admin',
        ]);

        // Buat data di tabel admin
        Admin::create([
            'user_id' => $user->id,
            'nama'    => 'Administrator',
        ]);
    }
}

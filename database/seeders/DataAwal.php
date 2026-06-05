<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DataAwal extends Seeder
{
    public function run()
    {
        // 1. SEEDER TABLE: guru
        DB::table('guru')->insert([
            [
                'nama' => 'Andini Komalasari, S.Si',
                'nik' => '1901031304930001',
                'email' => 'andini1@gmail.com',
                'password' => Hash::make('andini'),
                'no_hp' => '086754235617390',
                'alamat' => 'jalan raya selindung baru pangkalpinang',
                'created_at' => Carbon::parse('2025-06-23 21:58:50'),
                'updated_at' => Carbon::parse('2026-04-21 06:59:57'),
            ],
            [
                'nama' => 'Siti Hajar, S.Ag.',
                'nik' => '1901030404990001',
                'email' => 'siti2@gmail.com',
                'password' => Hash::make('siti'),
                'no_hp' => '08736245627848',
                'alamat' => 'jalan raya air anyir',
                'created_at' => Carbon::parse('2025-06-25 06:36:10'),
                'updated_at' => Carbon::parse('2025-06-25 06:36:10'),
            ],
            [
                'nama' => 'Padilatul Mukarromah, S.Sos',
                'nik' => '1901032506930001',
                'email' => 'padila3@gmail.com',
                'password' => Hash::make('padilatur'),
                'no_hp' => '0877663628344',
                'alamat' => 'jalan raya baturusa',
                'created_at' => Carbon::parse('2025-06-25 06:37:20'),
                'updated_at' => Carbon::parse('2025-06-25 06:37:20'),
            ],
            [
                'nama' => 'Tonny Aprizal Muchlis, SE.',
                'nik' => '1901031209200001',
                'email' => 'tonny4@gmail.com',
                'password' => Hash::make('tony'),
                'no_hp' => '08223561784728',
                'alamat' => 'jalan raya serandang',
                'created_at' => Carbon::parse('2025-06-25 06:38:41'),
                'updated_at' => Carbon::parse('2025-06-25 06:38:57'),
            ],
            [
                'nama' => 'Fajar Shydik, S.Kom',
                'nik' => '1901033007010001',
                'email' => 'fajar5@gmail.com',
                'password' => Hash::make('fajar'),
                'no_hp' => '086754366378823',
                'alamat' => 'jalan raya plaben',
                'created_at' => Carbon::parse('2025-06-25 06:39:52'),
                'updated_at' => Carbon::parse('2025-06-25 06:39:52'),
            ],
        ]);

        // 2. SEEDER TABLE: kategori
        DB::table('kategori')->insert([
            ['nama' => 'Kepala Madrasah', 'created_at' => Carbon::parse('2025-06-23 22:03:48'), 'updated_at' => Carbon::parse('2025-06-23 22:03:48')],
            ['nama' => 'Wakil Kepala Madrasah', 'created_at' => Carbon::parse('2025-06-25 07:01:06'), 'updated_at' => Carbon::parse('2025-06-25 07:01:19')],
            ['nama' => 'Pembina UKS', 'created_at' => Carbon::parse('2025-06-25 07:01:43'), 'updated_at' => Carbon::parse('2025-06-25 07:01:43')],
            ['nama' => 'Pustakawan', 'created_at' => Carbon::parse('2025-06-25 07:02:43'), 'updated_at' => Carbon::parse('2025-06-25 07:02:43')],
            ['nama' => 'Pembina Komputer', 'created_at' => Carbon::parse('2025-06-25 07:03:04'), 'updated_at' => Carbon::parse('2025-06-25 07:03:04')],
        ]);

        // 3. SEEDER TABLE: jabatan
        // Catatan: Field terakhir diasumsikan bernama 'honor'. Sesuaikan jika nama kolom di database Anda berbeda.
        DB::table('jabatan')->insert([
            ['guru_id' => 1, 'kategori_id' => 1, 'honor' => 450000, 'created_at' => Carbon::parse('2025-06-23 22:04:08'), 'updated_at' => Carbon::parse('2025-06-29 08:54:03')],
            ['guru_id' => 2, 'kategori_id' => 2, 'honor' => 400000, 'created_at' => Carbon::parse('2025-06-25 07:03:22'), 'updated_at' => Carbon::parse('2025-06-29 08:54:24')],
            ['guru_id' => 3, 'kategori_id' => 3, 'honor' => 300000, 'created_at' => Carbon::parse('2025-06-25 07:03:35'), 'updated_at' => Carbon::parse('2025-06-29 08:54:36')],
            ['guru_id' => 4, 'kategori_id' => 4, 'honor' => 350000, 'created_at' => Carbon::parse('2025-06-25 07:03:52'), 'updated_at' => Carbon::parse('2025-06-29 08:54:50')],
            ['guru_id' => 5, 'kategori_id' => 5, 'honor' => 300000, 'created_at' => Carbon::parse('2025-06-25 07:04:04'), 'updated_at' => Carbon::parse('2025-06-29 08:55:00')],
        ]);

        // 4. SEEDER TABLE: mapel
        DB::table('mapel')->insert([
            ['nama' => 'Biologi', 'jam_mulai' => '14:05:00', 'jam_selesai' => '14:40:00', 'tanggal' => '2025-06-23', 'guru_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Alquran Hadist', 'jam_mulai' => '07:15:00', 'jam_selesai' => '08:25:00', 'tanggal' => '2025-06-24', 'guru_id' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Seni Budaya', 'jam_mulai' => '07:15:00', 'jam_selesai' => '08:25:00', 'tanggal' => '2025-06-25', 'guru_id' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Ekonomi', 'jam_mulai' => '08:25:00', 'jam_selesai' => '09:35:00', 'tanggal' => '2025-06-24', 'guru_id' => 4, 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Sejarah Kebudayaan Islam', 'jam_mulai' => '11:10:00', 'jam_selesai' => '12:55:00', 'tanggal' => '2025-06-25', 'guru_id' => 5, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // 5. SEEDER TABLE: mapel11
        DB::table('mapel11')->insert([
            ['nama' => 'Biologi', 'jam_mulai' => '14:40:00', 'jam_selesai' => '15:15:00', 'tanggal' => '2025-06-24', 'guru_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Alquran Hadist', 'jam_mulai' => '07:50:00', 'jam_selesai' => '09:00:00', 'tanggal' => '2025-06-26', 'guru_id' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Seni Budaya', 'jam_mulai' => '10:00:00', 'jam_selesai' => '11:10:00', 'tanggal' => '2025-06-25', 'guru_id' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Ekonomi', 'jam_mulai' => '07:15:00', 'jam_selesai' => '08:25:00', 'tanggal' => '2025-06-24', 'guru_id' => 4, 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Sejarah Kebudayaan Islam', 'jam_mulai' => '08:25:00', 'jam_selesai' => '09:35:00', 'tanggal' => '2025-06-25', 'guru_id' => 5, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // 6. SEEDER TABLE: mapel12
        DB::table('mapel12')->insert([
            ['nama' => 'Matematika', 'jam_mulai' => '07:15:00', 'jam_selesai' => '08:25:00', 'tanggal' => '2025-06-25', 'guru_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Alquran Hadist', 'jam_mulai' => '12:55:00', 'jam_selesai' => '14:05:00', 'tanggal' => '2025-06-26', 'guru_id' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Seni Budaya', 'jam_mulai' => '08:25:00', 'jam_selesai' => '09:00:00', 'tanggal' => '2025-06-25', 'guru_id' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Sejarah', 'jam_mulai' => '07:50:00', 'jam_selesai' => '09:00:00', 'tanggal' => '2025-06-26', 'guru_id' => 4, 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'TIK', 'jam_mulai' => '08:25:00', 'jam_selesai' => '09:35:00', 'tanggal' => '2025-06-23', 'guru_id' => 5, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Mahasiswa;

class MahasiswaSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil semua user dengan role 'mahasiswa'
        $mahasiswaUsers = User::where('role', 'mahasiswa')->get();

        // Ambil daftar dosen wali (user dengan role 'dosen_wali')
        $dosenWali = User::where('role', 'dosen_wali')->get();

        if ($dosenWali->isEmpty()) {
            // Jika tidak ada dosen wali, set null
            $dosenWaliIds = collect([null]);
        } else {
            $dosenWaliIds = $dosenWali->pluck('id');
        }

        // Data prodi dan nim awal
        $prodiList = ['Informatika', 'Sistem Informasi', 'Teknik Elektro', 'Manajemen', 'Akuntansi'];
        $angkatanList = [2021, 2022, 2023, 2024];
        $semesterList = [2, 4, 6, 8];

        foreach ($mahasiswaUsers as $index => $user) {
            // Generate NIM unik (misal: 2100012345)
            $nim = '21000' . str_pad($user->id, 5, '0', STR_PAD_LEFT);

            // Pilih dosen wali secara acak (atau null jika tidak ada)
            $dosenWaliId = $dosenWaliIds->random() ?? null;

            // Data prodi, angkatan, semester berdasarkan indeks atau acak
            $prodi = $prodiList[array_rand($prodiList)];
            $angkatan = $angkatanList[array_rand($angkatanList)];
            $semester = $semesterList[array_rand($semesterList)];
            $ipk = rand(200, 350) / 100; // antara 2.00 - 3.50

            Mahasiswa::create([
                'user_id' => $user->id,
                'npm' => $nim,
                'prodi' => $prodi,
                'angkatan' => $angkatan,
                'semester' => $semester,
                'ipk' => $ipk,
                'dosen_wali_id' => $dosenWaliId,
            ]);
        }
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin
        User::create([
            'username' => 'admin',
            'name' => 'Administrator',
            'email' => 'admin@skepma.test',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Kaprodi
        User::create([
            'username' => 'kaprodi',
            'name' => 'Kepala Program Studi',
            'email' => 'kaprodi@skepma.test',
            'password' => Hash::make('password'),
            'role' => 'kaprodi',
        ]);

        // Dosen Wali (3 contoh)
        $dosen = [
            ['username' => 'dosen1', 'name' => 'Dr. Ahmad Syarif, M.Kom', 'email' => 'dosen1@skepma.test'],
            ['username' => 'dosen2', 'name' => 'Ir. Siti Aminah, M.Sc', 'email' => 'dosen2@skepma.test'],
            ['username' => 'dosen3', 'name' => 'Drs. Budi Santoso, M.Pd', 'email' => 'dosen3@skepma.test'],
        ];

        foreach ($dosen as $d) {
            User::create([
                'username' => $d['username'],
                'name' => $d['name'],
                'email' => $d['email'],
                'password' => Hash::make('password'),
                'role' => 'dosen_wali',
            ]);
        }

        // Mahasiswa (10 contoh)
        for ($i = 1; $i <= 10; $i++) {
            User::create([
                'username' => "mahasiswa{$i}",
                'name' => "Mahasiswa {$i}",
                'email' => "mahasiswa{$i}@skepma.test",
                'password' => Hash::make('password'),
                'role' => 'mahasiswa',
            ]);
        }
    }
}

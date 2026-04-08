<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\DosenWali;

class DosenWaliSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil semua user dengan role 'dosen_wali'
        $users = User::where('role', 'dosen_wali')->get();

        // Data NIP untuk masing-masing dosen (sesuaikan dengan nama dosen di UserSeeder)
        $nipList = [
            'dosen1' => '197001012005011001',
            'dosen2' => '197502152010022002',
            'dosen3' => '198003202015031003',
        ];

        foreach ($users as $user) {
            // Tentukan NIP berdasarkan username, jika tidak ada pakai default
            $nip = $nipList[$user->username] ?? '999999999999999999';

            DosenWali::create([
                'user_id' => $user->id,
                'nip' => $nip,
            ]);
        }
    }
}

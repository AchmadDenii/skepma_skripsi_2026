<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasterPoinSeeder extends Seeder
    {
        /**
         * Run the database seeds.
         */
            public function run(): void
            {
                DB::table('master_poin_sertifikat')->insert([
            // LKTI-Internasional
            [
                'kelompok_kegiatan' => 'LKTI',
                'tingkat' => 'Internasional',
                'peran' => 'Juara I/II/III',
                'kode' => 'LKTI-INTER-JRA',
                'poin' => 150,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kelompok_kegiatan' => 'LKTI',
                'tingkat' => 'Internasional',
                'peran' => 'Finalis',
                'kode' => 'LKTI-INTER-FIN',
                'poin' => 100,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kelompok_kegiatan' => 'LKTI',
                'tingkat' => 'Internasional',
                'peran' => 'Peserta',
                'kode' => 'LKTI-INTER-PST',
                'poin' => 75,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            //LKTI-Nasional
            [
                'kelompok_kegiatan' => 'LKTI',
                'tingkat' => 'Nasional',
                'peran' => 'Juara I/II/III',
                'kode' => 'LKTI-NAS-JRA',
                'poin' => 100,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kelompok_kegiatan' => 'LKTI',
                'tingkat' => 'Nasional',
                'peran' => 'Finalis',
                'kode' => 'LKTI-NAS-FIN',
                'poin' => 75,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kelompok_kegiatan' => 'LKTI',
                'tingkat' => 'Nasional',
                'peran' => 'Peserta (Lolos)',
                'kode' => 'LKTI-NAS-PST-1',
                'poin' => 60,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kelompok_kegiatan' => 'LKTI',
                'tingkat' => 'Nasional',
                'peran' => 'Peserta (Tidak Lolos)',
                'kode' => 'LKTI-NAS-PST-2',
                'poin' => 30,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            //LKTI-Regional
            [
                'kelompok_kegiatan' => 'LKTI',
                'tingkat' => 'Regional',
                'peran' => 'Juara I/II/III',
                'kode' => 'LKTI-REG-JRA',
                'poin' => 75,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kelompok_kegiatan' => 'LKTI',
                'tingkat' => 'Regional',
                'peran' => 'Finalis',
                'kode' => 'LKTI-REG-FIN',
                'poin' => 50,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kelompok_kegiatan' => 'LKTI',
                'tingkat' => 'Regional',
                'peran' => 'Peserta',
                'kode' => 'LKTI-REG-PST',
                'poin' => 30,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
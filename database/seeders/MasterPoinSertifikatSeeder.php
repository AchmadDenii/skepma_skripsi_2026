<?php

namespace Database\Seeders;

use App\Models\MasterPoinSertifikat;
use Illuminate\Database\Seeder;

class MasterPoinSertifikatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         MasterPoinSertifikat::insert([
            [
                'kategori' => 'akademik',
                'jenis_kegiatan' => 'LKTI',
                'peran' => 'Juara',
                'tingkat' => 'Internasional',
                'kode' => 'LKTI-AK-JUARA-INT',
                'poin' => 150,
            ],
            [
                'kategori' => 'akademik',
                'jenis_kegiatan' => 'LKTI',
                'peran' => 'Peserta',
                'tingkat' => 'Nasional',
                'kode' => 'LKTI-AK-PST-NAS',
                'poin' => 60,
            ],
            [
                'kategori' => 'non-akademik',
                'jenis_kegiatan' => 'KHMJ',
                'peran' => 'Panitia',
                'tingkat' => null,
                'kode' => 'KHMJ-NA-PNT',
                'poin' => 30,
            ],
        ]);
    }
}
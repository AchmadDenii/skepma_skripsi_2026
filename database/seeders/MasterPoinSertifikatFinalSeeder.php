<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasterPoinSertifikatFinalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
           ['kategori'=>'akademik','jenis_kegiatan'=>'LKTI','peran'=>'Juara I/II/III','tingkat'=>'Internasional','kode'=>'LKTI-AK-INT-JRA','poin'=>150],
            ['kategori'=>'akademik','jenis_kegiatan'=>'LKTI','peran'=>'Finalis','tingkat'=>'Internasional','kode'=>'LKTI-AK-INT-FIN','poin'=>100],
            ['kategori'=>'akademik','jenis_kegiatan'=>'LKTI','peran'=>'Peserta','tingkat'=>'Internasional','kode'=>'LKTI-AK-INT-PST','poin'=>75], 
        ];
        DB::table('master_poin_sertifikat')->insert($data);
    }
}

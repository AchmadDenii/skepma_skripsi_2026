<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JenisKegiatanSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('jenis_kegiatan')->delete();

        DB::table('jenis_kegiatan')->insert([
            ['nama'=>'LKTI','deskripsi'=>'Lomba Karya Tulis Ilmiah','kategori'=>'akademik'],
            ['nama'=>'LKI','deskripsi'=>'Lomba Karya Inovatif','kategori'=>'akademik'],
            ['nama'=>'KFKI','deskripsi'=>'Kegiatan Forum Keilmuan Ilmiah','kategori'=>'akademik'],

            ['nama'=>'PMB','deskripsi'=>'Pekan Mahasiswa Berprestasi','kategori'=>'non-akademik'],
            ['nama'=>'HMJ','deskripsi'=>'Himpunan Mahasiswa Jurusan','kategori'=>'non-akademik'],
            ['nama'=>'KHMJ','deskripsi'=>'Kepengurusan HMJ','kategori'=>'non-akademik'],
            ['nama'=>'UKM','deskripsi'=>'Unit Kegiatan Mahasiswa','kategori'=>'non-akademik'],
            ['nama'=>'KUKM','deskripsi'=>'Kepengurusan UKM','kategori'=>'non-akademik'],
            ['nama'=>'ASPROPT','deskripsi'=>'Asosiasi Profesi','kategori'=>'non-akademik'],
            ['nama'=>'PELKEPPRI','deskripsi'=>'Pelatihan Kepemimpinan','kategori'=>'non-akademik'],
            ['nama'=>'KEPSOSCSR','deskripsi'=>'Kegiatan Sosial/CSR','kategori'=>'non-akademik'],
            ['nama'=>'UPCR','deskripsi'=>'Upacara','kategori'=>'non-akademik'],
            ['nama'=>'PBN','deskripsi'=>'Pelatihan Bela Negara','kategori'=>'non-akademik'],
            ['nama'=>'KEGKAMPUS','deskripsi'=>'Kegiatan Kampus','kategori'=>'non-akademik'],
            ['nama'=>'PEMKAHPOT','deskripsi'=>'Pengembangan Kompetensi','kategori'=>'non-akademik'],
            ['nama'=>'PEMWIR','deskripsi'=>'Kewirausahaan','kategori'=>'non-akademik'],
        ]);
    }
}
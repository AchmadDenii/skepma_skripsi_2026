<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class MasterPoinImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row) {

            try {

                // VALIDASI WAJIB
                if (
                    empty($row['kategori']) ||
                    empty($row['jenis_kegiatan']) ||
                    empty($row['peran']) ||
                    empty($row['kode']) ||
                    empty($row['poin'])
                ) {
                    throw new \Exception("Kolom wajib kosong");
                }

                // VALIDASI KATEGORI
                if (!in_array(strtolower($row['kategori']), ['akademik','non-akademik'])) {
                    throw new \Exception("Kategori tidak valid");
                }

                // MAPPING JENIS KEGIATAN
                $jenis = DB::table('jenis_kegiatan')
                    ->where('nama', $row['jenis_kegiatan'])
                    ->first();

                if (!$jenis) {
                    throw new \Exception("Jenis kegiatan tidak ditemukan");
                }

                // INSERT / UPDATE
                DB::table('master_poin_sertifikat')->updateOrInsert(
                    ['kode' => $row['kode']],
                    [
                        'kategori' => strtolower($row['kategori']),
                        'jenis_kegiatan_id' => $jenis->id,
                        'peran' => $row['peran'],
                        'tingkat' => $row['tingkat'] ?? null,
                        'kode' => $row['kode'],
                        'poin' => (int)$row['poin'],
                        'butuh_bukti' => 1,
                        'aktif' => 1
                    ]
                );

            } catch (\Exception $e) {
                // sementara: skip error (nanti kita bisa improve logging)
                continue;
            }
        }
    }
}
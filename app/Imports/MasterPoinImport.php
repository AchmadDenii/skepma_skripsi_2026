<?php

namespace App\Imports;

use App\Models\JenisKegiatan;
use App\Models\MasterPoinSertifikat;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Support\Str;

class MasterPoinImport implements ToModel, WithStartRow, WithValidation
{
    public function startRow(): int
    {
        return 2; // Header 1 baris, data mulai baris ke-2
    }

    public function model(array $row)
    {
        if (count($row) < 6) {
            throw new \Exception("Data tidak lengkap: hanya " . count($row) . " kolom. Butuh 6 kolom.");
        }

        $namaKegiatan = trim($row[0] ?? '');
        $tingkat      = trim($row[1] ?? '');
        $peran        = trim($row[2] ?? '');
        $kode         = trim($row[3] ?? '');
        $poin         = trim($row[4] ?? '');
        $kategoriRaw  = trim($row[5] ?? '');

        // Normalisasi kategori ke format database: "Akademik" atau "Non-Akademik"
        $kategoriLower = strtolower($kategoriRaw);
        if ($kategoriLower === 'akademik') {
            $kategori = 'Akademik';
        } elseif ($kategoriLower === 'non-akademik') {
            $kategori = 'Non-Akademik';
        } else {
            throw new \Exception("Kategori harus 'Akademik' atau 'Non-Akademik', menerima: '$kategoriRaw'");
        }

        // Validasi dasar
        if (empty($namaKegiatan)) throw new \Exception("Jenis kegiatan kosong");
        if (empty($kode)) throw new \Exception("Kode singkatan kosong");
        if (!is_numeric($poin)) throw new \Exception("Poin harus angka, terima: '$poin'");

        // Cek unique kode
        if (MasterPoinSertifikat::where('kode', $kode)->exists()) {
            throw new \Exception("Kode '$kode' sudah terdaftar");
        }

        // Cari atau buat JenisKegiatan, simpan kategori sesuai format database
        $jenisKegiatan = JenisKegiatan::firstOrCreate(
            ['nama' => $namaKegiatan],
            ['kategori' => $kategori, 'slug' => Str::slug($namaKegiatan)]
        );

        // Update jika kategori berbeda
        if ($jenisKegiatan->kategori !== $kategori) {
            $jenisKegiatan->update(['kategori' => $kategori]);
        }

        // Simpan ke master_poin_sertifikat
        return new MasterPoinSertifikat([
            'jenis_kegiatan_id' => $jenisKegiatan->id,
            'tingkat'           => $tingkat,
            'peran'             => $peran,
            'kode'              => $kode,
            'poin'              => (int) $poin,
        ]);
    }

    public function rules(): array
    {
        return [
            '0' => 'required|string',
            '1' => 'nullable|string',
            '2' => 'required|string',
            '3' => 'required|string|unique:master_poin_sertifikat,kode',
            '4' => 'required|numeric|min:0',
            // Kolom 5 tidak perlu validasi in: karena sudah kita handle manual di model, 
            // tapi biar tetap ada pesan error yang bagus:
            '5' => 'required',
        ];
    }

    public function customValidationMessages()
    {
        return [
            '0.required' => 'Kolom A (Jenis Kegiatan) wajib diisi.',
            '2.required' => 'Kolom C (Prestasi/Peran) wajib diisi.',
            '3.required' => 'Kolom D (Kode Singkatan) wajib diisi.',
            '3.unique'   => 'Kode Singkatan :input sudah terdaftar.',
            '4.required' => 'Kolom E (Poin) wajib diisi.',
            '4.numeric'  => 'Poin harus berupa angka.',
            '4.min'      => 'Poin minimal 0.',
            '5.required' => 'Kolom F (Kategori) wajib diisi.',
        ];
    }
}

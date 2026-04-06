<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasterPoinSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('master_poin_sertifikat')->delete();

        $jenisMap = DB::table('jenis_kegiatan')
            ->pluck('id', 'nama')
            ->toArray();

        $data = [

            // ================= LKTI =================
            ['LKTI','Internasional','Juara I/II/III','LKTI-INTER-JRA',150,'akademik'],
            ['LKTI','Internasional','Finalis','LKTI-INTER-FIN',100,'akademik'],
            ['LKTI','Internasional','Peserta','LKTI-INTER-PST',75,'akademik'],

            ['LKTI','Nasional','Juara I/II/III','LKTI-NAS-JRA',100,'akademik'],
            ['LKTI','Nasional','Finalis (Pimnas)','LKTI-NAS-FIN',75,'akademik'],
            ['LKTI','Nasional','Peserta (Lolos)','LKTI-NAS-PST-1',60,'akademik'],
            ['LKTI','Nasional','Peserta (Tidak Lolos)','LKTI-NAS-PST-2',30,'akademik'],

            ['LKTI','Regional','Juara I/II/III','LKTI-REG-JRA',75,'akademik'],
            ['LKTI','Regional','Finalis','LKTI-REG-FIN',50,'akademik'],
            ['LKTI','Regional','Peserta','LKTI-REG-PST',30,'akademik'],

            ['LKTI','Institut','Juara I/II/III','LKTI-INST-JRA',50,'akademik'],
            ['LKTI','Institut','Finalis','LKTI-INST-FIN',40,'akademik'],
            ['LKTI','Institut','Peserta','LKTI-INST-PST',20,'akademik'],

            ['LKTI','Fakultas','Juara I/II/III','LKTI-FAK-JRA',40,'akademik'],
            ['LKTI','Fakultas','Finalis','LKTI-FAK-FIN',30,'akademik'],
            ['LKTI','Fakultas','Peserta','LKTI-FAK-PST',15,'akademik'],

            ['LKTI','Jurusan','Juara I/II/III','LKTI-JUR-JRA',30,'akademik'],
            ['LKTI','Jurusan','Finalis','LKTI-JUR-FIN',20,'akademik'],
            ['LKTI','Jurusan','Peserta','LKTI-JUR-PST',10,'akademik'],

            // ================= LKI =================
            ['LKI','Internasional','Juara I/II/III','LKI-INTER-JRA',150,'akademik'],
            ['LKI','Internasional','Finalis','LKI-INTER-FIN',100,'akademik'],
            ['LKI','Internasional','Peserta','LKI-INTER-PST',75,'akademik'],

            ['LKI','Nasional','Juara I/II/III','LKI-NAS-JRA',100,'akademik'],
            ['LKI','Nasional','Finalis (Pimnas)','LKI-NAS-FIN',75,'akademik'],
            ['LKI','Nasional','Peserta (Lolos)','LKI-NAS-PST-1',60,'akademik'],
            ['LKI','Nasional','Peserta (Tidak Lolos)','LKI-NAS-PST-2',30,'akademik'],

            ['LKI','Regional','Juara I/II/III','LKI-REG-JRA',75,'akademik'],
            ['LKI','Regional','Finalis','LKI-REG-FIN',50,'akademik'],
            ['LKI','Regional','Peserta','LKI-REG-PST',30,'akademik'],

            ['LKI','Institut','Juara I/II/III','LKI-INST-JRA',50,'akademik'],
            ['LKI','Institut','Finalis','LKI-INST-FIN',40,'akademik'],
            ['LKI','Institut','Peserta','LKI-INST-PST',20,'akademik'],

            ['LKI','Fakultas','Juara I/II/III','LKI-FAK-JRA',40,'akademik'],
            ['LKI','Fakultas','Finalis','LKI-FAK-FIN',30,'akademik'],
            ['LKI','Fakultas','Peserta','LKI-FAK-PST',15,'akademik'],

            ['LKI','Jurusan','Juara I/II/III','LKI-JUR-JRA',30,'akademik'],
            ['LKI','Jurusan','Finalis','LKI-JUR-FIN',20,'akademik'],
            ['LKI','Jurusan','Peserta','LKI-JUR-PST',10,'akademik'],

            // ================= KFKI =================
            ['KFKI','Internasional','Pembicara','KFKI-INT-TLK',150,'akademik'],
            ['KFKI','Internasional','Peserta','KFKI-INT-PST',75,'akademik'],
            ['KFKI','Nasional','Pembicara','KFKI-NAS-TLK',100,'akademik'],
            ['KFKI','Nasional','Peserta','KFKI-NAS-PST',50,'akademik'],
            ['KFKI','Regional','Pembicara','KFKI-REG-TLK',80,'akademik'],
            ['KFKI','Regional','Peserta','KFKI-REG-PST',40,'akademik'],
            ['KFKI','Institut','Pembicara','KFKI-INST-TLK',60,'akademik'],
            ['KFKI','Institut','Peserta','KFKI-INST-PST',30,'akademik'],
            ['KFKI','Fakultas','Pembicara','KFKI-FAK-TLK',40,'akademik'],
            ['KFKI','Fakultas','Peserta','KFKI-FAK-PST',20,'akademik'],
            ['KFKI','Jurusan','Pembicara','KFKI-JUR-TLK',30,'akademik'],
            ['KFKI','Jurusan','Peserta','KFKI-JUR-PST',15,'akademik'],

            // ================= PMB =================
            ['PMB','Internasional','Juara I/II/III','PMB-INT-JRA',150,'non-akademik'],
            ['PMB','Internasional','Finalis','PMB-INT-FIN',100,'non-akademik'],
            ['PMB','Internasional','Peserta','PMB-INT-PST',75,'non-akademik'],

            ['PMB','Nasional','Juara I/II/III','PMB-NAS-JRA',100,'non-akademik'],
            ['PMB','Nasional','Finalis (Pimnas)','PMB-NAS-FIN',75,'non-akademik'],
            ['PMB','Nasional','Peserta (Lolos)','PMB-NAS-PST-1',60,'non-akademik'],
            ['PMB','Nasional','Peserta (Tidak Lolos)','PMB-NAS-PST-2',75,'non-akademik'],

            // ================= HMJ =================
            ['HMJ',null,'Ketua','HMJ-PI-KA',100,'non-akademik'],
            ['HMJ',null,'Wakil Ketua','HMJ-PI-WKA',80,'non-akademik'],
            ['HMJ',null,'Sekretaris','HMJ-PI-SKT',80,'non-akademik'],
            ['HMJ',null,'Bendahara','HMJ-PI-BNHR',80,'non-akademik'],
            ['HMJ',null,'Koordinator','HMJ-KO-KRB',70,'non-akademik'],
            ['HMJ',null,'Anggota Aktif','HMJ-MBR-A',60,'non-akademik'],

            // ================= KHMJ =================
            ['KHMJ',null,'Ketua','KHMJ-PI-KA',50,'non-akademik'],
            ['KHMJ',null,'Wakil Ketua','KHMJ-PI-WKA',40,'non-akademik'],
            ['KHMJ',null,'Sekretaris','KHMJ-PI-SKT',40,'non-akademik'],
            ['KHMJ',null,'Bendahara','KHMJ-PI-BNHR',40,'non-akademik'],
            ['KHMJ',null,'Koordinator','KHMJ-KO-KRB',30,'non-akademik'],
            ['KHMJ',null,'Anggota','KHMJ-MBR-A',25,'non-akademik'],
            ['KHMJ',null,'Peserta','KHMJ-MBR-b',20,'non-akademik'],

            // ================= UKM =================
            ['UKM',null,'Ketua','UKM-PI-KA',80,'non-akademik'],
            ['UKM',null,'Wakil Ketua','UKM-PI-WKA',70,'non-akademik'],
            ['UKM',null,'Sekretaris','UKM-PI-SKT',70,'non-akademik'],
            ['UKM',null,'Bendahara','UKM-PI-BNHR',70,'non-akademik'],
            ['UKM',null,'Koordinator','UKM-KO-KRB',60,'non-akademik'],
            ['UKM',null,'Anggota','UKM-MBR-A',50,'non-akademik'],

            // ================= KUKM =================
            ['KUKM',null,'Ketua','KUKM-PI-KA',40,'non-akademik'],
            ['KUKM',null,'Wakil Ketua','KUKM-PI-WKA',30,'non-akademik'],
            ['KUKM',null,'Sekretaris','KUKM-PI-SKT',30,'non-akademik'],
            ['KUKM',null,'Bendahara','KUKM-PI-BNHR',30,'non-akademik'],
            ['KUKM',null,'Koordinator','KUKM-KO-KRB',25,'non-akademik'],
            ['KUKM',null,'Anggota','KUKM-PA-A',20,'non-akademik'],
            ['KUKM',null,'Peserta','KUKM-PST-A',15,'non-akademik'],

            // ================= ASPROPT =================
            ['ASPROPT','Internasional','Pengurus Inti','ASPROPT-INT-PI',120,'non-akademik'],
            ['ASPROPT','Internasional','Anggota Biasa','ASPROPT-INT-ANG',90,'non-akademik'],

            ['ASPROPT','Nasional','Pengurus Inti','ASPROPT-NAS-PI',100,'non-akademik'],
            ['ASPROPT','Nasional','Anggota Biasa','ASPROPT-NAS-ANG',70,'non-akademik'],

            ['ASPROPT','Regional','Pengurus Inti','ASPROPT-REG-PI',80,'non-akademik'],
            ['ASPROPT','Regional','Anggota Biasa','ASPROPT-REG-ANG',60,'non-akademik'],

            ['ASPROPT','Lokal','Pengurus Inti','ASPROPT-LOK-PI',70,'non-akademik'],
            ['ASPROPT','Lokal','Anggota Biasa','ASPROPT-LOK-ANG',50,'non-akademik'],


            // ================= PELKEPPRI =================
            ['PELKEPPRI',null,'LKMM-PraTD (ESQ)','PELKEPPRI-PST-ESQ',40,'non-akademik'],
            ['PELKEPPRI',null,'LKMM-RD','PELKEPPRI-PST-RD',40,'non-akademik'],
            ['PELKEPPRI',null,'LKKM-TM','PELKEPPRI-PST-TM',50,'non-akademik'],
            ['PELKEPPRI',null,'LKKM-TL','PELKEPPRI-PST-TL',60,'non-akademik'],
            ['PELKEPPRI',null,'Pemateri LKKM','PELKEPPRI-TLK-LKKM',70,'non-akademik'],


            // ================= KEPSOSCSR =================
            ['KEPSOSCSR','Internasional','Panitia/Peserta','KEPSOSCSR-INT-PST',100,'non-akademik'],
            ['KEPSOSCSR','Nasional','Panitia/Peserta','KEPSOSCSR-NAS-PST',70,'non-akademik'],
            ['KEPSOSCSR','Regional','Panitia/Peserta','KEPSOSCSR-REG-PST',60,'non-akademik'],
            ['KEPSOSCSR','Lokal','Panitia/Peserta','KEPSOSCSR-LOK-PST',50,'non-akademik'],
            ['KEPSOSCSR','Institut','Panitia/Peserta','KEPSOSCSR-INST-PST',40,'non-akademik'],
            ['KEPSOSCSR','Fakultas','Panitia/Peserta','KEPSOSCSR-FAK-PST',30,'non-akademik'],
            ['KEPSOSCSR','Jurusan','Panitia/Peserta','KEPSOSCSR-JUR-PST',20,'non-akademik'],


            // ================= UPCR =================
            ['UPCR',null,'Petugas Upacara','UPCR-PETUGAS',30,'non-akademik'],
            ['UPCR',null,'Peserta Upacara','UPCR-PST',20,'non-akademik'],


            // ================= PBN =================
            ['PBN',null,'Pelatihan Bela Negara','PBN-PAN',40,'non-akademik'],
            ['PBN',null,'Pelatihan Bela Negara','PBN-PTS',30,'non-akademik'],


            // ================= KEGKAMPUS =================
            ['KEGKAMPUS',null,'Petugas Wisuda','KEGKAMPUS-WISUDA-PETUGAS',30,'non-akademik'],
            ['KEGKAMPUS',null,'Paduan Suara','KEGKAMPUS-PADUANSUARA',30,'non-akademik'],
            ['KEGKAMPUS',null,'Panitia PPMB','KEGKAMPUS-PPMB-PANITIA',40,'non-akademik'],
            ['KEGKAMPUS',null,'Panitia Dies Natalis','KEGKAMPUS-DIES-PANITIA',30,'non-akademik'],
            ['KEGKAMPUS',null,'Peserta Dies Natalis','KEGKAMPUS-DIES-PST',20,'non-akademik'],
            ['KEGKAMPUS',null,'Panitia Acara Lainnya','KEGKAMPUS-LAIN-PANITIA',30,'non-akademik'],
            ['KEGKAMPUS',null,'Peserta Acara Lainnya','KEGKAMPUS-LAIN-PST',20,'non-akademik'],


            // ================= PEMKAHPOT =================
            ['PEMKAHPOT',null,'Pelatihan Bidang Keilmuan','PEMKAHPOT-PEL-SESUAI',30,'non-akademik'],
            ['PEMKAHPOT',null,'Pelatihan Lintas Bidang','PEMKAHPOT-PEL-LINTAS',20,'non-akademik'],
            ['PEMKAHPOT',null,'Menulis di Media Massa','PEMKAHPOT-MEDIA-MASSA',40,'non-akademik'],
            ['PEMKAHPOT',null,'Narasumber Radio/TV','PEMKAHPOT-NARASUMBER',30,'non-akademik'],
            ['PEMKAHPOT',null,'Student Exchange','PEMKAHPOT-EXCHANGE',80,'non-akademik'],
            ['PEMKAHPOT',null,'Hafal Alquran 3 Juz','PEMKAHPOT-HAFAL-QURAN',80,'non-akademik'],
            ['PEMKAHPOT',null,'Shalat Shubuh Berjamaah','PEMKAHPOT-SHUBUH-JAMAAH',20,'non-akademik'],
            ['PEMKAHPOT',null,'Prestasi Agama (Non Muslim)','PEMKAHPOT-AGAMA-NONMUSLIM',20,'non-akademik'],


            // ================= PEMWIR =================
            ['PEMWIR',null,'Berwirausaha','PEMWIR-WIRAUSAHA',25,'non-akademik'],
            ['PEMWIR',null,'Bekerja di Perusahaan/UKM','PEMWIR-KERJA-PERUSAHAAN',20,'non-akademik'],
            ['PEMWIR',null,'Marketer Maba','PEMWIR-MARKETER-MABA',10,'non-akademik'],
        ];

        foreach ($data as $row) {

            DB::table('master_poin_sertifikat')->insert([
                'kategori' => $row[5],
                'jenis_kegiatan_id' => $jenisMap[$row[0]] ?? null,
                'peran' => $row[2],
                'tingkat' => $this->normalizeTingkat($row[1]),
                'kode' => $row[3],
                'poin' => $row[4],
                'butuh_bukti' => 1,
                'aktif' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    private function normalizeTingkat($value)
    {
        if (!$value || $value == '-') return null;
        return strtolower($value);
    }
}
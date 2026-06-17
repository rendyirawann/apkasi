<?php

namespace Database\Seeders;

use App\Models\Pic;
use Illuminate\Database\Seeder;

class PicLoSeeder extends Seeder
{
    /**
     * LO (Liaison Officer) per provinsi: kecamatan + instansi/OPD.
     * Key = provinsi_id (kode BPS, sama dgn PicSeeder). Hanya MEMPERBARUI baris PIC yang ada
     * (tidak membuat baris baru, tidak menyentuh nama/no_hp). Jalankan setelah PicSeeder.
     * Sumber: tabel "Pembagian LO dan PIC APKASI di Kabupaten Deli Serdang".
     */
    public function run(): void
    {
        $lo = [
            11 => ['Kec. Lubuk Pakam',    'Inspektorat Kab. Deli Serdang'],
            12 => ['Kec. Tanjung Morawa', 'Bappedalitbang Kab. Deli Serdang'],
            13 => ['Kec. Beringin',       'Dinas Pendidikan Kab. Deli Serdang'],
            14 => ['Kec. Beringin',       'Dinas Pendidikan Kab. Deli Serdang'],
            15 => ['Kec. Pagar Merbau',   'Dinas Ketenagakerjaan Kab. Deli Serdang'],
            16 => ['Kec. Batang Kuis',    'BKPSDM Kab. Deli Serdang'],
            17 => ['Kec. Bangun Purba',   'Dinas PMD Kab. Deli Serdang'],
            18 => ['Kec. Bangun Purba',   'Dinas PMD Kab. Deli Serdang'],
            19 => ['Kec. Batang Kuis',    'BKPSDM Kab. Deli Serdang'],
            21 => ['Kec. Pagar Merbau',   'Dinas Ketenagakerjaan Kab. Deli Serdang'],
            32 => ['Kec. Percut Sei Tuan','Dinas Perkimtan Kab. Deli Serdang'],
            33 => ['Kec. Sunggal',        'Bapenda Kab. Deli Serdang'],
            34 => ['Kec. STM Hilir',      'Dinas Perikanan Kab. Deli Serdang'],
            35 => ['Kec. Deli Tua',       'SDABMBK Kab. Deli Serdang'],
            36 => ['Kec. STM Hilir',      'Dinas Perikanan Kab. Deli Serdang'],
            51 => ['Kec. STM Hulu',       'BKAD Kab. Deli Serdang'],
            52 => ['Kec. STM Hilir',      'Dinas Perikanan Kab. Deli Serdang'],
            53 => ['Kec. Biru-Biru',      'Dinas Sosial Kab. Deli Serdang'],
            61 => ['Kec. STM Hulu',       'BKAD Kab. Deli Serdang'],
            62 => ['Kec. Galang',         'BPBD Kab. Deli Serdang'],
            63 => ['Kec. Galang',         'BPBD Kab. Deli Serdang'],
            64 => ['Kec. Pancur Batu',    'Dinas Perpustakaan dan Arsip Kab. Deli Serdang'],
            65 => ['Kec. Pancur Batu',    'Dinas Perpustakaan dan Arsip Kab. Deli Serdang'],
            71 => ['Kec. Labuhan Deli',   'DPMPTSP Kab. Deli Serdang'],
            72 => ['Kec. Labuhan Deli',   'DPMPTSP Kab. Deli Serdang'],
            73 => ['Kec. Patumbak',       'Dinas P2P3KB Kab. Deli Serdang'],
            74 => ['Kec. Namorambe',      null],
            75 => ['Kec. Pancur Batu',    'Dinas Perpustakaan dan Arsip Kab. Deli Serdang'],
            76 => ['Kec. Gunung Meriah',  'Dinas Perpustakaan dan Arsip Kab. Deli Serdang'],
            81 => ['Kec. Pantai Labu',    'Dinas Dukcapil Kab. Deli Serdang'],
            82 => ['Kec. Pantai Labu',    'Dinas Dukcapil Kab. Deli Serdang'],
            91 => ['Kec. Kutalimbaru',    null],
            92 => ['Kec. Sibolangit',     null],
            94 => ['Kec. Hamparan Perak', null],
            95 => ['Kec. Kutalimbaru',    null],
            96 => ['Kec. Kutalimbaru',    null],
            97 => ['Kec. Sibolangit',     null],
        ];

        foreach ($lo as $provinsiId => [$kecamatan, $instansi]) {
            Pic::where('provinsi_id', $provinsiId)->update([
                'lo_kecamatan' => $kecamatan,
                'lo_instansi'  => $instansi,
            ]);
        }
    }
}

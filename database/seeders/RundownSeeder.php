<?php

namespace Database\Seeders;

use App\Models\Rundown;
use Illuminate\Database\Seeder;

class RundownSeeder extends Seeder
{
    /**
     * Rundown rangkaian kegiatan HUT Ke-26 APKASI & HUT Ke-80 Deli Serdang.
     * Hari 1 detail dari dokumen rundown; Hari 2-3 dari ringkasan agenda.
     */
    public function run(): void
    {
        $data = [
            [
                'tanggal' => '2026-07-01', 'label' => 'Hari 1',
                'kegiatan' => [
                    ['08.00 – 09.30', 'Upacara HUT Kabupaten Deli Serdang', 'Alun-Alun Kab. Deli Serdang', null],
                    ['10.00 – 11.00', 'Rapat Paripurna DPRD Kab. Deli Serdang dalam rangka HUT Kabupaten Deli Serdang', 'DPRD Kab. Deli Serdang', null],
                    ['12.00 – 16.00', 'Syukuran HUT Kabupaten Deli Serdang', 'Graha Bhineka Perkasa Jaya', null],
                    ['19.30 – till drop', 'Welcome Dinner HUT APKASI Ke-26', 'Graha Bhineka Perkasa Jaya', 'Photobooth, Jamuan Makanan Tamu VIP, Gate Welcome Dinner, Tarian Pembuka (Cerita Deli Serdang), Hiburan B Three Star.'],
                ],
            ],
            [
                'tanggal' => '2026-07-02', 'label' => 'Hari 2',
                'kegiatan' => [
                    ['09.00 – 12.00', 'Dialog Strategi Pembiayaan Alternatif Pembangunan Daerah', 'IKM Hall (P3UD Deli Serdang)', 'Narasumber dari Pemkab Sintang, Sumedang, akademisi, dan BUMN sektor pembiayaan.'],
                    ['09.00 – 13.00', 'Women Program — UMKM & Stunting', 'IKM Hall (P3UD Deli Serdang)', 'Talkshow penguatan peran perempuan dalam pengembangan UMKM dan penurunan stunting.'],
                    ['13.00 – 15.00', 'Forum Bisnis Daerah (FORBISDA)', 'IKM Hall (P3UD Deli Serdang)', 'Bersama KADIN, perwakilan Pemkab, pengusaha lokal, dan IBA.'],
                    ['18.30 – 22.00', 'Malam Grand Final Putri Otonomi Indonesia 2026', 'Graha Bhineka Perkasa Jaya', 'Puncak penobatan duta otonomi daerah dari seluruh kabupaten di Indonesia.'],
                ],
            ],
            [
                'tanggal' => '2026-07-03', 'label' => 'Hari 3',
                'kegiatan' => [
                    ['06.00 – 10.30', 'Fun Walk & Penanaman Pohon', 'Alun-Alun Deli Serdang', 'Jalan santai bersama, penanaman pohon, pembagian doorprize, dan hiburan rakyat.'],
                ],
            ],
        ];

        foreach ($data as $i => $row) {
            $kegiatan = $row['kegiatan'];
            unset($row['kegiatan']);

            $rundown = Rundown::updateOrCreate(
                ['tanggal' => $row['tanggal']],
                array_merge(['is_active' => true, 'urut' => $i + 1], $row)
            );

            $rundown->kegiatan()->delete();
            foreach ($kegiatan as $j => $k) {
                $rundown->kegiatan()->create([
                    'waktu'    => $k[0],
                    'kegiatan' => $k[1],
                    'lokasi'   => $k[2],
                    'rincian'  => $k[3],
                    'urut'     => $j + 1,
                ]);
            }
        }
    }
}

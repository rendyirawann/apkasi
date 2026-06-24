<?php

namespace Database\Seeders;

use App\Models\RekayasaLaluLintas;
use Illuminate\Database\Seeder;

class RekayasaLaluLintasSeeder extends Seeder
{
    /**
     * Peta rekayasa lalu lintas & parkir kegiatan APKASI Ke-26 + HUT Deli Serdang 2026.
     * Gambar berada di public/rekayasa/ (aset publik, bukan storage disk).
     * Aman: hanya mengisi bila tabel masih kosong (tidak menimpa data hasil edit admin).
     */
    public function run(): void
    {
        if (RekayasaLaluLintas::count() > 0) {
            return;
        }

        $desc = 'Peta jalur pengaturan rekayasa lalu lintas dan parkir kendaraan - Kegiatan APKASI Ke-26, Putri Otonomi Indonesia, dan Hari Jadi Kabupaten Deli Serdang Tahun 2026.';

        $data = [
            [
                'gambar'   => 'rekayasa/rekajalan1.jpeg',
                'judul'    => 'Peta Rekayasa Lalu Lintas & Parkir - Lembar 1',
                'deskripsi' => $desc,
                'lokasi'   => [],
            ],
            [
                'gambar'   => 'rekayasa/rekajalan2.jpeg',
                'judul'    => 'Peta Rekayasa Lalu Lintas & Parkir - Lembar 2',
                'deskripsi' => $desc,
                'lokasi'   => ['Graha Bhineka Perkasa Jaya', 'Politeknik Kesehatan Gizi', 'Kolam Renang', 'Museum', 'Pondok Rame', 'Rumah Dinas Sekda'],
            ],
            [
                'gambar'   => 'rekayasa/rekajalan3.jpeg',
                'judul'    => 'Peta Rekayasa Lalu Lintas & Parkir - Lembar 3',
                'deskripsi' => $desc,
                'lokasi'   => [],
            ],
            [
                'gambar'   => 'rekayasa/rekajalan4.jpeg',
                'judul'    => 'Peta Rekayasa Lalu Lintas & Parkir - Lembar 4',
                'deskripsi' => $desc,
                'lokasi'   => [],
            ],
        ];

        foreach ($data as $i => $row) {
            $rekayasa = RekayasaLaluLintas::create([
                'judul'     => $row['judul'],
                'deskripsi' => $row['deskripsi'],
                'gambar'    => $row['gambar'],
                'urut'      => $i + 1,
                'is_active' => true,
            ]);

            foreach ($row['lokasi'] as $j => $lok) {
                $rekayasa->lokasi()->create(['nama' => $lok, 'urut' => $j + 1]);
            }
        }
    }
}

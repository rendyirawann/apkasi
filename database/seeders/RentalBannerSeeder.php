<?php

namespace Database\Seeders;

use App\Models\RentalBanner;
use Illuminate\Database\Seeder;

class RentalBannerSeeder extends Seeder
{
    /**
     * Banner pada tab Rental (halaman Panduan), tampil berurutan di atas peta.
     * Gambar awal berada di public/assets/media/landing/ (aset publik bawaan).
     * Aman: hanya mengisi bila tabel masih kosong (tidak menimpa data hasil kelola admin).
     */
    public function run(): void
    {
        if (RentalBanner::count() > 0) {
            return;
        }

        $data = [
            ['judul' => 'PIC Kendaraan APKASI 2026', 'gambar' => 'assets/media/landing/pic-rental.jpg'],
            ['judul' => 'PT Naga Hitam Rentcar',     'gambar' => 'assets/media/landing/rentalnagahitam.png'],
            ['judul' => null,                          'gambar' => 'assets/media/landing/rentalbanner3.png'],
            ['judul' => null,                          'gambar' => 'assets/media/landing/rentalbanner4.png'],
        ];

        foreach ($data as $i => $row) {
            RentalBanner::create([
                'judul'     => $row['judul'],
                'gambar'    => $row['gambar'],
                'urut'      => $i + 1,
                'is_active' => true,
            ]);
        }
    }
}

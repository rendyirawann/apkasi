<?php

namespace Database\Seeders;

use App\Models\FooterLink;
use Illuminate\Database\Seeder;

class FooterLinkSeeder extends Seeder
{
    /**
     * Link kolom footer (2 kolom). URL relatif agar aman lintas domain.
     * Aman di-seed ulang: jika sudah ada data (admin sudah mengelola), seeder dilewati
     * supaya tidak menimpa perubahan admin.
     */
    public function run(): void
    {
        if (FooterLink::count() > 0) {
            return;
        }

        $rows = [
            // Kolom 1 (default judul "Navigasi")
            ['kolom' => 'col1', 'label' => 'Tentang',        'url' => '/#tentang'],
            ['kolom' => 'col1', 'label' => 'Agenda',         'url' => '/#agenda'],
            ['kolom' => 'col1', 'label' => 'Putri Otonomi',  'url' => '/#poi'],
            ['kolom' => 'col1', 'label' => 'Panduan',        'url' => '/panduan'],
            // Kolom 2 (default judul "Informasi")
            ['kolom' => 'col2', 'label' => 'Peta Lokasi Acara', 'url' => '/peta-hotel'],
            ['kolom' => 'col2', 'label' => 'Rekomendasi Hotel', 'url' => '/peta-hotel'],
            ['kolom' => 'col2', 'label' => 'Destinasi Wisata',  'url' => '/peta-hotel'],
            ['kolom' => 'col2', 'label' => 'Rental Mobil',      'url' => '/panduan'],
        ];

        foreach ($rows as $i => $r) {
            FooterLink::create($r + ['urut' => $i + 1, 'is_active' => true]);
        }
    }
}

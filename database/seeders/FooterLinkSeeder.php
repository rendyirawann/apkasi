<?php

namespace Database\Seeders;

use App\Models\FooterColumn;
use App\Models\FooterLink;
use Illuminate\Database\Seeder;

class FooterLinkSeeder extends Seeder
{
    /**
     * Kolom footer dinamis + link-nya. URL relatif agar aman lintas domain.
     * Aman di-seed ulang: jika sudah ada kolom (admin sudah mengelola), seeder dilewati
     * supaya tidak menimpa perubahan admin.
     */
    public function run(): void
    {
        if (FooterColumn::count() > 0) {
            return;
        }

        $columns = [
            [
                'judul' => 'Navigasi',
                'links' => [
                    ['Tentang', '/#tentang'],
                    ['Agenda', '/#agenda'],
                    ['Putri Otonomi', '/#poi'],
                    ['Panduan', '/panduan'],
                ],
            ],
            [
                'judul' => 'Informasi',
                'links' => [
                    ['Peta Lokasi Acara', '/peta-hotel'],
                    ['Rekomendasi Hotel', '/peta-hotel'],
                    ['Destinasi Wisata', '/peta-hotel'],
                    ['Rental Mobil', '/panduan'],
                ],
            ],
        ];

        foreach ($columns as $ci => $col) {
            $column = FooterColumn::create([
                'judul'     => $col['judul'],
                'urut'      => $ci + 1,
                'is_active' => true,
            ]);
            foreach ($col['links'] as $li => [$label, $url]) {
                FooterLink::create([
                    'footer_column_id' => $column->id,
                    'label'            => $label,
                    'url'              => $url,
                    'icon'             => FooterLink::detectIcon($url),
                    'urut'             => $li + 1,
                    'is_active'        => true,
                ]);
            }
        }
    }
}

<?php

namespace Database\Seeders;

use App\Models\DestinasiWisata;
use Illuminate\Database\Seeder;

class DestinasiWisataSeeder extends Seeder
{
    /**
     * Destinasi wisata Deli Serdang. Alamat/rating/harga tiket/koordinat hasil riset web.
     * thumbnail & galeri pakai gambar placeholder (Unsplash) — ganti dengan foto asli bila ada.
     */
    public function run(): void
    {
        $data = [
            [
                'nama'        => 'Danau Linting',
                'alamat'      => 'Desa Sibunga-bunga (Rumah Rih), Kec. Sinembah Tanjung Muda (STM) Hulu, Kab. Deli Serdang',
                'deskripsi'   => 'Danau vulkanik dengan air panas belerang berwarna hijau toska eksotis, sejuk & dikelilingi pepohonan rindang.',
                'rating'      => 4.3,
                'harga_tiket' => 'Rp10.000',
                'lat'         => 3.2295930,
                'lng'         => 98.7235657,
                'maps_url'    => 'https://maps.google.com/?q=Danau+Linting+Deli+Serdang',
                'thumbnail'   => 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=800&q=80',
                'urut'        => 1,
                'gambar'      => [
                    'https://images.unsplash.com/photo-1439066615861-d1af74d74000?auto=format&fit=crop&w=800&q=80',
                    'https://images.unsplash.com/photo-1470071459604-3b5ec3a7fe05?auto=format&fit=crop&w=800&q=80',
                ],
            ],
            [
                'nama'        => 'Wisata Kuliner Pasar Kamu',
                'alamat'      => 'Jl. Perintis, Dusun II, Desa Denai Lama, Kec. Pantai Labu, Kab. Deli Serdang',
                'deskripsi'   => 'Pasar kuliner tradisional khas Melayu (buka Minggu pagi) dengan transaksi koin bambu "tempu" & suasana pedesaan asri.',
                'rating'      => null,
                'harga_tiket' => 'Gratis (parkir ±Rp3.000)',
                'lat'         => 3.6400410,
                'lng'         => 98.9304040,
                'maps_url'    => 'https://maps.google.com/?q=Pasar+Kamu+Denai+Lama+Deli+Serdang',
                'thumbnail'   => 'https://images.unsplash.com/photo-1555396273-367ea4eb4db5?auto=format&fit=crop&w=800&q=80',
                'urut'        => 2,
                'gambar'      => [
                    'https://images.unsplash.com/photo-1488459716781-31db52582fe9?auto=format&fit=crop&w=800&q=80',
                    'https://images.unsplash.com/photo-1504674900247-0877df9cc836?auto=format&fit=crop&w=800&q=80',
                ],
            ],
            [
                'nama'        => 'Museum Daerah Deli Serdang',
                'alamat'      => 'Jl. Negara, Tanjung Garbus I, Kec. Lubuk Pakam, Kab. Deli Serdang 20517',
                'deskripsi'   => 'Museum modern berisi artefak sejarah Kesultanan Serdang serta kebudayaan Melayu, Karo, dan Simalungun.',
                'rating'      => 4.4,
                'harga_tiket' => 'Rp5.000',
                'lat'         => 3.5518389,
                'lng'         => 98.8665389,
                'maps_url'    => 'https://maps.google.com/?q=Museum+Deli+Serdang+Lubuk+Pakam',
                'thumbnail'   => 'https://images.unsplash.com/photo-1582719508461-905c673771fd?auto=format&fit=crop&w=800&q=80',
                'urut'        => 3,
                'gambar'      => [
                    'https://images.unsplash.com/photo-1566127992631-137a642a90f4?auto=format&fit=crop&w=800&q=80',
                    'https://images.unsplash.com/photo-1518998053901-5348d3961a04?auto=format&fit=crop&w=800&q=80',
                ],
            ],
            [
                'nama'        => 'Air Terjun Dua Warna',
                'alamat'      => 'Desa Durin Sirugun (akses via Bandar Baru), Kec. Sibolangit, Kab. Deli Serdang 20354',
                'deskripsi'   => 'Destinasi trekking petualangan dengan air terjun dua gradasi warna (biru dingin & putih hangat) akibat kandungan belerang ringan.',
                'rating'      => 4.0,
                'harga_tiket' => 'Rp25.000',
                'lat'         => 3.2616110,
                'lng'         => 98.5141940,
                'maps_url'    => 'https://maps.google.com/?q=Air+Terjun+Dua+Warna+Sibolangit',
                'thumbnail'   => 'https://images.unsplash.com/photo-1470071459604-3b5ec3a7fe05?auto=format&fit=crop&w=800&q=80',
                'urut'        => 4,
                'gambar'      => [
                    'https://images.unsplash.com/photo-1432405972618-c60b0225b8f9?auto=format&fit=crop&w=800&q=80',
                    'https://images.unsplash.com/photo-1467890947394-8171244e5410?auto=format&fit=crop&w=800&q=80',
                ],
            ],
        ];

        foreach ($data as $row) {
            $gambar = $row['gambar'];
            unset($row['gambar']);

            $d = DestinasiWisata::updateOrCreate(
                ['nama' => $row['nama']],
                array_merge(['is_lokasi_acara' => false, 'is_active' => true], $row)
            );

            // Sync galeri (idempotent)
            $d->gambar()->delete();
            foreach ($gambar as $i => $g) {
                $d->gambar()->create(['gambar' => $g, 'urut' => $i + 1]);
            }
        }
    }
}

<?php

namespace Database\Seeders;

use App\Models\Hotel;
use Illuminate\Database\Seeder;

class HotelSeeder extends Seeder
{
    /**
     * Daftar hotel (dokumen resmi: "Daftar Hotel di Kabupaten Deli Serdang" & "Daftar Hotel di Kota Medan").
     * - kategori: deli_serdang | medan (pemisah list di halaman Peta & Hotel).
     * - contact_person + contact_wa, jarak ke lokasi HUT APKASI, dan tipe kamar + harga (tabel anak hotel_kamar) dari dokumen.
     * - lat/lng + rating: hasil riset web + verifikasi area (sebagian masih perkiraan/area-level; maps_url query tetap akurat utk Rute).
     * AUTHORITATIVE: hotel yang tidak ada di daftar ini akan dihapus (re-seed = reset ke data dokumen).
     */
    public function run(): void
    {
        $data = [
            // ───────────── KABUPATEN DELI SERDANG ─────────────
            [
                'kategori' => 'deli_serdang', 'nama' => "D'Prima Hotel Kualanamu Medan",
                'alamat' => 'Jl. Sultan Serdang No.88, Tumpatan Nibung, Kec. Batang Kuis, Kab. Deli Serdang, Sumatera Utara 20362',
                'contact_person' => 'Irwan', 'contact_wa' => '08114017843', 'jarak' => '11 Km (13 Menit)',
                'lat' => 3.599515, 'lng' => 98.833088, 'rating' => 4.5,
                'kamar' => [
                    ['Superior Room King/Twin', 590000], ['Deluxe King/Twin', 810000], ['Superior Family', 950000],
                    ['Business Suite King', 1390000], ["D'Prima Suite King", 2050000],
                ],
            ],
            [
                'kategori' => 'deli_serdang', 'nama' => 'Wing Hotel Kualanamu',
                'alamat' => 'Komplek Hub, Kualanamu Hub Commercial Bizpark, Jl. Arteri Kualanamu No.9, Tumpatan Nibung, Kec. Batang Kuis, Kab. Deli Serdang, Sumatera Utara 20372',
                'contact_person' => 'Andri', 'contact_wa' => '082273927942', 'jarak' => '9,5 Km (13 Menit)',
                'lat' => 3.5982622, 'lng' => 98.8304684, 'rating' => null,
                'kamar' => [['Superior Room King/Twin', 750000], ['Deluxe King', 950000], ['Suite', 1750000]],
            ],
            [
                'kategori' => 'deli_serdang', 'nama' => 'Travel Hub Hotel Kualanamu',
                'alamat' => 'Jl. Arteri Kualanamu No.9, Tumpatan Nibung, Kec. Batang Kuis, Kab. Deli Serdang, Sumatera Utara 20372',
                'contact_person' => 'Andri', 'contact_wa' => '082273927942', 'jarak' => '9,9 Km (14 Menit)',
                'lat' => 3.61944, 'lng' => 98.81111, 'rating' => null,
                'kamar' => [['Standard', 550000], ['Deluxe', 750000], ['Junior Suite', 1250000]],
            ],
            [
                'kategori' => 'deli_serdang', 'nama' => 'The Quadrant Hotel Kualanamu',
                'alamat' => 'Komplek Hub, Commercial Bizpark, Jl. Arteri Kualanamu No. Blok A, Tumpatan Nibung, Kec. Batang Kuis, Kab. Deli Serdang, Sumatera Utara 20372',
                'contact_person' => 'Ahmad', 'contact_wa' => '089646355451', 'jarak' => '9,5 Km (13 Menit)',
                'lat' => 3.553765, 'lng' => 98.729728, 'rating' => null,
                'kamar' => [['Superior', 420000], ['Deluxe', 520000], ['Super Deluxe', 580000], ['Family Room', 900000]],
            ],
            [
                'kategori' => 'deli_serdang', 'nama' => 'Anara Sky Kualanamu',
                'alamat' => 'Kualanamu International Airport, Lt. Mezzanine, Beringin, Kab. Deli Serdang, Sumatera Utara 20553',
                'contact_person' => 'Putra', 'contact_wa' => '081263506650', 'jarak' => '14 Km (19 Menit)',
                'lat' => 3.635382, 'lng' => 98.878808, 'rating' => 4.5,
                'kamar' => [['Deluxe Room', 600000], ['Junior Suite Room', 950000], ['Executive Suite Room', 1350000]],
            ],
            [
                'kategori' => 'deli_serdang', 'nama' => "Thong's Inn Hotel Kualanamu",
                'alamat' => 'Kualanamu, Jl. Ps. V Kebun Klp., Penara Kebun, Kec. Beringin, Kab. Deli Serdang, Sumatera Utara 20511',
                'contact_person' => 'Ayu', 'contact_wa' => '082384297818', 'jarak' => '7,2 Km (16 Menit) · Non-tol',
                'lat' => 3.5926025, 'lng' => 98.8612221, 'rating' => null,
                'kamar' => [
                    ['Cottage', 485000], ['Villa', 560000], ['Villa Triple', 760000], ['Deluxe A', 610000],
                    ['Deluxe A Triple', 810000], ['Deluxe B', 520000], ['Deluxe B Triple', 710000], ['Family Room', 1010000],
                ],
            ],
            [
                'kategori' => 'deli_serdang', 'nama' => 'The Crew Hotel',
                'alamat' => 'Jl. Bandara Kuala Namu, Tumpatan Nibung, Kec. Batang Kuis, Kab. Deli Serdang, Sumatera Utara 20552',
                'contact_person' => null, 'contact_wa' => '081370316700', 'jarak' => '9,7 Km (13 Menit)',
                'lat' => 3.598034, 'lng' => 98.830444, 'rating' => null,
                'kamar' => [['Business Room', 350000]],
            ],
            [
                'kategori' => 'deli_serdang', 'nama' => 'T Hotel Kualanamu',
                'alamat' => 'Jl. Bakaran Batu No.151, Tumpatan Beringin, Penara Kebun, Tanjung Morawa, Kab. Deli Serdang, Sumatera Utara 20552',
                'contact_person' => 'Ariansyah', 'contact_wa' => '082272866278', 'jarak' => '5,8 Km (14 Menit) · Non-tol',
                'lat' => 3.5850178, 'lng' => 98.8612092, 'rating' => null,
                'kamar' => [
                    ['Superior Room', 400000], ['Junior Deluxe Room', 450000], ['Deluxe Room King', 500000],
                    ['Deluxe Room Twin', 500000], ['Executive Room', 600000],
                ],
            ],
            [
                'kategori' => 'deli_serdang', 'nama' => 'Miyanna Hotel',
                'alamat' => 'Jl. H. Anif No.28, Medan Estate, Kec. Percut Sei Tuan, Kab. Deli Serdang, Sumatera Utara 20372',
                'contact_person' => 'Nia', 'contact_wa' => '085277213935', 'jarak' => '33 Km (35 Menit)',
                'lat' => 3.6363, 'lng' => 98.708, 'rating' => 4.2,
                'kamar' => [
                    ['Premiere Suite', 2200000], ['Platinum Suite', 1400000], ['Junior Suite', 885000],
                    ['Super Deluxe King (Miyanna Plus)', 665000], ['Super Deluxe Twin (Miyanna Plus)', 665000],
                    ['Superior King (Noble)', 555000], ['Superior Twin (Noble)', 555000],
                ],
            ],
            [
                'kategori' => 'deli_serdang', 'nama' => 'Nivia Hotel',
                'alamat' => 'Jl. Letda Sujono No.91, Bantan Tim., Kec. Medan Tembung, Kota Medan, Sumatera Utara 20223',
                'contact_person' => 'Rahmatsyah', 'contact_wa' => '081397665030', 'jarak' => '29 Km (33 Menit)',
                'lat' => 3.5976, 'lng' => 98.7191, 'rating' => null,
                'kamar' => [
                    ['Superior King', 390000], ['Superior Twin', 390000], ['Deluxe King', 440000],
                    ['Deluxe Twin', 440000], ['Executive', 590000], ['Suit', 1100000],
                ],
            ],
            [
                'kategori' => 'deli_serdang', 'nama' => 'Sapadia Guesthouse Tamora',
                'alamat' => 'Jl. Medan - Tebing Tinggi No.99, Perdamaian, Kec. Tj. Morawa, Kab. Deli Serdang, Sumatera Utara 20513',
                'contact_person' => 'Yenni', 'contact_wa' => '082168859600', 'jarak' => '2,9 Km (5 Menit)',
                'lat' => 3.5483, 'lng' => 98.8447, 'rating' => null,
                'kamar' => [
                    ['Superior (Single Bed)', 325000], ['Deluxe (Single Bed)', 375000],
                    ['Deluxe (Twin Bed)', 375000], ['Suite (Single Bed)', 600000],
                ],
            ],
            [
                'kategori' => 'deli_serdang', 'nama' => 'Pancur Gading Hotel & Resort',
                'alamat' => 'Jl. Kuala Simeme, Pamah, Kec. Deli Tua, Kab. Deli Serdang, Sumatera Utara 20355',
                'contact_person' => 'Ricca Purnama', 'contact_wa' => '082168181801', 'jarak' => '34 Km (58 Menit)',
                'lat' => 3.4721345, 'lng' => 98.6799843, 'rating' => null,
                'kamar' => [
                    ['Junior Suite', 1300000], ['Family Room 6', 1500000], ['Family Room 5', 1250000],
                    ['Family Room 4', 1000000], ['Family Room 3', 750000], ['Super Deluxe Room', 850000],
                    ['Deluxe Room', 750000], ['Superior Room', 550000], ['Standar Room Triple', 450000],
                    ['Standar Room Twin', 400000], ['Studio Room', 350000],
                ],
            ],
            [
                'kategori' => 'deli_serdang', 'nama' => 'The Hill Hotel & Resort Sibolangit',
                'alamat' => 'Jl. Let. Jend Djamin Ginting KM.45,3, Desa Sukamakmur, Sibolangit, Kab. Deli Serdang, Sumatera Utara 20355',
                'contact_person' => 'Irma', 'contact_wa' => '081375202969', 'jarak' => '67 Km (120 Menit) · Non-tol',
                'lat' => 3.3221, 'lng' => 98.5778, 'rating' => 4.0,
                'kamar' => [
                    ['Superior', 1050000], ['Deluxe', 1150000], ['Super Deluxe', 1350000], ['Executive Deluxe', 1700000],
                    ['Junior Suite', 2650000], ['Lobby Suite', 3450000], ['Family Suite', 5750000],
                ],
            ],

            // ───────────── KOTA MEDAN ─────────────
            [
                'kategori' => 'medan', 'nama' => 'JW Marriott Medan',
                'alamat' => 'Jl. Putri Hijau No.10, Kesawan, Kec. Medan Barat, Kota Medan, Sumatera Utara 20111',
                'contact_person' => 'Catherine', 'contact_wa' => '081775052007', 'jarak' => '32 Km (42 Menit)',
                'lat' => 3.5963269, 'lng' => 98.6760601, 'rating' => 4.6,
                'kamar' => [
                    ['Deluxe Room', 1398000], ['Executive Room', 2195000], ['Executive Room (Upper)', 3500000],
                    ['Junior Suite', 8000000], ['Ambassador Suite', 10000000], ['Presidential Suite', 15000000],
                ],
            ],
            [
                'kategori' => 'medan', 'nama' => 'Grand Cityhall Medan',
                'alamat' => 'Jl. Balai Kota No.1, Medan, Sumatera Utara',
                'contact_person' => 'Lisa', 'contact_wa' => '08116063577', 'jarak' => '32 Km (42 Menit)',
                'lat' => 3.5902, 'lng' => 98.6769, 'rating' => 4.0,
                'kamar' => [
                    ['Deluxe', 1060000], ['Deluxe Corner', 1160000], ['Premier Deluxe', 1160000], ['Junior Suite', 1260000],
                    ['Cityhall Spa', 1360000], ['Cityhall Suite', 1880000], ['Executive Suite', 2510000], ['Ambassador', 2810000],
                    ['Presidential Suite', 15940000], ['Apartment 1 Bedroom', 1280000], ['Apartment 2 Bedroom', 1580000],
                    ['Apartment 3 Bedroom', 2230000],
                ],
            ],
            [
                'kategori' => 'medan', 'nama' => 'Adimulia Hotel Medan',
                'alamat' => 'Jl. Pangeran Diponegoro No.8, Petisah Tengah, Kec. Medan Petisah, Kota Medan',
                'contact_person' => 'Martha Lumbangaol', 'contact_wa' => '081394306918', 'jarak' => '33 Km (47 Menit)',
                'lat' => 3.5850866, 'lng' => 98.6724472, 'rating' => 4.0,
                'kamar' => [
                    ['Deluxe', 1088000], ['Executive Deluxe', 1350000], ['Family Room', 1850000], ['Junior Suite', 2250000],
                    ['Family Suite', 2850000], ['Business Suite', 4050000], ['President Suite', 14050000],
                ],
            ],
            [
                'kategori' => 'medan', 'nama' => 'Cambridge Hotel Medan',
                'alamat' => 'Jl. S. Parman No.217, Petisah Tengah, Kec. Medan Petisah, Kota Medan, Sumatera Utara 20152',
                'contact_person' => 'Andri', 'contact_wa' => '081919725338', 'jarak' => '34 Km (49 Menit)',
                'lat' => 3.5849487, 'lng' => 98.6673543, 'rating' => 4.0,
                'kamar' => [
                    ['Deluxe', 1000000], ['Superior Deluxe', 1100000], ['Executive Room', 1400000], ['Junior Suite', 1800000],
                    ['Suites', 3500000], ['Presidential Suites', 10000000],
                ],
            ],
            [
                'kategori' => 'medan', 'nama' => 'Aryaduta Medan',
                'alamat' => 'Jl. Kapten Maulana Lubis No.8, Medan, Sumatera Utara',
                'contact_person' => 'Florenta', 'contact_wa' => '081916500153', 'jarak' => '34 Km (49 Menit)',
                'lat' => 3.5899197, 'lng' => 98.6741791, 'rating' => null,
                'kamar' => [
                    ['Premier', 1000000], ['Arya Club', 1100000], ['Arya Club Pool Terrace', 1530000],
                    ['Arya Club Studio', 1630000], ['Arya Suite', 1730000], ['Executive Suite', 2530000],
                    ['Business Suite', 4030000], ['Aryaduta Suite', 6030000],
                ],
            ],
            [
                'kategori' => 'medan', 'nama' => 'Grand Mercure Medan Angkasa',
                'alamat' => 'Jl. Sutomo No.1, Perintis, Kec. Medan Tim., Kota Medan',
                'contact_person' => 'Debby', 'contact_wa' => '082364733421', 'jarak' => '31 Km (43 Menit)',
                'lat' => 3.5984, 'lng' => 98.682, 'rating' => 4.4,
                'kamar' => [
                    ['Deluxe Room', 850000], ['Premiere Room', 1100000], ['Executive Room', 1300000], ['Junior Suite', 1500000],
                    ['Deluxe Suite Room', 1730000], ['Family Suite Room', 4960000], ['President Suite', 18150000],
                ],
            ],
            [
                'kategori' => 'medan', 'nama' => 'Le Polonia Hotel & Convention',
                'alamat' => 'Jl. Jenderal Sudirman No.14-18, Madras Hulu, Kec. Medan Polonia, Kota Medan',
                'contact_person' => 'Yenny', 'contact_wa' => '082370066555', 'jarak' => '30 Km (44 Menit)',
                'lat' => 3.5765155, 'lng' => 98.6751473, 'rating' => 4.0,
                'kamar' => [
                    ['Superior Deluxe', 700000], ['Premier Room', 900000], ['Family Room', 1050000], ['Grand Family Room', 1200000],
                    ['Junior Suite', 1350000], ['Executive Suite', 1800000], ['Polonia Luxury Suite', 4000000],
                ],
            ],
            [
                'kategori' => 'medan', 'nama' => 'Grand Central Medan',
                'alamat' => 'Jl. Sei Belutu No.17B, Merdeka, Kec. Medan Baru, Kota Medan',
                'contact_person' => 'Abdul', 'contact_wa' => '08116063681', 'jarak' => '32 Km (43 Menit)',
                'lat' => 3.5751099, 'lng' => 98.6534302, 'rating' => 4.3,
                'kamar' => [
                    ['Superior', 590000], ['Deluxe', 650000], ['Super Deluxe', 690000], ['Grand Deluxe', 950000], ['Grand Suite', 1500000],
                ],
            ],
            [
                'kategori' => 'medan', 'nama' => 'Grand Kanaya Hotel Medan',
                'alamat' => 'Jl. Darussalam No.12, Medan Petisah, Kota Medan',
                'contact_person' => 'Afrida', 'contact_wa' => '081362217489', 'jarak' => '35 Km (54 Menit)',
                'lat' => 3.5891771, 'lng' => 98.6530323, 'rating' => null,
                'kamar' => [['Superior Room', 550000]],
            ],
            [
                'kategori' => 'medan', 'nama' => 'Travellers Suites Medan',
                'alamat' => 'Jl. Listrik No.15, Petisah Tengah, Kec. Medan Petisah, Kota Medan',
                'contact_person' => 'Teguh', 'contact_wa' => '085669441540', 'jarak' => '33 Km (47 Menit)',
                'lat' => 3.585999, 'lng' => 98.675795, 'rating' => 4.5,
                'kamar' => [
                    ['One Bedroom Suite', 787000], ['Two Bedrooms Suite', 1017000], ['Two Bedrooms Deluxe Suite', 1210000],
                    ['Three Bedrooms Suite', 1280000], ['Three Bedrooms Deluxe', 1410000], ['Penthouse Suite', 2520000],
                    ['Honeymoon Suite', 3620000],
                ],
            ],
        ];

        foreach ($data as $i => $row) {
            $kamar = $row['kamar'];
            unset($row['kamar']);
            $row['maps_url'] = 'https://www.google.com/maps/search/?api=1&query='
                . urlencode($row['nama'] . ', ' . ($row['kategori'] === 'medan' ? 'Medan' : 'Deli Serdang'));

            $hotel = Hotel::updateOrCreate(
                ['nama' => $row['nama']],
                array_merge(['is_active' => true, 'is_lokasi_acara' => false, 'urut' => $i + 1], $row)
            );

            $hotel->kamar()->delete();
            foreach ($kamar as $j => $k) {
                $hotel->kamar()->create(['tipe' => $k[0], 'harga' => $k[1], 'urut' => $j + 1]);
            }
        }

        // Authoritative: hapus hotel lama yang tidak ada di daftar dokumen (mis. duplikat ejaan nama).
        Hotel::whereNotIn('nama', array_column($data, 'nama'))->delete();
    }
}

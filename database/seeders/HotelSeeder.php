<?php

namespace Database\Seeders;

use App\Models\Hotel;
use Illuminate\Database\Seeder;

class HotelSeeder extends Seeder
{
    /**
     * Daftar hotel di Kabupaten Deli Serdang.
     * - nama & ketersediaan_kamar: dari dokumen "Daftar Nama Hotel di Kabupaten Deli Serdang".
     * - alamat, lat/lng, contact_wa, contact_email, rating: hasil riset web (Google/booking/situs resmi).
     *
     * CATATAN akurasi:
     * - Sebagian koordinat masih PERKIRAAN (ditandai approx); maps_url memakai query nama
     *   sehingga tombol "Rute" tetap mengarah benar. Sempurnakan lat/lng dari Google Maps bila perlu.
     * - rating = rating bintang (skala 5) bila ditemukan; null bila hanya skor OTA (skala 10).
     * - Wing/T Hotel/Travel Hub: nomor yang ditemukan adalah telepon kantor (bukan dipastikan WA).
     * - Nivia Hotel secara administratif di Medan Tembung (berbatasan Deli Serdang).
     */
    public function run(): void
    {
        $data = [
            [
                'urut' => 1, 'nama' => "D'Prima Hotel Kualanamu", 'ketersediaan_kamar' => 79, 'rating' => 4.4,
                'alamat' => 'Jl. Sultan Serdang No.88, Tumpatan Nibung, Kec. Batang Kuis, Kab. Deli Serdang 20362',
                'lat' => 3.5995150, 'lng' => 98.8330880,
                'contact_wa' => '082288109899', 'contact_email' => 'rsv.kualanamu@dprimahotel.com',
                'maps_url' => 'https://www.google.com/maps/search/?api=1&query=Dprima+Hotel+Kualanamu',
            ],
            [
                'urut' => 2, 'nama' => 'Wing Hotel Kualanamu', 'ketersediaan_kamar' => 130, 'rating' => 4.2,
                'alamat' => 'Komplek Hub, Jl. Arteri Kualanamu No.9, Tumpatan Nibung, Kec. Batang Kuis, Kab. Deli Serdang 20372',
                'lat' => 3.5992000, 'lng' => 98.8315000, // approx (kompleks Kualanamu Hub Bizpark)
                'contact_wa' => '061-8011-0800', 'contact_email' => null,
                'maps_url' => 'https://www.google.com/maps/search/?api=1&query=Wing+Hotel+Kualanamu',
            ],
            [
                'urut' => 3, 'nama' => 'T Hotel Kualanamu', 'ketersediaan_kamar' => 42, 'rating' => null,
                'alamat' => 'Jl. Bakaran Batu No.151, Penara Kebun, Kec. Tanjung Morawa, Kab. Deli Serdang 20552',
                'lat' => 3.5850000, 'lng' => 98.8612000, // approx
                'contact_wa' => '061-7975-1470', 'contact_email' => null,
                'maps_url' => 'https://www.google.com/maps/search/?api=1&query=T+Hotel+Kualanamu',
            ],
            [
                'urut' => 4, 'nama' => 'Travel Hub Hotel Kualanamu', 'ketersediaan_kamar' => 141, 'rating' => 4.2,
                'alamat' => 'Jl. Arteri Kualanamu No.9, Tumpatan Nibung, Kec. Batang Kuis, Kab. Deli Serdang 20372',
                'lat' => 3.5994000, 'lng' => 98.8318000, // approx (kompleks Kualanamu Hub Bizpark)
                'contact_wa' => '061-8011-0806', 'contact_email' => null,
                'maps_url' => 'https://www.google.com/maps/search/?api=1&query=Travel+Hub+Hotel+Kualanamu',
            ],
            [
                'urut' => 5, 'nama' => 'Miyana Hotel', 'ketersediaan_kamar' => 80, 'rating' => null,
                'alamat' => 'Jl. H. Anif No.28, Medan Estate, Kec. Percut Sei Tuan, Kab. Deli Serdang 20372',
                'lat' => 3.6339660, 'lng' => 98.7084160,
                'contact_wa' => '085361001800', 'contact_email' => 'reservation@miyannahotel.com',
                'maps_url' => 'https://www.google.com/maps/search/?api=1&query=Miyana+Hotel+Medan+Estate',
            ],
            [
                'urut' => 6, 'nama' => 'The Crew Hotel Kualanamu', 'ketersediaan_kamar' => 20, 'rating' => null,
                'alamat' => 'Komplek Hub Kualanamu Bizpark, Jl. Arteri Bandara Kualanamu, Tumpatan Nibung, Kec. Batang Kuis, Kab. Deli Serdang 20552',
                'lat' => 3.5980340, 'lng' => 98.8304440,
                'contact_wa' => '082365211199', 'contact_email' => 'reservation@thecrewhotel.co.id',
                'maps_url' => 'https://www.google.com/maps/search/?api=1&query=The+Crew+Hotel+Kualanamu',
            ],
            [
                'urut' => 7, 'nama' => 'Nivia Hotel', 'ketersediaan_kamar' => 57, 'rating' => null,
                'alamat' => 'Jl. Letda Sujono No.91, Bantan Timur, Kec. Medan Tembung (berbatasan Deli Serdang) 20223',
                'lat' => 3.5980260, 'lng' => 98.7091670,
                'contact_wa' => '0811-6188933', 'contact_email' => null,
                'maps_url' => 'https://www.google.com/maps/search/?api=1&query=Nivia+Hotel+Medan',
            ],
            [
                'urut' => 8, 'nama' => 'Pancur Gading Hotel dan Resort', 'ketersediaan_kamar' => 53, 'rating' => null,
                'alamat' => 'Jl. Kuala Simeme No.1, Pamah, Kec. Namorambe, Kab. Deli Serdang 20356',
                'lat' => 3.4902000, 'lng' => 98.6931000, // approx
                'contact_wa' => '085261618174', 'contact_email' => 'pancurgadinghotelresort@gmail.com',
                'maps_url' => 'https://www.google.com/maps/search/?api=1&query=Pancur+Gading+Hotel+dan+Resort',
            ],
            [
                'urut' => 9, 'nama' => 'Sapadia Guest House Tanjung Morawa', 'ketersediaan_kamar' => 25, 'rating' => null,
                'alamat' => 'Jl. Medan - Tebing Tinggi No.99, Perdamaian, Kec. Tanjung Morawa, Kab. Deli Serdang 20513',
                'lat' => 3.5300000, 'lng' => 98.8230000, // approx
                'contact_wa' => '082168859600', 'contact_email' => 'sapadiaguesthousetamora@gmail.com',
                'maps_url' => 'https://www.google.com/maps/search/?api=1&query=Sapadia+Guest+House+Tanjung+Morawa',
            ],
            [
                'urut' => 10, 'nama' => 'Anara Sky Hotel Kualanamu', 'ketersediaan_kamar' => 25, 'rating' => 4.5,
                'alamat' => 'Bandara Internasional Kualanamu, Lt.2 Mezzanine, Beringin, Kab. Deli Serdang 20553',
                'lat' => 3.6353820, 'lng' => 98.8788080,
                'contact_wa' => '081161400818', 'contact_email' => 'rsv.skykualanamu@anara.id',
                'maps_url' => 'https://www.google.com/maps/search/?api=1&query=Anara+Sky+Hotel+Kualanamu',
            ],
            [
                'urut' => 11, 'nama' => 'The Quadrant Hotel KNO', 'ketersediaan_kamar' => 45, 'rating' => null,
                'alamat' => 'Komplek Hub Bizpark, Jl. Arteri Kualanamu No.30 Blok A, Tumpatan Nibung, Kec. Batang Kuis, Kab. Deli Serdang 20372',
                'lat' => 3.5990000, 'lng' => 98.8312000, // approx (kompleks Kualanamu Hub Bizpark)
                'contact_wa' => '08116589898', 'contact_email' => 'reservasi@hotelquadrantkno.com',
                'maps_url' => 'https://www.google.com/maps/search/?api=1&query=The+Quadrant+Hotel+KNO+Kualanamu',
            ],
            [
                'urut' => 12, 'nama' => "Thong's Inn Hotel Kualanamu", 'ketersediaan_kamar' => 102, 'rating' => 4.0,
                'alamat' => 'Jl. Pasar V Kebun Kelapa, Kualanamu, Kec. Beringin, Kab. Deli Serdang 20552',
                'lat' => 3.6360000, 'lng' => 98.8790000, // approx
                'contact_wa' => '081212111081', 'contact_email' => 'reservation@thongsinn.com',
                'maps_url' => 'https://www.google.com/maps/search/?api=1&query=Thongs+Inn+Hotel+Kualanamu',
            ],
            [
                'urut' => 13, 'nama' => 'The Hill Hotel dan Resort Sibolangit', 'ketersediaan_kamar' => null, 'rating' => 4.2,
                'alamat' => 'Jl. Letjend Jamin Ginting KM 45,3, Sikeben, Kec. Sibolangit, Kab. Deli Serdang 20357',
                'lat' => 3.2710200, 'lng' => 98.5472200,
                'contact_wa' => '082277791131', 'contact_email' => 'info@thehillresort.com',
                'maps_url' => 'https://www.google.com/maps/search/?api=1&query=The+Hill+Hotel+dan+Resort+Sibolangit',
            ],
        ];

        foreach ($data as $row) {
            Hotel::updateOrCreate(['nama' => $row['nama']], array_merge(['is_active' => true], $row));
        }
    }
}

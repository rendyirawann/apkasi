<?php

namespace Database\Seeders;

use App\Models\Setting;
use App\Models\SiteLogo;
use App\Models\Faq;
use Illuminate\Database\Seeder;

class LandingSeeder extends Seeder
{
    /**
     * Konten landing page (CMS). Settings pakai firstOrCreate agar TIDAK menimpa
     * perubahan admin saat re-seed. Logo & FAQ hanya diisi bila tabel masih kosong.
     */
    public function run(): void
    {
        $settings = [
            // Hero
            'lp_hero_headline'      => 'Bersinergi Membangun Daerah',
            'lp_hero_accent'        => 'Memperkuat Otonomi Indonesia',
            'lp_hero_subtitle'      => 'HUT Ke-26 APKASI & HUT Ke-80 Kabupaten Deli Serdang',
            'lp_hero_location'      => 'Kabupaten Deli Serdang, Sumatera Utara',
            'lp_event_start'        => '2026-07-01',
            'lp_event_end'          => '2026-07-03',
            'lp_countdown_time'     => '19:00',
            'lp_hero_tagline_title' => 'Portal Resmi HUT APKASI 2026',
            'lp_hero_tagline_desc'  => 'Informasi agenda, panduan delegasi, akomodasi, dan peta lokasi selama rangkaian kegiatan di Deli Serdang.',
            'lp_hero_footer_place'  => 'Deli Serdang',
            // Background hero (gambar / video)
            'lp_hero_bg_type'       => 'image',
            'lp_hero_bg_image'      => 'logos/hero-bg.png',
            'lp_hero_bg_video'      => 'assets/apkasi/mars-hero.mp4',
            // Kolaborasi
            'lp_partners_title'     => 'Kolaborasi Penyelenggara',
            // Pimpinan
            'lp_pimpinan_badge'        => 'Pimpinan Daerah Tuan Rumah',
            'lp_pimpinan_heading'      => 'Kabupaten Deli Serdang',
            'lp_pimpinan_sub'          => 'Menyambut seluruh delegasi APKASI dalam rangkaian peringatan HUT Ke-80 Kabupaten Deli Serdang.',
            'lp_bupati_nama'           => 'H. Ali Yusuf Siregar, S.Sos.',
            'lp_bupati_jabatan'        => 'Bupati Deli Serdang',
            'lp_bupati_periode'        => 'Periode 2024–2029',
            'lp_bupati_foto'           => 'assets/apkasi/z_04_LOGO-LOGO APKASI/BUPATI.png',
            'lp_wabup_nama'            => 'HM. Yusuf Siregar, S.E.',
            'lp_wabup_jabatan'         => 'Wakil Bupati Deli Serdang',
            'lp_wabup_periode'         => 'Periode 2024–2029',
            'lp_wabup_foto'            => 'assets/apkasi/z_04_LOGO-LOGO APKASI/WABUPATI.png',
            'lp_pimpinan_quote'        => 'Kami sangat bangga menjadi tuan rumah HUT APKASI Ke-26. Deli Serdang siap menyambut seluruh Bupati dan perwakilan kabupaten se-Indonesia untuk bersinergi membangun daerah.',
            'lp_pimpinan_quote_author' => 'Bupati Deli Serdang',
            // Tentang Event
            'lp_about_badge'   => 'Tentang Event',
            'lp_about_heading' => 'Dua Hari Jadi Besar, Satu Tekad Bersinergi',
            'lp_about_p1'      => 'Rangkaian ini memperingati HUT APKASI (Asosiasi Pemerintah Kabupaten Seluruh Indonesia) Ke-26 sekaligus HUT Kabupaten Deli Serdang Ke-80, dengan tema besar:',
            'lp_about_quote'   => '"Penguatan Sinergi Antar Pemerintah Kabupaten Dalam Mendukung Pembangunan Daerah dan Otonomi Daerah."',
            'lp_about_p2'      => 'Pemerintah Kabupaten Deli Serdang, Sumatera Utara, menyambut perwakilan dari seluruh pemerintah kabupaten di Indonesia untuk membahas strategi pembiayaan alternatif, kemandirian ekonomi lokal, dan peran perempuan dalam pemberantasan stunting.',
            'lp_about_image'   => 'https://images.unsplash.com/photo-1540575467063-178a50c2df87?auto=format&fit=crop&w=900&q=80',
            'lp_about_stat1_label' => '400+ Delegasi',  'lp_about_stat1_sub' => 'Bupati se-Indonesia',
            'lp_about_stat2_label' => '3 Venue Utama',   'lp_about_stat2_sub' => 'Deli Serdang',
            'lp_about_stat3_label' => 'Grand Final POI', 'lp_about_stat3_sub' => 'Putri Otonomi 2026',
            'lp_about_stat4_label' => 'Women Program',   'lp_about_stat4_sub' => 'UMKM & Stunting',
            // Putri Otonomi
            'lp_poi_badge'    => 'Special Event',
            'lp_poi_heading1' => 'Malam Grand Final',
            'lp_poi_heading2' => 'Putri Otonomi Indonesia',
            'lp_poi_heading3' => '2026',
            'lp_poi_desc'     => 'Ajang bergengsi pemilihan duta otonomi daerah dari seluruh kabupaten di Indonesia. Malam penobatan puncak dilaksanakan Kamis, 2 Juli 2026 di Graha Bhineka.',
            'lp_poi_image'    => 'assets/apkasi/poi-awards.svg',
            'lp_poi_info1_title' => 'Penobatan Juara',    'lp_poi_info1_sub' => 'Duta Otonomi Nasional',
            'lp_poi_info2_title' => '400+ Kepala Daerah',  'lp_poi_info2_sub' => 'Bupati & Tokoh Nasional',
            'lp_poi_info3_title' => 'Kamis, 2 Juli 2026',  'lp_poi_info3_sub' => '18.30 – 22.00 WIB',
            'lp_poi_info4_title' => 'Graha Bhineka',       'lp_poi_info4_sub' => 'Deli Serdang',
            // Footer
            'lp_footer_tagline'     => 'Asosiasi Pemerintah Kabupaten Seluruh Indonesia. Memperkuat otonomi daerah untuk Indonesia Maju.',
            'lp_footer_sekretariat' => 'Dinas Kominfo Kabupaten Deli Serdang, Sumatera Utara',
            'lp_footer_copyright'   => '© 2026 Pemerintah Kabupaten Deli Serdang & APKASI. All rights reserved.',
        ];

        foreach ($settings as $key => $value) {
            Setting::firstOrCreate(['key' => $key], ['value' => $value]);
        }
        Setting::clearCache();

        // Logo (hanya bila kosong)
        if (SiteLogo::count() === 0) {
            $logos = [
                ['navbar', 'logos/logo-ds.png', 'Deli Serdang'],
                ['navbar', 'logos/apkasi-alt2.png', 'APKASI'],
                ['navbar', 'logos/aoe2026.png', 'AOE 2026'],
                ['hero_v1', 'logos/logo-ds.png', 'Deli Serdang'],
                ['hero_v1', 'logos/apkasi-official.png', 'APKASI'],
                ['hero_v2', 'logos/logo_hut26.webp', 'HUT Ke-26 APKASI'],
                ['hero_v2', 'logos/hutds80.png', 'HUT Ke-80 Deli Serdang'],
                ['hero_v3', 'logos/aoe2026.png', 'APKASI Otonomi Expo'],
                ['hero_v3', 'logos/poi.png', 'Putri Otonomi Indonesia'],
                ['partners', 'logos/apkasi-full.png', 'APKASI'],
                ['partners', 'logos/hut-apkasi.png', 'HUT APKASI 2026'],
                ['partners', 'logos/aoe2026.png', 'AOE 2026'],
                ['partners', 'logos/logo-ds.png', 'Kab. Deli Serdang'],
                ['footer_brand', 'logos/apkasi-logo.png', 'APKASI'],
                ['footer_brand', 'logos/hut-apkasi.png', 'HUT APKASI'],
                ['footer_side', 'logos/logo-ds.png', 'Deli Serdang'],
                ['footer_side', 'logos/aoe2026-trans.png', 'AOE 2026'],
            ];
            foreach ($logos as $i => $l) {
                SiteLogo::create(['grup' => $l[0], 'gambar' => $l[1], 'alt' => $l[2], 'urut' => $i + 1, 'is_active' => true]);
            }
        }

        // Logo hero CAROUSEL 2 versi — idempotent (tetap dibuat walau tabel logo sudah terisi
        // dari seed lama). Versi 1: Deli Serdang + APKASI. Versi 2: HUT Ke-26 + HUT Ke-80.
        $heroCarousel = [
            ['hero_v1', 'logos/logo-ds.png', 'Deli Serdang', 1],
            ['hero_v1', 'logos/apkasi-official.png', 'APKASI', 2],
            ['hero_v2', 'logos/logo_hut26.webp', 'HUT Ke-26 APKASI', 1],
            ['hero_v2', 'logos/hutds80.png', 'HUT Ke-80 Deli Serdang', 2],
            ['hero_v3', 'logos/aoe2026.png', 'APKASI Otonomi Expo', 1],
            ['hero_v3', 'logos/poi.png', 'Putri Otonomi Indonesia', 2],
        ];
        foreach ($heroCarousel as $l) {
            SiteLogo::firstOrCreate(
                ['grup' => $l[0], 'gambar' => $l[1]],
                ['alt' => $l[2], 'urut' => $l[3], 'is_active' => true]
            );
        }

        // FAQ (hanya bila kosong)
        if (Faq::count() === 0) {
            $faqs = [
                ['Apa saja dresscode untuk Kepala Daerah selama acara?', 'Welcome Dinner: Batik khas Deli Serdang. Dialog & FORBISDA: Kemeja Putih APKASI. Malam Final POI: Batik Resmi APKASI. Fun Walk: Kaos, topi, dan gelang peserta dari APKASI.'],
                ['Di mana dan kapan stempel SPPD / Surat Tugas bisa diproses?', 'Di Meja Registrasi Delegasi pada IKM Hall (2 Juli, 08.00–15.00 WIB) dan Graha Bhineka (1 Juli, 18.00–20.00 WIB). Pastikan membawa dokumen cetak Surat Tugas.'],
                ['Bagaimana shuttle bus untuk delegasi daerah?', 'Panitia menyediakan bus shuttle dari hotel rekomendasi menuju venue acara (PP). Bagi yang menghendaki mobil privat, tersedia info rental di halaman Panduan Delegasi.'],
                ['Bagaimana cara mendapatkan kaos Fun Walk?', 'Kaos, topi, dan gelang peserta (dengan nomor doorprize) disiapkan oleh APKASI dan dibagikan di loket pendaftaran Fun Walk, Alun-Alun Deli Serdang, pukul 06.00 WIB.'],
            ];
            foreach ($faqs as $i => $f) {
                Faq::create(['pertanyaan' => $f[0], 'jawaban' => $f[1], 'urut' => $i + 1, 'is_active' => true]);
            }
        }
    }
}

<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Display the landing page.
     */
    public function index(Request $request)
    {
        // Intro hero tampil maksimal sekali per 2 jam per IP (server-side gate).
        // Setelah loader selesai, intro hanya muncul bila IP ini belum melihatnya dalam 2 jam terakhir.
        $introKey = 'intro_shown_' . md5((string) $request->ip());
        $showIntro = ! \Illuminate\Support\Facades\Cache::has($introKey);
        if ($showIntro) {
            \Illuminate\Support\Facades\Cache::put($introKey, true, now()->addHours(2));
        }

        // Target date for countdown (1 July 2026, 19:00:00)
        $eventTargetDate = "2026-07-01 19:00:00";

        // Rundown summary for preview
        $rundownHighlights = [
            [
                'day' => 'Hari 1',
                'date' => 'Rabu, 1 Juli 2026',
                'time' => '19.00 - 22.00',
                'title' => 'Welcome Dinner & Syukuran HUT APKASI',
                'location' => 'Graha Bhineka',
                'icon' => 'ki-glass',
                'desc' => 'Makan malam selamat datang untuk para Bupati, TP PKK, dan OPD se-Indonesia dilanjutkan syukuran hari jadi APKASI.'
            ],
            [
                'day' => 'Hari 2',
                'date' => 'Kamis, 2 Juli 2026',
                'time' => '09.00 - 15.00',
                'title' => 'Dialog Otonomi Daerah & Forum Bisnis Daerah',
                'location' => 'IKM Hall',
                'icon' => 'ki-briefcase',
                'desc' => 'Dialog strategi pembiayaan daerah, talkshow peran perempuan (UMKM & Stunting), dan forum bisnis daerah (FORBISDA).'
            ],
            [
                'day' => 'Hari 2',
                'date' => 'Kamis, 2 Juli 2026',
                'time' => '18.30 - 22.00',
                'title' => 'Malam Final Putri Otonomi Indonesia 2026',
                'location' => 'Graha Bhineka',
                'icon' => 'ki-crown',
                'desc' => 'Malam puncak penobatan Putri Otonomi Indonesia 2026 yang dihadiri oleh seluruh delegasi daerah.'
            ],
            [
                'day' => 'Hari 3',
                'date' => 'Jumat, 3 Juli 2026',
                'time' => '06.00 - 12.00',
                'title' => 'Fun Walk & Penanaman Pohon',
                'location' => 'Alun-Alun Deli Serdang',
                'icon' => 'ki-color-swatch',
                'desc' => 'Jalan santai bersama, penanaman pohon untuk kelestarian lingkungan, dan pengundian doorprize.'
            ]
        ];

        // Rundown rangkaian kegiatan (dari tabel rundown + child kegiatan)
        $agenda = \App\Models\Rundown::with('kegiatan')
            ->where('is_active', true)
            ->orderBy('urut')
            ->orderBy('tanggal')
            ->get();

        // Semua logo aktif dlm SATU query lalu dikelompokkan per grup (hindari 5 query terpisah).
        $logosByGrup = \App\Models\SiteLogo::where('is_active', true)
            ->orderBy('urut')->orderBy('id')->get()->groupBy('grup');
        $emptyLogos       = collect();
        $navbarLogos      = $logosByGrup->get('navbar', $emptyLogos);
        $heroLogosV1      = $logosByGrup->get('hero_v1', $emptyLogos); // carousel versi 1: Deli Serdang + APKASI
        $heroLogosV2      = $logosByGrup->get('hero_v2', $emptyLogos); // carousel versi 2: HUT Ke-26 + HUT Ke-80
        $partnerLogos     = $logosByGrup->get('partners', $emptyLogos);
        $footerBrandLogos = $logosByGrup->get('footer_brand', $emptyLogos);
        $footerSideLogos  = $logosByGrup->get('footer_side', $emptyLogos);
        $faqs             = \App\Models\Faq::active()->get();

        // Countdown & rentang tanggal acara — ambil dari settings yang SUDAH dimuat (allCached, memoized),
        // hindari 3 lookup terpisah.
        $s     = \App\Models\Setting::allCached();
        $start = $s['lp_event_start'] ?? '2026-07-01';
        $end   = $s['lp_event_end'] ?? '2026-07-03';
        $time  = $s['lp_countdown_time'] ?? '19:00';

        $countdownTarget = \Carbon\Carbon::parse($start . ' ' . $time, 'Asia/Jakarta')->toIso8601String();

        $cs = \Carbon\Carbon::parse($start)->locale('id');
        $ce = \Carbon\Carbon::parse($end)->locale('id');
        if ($cs->isSameDay($ce)) {
            $eventRangeText = $cs->translatedFormat('j F Y');
        } elseif ($cs->month === $ce->month && $cs->year === $ce->year) {
            $eventRangeText = $cs->translatedFormat('j') . ' – ' . $ce->translatedFormat('j F Y');
        } else {
            $eventRangeText = $cs->translatedFormat('j M') . ' – ' . $ce->translatedFormat('j M Y');
        }

        return view('frontend.index', compact(
            'eventTargetDate', 'rundownHighlights', 'agenda',
            'navbarLogos', 'heroLogosV1', 'heroLogosV2', 'partnerLogos', 'footerBrandLogos', 'footerSideLogos',
            'faqs', 'countdownTarget', 'eventRangeText', 'showIntro'
        ));
    }

    /**
     * Display the unified guide portal.
     */
    public function guide()
    {
        // Detailed venues data
        $venues = [
            [
                'name' => 'Graha Bhineka Deli Serdang',
                'type' => 'Welcome Dinner & Malam Final POI',
                'address' => 'Kawasan Kantor Bupati Deli Serdang, Lubuk Pakam',
                'map_embed' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3982.3553257321526!2d98.87515057589993!3d3.50482279646949!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x303125df9dbccb5b%3A0xe6bf4465e94b2f15!2sGraha%20Bhinneka!5e0!3m2!1sid!2sid!4v1717478000000!5m2!1sid!2sid',
                'map_link' => 'https://maps.google.com/?q=Graha+Bhinneka+Deli+Serdang',
                'image' => 'https://images.unsplash.com/photo-1511578314322-379afb476865?auto=format&fit=crop&w=600&q=80',
                'details' => 'Menjadi lokasi pembukaan (Welcome Dinner) pada 1 Juli malam, serta perhelatan akbar Malam Puncak Penobatan Putri Otonomi Indonesia 2026.'
            ],
            [
                'name' => 'IKM Hall Deli Serdang',
                'type' => 'Dialog Otonomi, Women Program & Forum Bisnis',
                'address' => 'Komplek Pusat Pengembangan Produk Unggulan Daerah (P3UD) Deli Serdang, Tanjung Morawa',
                'map_embed' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3982.1228514109724!2d98.78918237590209!3d3.5591963964147743!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x30313bca883bfd11%3A0x67db2cc8327d7f7e!2sP3UD%20Deli%20Serdang!5e0!3m2!1sid!2sid!4v1717478100000!5m2!1sid!2sid',
                'map_link' => 'https://maps.google.com/?q=P3UD+Deli+Serdang+Tanjung+Morawa',
                'image' => 'https://images.unsplash.com/photo-1497366216548-37526070297c?auto=format&fit=crop&w=600&q=80',
                'details' => 'Lokasi seluruh kegiatan forum ilmiah, diskusi strategi pembiayaan alternatif pembangunan daerah, talkshow penanganan stunting dan UMKM, serta forum bisnis daerah (FORBISDA).'
            ],
            [
                'name' => 'Alun-Alun Deli Serdang',
                'type' => 'Fun Walk & Penanaman Pohon',
                'address' => 'Jalan Karya Jasa, Lubuk Pakam (Depan Kantor Bupati)',
                'map_embed' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3982.3524976722336!2d98.87413697590021!3d3.5054944964687847!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x303125df1a1a1f01%3A0x58c0c4c478a572a1!2sAlun-Alun%20Kabupaten%20Deli%20Serdang!5e0!3m2!1sid!2sid!4v1717478200000!5m2!1sid!2sid',
                'map_link' => 'https://maps.google.com/?q=Alun-Alun+Kabupaten+Deli+Serdang',
                'image' => 'https://images.unsplash.com/photo-1502082553048-f009c37129b9?auto=format&fit=crop&w=600&q=80',
                'details' => 'Titik start & finish kegiatan jalan sehat (Fun Walk), lokasi panggung hiburan rakyat, pembagian doorprize, serta simbolisasi aksi penanaman pohon.'
            ]
        ];

        // Tourist attractions data
        $tourisms = [
            [
                'name' => 'Danau Linting',
                'location' => 'Kecamatan Sinembah Tanjung Muda Hulu',
                'description' => 'Danau vulkanik dengan air panas belerang berwarna hijau toska eksotis. Suasananya sejuk dan dikelilingi pepohonan rindang.',
                'map_link' => 'https://maps.google.com/?q=Danau+Linting+Deli+Serdang',
                'image' => 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=600&q=80', // Fallback placeholder image
                'distance' => '± 1.5 jam dari Lubuk Pakam'
            ],
            [
                'name' => 'Wisata Kuliner Pasar Kamu',
                'location' => 'Desa Denai Lama, Kecamatan Pantai Labu',
                'description' => 'Pasar kuliner tradisional khas Melayu yang hanya buka hari Minggu pagi. Transaksi menggunakan koin bambu ("tempu") dengan suasana pedesaan yang asri.',
                'map_link' => 'https://maps.google.com/?q=Pasar+Kamu+Denai+Lama+Deli+Serdang',
                'image' => 'https://images.unsplash.com/photo-1555396273-367ea4eb4db5?auto=format&fit=crop&w=600&q=80',
                'distance' => '± 25 menit dari Kualanamu'
            ],
            [
                'name' => 'Museum Daerah Deli Serdang',
                'location' => 'Komplek Pemkab Deli Serdang, Lubuk Pakam',
                'description' => 'Museum modern yang menyimpan artefak sejarah Kesultanan Serdang, kebudayaan Melayu, Karo, dan Simalungun. Sangat edukatif.',
                'map_link' => 'https://maps.google.com/?q=Museum+Deli+Serdang+Lubuk+Pakam',
                'image' => 'https://images.unsplash.com/photo-1582719508461-905c673771fd?auto=format&fit=crop&w=600&q=80',
                'distance' => 'Dekat dengan lokasi Welcome Dinner'
            ],
            [
                'name' => 'Air Terjun Dua Warna',
                'location' => 'Kecamatan Sibolangit',
                'description' => 'Destinasi trekking petualangan legendaris dengan air terjun dua gradasi warna (biru muda dingin dan putih hangat) akibat kandungan belerang ringannya.',
                'map_link' => 'https://maps.google.com/?q=Air+Terjun+Dua+Warna+Sibolangit',
                'image' => 'https://images.unsplash.com/photo-1470071459604-3b5ec3a7fe05?auto=format&fit=crop&w=600&q=80',
                'distance' => '± 2 jam dari Lubuk Pakam (arah Berastagi)'
            ]
        ];

        // Hotels data
        $hotels = [
            [
                'name' => 'Prime Plaza Hotel Kualanamu',
                'class' => '⭐️⭐️⭐️⭐️ (Bintang 4)',
                'address' => 'Jl. Arteri Kualanamu, Tumpatan Nibung, Batang Kuis',
                'distance' => '10 Menit dari Bandara Kualanamu, 20 Menit ke Lubuk Pakam',
                'phone' => '+62 61-8881-2888',
                'map_link' => 'https://maps.google.com/?q=Prime+Plaza+Hotel+Kualanamu',
                'image' => 'https://images.unsplash.com/photo-1566073771259-6a8506099945?auto=format&fit=crop&w=600&q=80'
            ],
            [
                'name' => 'Thong’s Inn Transit Hotel Kualanamu',
                'class' => '⭐️⭐️⭐️ (Bintang 3 - Garden Resort)',
                'address' => 'Jl. Pasar V Kebun Kelapa, Kualanamu, Beringin',
                'distance' => '8 Menit dari Bandara Kualanamu, 15 Menit ke Lubuk Pakam',
                'phone' => '+62 61-7956-888',
                'map_link' => 'https://maps.google.com/?q=Thongs+Inn+Kualanamu',
                'image' => 'https://images.unsplash.com/photo-1520250497591-112f2f40a3f4?auto=format&fit=crop&w=600&q=80'
            ],
            [
                'name' => 'Wings Hotel Kualanamu',
                'class' => '⭐️⭐️⭐️ (Bintang 3)',
                'address' => 'Jl. Arteri Kualanamu No.9, Komplek Hub Kualanamu',
                'distance' => '10 Menit dari Bandara Kualanamu, 25 Menit ke IKM Hall',
                'phone' => '+62 61-7956-999',
                'map_link' => 'https://maps.google.com/?q=Wings+Hotel+Kualanamu',
                'image' => 'https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?auto=format&fit=crop&w=600&q=80'
            ],
            [
                'name' => 'd’Prima Hotel Kualanamu',
                'class' => '⭐️⭐️ (Bintang 2 - Transit)',
                'address' => 'Stasiun KA Bandara Kualanamu Lantai 2, Beringin',
                'distance' => 'Langsung di dalam Bandara Kualanamu, 25 Menit ke Lubuk Pakam',
                'phone' => '+62 61-8881-2299',
                'map_link' => 'https://maps.google.com/?q=dPrima+Hotel+Kualanamu',
                'image' => 'https://images.unsplash.com/photo-1551882547-ff40c63fe5fa?auto=format&fit=crop&w=600&q=80'
            ]
        ];

        // Rental mobil (dari DB: tabel rentals + child rental_mobil)
        $rentals = \App\Models\Rental::with('mobil')
            ->where('is_active', true)
            ->orderBy('urut')
            ->get();

        // PIC kegiatan APKASI per provinsi (dari master wilayah_provinsi).
        $pics = \App\Models\Pic::with('provinsi')
            ->where('is_active', true)
            ->orderBy('urut')
            ->get();

        return view('frontend.guide', compact('venues', 'tourisms', 'hotels', 'rentals', 'pics'));
    }
}

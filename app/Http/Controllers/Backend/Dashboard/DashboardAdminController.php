<?php

namespace App\Http\Controllers\Backend\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\DestinasiWisata;
use App\Models\Faq;
use App\Models\Gedung;
use App\Models\Hotel;
use App\Models\Pic;
use App\Models\Rental;
use App\Models\RentalMobil;
use App\Models\Rundown;
use App\Models\RundownKegiatan;
use App\Models\Setting;
use App\Models\SiteLogo;
use App\Models\User;
use App\Models\WilayahProvinsi;
use Carbon\Carbon;

class DashboardAdminController extends Controller
{
    public function index()
    {
        // --- Gedung ---
        $gedungTotal        = Gedung::count();
        $gedungActive       = Gedung::where('is_active', true)->count();
        $gedungLokasiAcara  = Gedung::where('is_active', true)->where('is_lokasi_acara', true)->count();

        // --- Hotel ---
        $hotelTotal       = Hotel::count();
        $hotelActive      = Hotel::where('is_active', true)->count();
        $hotelLokasiAcara = Hotel::where('is_active', true)->where('is_lokasi_acara', true)->count();
        $hotelRatingAvg   = round((float) Hotel::where('is_active', true)->avg('rating'), 1);

        // --- Destinasi Wisata ---
        $destinasiTotal       = DestinasiWisata::count();
        $destinasiActive      = DestinasiWisata::where('is_active', true)->count();
        $destinasiLokasiAcara = DestinasiWisata::where('is_active', true)->where('is_lokasi_acara', true)->count();

        // --- Rental ---
        $rentalTotal    = Rental::where('is_active', true)->count();
        $armadaTotal    = (int) RentalMobil::sum('jumlah_unit');
        $jenisMobilTotal = RentalMobil::count();

        // --- PIC & Provinsi ---
        $picTotal         = Pic::where('is_active', true)->count();
        $provinsiCovered  = Pic::where('is_active', true)->distinct('provinsi_id')->count('provinsi_id');
        $provinsiTotal    = WilayahProvinsi::count();

        // --- Rundown / Agenda ---
        $rundownTotal  = Rundown::where('is_active', true)->count();
        $kegiatanTotal = RundownKegiatan::count();
        $agenda        = Rundown::with('kegiatan')
            ->where('is_active', true)
            ->orderBy('urut')
            ->orderBy('tanggal')
            ->get();

        // --- Konten lain ---
        $faqTotal  = Faq::where('is_active', true)->count();
        $logoTotal = SiteLogo::count();
        $userTotal  = User::count();
        $userActive = User::where('is_active', true)->count();

        // --- Tanggal acara & countdown (mengikuti pola HomeController) ---
        $start = Setting::get('lp_event_start', '2026-07-01');
        $end   = Setting::get('lp_event_end', '2026-07-03');
        $time  = Setting::get('lp_countdown_time', '19:00');

        $countdownTarget = Carbon::parse($start . ' ' . $time, 'Asia/Jakarta')->toIso8601String();

        $cs = Carbon::parse($start)->locale('id');
        $ce = Carbon::parse($end)->locale('id');
        if ($cs->isSameDay($ce)) {
            $eventRangeText = $cs->translatedFormat('j F Y');
        } elseif ($cs->month === $ce->month && $cs->year === $ce->year) {
            $eventRangeText = $cs->translatedFormat('j') . ' – ' . $ce->translatedFormat('j F Y');
        } else {
            $eventRangeText = $cs->translatedFormat('j M') . ' – ' . $ce->translatedFormat('j M Y');
        }

        $eventTitle = Setting::get('lp_event_title', 'HUT Ke-26 APKASI & HUT Ke-80 Kabupaten Deli Serdang');

        return view('backend.dashboard.index', compact(
            'gedungTotal', 'gedungActive', 'gedungLokasiAcara',
            'hotelTotal', 'hotelActive', 'hotelLokasiAcara', 'hotelRatingAvg',
            'destinasiTotal', 'destinasiActive', 'destinasiLokasiAcara',
            'rentalTotal', 'armadaTotal', 'jenisMobilTotal',
            'picTotal', 'provinsiCovered', 'provinsiTotal',
            'rundownTotal', 'kegiatanTotal', 'agenda',
            'faqTotal', 'logoTotal', 'userTotal', 'userActive',
            'countdownTarget', 'eventRangeText', 'eventTitle'
        ));
    }
}

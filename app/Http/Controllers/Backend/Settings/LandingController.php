<?php

namespace App\Http\Controllers\Backend\Settings;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\SiteLogo;
use App\Models\Faq;
use App\Models\FooterColumn;
use App\Models\FooterLink;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LandingController extends Controller
{
    public function index()
    {
        $s = Setting::allCached();
        $logos = [
            'navbar'       => SiteLogo::where('grup', 'navbar')->orderBy('urut')->get(),
            'hero_v1'      => SiteLogo::where('grup', 'hero_v1')->orderBy('urut')->get(),
            'hero_v2'      => SiteLogo::where('grup', 'hero_v2')->orderBy('urut')->get(),
            'hero_v3'      => SiteLogo::where('grup', 'hero_v3')->orderBy('urut')->get(),
            'partners'     => SiteLogo::where('grup', 'partners')->orderBy('urut')->get(),
            'footer_brand' => SiteLogo::where('grup', 'footer_brand')->orderBy('urut')->get(),
            'footer_side'  => SiteLogo::where('grup', 'footer_side')->orderBy('urut')->get(),
        ];
        $faqs = Faq::orderBy('urut')->get();

        $footerColumns = FooterColumn::with('links')->orderBy('urut')->orderBy('id')->get();

        return view('backend.settings.landing', compact('s', 'logos', 'faqs', 'footerColumns'));
    }

    public function update(Request $request)
    {
        // Simpan semua field teks (key diawali lp_)
        foreach ($request->all() as $key => $value) {
            if (Str::startsWith($key, 'lp_') && ! Str::endsWith($key, '_file')) {
                Setting::set($key, is_string($value) ? $value : (string) $value);
            }
        }

        // Upload gambar tunggal (about, poi, foto pimpinan, background hero)
        $imageFields = [
            'lp_about_image_file'    => 'lp_about_image',
            'lp_poi_image_file'      => 'lp_poi_image',
            'lp_bupati_foto_file'    => 'lp_bupati_foto',
            'lp_wabup_foto_file'     => 'lp_wabup_foto',
            'lp_hero_bg_image_file'  => 'lp_hero_bg_image',
            'lp_hero_leaders_file'   => 'lp_hero_leaders_img',
            'panduan_rental_banner_file' => 'panduan_rental_banner',
        ];
        foreach ($imageFields as $fileKey => $settingKey) {
            if ($request->hasFile($fileKey)) {
                $request->validate([$fileKey => 'image|mimes:jpg,jpeg,png,webp|max:5120']);
                $this->deleteLanding(Setting::get($settingKey));
                Setting::set($settingKey, $this->uploadLanding($request->file($fileKey)));
            }
        }

        // Upload video background hero (mp4/webm) — maks 50MB. Default (mars-hero.mp4) tidak dihapus
        // karena berada di luar folder upload landing (deleteLanding hanya hapus file di assets/media/landing).
        if ($request->hasFile('lp_hero_bg_video_file')) {
            $request->validate(['lp_hero_bg_video_file' => 'mimetypes:video/mp4,video/webm,video/quicktime|max:51200']);
            $this->deleteLanding(Setting::get('lp_hero_bg_video'));
            Setting::set('lp_hero_bg_video', $this->uploadLanding($request->file('lp_hero_bg_video_file')));
        }

        Setting::clearCache();

        return back()->with('success', 'Konten landing page berhasil diperbarui!');
    }

    public function logoStore(Request $request)
    {
        $request->validate([
            'grup'   => 'required|in:navbar,hero,hero_v1,hero_v2,hero_v3,partners,footer_brand,footer_side',
            'gambar' => 'required|image|mimes:jpg,jpeg,png,webp,svg|max:3072',
            'alt'    => 'nullable|string|max:100',
        ]);

        // Pindahkan file dulu (lokal, cepat). Bila penyimpanan DB (host terpisah) gagal/timeout,
        // hapus file yatim & tampilkan pesan ramah — jangan biarkan jadi 500 mentah.
        $path = $this->uploadLanding($request->file('gambar'));
        try {
            SiteLogo::create([
                'grup'      => $request->grup,
                'gambar'    => $path,
                'alt'       => $request->alt,
                'urut'      => (int) (SiteLogo::where('grup', $request->grup)->max('urut')) + 1,
                'is_active' => true,
            ]);
        } catch (\Throwable $e) {
            $this->deleteLanding($path);
            report($e);
            return back()->with('error', 'Logo gagal disimpan (koneksi database terputus sesaat). Silakan coba lagi.');
        }

        return back()->with('success', 'Logo berhasil ditambahkan.');
    }

    public function logoDestroy($id)
    {
        $logo = SiteLogo::findOrFail($id);
        $this->deleteLanding($logo->gambar);
        $logo->delete();

        return back()->with('success', 'Logo berhasil dihapus.');
    }

    public function faqSync(Request $request)
    {
        $pertanyaan = (array) $request->input('faq_pertanyaan', []);
        $jawaban    = (array) $request->input('faq_jawaban', []);

        Faq::query()->delete();
        $i = 0;
        foreach ($pertanyaan as $idx => $q) {
            if (! filled($q)) continue;
            Faq::create([
                'pertanyaan' => $q,
                'jawaban'    => $jawaban[$idx] ?? null,
                'urut'       => ++$i,
                'is_active'  => true,
            ]);
        }

        return back()->with('success', 'FAQ berhasil disimpan.');
    }

    /**
     * Sinkron kolom footer dinamis + link-nya dari form berulang.
     * Input: col_judul[ci], link_label[ci][], link_url[ci][]. Icon sosmed dideteksi otomatis.
     */
    public function footerSync(Request $request)
    {
        FooterLink::query()->delete();
        FooterColumn::query()->delete();

        $juduls  = (array) $request->input('col_judul', []);
        $urutCol = 0;
        foreach ($juduls as $ci => $judul) {
            if (! filled($judul)) continue;

            $column = FooterColumn::create([
                'judul'     => $judul,
                'urut'      => ++$urutCol,
                'is_active' => true,
            ]);

            $labels   = (array) $request->input("link_label.$ci", []);
            $urls     = (array) $request->input("link_url.$ci", []);
            $urutLink = 0;
            foreach ($labels as $idx => $label) {
                if (! filled($label)) continue;
                $url = filled($urls[$idx] ?? null) ? $urls[$idx] : '#';
                FooterLink::create([
                    'footer_column_id' => $column->id,
                    'label'            => $label,
                    'url'              => $url,
                    'icon'             => FooterLink::detectIcon($url),
                    'urut'             => ++$urutLink,
                    'is_active'        => true,
                ]);
            }
        }

        return back()->with('success', 'Footer berhasil disimpan.');
    }

    private function uploadLanding($file): string
    {
        // Semua upload landing ke storage/app/public/landing (seragam dgn data master).
        return $file->store('landing', 'public');
    }

    private function deleteLanding(?string $path): void
    {
        if ($path && ! Str::startsWith($path, ['http://', 'https://'])
            && \Illuminate\Support\Facades\Storage::disk('public')->exists($path)) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($path);
        }
    }
}

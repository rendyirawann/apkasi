<?php

namespace App\Http\Controllers\Backend\Settings;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\SiteLogo;
use App\Models\Faq;
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
            'partners'     => SiteLogo::where('grup', 'partners')->orderBy('urut')->get(),
            'footer_brand' => SiteLogo::where('grup', 'footer_brand')->orderBy('urut')->get(),
            'footer_side'  => SiteLogo::where('grup', 'footer_side')->orderBy('urut')->get(),
        ];
        $faqs = Faq::orderBy('urut')->get();

        return view('backend.settings.landing', compact('s', 'logos', 'faqs'));
    }

    public function update(Request $request)
    {
        // Simpan semua field teks (key diawali lp_)
        foreach ($request->all() as $key => $value) {
            if (Str::startsWith($key, 'lp_') && ! Str::endsWith($key, '_file')) {
                Setting::set($key, is_string($value) ? $value : (string) $value);
            }
        }

        // Upload gambar tunggal (about, poi, foto pimpinan)
        $imageFields = [
            'lp_about_image_file'  => 'lp_about_image',
            'lp_poi_image_file'    => 'lp_poi_image',
            'lp_bupati_foto_file'  => 'lp_bupati_foto',
            'lp_wabup_foto_file'   => 'lp_wabup_foto',
        ];
        foreach ($imageFields as $fileKey => $settingKey) {
            if ($request->hasFile($fileKey)) {
                $request->validate([$fileKey => 'image|mimes:jpg,jpeg,png,webp|max:3072']);
                $this->deleteLanding(Setting::get($settingKey));
                Setting::set($settingKey, $this->uploadLanding($request->file($fileKey)));
            }
        }

        Setting::clearCache();

        return back()->with('success', 'Konten landing page berhasil diperbarui!');
    }

    public function logoStore(Request $request)
    {
        $request->validate([
            'grup'   => 'required|in:navbar,hero,hero_v1,hero_v2,partners,footer_brand,footer_side',
            'gambar' => 'required|image|mimes:jpg,jpeg,png,webp,svg|max:3072',
            'alt'    => 'nullable|string|max:100',
        ]);

        SiteLogo::create([
            'grup'      => $request->grup,
            'gambar'    => $this->uploadLanding($request->file('gambar')),
            'alt'       => $request->alt,
            'urut'      => (int) (SiteLogo::where('grup', $request->grup)->max('urut')) + 1,
            'is_active' => true,
        ]);

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

    private function uploadLanding($file): string
    {
        $dir = public_path('assets/media/landing');
        if (! is_dir($dir)) {
            @mkdir($dir, 0755, true);
        }
        $name = 'lp-' . time() . '-' . Str::random(6) . '.' . $file->getClientOriginalExtension();
        $file->move($dir, $name);
        return 'assets/media/landing/' . $name;
    }

    private function deleteLanding(?string $path): void
    {
        if ($path && Str::startsWith($path, 'assets/media/landing/') && file_exists(public_path($path))) {
            @unlink(public_path($path));
        }
    }
}

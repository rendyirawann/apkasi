@extends('backend.layout.app')

@section('title', 'Pengaturan Landing Page')

@php
    $g = fn($k, $d = '') => $s[$k] ?? $d;
    $logoGroups = [
        'navbar'       => 'Logo Navbar',
        'hero_v1'      => 'Logo Hero — Versi 1 (Deli Serdang + APKASI)',
        'hero_v2'      => 'Logo Hero — Versi 2 (HUT Ke-26 APKASI + HUT Ke-80 DS)',
        'partners'     => 'Logo Kolaborasi Penyelenggara',
        'footer_brand' => 'Logo Footer (Brand)',
        'footer_side'  => 'Logo Footer (Samping Copyright)',
    ];
@endphp

@section('content')
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-0">
    <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
        <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
            <h1 class="page-heading text-gray-900 fw-bold fs-3 my-0">Pengaturan Landing Page</h1>
            <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                <li class="breadcrumb-item text-muted"><a href="{{ route('dashboard') }}" class="text-muted text-hover-primary">Home</a></li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-gray-900">Landing Page</li>
            </ul>
        </div>
        <a href="{{ route('home') }}" target="_blank" class="btn btn-sm btn-light-primary"><i class="ki-outline ki-eye fs-4"></i> Lihat Landing</a>
    </div>
</div>

<div id="kt_app_content" class="app-content flex-column-fluid">
    <div id="kt_app_content_container" class="app-container container-xxl">

        <div class="card card-flush mb-10">
            <div class="card-body py-6">

                {{-- ===================== TABS ===================== --}}
                <ul class="nav nav-tabs nav-line-tabs nav-line-tabs-2x fs-6 fw-semibold mb-6">
                    <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#tab_hero"><i class="ki-outline ki-rocket fs-5 me-1"></i> Hero</a></li>
                    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#tab_pimpinan"><i class="ki-outline ki-profile-user fs-5 me-1"></i> Pimpinan</a></li>
                    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#tab_tentang"><i class="ki-outline ki-information fs-5 me-1"></i> Tentang Event</a></li>
                    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#tab_poi"><i class="ki-outline ki-crown fs-5 me-1"></i> Putri Otonomi</a></li>
                    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#tab_footer"><i class="ki-outline ki-element-11 fs-5 me-1"></i> Footer</a></li>
                    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#tab_kolaborasi"><i class="ki-outline ki-people fs-5 me-1"></i> Kolaborasi</a></li>
                    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#tab_logo"><i class="ki-outline ki-picture fs-5 me-1"></i> Logo</a></li>
                    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#tab_faq"><i class="ki-outline ki-message-question fs-5 me-1"></i> FAQ</a></li>
                </ul>

                <div class="tab-content">

                    {{-- ===================== TAB: HERO ===================== --}}
                    <div class="tab-pane fade show active" id="tab_hero" role="tabpanel">
                        <div class="mb-5">
                            <h3 class="fw-bold fs-4 mb-1">Hero Section</h3>
                            <div class="text-muted fs-7">Bagian paling atas landing page: headline besar, sub judul, lokasi, countdown, dan tagline di sudut layar.</div>
                        </div>
                        <form action="{{ route('landing.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row g-4">
                                <div class="col-md-6"><label class="fw-semibold fs-7 mb-1">Headline (baris 1)</label><input type="text" name="lp_hero_headline" class="form-control" value="{{ $g('lp_hero_headline') }}"></div>
                                <div class="col-md-6"><label class="fw-semibold fs-7 mb-1">Headline aksen (baris 2)</label><input type="text" name="lp_hero_accent" class="form-control" value="{{ $g('lp_hero_accent') }}"></div>
                                <div class="col-md-8"><label class="fw-semibold fs-7 mb-1">Sub judul (di bawah headline)</label><input type="text" name="lp_hero_subtitle" class="form-control" value="{{ $g('lp_hero_subtitle') }}"></div>
                                <div class="col-md-4"><label class="fw-semibold fs-7 mb-1">Teks lokasi (ikon pin)</label><input type="text" name="lp_hero_location" class="form-control" value="{{ $g('lp_hero_location') }}"></div>
                                <div class="col-md-3"><label class="fw-semibold fs-7 mb-1">Tanggal mulai acara</label><input type="date" name="lp_event_start" class="form-control" value="{{ $g('lp_event_start') }}"></div>
                                <div class="col-md-3"><label class="fw-semibold fs-7 mb-1">Tanggal selesai</label><input type="date" name="lp_event_end" class="form-control" value="{{ $g('lp_event_end') }}"></div>
                                <div class="col-md-3"><label class="fw-semibold fs-7 mb-1">Jam mulai (countdown)</label><input type="time" name="lp_countdown_time" class="form-control" value="{{ $g('lp_countdown_time', '19:00') }}"></div>
                                <div class="col-md-3"><label class="fw-semibold fs-7 mb-1">Tempat (sudut kanan bawah)</label><input type="text" name="lp_hero_footer_place" class="form-control" value="{{ $g('lp_hero_footer_place') }}"></div>
                                <div class="col-md-12"><div class="alert alert-light-info py-2 px-3 fs-8 mb-0">Countdown & tanggal di sudut kanan bawah otomatis menyesuaikan dari Tanggal mulai/selesai + jam mulai.</div></div>
                                <div class="col-md-5"><label class="fw-semibold fs-7 mb-1">Judul tagline (kiri bawah)</label><input type="text" name="lp_hero_tagline_title" class="form-control" value="{{ $g('lp_hero_tagline_title') }}"></div>
                                <div class="col-md-7"><label class="fw-semibold fs-7 mb-1">Deskripsi tagline (kiri bawah)</label><input type="text" name="lp_hero_tagline_desc" class="form-control" value="{{ $g('lp_hero_tagline_desc') }}"></div>
                            </div>
                            <div class="d-flex align-items-center justify-content-end gap-3 mt-6">
                                <span class="text-muted fs-8">Menyimpan hanya field Hero Section.</span>
                                <button type="submit" class="btn btn-primary"><i class="ki-outline ki-check fs-3"></i> Simpan Hero</button>
                            </div>
                        </form>
                    </div>

                    {{-- ===================== TAB: PIMPINAN ===================== --}}
                    <div class="tab-pane fade" id="tab_pimpinan" role="tabpanel">
                        <div class="mb-5">
                            <h3 class="fw-bold fs-4 mb-1">Pimpinan Daerah Tuan Rumah</h3>
                            <div class="text-muted fs-7">Profil Bupati & Wakil Bupati (foto, nama, jabatan, periode) beserta quote yang tampil di section pimpinan.</div>
                        </div>
                        <form action="{{ route('landing.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row g-4">
                                <div class="col-md-4"><label class="fw-semibold fs-7 mb-1">Badge</label><input type="text" name="lp_pimpinan_badge" class="form-control" value="{{ $g('lp_pimpinan_badge') }}"></div>
                                <div class="col-md-4"><label class="fw-semibold fs-7 mb-1">Heading</label><input type="text" name="lp_pimpinan_heading" class="form-control" value="{{ $g('lp_pimpinan_heading') }}"></div>
                                <div class="col-md-4"><label class="fw-semibold fs-7 mb-1">Sub teks</label><input type="text" name="lp_pimpinan_sub" class="form-control" value="{{ $g('lp_pimpinan_sub') }}"></div>

                                <div class="col-md-6">
                                    <div class="border border-gray-300 rounded p-3">
                                        <div class="fw-bold text-gray-700 mb-2">Bupati</div>
                                        <div class="d-flex gap-3 align-items-start">
                                            <img src="{{ asset($g('lp_bupati_foto', 'assets/apkasi/z_04_LOGO-LOGO APKASI/BUPATI.png')) }}" class="rounded-circle border" style="width:64px;height:64px;object-fit:cover">
                                            <div class="flex-1 d-flex flex-column gap-2 w-100">
                                                <input type="text" name="lp_bupati_nama" class="form-control form-control-sm" placeholder="Nama" value="{{ $g('lp_bupati_nama') }}">
                                                <input type="text" name="lp_bupati_jabatan" class="form-control form-control-sm" placeholder="Jabatan" value="{{ $g('lp_bupati_jabatan') }}">
                                                <input type="text" name="lp_bupati_periode" class="form-control form-control-sm" placeholder="Periode" value="{{ $g('lp_bupati_periode') }}">
                                                <input type="file" name="lp_bupati_foto_file" accept="image/*" class="form-control form-control-sm">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="border border-gray-300 rounded p-3">
                                        <div class="fw-bold text-gray-700 mb-2">Wakil Bupati</div>
                                        <div class="d-flex gap-3 align-items-start">
                                            <img src="{{ asset($g('lp_wabup_foto', 'assets/apkasi/z_04_LOGO-LOGO APKASI/WABUPATI.png')) }}" class="rounded-circle border" style="width:64px;height:64px;object-fit:cover">
                                            <div class="flex-1 d-flex flex-column gap-2 w-100">
                                                <input type="text" name="lp_wabup_nama" class="form-control form-control-sm" placeholder="Nama" value="{{ $g('lp_wabup_nama') }}">
                                                <input type="text" name="lp_wabup_jabatan" class="form-control form-control-sm" placeholder="Jabatan" value="{{ $g('lp_wabup_jabatan') }}">
                                                <input type="text" name="lp_wabup_periode" class="form-control form-control-sm" placeholder="Periode" value="{{ $g('lp_wabup_periode') }}">
                                                <input type="file" name="lp_wabup_foto_file" accept="image/*" class="form-control form-control-sm">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-9"><label class="fw-semibold fs-7 mb-1">Pesan / Quote Bupati</label><textarea name="lp_pimpinan_quote" rows="2" class="form-control">{{ $g('lp_pimpinan_quote') }}</textarea></div>
                                <div class="col-md-3"><label class="fw-semibold fs-7 mb-1">Penanda quote</label><input type="text" name="lp_pimpinan_quote_author" class="form-control" value="{{ $g('lp_pimpinan_quote_author') }}"></div>
                            </div>
                            <div class="d-flex align-items-center justify-content-end gap-3 mt-6">
                                <span class="text-muted fs-8">Menyimpan hanya field Pimpinan.</span>
                                <button type="submit" class="btn btn-primary"><i class="ki-outline ki-check fs-3"></i> Simpan Pimpinan</button>
                            </div>
                        </form>
                    </div>

                    {{-- ===================== TAB: TENTANG EVENT ===================== --}}
                    <div class="tab-pane fade" id="tab_tentang" role="tabpanel">
                        <div class="mb-5">
                            <h3 class="fw-bold fs-4 mb-1">Tentang Event</h3>
                            <div class="text-muted fs-7">Deskripsi acara, quote tema, gambar pendukung, dan 4 kotak statistik yang tampil di section "Tentang".</div>
                        </div>
                        <form action="{{ route('landing.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row g-4">
                                <div class="col-md-4"><label class="fw-semibold fs-7 mb-1">Badge</label><input type="text" name="lp_about_badge" class="form-control" value="{{ $g('lp_about_badge') }}"></div>
                                <div class="col-md-8"><label class="fw-semibold fs-7 mb-1">Heading</label><input type="text" name="lp_about_heading" class="form-control" value="{{ $g('lp_about_heading') }}"></div>
                                <div class="col-md-12"><label class="fw-semibold fs-7 mb-1">Paragraf 1</label><textarea name="lp_about_p1" rows="2" class="form-control">{{ $g('lp_about_p1') }}</textarea></div>
                                <div class="col-md-12"><label class="fw-semibold fs-7 mb-1">Quote tema</label><input type="text" name="lp_about_quote" class="form-control" value="{{ $g('lp_about_quote') }}"></div>
                                <div class="col-md-12"><label class="fw-semibold fs-7 mb-1">Paragraf 2</label><textarea name="lp_about_p2" rows="2" class="form-control">{{ $g('lp_about_p2') }}</textarea></div>
                                <div class="col-md-8"><label class="fw-semibold fs-7 mb-1">Gambar (URL)</label><input type="text" name="lp_about_image" class="form-control" value="{{ $g('lp_about_image') }}"></div>
                                <div class="col-md-4"><label class="fw-semibold fs-7 mb-1">atau Upload Gambar</label><input type="file" name="lp_about_image_file" accept="image/*" class="form-control"></div>
                                @for ($i = 1; $i <= 4; $i++)
                                    <div class="col-md-3">
                                        <div class="border border-gray-300 rounded p-2">
                                            <div class="fs-8 text-muted mb-1">Statistik {{ $i }}</div>
                                            <input type="text" name="lp_about_stat{{ $i }}_label" class="form-control form-control-sm mb-1" placeholder="Label" value="{{ $g('lp_about_stat'.$i.'_label') }}">
                                            <input type="text" name="lp_about_stat{{ $i }}_sub" class="form-control form-control-sm" placeholder="Sub" value="{{ $g('lp_about_stat'.$i.'_sub') }}">
                                        </div>
                                    </div>
                                @endfor
                            </div>
                            <div class="d-flex align-items-center justify-content-end gap-3 mt-6">
                                <span class="text-muted fs-8">Menyimpan hanya field Tentang Event.</span>
                                <button type="submit" class="btn btn-primary"><i class="ki-outline ki-check fs-3"></i> Simpan Tentang Event</button>
                            </div>
                        </form>
                    </div>

                    {{-- ===================== TAB: PUTRI OTONOMI ===================== --}}
                    <div class="tab-pane fade" id="tab_poi" role="tabpanel">
                        <div class="mb-5">
                            <h3 class="fw-bold fs-4 mb-1">Putri Otonomi (Special Event)</h3>
                            <div class="text-muted fs-7">Section khusus Putri Otonomi Indonesia: heading, deskripsi, gambar, dan 4 kotak info pendukung.</div>
                        </div>
                        <form action="{{ route('landing.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row g-4">
                                <div class="col-md-3"><label class="fw-semibold fs-7 mb-1">Badge</label><input type="text" name="lp_poi_badge" class="form-control" value="{{ $g('lp_poi_badge') }}"></div>
                                <div class="col-md-3"><label class="fw-semibold fs-7 mb-1">Heading 1</label><input type="text" name="lp_poi_heading1" class="form-control" value="{{ $g('lp_poi_heading1') }}"></div>
                                <div class="col-md-3"><label class="fw-semibold fs-7 mb-1">Heading 2 (aksen)</label><input type="text" name="lp_poi_heading2" class="form-control" value="{{ $g('lp_poi_heading2') }}"></div>
                                <div class="col-md-3"><label class="fw-semibold fs-7 mb-1">Heading 3 (tahun)</label><input type="text" name="lp_poi_heading3" class="form-control" value="{{ $g('lp_poi_heading3') }}"></div>
                                <div class="col-md-12"><label class="fw-semibold fs-7 mb-1">Deskripsi</label><textarea name="lp_poi_desc" rows="2" class="form-control">{{ $g('lp_poi_desc') }}</textarea></div>
                                <div class="col-md-8"><label class="fw-semibold fs-7 mb-1">Gambar (URL)</label><input type="text" name="lp_poi_image" class="form-control" value="{{ $g('lp_poi_image') }}"></div>
                                <div class="col-md-4"><label class="fw-semibold fs-7 mb-1">atau Upload Gambar</label><input type="file" name="lp_poi_image_file" accept="image/*" class="form-control"></div>
                                @for ($i = 1; $i <= 4; $i++)
                                    <div class="col-md-3">
                                        <div class="border border-gray-300 rounded p-2">
                                            <div class="fs-8 text-muted mb-1">Info {{ $i }}</div>
                                            <input type="text" name="lp_poi_info{{ $i }}_title" class="form-control form-control-sm mb-1" placeholder="Judul" value="{{ $g('lp_poi_info'.$i.'_title') }}">
                                            <input type="text" name="lp_poi_info{{ $i }}_sub" class="form-control form-control-sm" placeholder="Sub" value="{{ $g('lp_poi_info'.$i.'_sub') }}">
                                        </div>
                                    </div>
                                @endfor
                            </div>
                            <div class="d-flex align-items-center justify-content-end gap-3 mt-6">
                                <span class="text-muted fs-8">Menyimpan hanya field Putri Otonomi.</span>
                                <button type="submit" class="btn btn-primary"><i class="ki-outline ki-check fs-3"></i> Simpan Putri Otonomi</button>
                            </div>
                        </form>
                    </div>

                    {{-- ===================== TAB: FOOTER ===================== --}}
                    <div class="tab-pane fade" id="tab_footer" role="tabpanel">
                        <div class="mb-5">
                            <h3 class="fw-bold fs-4 mb-1">Footer</h3>
                            <div class="text-muted fs-7">Teks tagline brand, sekretariat, dan copyright di bagian bawah landing page.</div>
                        </div>
                        <form action="{{ route('landing.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row g-4">
                                <div class="col-md-12"><label class="fw-semibold fs-7 mb-1">Tagline brand</label><input type="text" name="lp_footer_tagline" class="form-control" value="{{ $g('lp_footer_tagline') }}"></div>
                                <div class="col-md-6"><label class="fw-semibold fs-7 mb-1">Teks Sekretariat</label><input type="text" name="lp_footer_sekretariat" class="form-control" value="{{ $g('lp_footer_sekretariat') }}"></div>
                                <div class="col-md-6"><label class="fw-semibold fs-7 mb-1">Teks Copyright</label><input type="text" name="lp_footer_copyright" class="form-control" value="{{ $g('lp_footer_copyright') }}"></div>
                                <div class="col-md-12"><div class="alert alert-light-warning py-2 px-3 fs-8 mb-0">Kolom <b>Navigasi</b> & <b>Panduan</b> di footer mengikuti menu (tidak diedit di sini).</div></div>
                            </div>
                            <div class="d-flex align-items-center justify-content-end gap-3 mt-6">
                                <span class="text-muted fs-8">Menyimpan hanya field Footer.</span>
                                <button type="submit" class="btn btn-primary"><i class="ki-outline ki-check fs-3"></i> Simpan Footer</button>
                            </div>
                        </form>
                    </div>

                    {{-- ===================== TAB: KOLABORASI ===================== --}}
                    <div class="tab-pane fade" id="tab_kolaborasi" role="tabpanel">
                        <div class="mb-5">
                            <h3 class="fw-bold fs-4 mb-1">Kolaborasi Penyelenggara</h3>
                            <div class="text-muted fs-7">Judul section kolaborasi penyelenggara. Logo-logonya dikelola di tab <b>Logo</b> (grup "Kolaborasi Penyelenggara").</div>
                        </div>
                        <form action="{{ route('landing.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row g-4">
                                <div class="col-md-12">
                                    <label class="fw-semibold fs-7 mb-1">Teks judul section</label>
                                    <input type="text" name="lp_partners_title" class="form-control" value="{{ $g('lp_partners_title') }}">
                                </div>
                            </div>
                            <div class="d-flex align-items-center justify-content-end gap-3 mt-6">
                                <span class="text-muted fs-8">Menyimpan hanya field Kolaborasi.</span>
                                <button type="submit" class="btn btn-primary"><i class="ki-outline ki-check fs-3"></i> Simpan Kolaborasi</button>
                            </div>
                        </form>
                    </div>

                    {{-- ===================== TAB: LOGO ===================== --}}
                    <div class="tab-pane fade" id="tab_logo" role="tabpanel">
                        <div class="mb-5">
                            <h3 class="fw-bold fs-4 mb-1">Kelola Logo</h3>
                            <div class="text-muted fs-7">Unggah / hapus logo per area: navbar, hero, kolaborasi penyelenggara, dan footer. <span class="text-gray-700">Logo <b>Hero Versi 1</b> &amp; <b>Versi 2</b> akan tampil bergantian (carousel) di hero landing.</span></div>
                        </div>
                        @foreach ($logoGroups as $grp => $judul)
                            <div class="mb-6">
                                <div class="fw-bold text-gray-800 mb-2">{{ $judul }}</div>
                                <div class="d-flex flex-wrap gap-3 mb-3">
                                    @forelse ($logos[$grp] as $logo)
                                        <div class="position-relative border rounded p-2 bg-light d-flex align-items-center justify-content-center" style="height:64px;width:120px">
                                            <img src="{{ $logo->gambar_url }}" alt="{{ $logo->alt }}" style="max-height:48px;max-width:104px;object-fit:contain">
                                            <form action="{{ route('landing.logo.destroy', $logo->id) }}" method="POST" class="js-del-form position-absolute top-0 end-0">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-icon btn-danger rounded-circle" style="width:22px;height:22px;transform:translate(30%,-30%)"><i class="ki-outline ki-cross fs-8"></i></button>
                                            </form>
                                        </div>
                                    @empty
                                        <span class="text-muted fs-8">Belum ada logo.</span>
                                    @endforelse
                                </div>
                                <form action="{{ route('landing.logo.store') }}" method="POST" enctype="multipart/form-data" class="d-flex flex-wrap gap-2 align-items-end">
                                    @csrf
                                    <input type="hidden" name="grup" value="{{ $grp }}">
                                    <div><label class="fs-8 text-muted d-block">File logo</label><input type="file" name="gambar" accept="image/*" required class="form-control form-control-sm"></div>
                                    <div><label class="fs-8 text-muted d-block">Teks alt</label><input type="text" name="alt" class="form-control form-control-sm" placeholder="opsional"></div>
                                    <button type="submit" class="btn btn-sm btn-light-primary"><i class="ki-outline ki-plus fs-5"></i> Tambah Logo</button>
                                </form>
                            </div>
                            @if (! $loop->last)<div class="separator separator-dashed mb-6"></div>@endif
                        @endforeach
                    </div>

                    {{-- ===================== TAB: FAQ ===================== --}}
                    <div class="tab-pane fade" id="tab_faq" role="tabpanel">
                        <div class="d-flex flex-stack mb-5">
                            <div>
                                <h3 class="fw-bold fs-4 mb-1">Informasi Penting (FAQ)</h3>
                                <div class="text-muted fs-7">Daftar pertanyaan & jawaban yang tampil di section FAQ landing page.</div>
                            </div>
                            <button type="button" class="btn btn-sm btn-light-primary" id="btnAddFaq"><i class="ki-outline ki-plus fs-5"></i> Tambah Pertanyaan</button>
                        </div>
                        <form action="{{ route('landing.faq.sync') }}" method="POST" id="faqForm">
                            @csrf
                            <div id="faqRows" class="d-flex flex-column gap-3">
                                @foreach ($faqs as $f)
                                    <div class="border border-gray-300 rounded p-3 position-relative">
                                        <button type="button" class="btn btn-icon btn-sm btn-light-danger position-absolute top-0 end-0 m-1 btn-rm-faq"><i class="ki-outline ki-cross fs-5"></i></button>
                                        <label class="fw-semibold fs-8 text-muted mb-1">Pertanyaan</label>
                                        <input type="text" name="faq_pertanyaan[]" class="form-control form-control-sm mb-3" placeholder="Pertanyaan" value="{{ $f->pertanyaan }}">
                                        <label class="fw-semibold fs-8 text-muted mb-1">Jawaban</label>
                                        <textarea name="faq_jawaban[]" id="faq_jawaban_{{ $loop->index }}" class="form-control form-control-sm faq-editor" placeholder="Jawaban">{{ $f->jawaban }}</textarea>
                                    </div>
                                @endforeach
                            </div>
                            <div class="d-flex justify-content-end mt-4">
                                <button type="submit" class="btn btn-primary"><i class="ki-outline ki-check fs-3"></i> Simpan FAQ</button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
{{-- Rich text editor untuk jawaban FAQ (TinyMCE self-host via jsDelivr, tanpa API key) --}}
<script src="https://cdn.jsdelivr.net/npm/tinymce@7/tinymce.min.js" referrerpolicy="origin"></script>
<script>
    // Hapus logo (konfirmasi SweetAlert)
    document.querySelectorAll('.js-del-form').forEach(function (f) {
        f.addEventListener('submit', function (e) {
            e.preventDefault();
            Swal.fire({ title: 'Hapus logo ini?', icon: 'warning', showCancelButton: true, confirmButtonText: 'Ya, hapus', cancelButtonText: 'Batal', confirmButtonColor: '#d33' })
                .then(function (r) { if (r.isConfirmed) f.submit(); });
        });
    });

    // ---------- FAQ: editor teks (TinyMCE) + baris dinamis ----------
    var faqEditorSeq = 1000; // counter id unik utk baris baru
    var isDarkMode = function () { return document.documentElement.getAttribute('data-bs-theme') === 'dark'; };

    function initFaqEditor(el) {
        if (typeof tinymce === 'undefined' || !el || el.dataset.tinyInit === '1') return;
        el.dataset.tinyInit = '1';
        tinymce.init({
            target: el,
            menubar: false,
            height: 200,
            branding: false,
            promotion: false,
            plugins: 'lists link autolink',
            toolbar: 'bold italic underline | bullist numlist | link | removeformat',
            skin: isDarkMode() ? 'oxide-dark' : 'oxide',
            content_css: isDarkMode() ? 'dark' : 'default',
            content_style: 'body{font-family:inherit;font-size:14px}'
        });
    }

    function destroyFaqEditor(el) {
        if (typeof tinymce === 'undefined' || !el) return;
        var ed = tinymce.get(el.id);
        if (ed) ed.remove();
    }

    function faqRow() {
        var id = 'faq_jawaban_' + (faqEditorSeq++);
        var w = document.createElement('div');
        w.className = 'border border-gray-300 rounded p-3 position-relative';
        w.innerHTML = '<button type="button" class="btn btn-icon btn-sm btn-light-danger position-absolute top-0 end-0 m-1 btn-rm-faq"><i class="ki-outline ki-cross fs-5"></i></button>' +
            '<label class="fw-semibold fs-8 text-muted mb-1">Pertanyaan</label>' +
            '<input type="text" name="faq_pertanyaan[]" class="form-control form-control-sm mb-3" placeholder="Pertanyaan">' +
            '<label class="fw-semibold fs-8 text-muted mb-1">Jawaban</label>' +
            '<textarea name="faq_jawaban[]" id="' + id + '" class="form-control form-control-sm faq-editor" placeholder="Jawaban"></textarea>';
        return w;
    }

    // Init editor untuk baris yang sudah ada
    document.querySelectorAll('#faqRows .faq-editor').forEach(initFaqEditor);

    // Tambah pertanyaan baru
    document.getElementById('btnAddFaq').addEventListener('click', function () {
        var row = faqRow();
        document.getElementById('faqRows').appendChild(row);
        initFaqEditor(row.querySelector('.faq-editor'));
    });

    // Hapus baris (sekaligus destroy editor-nya)
    document.getElementById('faqRows').addEventListener('click', function (e) {
        var b = e.target.closest('.btn-rm-faq');
        if (!b) return;
        var row = b.closest('.border');
        var ta = row.querySelector('.faq-editor');
        if (ta) destroyFaqEditor(ta);
        row.remove();
    });

    // Sinkronkan isi editor ke textarea sebelum submit
    document.getElementById('faqForm').addEventListener('submit', function () {
        if (typeof tinymce !== 'undefined') tinymce.triggerSave();
    });
</script>
@endpush

@extends('backend.layout.app')
@section('title', 'Dashboard')
@section('content')

    <div class="mt-5 mb-10">

        {{-- begin::Hero / Welcome --}}
        <div class="card mb-7 border-0 overflow-hidden" style="background: linear-gradient(120deg, #0b8a3a 0%, #17C653 100%);">
            <div class="card-body p-8 p-lg-10">
                <div class="row align-items-center gy-7">
                    <div class="col-lg-7">
                        <span class="badge badge-light text-success fw-bold mb-3">
                            <i class="ki-duotone ki-calendar-tick fs-7 text-success me-1"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span><span class="path6"></span></i>
                            {{ $eventRangeText }} · Deli Serdang, Sumatera Utara
                        </span>
                        <h1 class="text-white fw-bolder fs-2x mb-2">
                            Halo, {{ Auth::user()->name }} 👋
                        </h1>
                        <div class="text-white opacity-75 fs-5 fw-semibold mb-4">
                            Selamat datang di panel admin <span class="fw-bold">{{ $eventTitle }}</span>.
                        </div>
                        <div class="d-flex flex-wrap gap-3">
                            <a href="{{ route('rundown.index') }}" class="btn btn-light text-success fw-bold">
                                <i class="ki-duotone ki-calendar fs-4 text-success"><span class="path1"></span><span class="path2"></span></i>
                                Kelola Agenda
                            </a>
                            <a href="{{ route('landing.index') }}" class="btn btn-color-white btn-outline border border-white border-opacity-50 fw-bold">
                                <i class="ki-duotone ki-screen fs-4"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span></i>
                                Landing Page
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-5">
                        {{-- begin::Countdown --}}
                        <div class="bg-white bg-opacity-10 rounded p-5">
                            <div class="text-white text-uppercase fs-8 fw-bold opacity-75 mb-3 text-center text-lg-start">
                                Hitung mundur menuju acara
                            </div>
                            <div class="row g-3 text-center" id="kt_dashboard_countdown" data-target="{{ $countdownTarget }}">
                                <div class="col">
                                    <div class="bg-white rounded py-3">
                                        <div class="fs-2x fw-bolder text-success lh-1" data-cd="days">--</div>
                                        <div class="fs-8 fw-semibold text-gray-600 text-uppercase mt-1">Hari</div>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="bg-white rounded py-3">
                                        <div class="fs-2x fw-bolder text-success lh-1" data-cd="hours">--</div>
                                        <div class="fs-8 fw-semibold text-gray-600 text-uppercase mt-1">Jam</div>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="bg-white rounded py-3">
                                        <div class="fs-2x fw-bolder text-success lh-1" data-cd="mins">--</div>
                                        <div class="fs-8 fw-semibold text-gray-600 text-uppercase mt-1">Menit</div>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="bg-white rounded py-3">
                                        <div class="fs-2x fw-bolder text-success lh-1" data-cd="secs">--</div>
                                        <div class="fs-8 fw-semibold text-gray-600 text-uppercase mt-1">Detik</div>
                                    </div>
                                </div>
                            </div>
                            <div class="text-white text-center fs-8 fw-semibold opacity-75 mt-3 d-none" data-cd="done">
                                Acara sedang / telah berlangsung 🎉
                            </div>
                        </div>
                        {{-- end::Countdown --}}
                    </div>
                </div>
            </div>
        </div>
        {{-- end::Hero / Welcome --}}

        {{-- begin::Stat cards --}}
        <div class="row g-5 g-xl-7 mb-7">

            {{-- Gedung --}}
            <div class="col-sm-6 col-xl-4">
                <a href="{{ route('gedung.index') }}" class="card card-flush h-100 border border-gray-300 border-hover">
                    <div class="card-body d-flex flex-column">
                        <div class="d-flex align-items-center mb-3">
                            <div class="symbol symbol-50px me-4">
                                <span class="symbol-label bg-light-success">
                                    <i class="ki-duotone ki-bank fs-2x text-success"><span class="path1"></span><span class="path2"></span></i>
                                </span>
                            </div>
                            <div>
                                <span class="fs-2hx fw-bolder text-gray-900 lh-1">{{ $gedungActive }}</span>
                                <span class="fs-5 fw-semibold text-gray-500"> / {{ $gedungTotal }}</span>
                                <div class="fs-6 fw-bold text-gray-600">Gedung Aktif</div>
                            </div>
                        </div>
                        <div class="d-flex flex-wrap gap-2 mt-auto">
                            <span class="badge badge-light-success fw-bold">{{ $gedungLokasiAcara }} lokasi acara</span>
                        </div>
                    </div>
                </a>
            </div>

            {{-- Hotel --}}
            <div class="col-sm-6 col-xl-4">
                <a href="{{ route('hotels.index') }}" class="card card-flush h-100 border border-gray-300 border-hover">
                    <div class="card-body d-flex flex-column">
                        <div class="d-flex align-items-center mb-3">
                            <div class="symbol symbol-50px me-4">
                                <span class="symbol-label bg-light-primary">
                                    <i class="ki-duotone ki-home-2 fs-2x text-primary"><span class="path1"></span><span class="path2"></span></i>
                                </span>
                            </div>
                            <div>
                                <span class="fs-2hx fw-bolder text-gray-900 lh-1">{{ $hotelActive }}</span>
                                <span class="fs-5 fw-semibold text-gray-500"> / {{ $hotelTotal }}</span>
                                <div class="fs-6 fw-bold text-gray-600">Hotel Aktif</div>
                            </div>
                        </div>
                        <div class="d-flex flex-wrap gap-2 mt-auto">
                            <span class="badge badge-light-primary fw-bold">{{ $hotelLokasiAcara }} lokasi acara</span>
                            <span class="badge badge-light-warning fw-bold">
                                <i class="ki-duotone ki-star fs-7 text-warning me-1"></i>{{ number_format($hotelRatingAvg, 1) }}
                            </span>
                        </div>
                    </div>
                </a>
            </div>

            {{-- Destinasi Wisata --}}
            <div class="col-sm-6 col-xl-4">
                <a href="{{ route('destinasi.index') }}" class="card card-flush h-100 border border-gray-300 border-hover">
                    <div class="card-body d-flex flex-column">
                        <div class="d-flex align-items-center mb-3">
                            <div class="symbol symbol-50px me-4">
                                <span class="symbol-label bg-light-success">
                                    <i class="ki-duotone ki-geolocation fs-2x text-success"><span class="path1"></span><span class="path2"></span></i>
                                </span>
                            </div>
                            <div>
                                <span class="fs-2hx fw-bolder text-gray-900 lh-1">{{ $destinasiActive }}</span>
                                <span class="fs-5 fw-semibold text-gray-500"> / {{ $destinasiTotal }}</span>
                                <div class="fs-6 fw-bold text-gray-600">Destinasi Wisata</div>
                            </div>
                        </div>
                        <div class="d-flex flex-wrap gap-2 mt-auto">
                            <span class="badge badge-light-success fw-bold">{{ $destinasiLokasiAcara }} lokasi acara</span>
                        </div>
                    </div>
                </a>
            </div>

            {{-- Rental --}}
            <div class="col-sm-6 col-xl-4">
                <a href="{{ route('rentals.index') }}" class="card card-flush h-100 border border-gray-300 border-hover">
                    <div class="card-body d-flex flex-column">
                        <div class="d-flex align-items-center mb-3">
                            <div class="symbol symbol-50px me-4">
                                <span class="symbol-label bg-light-info">
                                    <i class="ki-duotone ki-car fs-2x text-info"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
                                </span>
                            </div>
                            <div>
                                <span class="fs-2hx fw-bolder text-gray-900 lh-1">{{ $rentalTotal }}</span>
                                <div class="fs-6 fw-bold text-gray-600">Rental Aktif</div>
                            </div>
                        </div>
                        <div class="d-flex flex-wrap gap-2 mt-auto">
                            <span class="badge badge-light-info fw-bold">{{ $armadaTotal }} unit armada</span>
                            <span class="badge badge-light fw-bold text-gray-700">{{ $jenisMobilTotal }} jenis mobil</span>
                        </div>
                    </div>
                </a>
            </div>

            {{-- PIC --}}
            <div class="col-sm-6 col-xl-4">
                <a href="{{ route('pics.index') }}" class="card card-flush h-100 border border-gray-300 border-hover">
                    <div class="card-body d-flex flex-column">
                        <div class="d-flex align-items-center mb-3">
                            <div class="symbol symbol-50px me-4">
                                <span class="symbol-label bg-light-warning">
                                    <i class="ki-duotone ki-people fs-2x text-warning"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
                                </span>
                            </div>
                            <div>
                                <span class="fs-2hx fw-bolder text-gray-900 lh-1">{{ $picTotal }}</span>
                                <div class="fs-6 fw-bold text-gray-600">PIC Aktif</div>
                            </div>
                        </div>
                        <div class="d-flex flex-wrap gap-2 mt-auto">
                            <span class="badge badge-light-warning fw-bold">{{ $provinsiCovered }} / {{ $provinsiTotal }} provinsi</span>
                        </div>
                    </div>
                </a>
            </div>

            {{-- Rundown / Agenda --}}
            <div class="col-sm-6 col-xl-4">
                <a href="{{ route('rundown.index') }}" class="card card-flush h-100 border border-gray-300 border-hover">
                    <div class="card-body d-flex flex-column">
                        <div class="d-flex align-items-center mb-3">
                            <div class="symbol symbol-50px me-4">
                                <span class="symbol-label bg-light-danger">
                                    <i class="ki-duotone ki-calendar-8 fs-2x text-danger"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span><span class="path6"></span></i>
                                </span>
                            </div>
                            <div>
                                <span class="fs-2hx fw-bolder text-gray-900 lh-1">{{ $rundownTotal }}</span>
                                <div class="fs-6 fw-bold text-gray-600">Hari Agenda</div>
                            </div>
                        </div>
                        <div class="d-flex flex-wrap gap-2 mt-auto">
                            <span class="badge badge-light-danger fw-bold">{{ $kegiatanTotal }} kegiatan</span>
                        </div>
                    </div>
                </a>
            </div>

            {{-- FAQ / Konten Landing --}}
            <div class="col-sm-6 col-xl-4">
                <a href="{{ route('landing.index') }}" class="card card-flush h-100 border border-gray-300 border-hover">
                    <div class="card-body d-flex flex-column">
                        <div class="d-flex align-items-center mb-3">
                            <div class="symbol symbol-50px me-4">
                                <span class="symbol-label bg-light-primary">
                                    <i class="ki-duotone ki-questionnaire-tablet fs-2x text-primary"><span class="path1"></span><span class="path2"></span></i>
                                </span>
                            </div>
                            <div>
                                <span class="fs-2hx fw-bolder text-gray-900 lh-1">{{ $faqTotal }}</span>
                                <div class="fs-6 fw-bold text-gray-600">FAQ Aktif</div>
                            </div>
                        </div>
                        <div class="d-flex flex-wrap gap-2 mt-auto">
                            <span class="badge badge-light fw-bold text-gray-700">{{ $logoTotal }} logo</span>
                            <span class="badge badge-light fw-bold text-gray-700">{{ $userActive }} / {{ $userTotal }} user</span>
                        </div>
                    </div>
                </a>
            </div>

        </div>
        {{-- end::Stat cards --}}

        <div class="row g-5 g-xl-7">

            {{-- begin::Agenda Acara --}}
            <div class="col-xl-8">
                <div class="card card-flush h-100">
                    <div class="card-header border-0 pt-6">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bold fs-3 mb-1">Agenda Acara</span>
                            <span class="text-muted mt-1 fw-semibold fs-7">Rangkaian kegiatan {{ $eventRangeText }}</span>
                        </h3>
                        <div class="card-toolbar">
                            <a href="{{ route('rundown.index') }}" class="btn btn-sm btn-light-primary fw-bold">
                                <i class="ki-duotone ki-pencil fs-5"><span class="path1"></span><span class="path2"></span></i>
                                Kelola
                            </a>
                        </div>
                    </div>
                    <div class="card-body pt-2">
                        @forelse ($agenda as $rundown)
                            <div class="mb-7">
                                <div class="d-flex align-items-center mb-4">
                                    <div class="symbol symbol-40px me-3">
                                        <span class="symbol-label bg-light-success">
                                            <i class="ki-duotone ki-calendar-tick fs-3 text-success"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span><span class="path6"></span></i>
                                        </span>
                                    </div>
                                    <div>
                                        <div class="fw-bold text-gray-900 fs-5">{{ $rundown->label ?: $rundown->tanggal_format }}</div>
                                        @if ($rundown->label && $rundown->tanggal_format)
                                            <div class="fw-semibold text-muted fs-7">{{ $rundown->tanggal_format }}</div>
                                        @endif
                                    </div>
                                </div>

                                @if ($rundown->kegiatan->isNotEmpty())
                                    <div class="timeline timeline-border-dashed ms-4">
                                        @foreach ($rundown->kegiatan as $keg)
                                            <div class="timeline-item">
                                                <div class="timeline-line"></div>
                                                <div class="timeline-icon">
                                                    <i class="ki-duotone ki-time fs-4 text-success"><span class="path1"></span><span class="path2"></span></i>
                                                </div>
                                                <div class="timeline-content mb-6 mt-n1">
                                                    <div class="pe-3 mb-2">
                                                        <div class="fs-6 fw-bold text-gray-900">{{ $keg->kegiatan }}</div>
                                                        <div class="d-flex align-items-center flex-wrap gap-3 mt-1 fs-7 text-muted">
                                                            @if ($keg->waktu)
                                                                <span class="d-flex align-items-center">
                                                                    <i class="ki-duotone ki-time fs-7 me-1"><span class="path1"></span><span class="path2"></span></i>{{ $keg->waktu }}
                                                                </span>
                                                            @endif
                                                            @if ($keg->lokasi)
                                                                <span class="d-flex align-items-center">
                                                                    <i class="ki-duotone ki-geolocation fs-7 me-1"><span class="path1"></span><span class="path2"></span></i>{{ $keg->lokasi }}
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="text-muted fs-7 ms-4">Belum ada kegiatan untuk hari ini.</div>
                                @endif
                            </div>
                        @empty
                            <div class="d-flex flex-column align-items-center text-center py-10">
                                <i class="ki-duotone ki-calendar-remove fs-3x text-gray-400 mb-4"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span></i>
                                <div class="fw-bold text-gray-700 fs-5 mb-2">Belum ada agenda</div>
                                <div class="text-muted fs-7 mb-4">Tambahkan rundown dan kegiatan acara untuk ditampilkan di sini.</div>
                                <a href="{{ route('rundown.index') }}" class="btn btn-sm btn-primary fw-bold">Tambah Agenda</a>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
            {{-- end::Agenda Acara --}}

            {{-- begin::Akses Cepat --}}
            <div class="col-xl-4">
                <div class="card card-flush h-100">
                    <div class="card-header border-0 pt-6">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bold fs-3 mb-1">Akses Cepat</span>
                            <span class="text-muted mt-1 fw-semibold fs-7">Data master & halaman publik</span>
                        </h3>
                    </div>
                    <div class="card-body pt-2">
                        @php
                            $quickLinks = [
                                ['route' => 'hotels.index',    'label' => 'Hotel',            'desc' => 'Kelola data hotel',       'icon' => 'ki-home-2',     'color' => 'primary'],
                                ['route' => 'destinasi.index', 'label' => 'Destinasi Wisata', 'desc' => 'Kelola tempat wisata',    'icon' => 'ki-geolocation', 'color' => 'success'],
                                ['route' => 'rentals.index',   'label' => 'Rental Mobil',     'desc' => 'Kelola rental & armada',  'icon' => 'ki-car',        'color' => 'info'],
                                ['route' => 'pics.index',      'label' => 'PIC Provinsi',     'desc' => 'Kelola PIC per provinsi', 'icon' => 'ki-people',     'color' => 'warning'],
                                ['route' => 'rundown.index',   'label' => 'Rundown Acara',    'desc' => 'Kelola agenda kegiatan',  'icon' => 'ki-calendar-8', 'color' => 'danger'],
                                ['route' => 'landing.index',   'label' => 'Landing Page',     'desc' => 'CMS, FAQ, logo & hero',   'icon' => 'ki-screen',     'color' => 'primary'],
                            ];
                        @endphp
                        @foreach ($quickLinks as $link)
                            <a href="{{ route($link['route']) }}" class="d-flex align-items-center bg-light-{{ $link['color'] }} bg-hover-light rounded p-4 mb-4">
                                <div class="symbol symbol-40px me-4">
                                    <span class="symbol-label bg-{{ $link['color'] }}">
                                        <i class="ki-duotone {{ $link['icon'] }} fs-3 text-white"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
                                    </span>
                                </div>
                                <div class="flex-grow-1">
                                    <span class="fw-bold text-gray-900 fs-6 d-block">{{ $link['label'] }}</span>
                                    <span class="fw-semibold text-muted fs-7">{{ $link['desc'] }}</span>
                                </div>
                                <i class="ki-duotone ki-arrow-right fs-3 text-{{ $link['color'] }}"><span class="path1"></span><span class="path2"></span></i>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
            {{-- end::Akses Cepat --}}

        </div>

    </div>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var el = document.getElementById('kt_dashboard_countdown');
        if (!el) return;

        var target = new Date(el.getAttribute('data-target')).getTime();
        var fields = {
            days:  el.querySelector('[data-cd="days"]'),
            hours: el.querySelector('[data-cd="hours"]'),
            mins:  el.querySelector('[data-cd="mins"]'),
            secs:  el.querySelector('[data-cd="secs"]'),
            done:  el.querySelector('[data-cd="done"]')
        };

        function pad(n) { return (n < 10 ? '0' : '') + n; }

        var timer;
        function tick() {
            var diff = target - Date.now();
            if (isNaN(target) || diff <= 0) {
                if (fields.days)  fields.days.textContent  = '00';
                if (fields.hours) fields.hours.textContent = '00';
                if (fields.mins)  fields.mins.textContent  = '00';
                if (fields.secs)  fields.secs.textContent  = '00';
                if (fields.done)  fields.done.classList.remove('d-none');
                clearInterval(timer);
                return;
            }
            var d = Math.floor(diff / 86400000);
            var h = Math.floor((diff % 86400000) / 3600000);
            var m = Math.floor((diff % 3600000) / 60000);
            var s = Math.floor((diff % 60000) / 1000);
            if (fields.days)  fields.days.textContent  = d;
            if (fields.hours) fields.hours.textContent = pad(h);
            if (fields.mins)  fields.mins.textContent  = pad(m);
            if (fields.secs)  fields.secs.textContent  = pad(s);
        }

        tick();
        timer = setInterval(tick, 1000);
    });
</script>
@endpush

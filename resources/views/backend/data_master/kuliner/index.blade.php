@extends('backend.layout.app')

@section('title', 'Data Kuliner')

@push('stylesheets')
    <meta name="csrf-token" content="{{ csrf_token() }}" />
@endpush

@section('content')
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-0">
    <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
        <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
            <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">Data Kuliner</h1>
            <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                <li class="breadcrumb-item text-muted"><a href="{{ route('dashboard') }}" class="text-muted text-hover-primary">Home</a></li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted">Data Master</li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-gray-900">Kuliner</li>
            </ul>
        </div>
    </div>
</div>

<div id="kt_app_content" class="app-content flex-column-fluid">
    <div id="kt_app_content_container" class="app-container container-xxl">

        {{-- ===== Pengaturan Logo Halal (global, dipakai semua kuliner halal di peta) ===== --}}
        <div class="card card-flush mb-5">
            <div class="card-header align-items-center py-4">
                <div class="card-title">
                    <span class="fs-6 fw-bold">Logo Halal</span>
                    <span class="text-muted fs-8 ms-2">Ditampilkan pada kuliner berlabel halal di halaman peta publik.</span>
                </div>
            </div>
            <div class="card-body pt-0">
                <form id="halalLogoForm" class="d-flex flex-wrap align-items-center gap-4">
                    <div class="symbol symbol-75px bg-light rounded d-flex align-items-center justify-content-center" style="padding:8px">
                        <img id="halalLogoPreview" src="{{ $halalLogoUrl }}" alt="Logo Halal" style="max-height:60px;max-width:120px;object-fit:contain" />
                    </div>
                    <div class="flex-grow-1" style="min-width:240px">
                        <input type="file" name="halal_logo" id="halal_logo_file" accept=".jpg,.jpeg,.png,.webp,.svg" class="form-control form-control-solid" />
                        <div class="text-danger fs-8 mt-1" data-error="halal_logo"></div>
                        <div class="text-muted fs-8 mt-1">Maks 5 MB — jpg/png/webp/svg. Logo akan disimpan di storage.</div>
                    </div>
                    <button type="submit" class="btn btn-light-success" id="halalLogoBtn" data-kt-indicator="off">
                        <span class="indicator-label"><i class="ki-outline ki-cloud-add fs-4"></i> Ganti Logo</span>
                        <span class="indicator-progress">Mengunggah... <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                    </button>
                </form>
            </div>
        </div>

        <div class="card card-flush">
            <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                <div class="card-title">
                    <span class="fs-5 fw-bold">Daftar Kuliner (Rumah Makan / Restoran)</span>
                </div>
                <div class="card-toolbar">
                    <div class="position-relative my-1 me-3">
                        <i class="ki-outline ki-magnifier fs-3 position-absolute top-50 translate-middle-y ms-4"></i>
                        <input type="text" id="kulinerSearch" class="form-control form-control-solid form-control-sm w-200px w-md-250px ps-11" placeholder="Cari kuliner..." autocomplete="off" />
                    </div>
                    @can('kuliner.create')
                    <button type="button" class="btn btn-primary btn-sm" id="btnAddKuliner">
                        <i class="ki-outline ki-plus fs-3"></i> Tambah Kuliner
                    </button>
                    @endcan
                </div>
            </div>
            <div class="card-body pt-0">
                <table id="kulinerTable" class="table align-middle table-row-dashed fs-6 gy-4 w-100">
                    <thead>
                        <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                            <th class="text-center">No</th>
                            <th>Nama</th>
                            <th>Alamat</th>
                            <th>Jenis</th>
                            <th class="text-center">Halal</th>
                            <th class="text-center">Rating</th>
                            <th class="text-center">Status</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-700 fw-semibold"></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- ===== Modal Tambah/Edit ===== --}}
<div class="modal fade" id="kulinerFormModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-650px">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="fw-bold" id="kulinerFormTitle">Tambah Kuliner</h2>
                <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal"><i class="ki-outline ki-cross fs-1"></i></div>
            </div>
            <form id="kulinerForm">
                <div class="modal-body py-6 px-lg-10">
                    <input type="hidden" name="id" id="k_id" />
                    <div class="row g-4">
                        <div class="col-md-8">
                            <label class="required fw-semibold fs-7 mb-1">Nama</label>
                            <input type="text" name="nama" id="k_nama" class="form-control form-control-solid" placeholder="cth: RM Sehati" />
                            <div class="text-danger fs-8 mt-1" data-error="nama"></div>
                        </div>
                        <div class="col-md-4">
                            <label class="fw-semibold fs-7 mb-1">Jenis Kuliner</label>
                            <input type="text" name="jenis_kuliner" id="k_jenis_kuliner" list="jenisKulinerList" class="form-control form-control-solid" placeholder="cth: Nusantara" />
                            <datalist id="jenisKulinerList">
                                <option value="Nusantara"></option>
                                <option value="Internasional"></option>
                                <option value="Fast Food"></option>
                            </datalist>
                            <div class="text-danger fs-8 mt-1" data-error="jenis_kuliner"></div>
                        </div>
                        <div class="col-md-12">
                            <label class="fw-semibold fs-7 mb-1">Alamat</label>
                            <input type="text" name="alamat" id="k_alamat" class="form-control form-control-solid" placeholder="Alamat lengkap" />
                            <div class="text-danger fs-8 mt-1" data-error="alamat"></div>
                        </div>
                        <div class="col-md-4">
                            <label class="fw-semibold fs-7 mb-1">Rating (0-5)</label>
                            <input type="number" name="rating" id="k_rating" class="form-control form-control-solid" placeholder="cth: 4.4" step="0.1" min="0" max="5" />
                            <div class="text-danger fs-8 mt-1" data-error="rating"></div>
                        </div>
                        <div class="col-md-4">
                            <label class="fw-semibold fs-7 mb-1">Urutan</label>
                            <input type="number" name="urut" id="k_urut" class="form-control form-control-solid" placeholder="0" min="0" />
                            <div class="text-danger fs-8 mt-1" data-error="urut"></div>
                        </div>
                        <div class="col-md-4 d-flex align-items-end">
                            <label class="form-check form-switch form-check-custom form-check-solid mb-2">
                                <input class="form-check-input" type="checkbox" name="halal" id="k_halal" value="1" />
                                <span class="form-check-label fw-semibold">Halal</span>
                            </label>
                        </div>
                        <div class="col-md-6">
                            <label class="fw-semibold fs-7 mb-1">Latitude</label>
                            <input type="text" name="lat" id="k_lat" class="form-control form-control-solid" placeholder="cth: 3.609590" />
                            <div class="text-danger fs-8 mt-1" data-error="lat"></div>
                        </div>
                        <div class="col-md-6">
                            <label class="fw-semibold fs-7 mb-1">Longitude</label>
                            <input type="text" name="lng" id="k_lng" class="form-control form-control-solid" placeholder="cth: 98.658061" />
                            <div class="text-danger fs-8 mt-1" data-error="lng"></div>
                        </div>
                        <div class="col-md-12">
                            <label class="fw-semibold fs-7 mb-1">Link Google Maps <span class="text-muted">(opsional)</span></label>
                            <input type="text" name="maps_url" id="k_maps_url" class="form-control form-control-solid" placeholder="https://maps.google.com/?q=..." />
                            <div class="text-danger fs-8 mt-1" data-error="maps_url"></div>
                        </div>
                        <div class="col-md-12">
                            <label class="fw-semibold fs-7 mb-1">Foto <span class="text-muted">(opsional, maks 10MB — jpg/png/webp)</span></label>
                            <input type="file" name="image_file" id="k_image_file" accept=".jpg,.jpeg,.png,.webp" class="form-control form-control-solid" />
                            <div class="text-danger fs-8 mt-1" data-error="image_file"></div>
                            <div id="k_image_preview" class="mt-2"></div>
                        </div>
                        <div class="col-md-12">
                            <label class="form-check form-switch form-check-custom form-check-solid">
                                <input class="form-check-input" type="checkbox" name="is_active" id="k_is_active" value="1" checked />
                                <span class="form-check-label fw-semibold">Aktif (tampil di halaman publik)</span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" id="kulinerSubmitBtn" data-kt-indicator="off">
                        <span class="indicator-label">Simpan</span>
                        <span class="indicator-progress">Menyimpan... <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ===== Modal Detail ===== --}}
<div class="modal fade" id="kulinerViewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-600px">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="fw-bold">Detail Kuliner</h2>
                <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal"><i class="ki-outline ki-cross fs-1"></i></div>
            </div>
            <div class="modal-body py-6 px-lg-10" id="kulinerViewBody"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

    const URLS = {
        data:      "{{ route('kuliner.data') }}",
        store:     "{{ route('kuliner.store') }}",
        base:      "{{ url('admin/kuliner') }}",
        halalLogo: "{{ route('kuliner.halalLogo') }}",
    };
    const FIELDS = ['nama','jenis_kuliner','alamat','rating','urut','lat','lng','maps_url'];
    let mode = 'create';

    const table = $('#kulinerTable').DataTable({
        dom: "<'row align-items-center'<'col-sm-6 d-flex align-items-center'l><'col-sm-6'>>" +
             "<'table-responsive'tr>" +
             "<'row align-items-center mt-3'<'col-sm-12 col-md-5 text-muted'i><'col-sm-12 col-md-7 d-flex justify-content-md-end'p>>",
        processing: true,
        serverSide: true,
        order: [],
        ajax: { url: URLS.data },
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, className: 'text-center' },
            { data: 'nama', name: 'nama' },
            { data: 'alamat', name: 'alamat' },
            { data: 'jenis', name: 'jenis_kuliner' },
            { data: 'halal_badge', name: 'halal', className: 'text-center' },
            { data: 'rating_badge', name: 'rating', className: 'text-center' },
            { data: 'status', name: 'is_active', className: 'text-center' },
            { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-end' },
        ],
        language: {
            search: 'Cari:', searchPlaceholder: 'nama / alamat',
            lengthMenu: 'Tampilkan _MENU_', info: 'Menampilkan _START_–_END_ dari _TOTAL_',
            infoEmpty: 'Tidak ada data', infoFiltered: '(disaring dari _MAX_)', zeroRecords: 'Data tidak ditemukan',
            paginate: { first: '«', previous: '‹', next: '›', last: '»' }
        }
    });

    $('#kulinerSearch').on('keyup', function () { table.search(this.value).draw(); });

    function clearErrors() {
        document.querySelectorAll('#kulinerForm [data-error]').forEach(el => el.textContent = '');
    }
    function setLoading(on) {
        document.getElementById('kulinerSubmitBtn').setAttribute('data-kt-indicator', on ? 'on' : 'off');
        document.getElementById('kulinerSubmitBtn').disabled = on;
    }
    const formModal = () => bootstrap.Modal.getOrCreateInstance(document.getElementById('kulinerFormModal'));
    const viewModal = () => bootstrap.Modal.getOrCreateInstance(document.getElementById('kulinerViewModal'));

    // Tambah
    $('#btnAddKuliner').on('click', function () {
        mode = 'create';
        document.getElementById('kulinerForm').reset();
        document.getElementById('k_id').value = '';
        document.getElementById('k_is_active').checked = true;
        document.getElementById('k_halal').checked = false;
        document.getElementById('k_image_preview').innerHTML = '';
        clearErrors();
        document.getElementById('kulinerFormTitle').textContent = 'Tambah Kuliner';
        formModal().show();
    });

    // Edit
    $('#kulinerTable').on('click', '.btn-edit', function () {
        const id = $(this).data('id');
        $.get(URLS.base + '/' + id + '/edit', function (res) {
            const d = res.data;
            mode = 'edit';
            clearErrors();
            document.getElementById('k_id').value = d.id;
            FIELDS.forEach(f => { document.getElementById('k_' + f).value = (d[f] ?? ''); });
            document.getElementById('k_is_active').checked = !!d.is_active;
            document.getElementById('k_halal').checked = !!d.halal;
            document.getElementById('k_image_file').value = '';
            document.getElementById('k_image_preview').innerHTML = d.image_url ? '<img src="' + d.image_url + '" class="rounded mt-1" style="height:90px" /> <div class="text-muted fs-8 mt-1">Biarkan kosong jika tidak ingin mengganti foto.</div>' : '<span class="text-muted fs-8">Belum ada foto.</span>';
            document.getElementById('kulinerFormTitle').textContent = 'Edit Kuliner';
            formModal().show();
        }).fail(() => Swal.fire('Gagal', 'Tidak dapat memuat data.', 'error'));
    });

    // Detail
    $('#kulinerTable').on('click', '.btn-view', function () {
        const id = $(this).data('id');
        $.get(URLS.base + '/' + id, function (res) {
            const d = res.data;
            const row = (l, v) => '<div class="d-flex justify-content-between py-2 border-bottom border-gray-200"><span class="text-muted">' + l + '</span><span class="fw-bold text-end ms-4">' + (v ?? '-') + '</span></div>';
            document.getElementById('kulinerViewBody').innerHTML =
                (d.image_url ? '<img src="' + d.image_url + '" class="rounded w-100 mb-4" style="height:170px;object-fit:cover" />' : '') +
                row('Nama', d.nama) + row('Jenis Kuliner', d.jenis_kuliner) + row('Alamat', d.alamat) +
                row('Halal', d.halal ? 'Ya' : 'Tidak') +
                row('Rating', d.rating ?? '-') +
                row('Koordinat', (d.lat && d.lng) ? (d.lat + ', ' + d.lng) : '-') +
                row('Status', d.is_active ? 'Aktif' : 'Nonaktif') +
                (d.maps_url ? '<div class="pt-3"><a href="' + d.maps_url + '" target="_blank" class="btn btn-sm btn-light-primary w-100"><i class="ki-outline ki-geolocation fs-5"></i> Buka di Google Maps</a></div>' : '');
            viewModal().show();
        }).fail(() => Swal.fire('Gagal', 'Tidak dapat memuat data.', 'error'));
    });

    // Submit (create / update)
    $('#kulinerForm').on('submit', function (e) {
        e.preventDefault();
        clearErrors();
        setLoading(true);

        const form = this;
        const fd = new FormData(form);
        fd.set('is_active', document.getElementById('k_is_active').checked ? '1' : '0');
        fd.set('halal', document.getElementById('k_halal').checked ? '1' : '0');

        let url = URLS.store;
        if (mode === 'edit') { url = URLS.base + '/' + document.getElementById('k_id').value; fd.append('_method', 'PUT'); }

        $.ajax({
            url: url, method: 'POST', data: fd, processData: false, contentType: false,
            success: function (res) {
                if (res.errors) {
                    Object.keys(res.errors).forEach(k => {
                        const el = document.querySelector('#kulinerForm [data-error="' + k + '"]');
                        if (el) el.textContent = res.errors[k][0];
                    });
                    return;
                }
                formModal().hide();
                table.ajax.reload(null, false);
                Swal.fire({ icon: 'success', title: res.judul || 'Berhasil', text: res.success, timer: 1800, showConfirmButton: false });
            },
            error: function (xhr) {
                const r = xhr.responseJSON || {};
                Swal.fire('Gagal', r.errorMessage || r.error || 'Terjadi kesalahan.', 'error');
            },
            complete: function () { setLoading(false); }
        });
    });

    // Hapus
    $('#kulinerTable').on('click', '.btn-delete', function () {
        const id = $(this).data('id');
        const nama = $(this).data('nama');
        Swal.fire({
            title: 'Hapus kuliner ini?', html: 'Data <b>' + nama + '</b> akan dihapus permanen.',
            icon: 'warning', showCancelButton: true, confirmButtonText: 'Ya, hapus', cancelButtonText: 'Batal',
            confirmButtonColor: '#d33'
        }).then((r) => {
            if (!r.isConfirmed) return;
            $.ajax({
                url: URLS.base + '/' + id, method: 'POST', data: { _method: 'DELETE' },
                success: function (res) {
                    if (res.success) { table.ajax.reload(null, false); Swal.fire({ icon: 'success', title: 'Berhasil', text: res.success, timer: 1600, showConfirmButton: false }); }
                    else Swal.fire('Gagal', res.error || 'Gagal menghapus.', 'error');
                },
                error: function (xhr) { const r = xhr.responseJSON || {}; Swal.fire('Gagal', r.errorMessage || r.error || 'Gagal menghapus.', 'error'); }
            });
        });
    });

    // ===== Ganti Logo Halal (global) =====
    function setLogoLoading(on) {
        document.getElementById('halalLogoBtn').setAttribute('data-kt-indicator', on ? 'on' : 'off');
        document.getElementById('halalLogoBtn').disabled = on;
    }
    $('#halalLogoForm').on('submit', function (e) {
        e.preventDefault();
        document.querySelector('#halalLogoForm [data-error="halal_logo"]').textContent = '';
        const fileInput = document.getElementById('halal_logo_file');
        if (!fileInput.files.length) {
            document.querySelector('#halalLogoForm [data-error="halal_logo"]').textContent = 'Pilih file gambar logo halal.';
            return;
        }
        setLogoLoading(true);
        const fd = new FormData(this);
        $.ajax({
            url: URLS.halalLogo, method: 'POST', data: fd, processData: false, contentType: false,
            success: function (res) {
                if (res.errors) {
                    document.querySelector('#halalLogoForm [data-error="halal_logo"]').textContent = (res.errors.halal_logo || [''])[0];
                    return;
                }
                if (res.url) document.getElementById('halalLogoPreview').src = res.url + '?t=' + new Date().getTime();
                fileInput.value = '';
                Swal.fire({ icon: 'success', title: res.judul || 'Berhasil', text: res.success, timer: 1800, showConfirmButton: false });
            },
            error: function (xhr) {
                const r = xhr.responseJSON || {};
                Swal.fire('Gagal', r.errorMessage || r.error || 'Terjadi kesalahan.', 'error');
            },
            complete: function () { setLogoLoading(false); }
        });
    });
</script>
@endpush

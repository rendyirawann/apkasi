@extends('backend.layout.app')

@section('title', 'Banner Rental')

@push('stylesheets')
    <meta name="csrf-token" content="{{ csrf_token() }}" />
@endpush

@section('content')
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-0">
    <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
        <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
            <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">Banner Rental</h1>
            <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                <li class="breadcrumb-item text-muted"><a href="{{ route('dashboard') }}" class="text-muted text-hover-primary">Home</a></li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted">Data Master</li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-gray-900">Banner Rental</li>
            </ul>
        </div>
    </div>
</div>

<div id="kt_app_content" class="app-content flex-column-fluid">
    <div id="kt_app_content_container" class="app-container container-xxl">
        <div class="card card-flush">
            <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                <div class="card-title">
                    <span class="fs-5 fw-bold">Banner Tab Rental (halaman Panduan)</span>
                    <span class="text-muted fs-8 ms-2">Tampil berurutan di atas peta. Urutkan lewat kolom Urutan.</span>
                </div>
                <div class="card-toolbar">
                    @can('rental_banner.create')
                    <button type="button" class="btn btn-primary btn-sm" id="btnAddBanner">
                        <i class="ki-outline ki-plus fs-3"></i> Tambah Banner
                    </button>
                    @endcan
                </div>
            </div>
            <div class="card-body pt-0">
                <table id="bannerTable" class="table align-middle table-row-dashed fs-6 gy-4 w-100">
                    <thead>
                        <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                            <th class="text-center">No</th>
                            <th class="text-center">Banner</th>
                            <th>Judul</th>
                            <th class="text-center">Urutan</th>
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
<div class="modal fade" id="bannerFormModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-650px">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="fw-bold" id="bannerFormTitle">Tambah Banner</h2>
                <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal"><i class="ki-outline ki-cross fs-1"></i></div>
            </div>
            <form id="bannerForm">
                <div class="modal-body py-6 px-lg-10">
                    <input type="hidden" name="id" id="b_id" />
                    <div class="row g-4">
                        <div class="col-md-8">
                            <label class="fw-semibold fs-7 mb-1">Judul <span class="text-muted">(opsional, untuk catatan internal / alt)</span></label>
                            <input type="text" name="judul" id="b_judul" class="form-control form-control-solid" placeholder="cth: PT Naga Hitam Rentcar" />
                            <div class="text-danger fs-8 mt-1" data-error="judul"></div>
                        </div>
                        <div class="col-md-4">
                            <label class="fw-semibold fs-7 mb-1">Urutan</label>
                            <input type="number" name="urut" id="b_urut" class="form-control form-control-solid" placeholder="0" min="0" />
                            <div class="text-danger fs-8 mt-1" data-error="urut"></div>
                        </div>
                        <div class="col-md-12">
                            <label class="fw-semibold fs-7 mb-1">Gambar Banner <span class="text-muted" id="b_gambar_hint">(wajib, maks 5MB — jpg/png/webp)</span></label>
                            <input type="file" name="gambar_file" id="b_gambar_file" accept=".jpg,.jpeg,.png,.webp" class="form-control form-control-solid" />
                            <div class="text-danger fs-8 mt-1" data-error="gambar_file"></div>
                            <div id="b_gambar_preview" class="mt-2"></div>
                        </div>
                        <div class="col-md-12">
                            <label class="form-check form-switch form-check-custom form-check-solid">
                                <input class="form-check-input" type="checkbox" name="is_active" id="b_is_active" value="1" checked />
                                <span class="form-check-label fw-semibold">Tampilkan di halaman publik</span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" id="bannerSubmitBtn" data-kt-indicator="off">
                        <span class="indicator-label">Simpan</span>
                        <span class="indicator-progress">Menyimpan... <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ===== Modal Detail ===== --}}
<div class="modal fade" id="bannerViewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-600px">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="fw-bold">Detail Banner</h2>
                <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal"><i class="ki-outline ki-cross fs-1"></i></div>
            </div>
            <div class="modal-body py-6 px-lg-10" id="bannerViewBody"></div>
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
        data:  "{{ route('rental-banners.data') }}",
        store: "{{ route('rental-banners.store') }}",
        base:  "{{ url('admin/rental-banners') }}",
    };
    const FIELDS = ['judul','urut'];
    let mode = 'create';

    const table = $('#bannerTable').DataTable({
        dom: "<'row align-items-center'<'col-sm-6 d-flex align-items-center'l><'col-sm-6'>>" +
             "<'table-responsive'tr>" +
             "<'row align-items-center mt-3'<'col-sm-12 col-md-5 text-muted'i><'col-sm-12 col-md-7 d-flex justify-content-md-end'p>>",
        processing: true,
        serverSide: true,
        order: [],
        ajax: { url: URLS.data },
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, className: 'text-center' },
            { data: 'gambar_thumb', name: 'gambar', orderable: false, searchable: false, className: 'text-center' },
            { data: 'judul_col', name: 'judul' },
            { data: 'urut', name: 'urut', className: 'text-center' },
            { data: 'status', name: 'is_active', className: 'text-center' },
            { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-end' },
        ],
        language: {
            lengthMenu: 'Tampilkan _MENU_', info: 'Menampilkan _START_–_END_ dari _TOTAL_',
            infoEmpty: 'Tidak ada data', zeroRecords: 'Data tidak ditemukan',
            paginate: { first: '«', previous: '‹', next: '›', last: '»' }
        }
    });

    function clearErrors() {
        document.querySelectorAll('#bannerForm [data-error]').forEach(el => el.textContent = '');
    }
    function setLoading(on) {
        document.getElementById('bannerSubmitBtn').setAttribute('data-kt-indicator', on ? 'on' : 'off');
        document.getElementById('bannerSubmitBtn').disabled = on;
    }
    const formModal = () => bootstrap.Modal.getOrCreateInstance(document.getElementById('bannerFormModal'));
    const viewModal = () => bootstrap.Modal.getOrCreateInstance(document.getElementById('bannerViewModal'));

    // Tambah
    $('#btnAddBanner').on('click', function () {
        mode = 'create';
        document.getElementById('bannerForm').reset();
        document.getElementById('b_id').value = '';
        document.getElementById('b_is_active').checked = true;
        document.getElementById('b_gambar_preview').innerHTML = '';
        document.getElementById('b_gambar_hint').textContent = '(wajib, maks 5MB — jpg/png/webp)';
        clearErrors();
        document.getElementById('bannerFormTitle').textContent = 'Tambah Banner';
        formModal().show();
    });

    // Edit
    $('#bannerTable').on('click', '.btn-edit', function () {
        const id = $(this).data('id');
        $.get(URLS.base + '/' + id + '/edit', function (res) {
            const d = res.data;
            mode = 'edit';
            clearErrors();
            document.getElementById('b_id').value = d.id;
            FIELDS.forEach(f => { document.getElementById('b_' + f).value = (d[f] ?? ''); });
            document.getElementById('b_is_active').checked = !!d.is_active;
            document.getElementById('b_gambar_file').value = '';
            document.getElementById('b_gambar_hint').textContent = '(opsional — biarkan kosong jika tidak ganti gambar)';
            document.getElementById('b_gambar_preview').innerHTML = res.gambar_url
                ? '<img src="' + res.gambar_url + '" class="rounded mt-1 w-100" style="max-height:150px;object-fit:contain;background:#f5f5f5" /> <div class="text-muted fs-8 mt-1">Biarkan kosong jika tidak ingin mengganti gambar.</div>'
                : '<span class="text-muted fs-8">Belum ada gambar.</span>';
            document.getElementById('bannerFormTitle').textContent = 'Edit Banner';
            formModal().show();
        }).fail(() => Swal.fire('Gagal', 'Tidak dapat memuat data.', 'error'));
    });

    // Detail
    $('#bannerTable').on('click', '.btn-view', function () {
        const id = $(this).data('id');
        $.get(URLS.base + '/' + id, function (res) {
            const d = res.data;
            const row = (l, v) => '<div class="d-flex justify-content-between py-2 border-bottom border-gray-200"><span class="text-muted">' + l + '</span><span class="fw-bold text-end ms-4">' + (v ?? '-') + '</span></div>';
            document.getElementById('bannerViewBody').innerHTML =
                (res.gambar_url ? '<img src="' + res.gambar_url + '" class="rounded w-100 mb-4" style="max-height:220px;object-fit:contain;background:#f5f5f5" />' : '') +
                row('Judul', d.judul ? $('<div>').text(d.judul).html() : '(tanpa judul)') +
                row('Urutan', d.urut) +
                row('Status', d.is_active ? 'Aktif' : 'Nonaktif');
            viewModal().show();
        }).fail(() => Swal.fire('Gagal', 'Tidak dapat memuat data.', 'error'));
    });

    // Submit (create / update)
    $('#bannerForm').on('submit', function (e) {
        e.preventDefault();
        clearErrors();
        setLoading(true);

        const form = this;
        const fd = new FormData(form);
        fd.set('is_active', document.getElementById('b_is_active').checked ? '1' : '0');

        let url = URLS.store;
        if (mode === 'edit') { url = URLS.base + '/' + document.getElementById('b_id').value; fd.append('_method', 'PUT'); }

        $.ajax({
            url: url, method: 'POST', data: fd, processData: false, contentType: false,
            success: function (res) {
                if (res.errors) {
                    Object.keys(res.errors).forEach(k => {
                        const el = document.querySelector('#bannerForm [data-error="' + k + '"]');
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
    $('#bannerTable').on('click', '.btn-delete', function () {
        const id = $(this).data('id');
        const nama = $(this).data('nama');
        Swal.fire({
            title: 'Hapus banner ini?', html: 'Data <b>' + $('<div>').text(nama).html() + '</b> akan dihapus permanen.',
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
</script>
@endpush

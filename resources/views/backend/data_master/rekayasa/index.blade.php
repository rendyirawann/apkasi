@extends('backend.layout.app')

@section('title', 'Rekayasa Lalu Lintas')

@push('stylesheets')
    <meta name="csrf-token" content="{{ csrf_token() }}" />
@endpush

@section('content')
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-0">
    <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
        <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
            <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">Rekayasa Lalu Lintas</h1>
            <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                <li class="breadcrumb-item text-muted"><a href="{{ route('dashboard') }}" class="text-muted text-hover-primary">Home</a></li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted">Data Master</li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-gray-900">Rekayasa Lalu Lintas</li>
            </ul>
        </div>
    </div>
</div>

<div id="kt_app_content" class="app-content flex-column-fluid">
    <div id="kt_app_content_container" class="app-container container-xxl">
        <div class="card card-flush">
            <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                <div class="card-title">
                    <span class="fs-5 fw-bold">Galeri Rekayasa Lalu Lintas (tampil di landing page)</span>
                </div>
                <div class="card-toolbar">
                    <div class="position-relative my-1 me-3">
                        <i class="ki-outline ki-magnifier fs-3 position-absolute top-50 translate-middle-y ms-4"></i>
                        <input type="text" id="rekayasaSearch" class="form-control form-control-solid form-control-sm w-200px w-md-250px ps-11" placeholder="Cari judul..." autocomplete="off" />
                    </div>
                    @can('rekayasa.create')
                    <button type="button" class="btn btn-primary btn-sm" id="btnAddRekayasa">
                        <i class="ki-outline ki-plus fs-3"></i> Tambah Gambar
                    </button>
                    @endcan
                </div>
            </div>
            <div class="card-body pt-0">
                <table id="rekayasaTable" class="table align-middle table-row-dashed fs-6 gy-4 w-100">
                    <thead>
                        <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                            <th class="text-center">No</th>
                            <th class="text-center">Gambar</th>
                            <th>Judul</th>
                            <th class="text-center">Lokasi</th>
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
<div class="modal fade" id="rekayasaFormModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-650px">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="fw-bold" id="rekayasaFormTitle">Tambah Gambar</h2>
                <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal"><i class="ki-outline ki-cross fs-1"></i></div>
            </div>
            <form id="rekayasaForm">
                <div class="modal-body py-6 px-lg-10">
                    <input type="hidden" name="id" id="r_id" />
                    <div class="row g-4">
                        <div class="col-md-12">
                            <label class="required fw-semibold fs-7 mb-1">Judul</label>
                            <input type="text" name="judul" id="r_judul" class="form-control form-control-solid" placeholder="cth: Peta Rekayasa Lalu Lintas & Parkir - Lembar 1" />
                            <div class="text-danger fs-8 mt-1" data-error="judul"></div>
                        </div>
                        <div class="col-md-12">
                            <label class="fw-semibold fs-7 mb-1">Deskripsi <span class="text-muted">(opsional)</span></label>
                            <textarea name="deskripsi" id="r_deskripsi" rows="3" class="form-control form-control-solid" placeholder="Keterangan singkat peta/rekayasa..."></textarea>
                            <div class="text-danger fs-8 mt-1" data-error="deskripsi"></div>
                        </div>
                        <div class="col-md-12">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <label class="fw-semibold fs-7 mb-0">Lokasi Acara <span class="text-muted">(opsional, boleh lebih dari satu)</span></label>
                                <button type="button" class="btn btn-sm btn-light-primary py-1 px-3" id="btnAddLokasi"><i class="ki-outline ki-plus fs-5"></i> Tambah Lokasi</button>
                            </div>
                            <div id="r_lokasi" class="d-flex flex-column gap-2"></div>
                        </div>
                        <div class="col-md-8">
                            <label class="fw-semibold fs-7 mb-1">Gambar <span class="text-muted" id="r_gambar_hint">(wajib, maks 10MB — jpg/png/webp)</span></label>
                            <input type="file" name="gambar_file" id="r_gambar_file" accept=".jpg,.jpeg,.png,.webp" class="form-control form-control-solid" />
                            <div class="text-danger fs-8 mt-1" data-error="gambar_file"></div>
                        </div>
                        <div class="col-md-4">
                            <label class="fw-semibold fs-7 mb-1">Urutan</label>
                            <input type="number" name="urut" id="r_urut" class="form-control form-control-solid" placeholder="0" min="0" />
                            <div class="text-danger fs-8 mt-1" data-error="urut"></div>
                        </div>
                        <div class="col-md-12">
                            <div id="r_gambar_preview"></div>
                        </div>
                        <div class="col-md-12">
                            <label class="form-check form-switch form-check-custom form-check-solid">
                                <input class="form-check-input" type="checkbox" name="is_active" id="r_is_active" value="1" checked />
                                <span class="form-check-label fw-semibold">Aktif (tampil di halaman publik)</span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" id="rekayasaSubmitBtn" data-kt-indicator="off">
                        <span class="indicator-label">Simpan</span>
                        <span class="indicator-progress">Menyimpan... <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ===== Modal Detail ===== --}}
<div class="modal fade" id="rekayasaViewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-600px">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="fw-bold">Detail Rekayasa Lalu Lintas</h2>
                <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal"><i class="ki-outline ki-cross fs-1"></i></div>
            </div>
            <div class="modal-body py-6 px-lg-10" id="rekayasaViewBody"></div>
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
        data:  "{{ route('rekayasa.data') }}",
        store: "{{ route('rekayasa.store') }}",
        base:  "{{ url('admin/rekayasa') }}",
    };
    const FIELDS = ['judul','deskripsi','urut'];
    let mode = 'create';

    const table = $('#rekayasaTable').DataTable({
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
            { data: 'judul', name: 'judul' },
            { data: 'lokasi_badge', name: 'lokasi', orderable: false, searchable: false, className: 'text-center' },
            { data: 'status', name: 'is_active', className: 'text-center' },
            { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-end' },
        ],
        language: {
            search: 'Cari:', searchPlaceholder: 'judul',
            lengthMenu: 'Tampilkan _MENU_', info: 'Menampilkan _START_–_END_ dari _TOTAL_',
            infoEmpty: 'Tidak ada data', infoFiltered: '(disaring dari _MAX_)', zeroRecords: 'Data tidak ditemukan',
            paginate: { first: '«', previous: '‹', next: '›', last: '»' }
        }
    });

    $('#rekayasaSearch').on('keyup', function () { table.search(this.value).draw(); });

    function clearErrors() {
        document.querySelectorAll('#rekayasaForm [data-error]').forEach(el => el.textContent = '');
    }
    function setLoading(on) {
        document.getElementById('rekayasaSubmitBtn').setAttribute('data-kt-indicator', on ? 'on' : 'off');
        document.getElementById('rekayasaSubmitBtn').disabled = on;
    }
    const formModal = () => bootstrap.Modal.getOrCreateInstance(document.getElementById('rekayasaFormModal'));
    const viewModal = () => bootstrap.Modal.getOrCreateInstance(document.getElementById('rekayasaViewModal'));

    // ===== Repeatable rows: Lokasi acara =====
    function lokasiRow(nama) {
        const w = document.createElement('div');
        w.className = 'input-group input-group-sm';
        w.innerHTML = '<span class="input-group-text"><i class="ki-outline ki-geolocation fs-6"></i></span>' +
            '<input type="text" name="lokasi_nama[]" class="form-control form-control-solid" placeholder="Nama lokasi acara (cth: Graha Bhineka)" value="' + (nama ? String(nama).replace(/"/g, '&quot;') : '') + '" />' +
            '<button type="button" class="btn btn-light-danger btn-rm-lokasi"><i class="ki-outline ki-trash fs-6"></i></button>';
        return w;
    }
    function resetLokasi(items) {
        const c = document.getElementById('r_lokasi'); c.innerHTML = '';
        if (items && items.length) items.forEach(k => c.appendChild(lokasiRow(k.nama)));
    }
    document.getElementById('btnAddLokasi').addEventListener('click', () => document.getElementById('r_lokasi').appendChild(lokasiRow('')));
    document.getElementById('r_lokasi').addEventListener('click', function (e) {
        const b = e.target.closest('.btn-rm-lokasi'); if (b) b.closest('.input-group').remove();
    });

    // Tambah
    $('#btnAddRekayasa').on('click', function () {
        mode = 'create';
        document.getElementById('rekayasaForm').reset();
        document.getElementById('r_id').value = '';
        document.getElementById('r_is_active').checked = true;
        document.getElementById('r_gambar_preview').innerHTML = '';
        document.getElementById('r_gambar_hint').textContent = '(wajib, maks 10MB — jpg/png/webp)';
        resetLokasi([]);
        clearErrors();
        document.getElementById('rekayasaFormTitle').textContent = 'Tambah Gambar';
        formModal().show();
    });

    // Edit
    $('#rekayasaTable').on('click', '.btn-edit', function () {
        const id = $(this).data('id');
        $.get(URLS.base + '/' + id + '/edit', function (res) {
            const d = res.data;
            mode = 'edit';
            clearErrors();
            document.getElementById('r_id').value = d.id;
            FIELDS.forEach(f => { document.getElementById('r_' + f).value = (d[f] ?? ''); });
            document.getElementById('r_is_active').checked = !!d.is_active;
            document.getElementById('r_gambar_file').value = '';
            document.getElementById('r_gambar_hint').textContent = '(opsional — biarkan kosong jika tidak ganti gambar)';
            resetLokasi(res.lokasi || []);
            document.getElementById('r_gambar_preview').innerHTML = res.gambar_url
                ? '<img src="' + res.gambar_url + '" class="rounded mt-1 w-100" style="max-height:200px;object-fit:contain;background:#f5f5f5" /> <div class="text-muted fs-8 mt-1">Biarkan kosong jika tidak ingin mengganti gambar.</div>'
                : '<span class="text-muted fs-8">Belum ada gambar.</span>';
            document.getElementById('rekayasaFormTitle').textContent = 'Edit Gambar';
            formModal().show();
        }).fail(() => Swal.fire('Gagal', 'Tidak dapat memuat data.', 'error'));
    });

    // Detail
    $('#rekayasaTable').on('click', '.btn-view', function () {
        const id = $(this).data('id');
        $.get(URLS.base + '/' + id, function (res) {
            const d = res.data;
            const lokasi = res.lokasi || [];
            const row = (l, v) => '<div class="d-flex justify-content-between py-2 border-bottom border-gray-200"><span class="text-muted">' + l + '</span><span class="fw-bold text-end ms-4">' + (v ?? '-') + '</span></div>';
            const lokasiHtml = lokasi.length
                ? '<div class="py-2"><div class="text-muted mb-2">Lokasi Acara</div>' + lokasi.map(k => '<span class="badge badge-light-primary me-1 mb-1"><i class="ki-outline ki-geolocation fs-7 me-1"></i>' + $('<div>').text(k.nama).html() + '</span>').join('') + '</div>'
                : '';
            document.getElementById('rekayasaViewBody').innerHTML =
                (res.gambar_url ? '<img src="' + res.gambar_url + '" class="rounded w-100 mb-4" style="max-height:260px;object-fit:contain;background:#f5f5f5" />' : '') +
                row('Judul', $('<div>').text(d.judul || '').html()) +
                (d.deskripsi ? '<div class="py-2"><div class="text-muted mb-1">Deskripsi</div><div class="fw-semibold">' + $('<div>').text(d.deskripsi).html() + '</div></div>' : '') +
                lokasiHtml +
                row('Urutan', d.urut) +
                row('Status', d.is_active ? 'Aktif' : 'Nonaktif');
            viewModal().show();
        }).fail(() => Swal.fire('Gagal', 'Tidak dapat memuat data.', 'error'));
    });

    // Submit (create / update)
    $('#rekayasaForm').on('submit', function (e) {
        e.preventDefault();
        clearErrors();
        setLoading(true);

        const form = this;
        const fd = new FormData(form);
        fd.set('is_active', document.getElementById('r_is_active').checked ? '1' : '0');

        let url = URLS.store;
        if (mode === 'edit') { url = URLS.base + '/' + document.getElementById('r_id').value; fd.append('_method', 'PUT'); }

        $.ajax({
            url: url, method: 'POST', data: fd, processData: false, contentType: false,
            success: function (res) {
                if (res.errors) {
                    Object.keys(res.errors).forEach(k => {
                        const key = k.split('.')[0];
                        const el = document.querySelector('#rekayasaForm [data-error="' + key + '"]');
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
    $('#rekayasaTable').on('click', '.btn-delete', function () {
        const id = $(this).data('id');
        const nama = $(this).data('nama');
        Swal.fire({
            title: 'Hapus gambar ini?', html: 'Data <b>' + $('<div>').text(nama).html() + '</b> akan dihapus permanen.',
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

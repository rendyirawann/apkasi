@extends('backend.layout.app')

@section('title', 'Data Rundown Kegiatan')

@push('stylesheets')
    <meta name="csrf-token" content="{{ csrf_token() }}" />
@endpush

@section('content')
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-0">
    <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
        <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
            <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">Rundown Kegiatan</h1>
            <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                <li class="breadcrumb-item text-muted"><a href="{{ route('dashboard') }}" class="text-muted text-hover-primary">Home</a></li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted">Data Master</li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-gray-900">Rundown</li>
            </ul>
        </div>
    </div>
</div>

<div id="kt_app_content" class="app-content flex-column-fluid">
    <div id="kt_app_content_container" class="app-container container-xxl">
        <div class="card card-flush">
            <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                <div class="card-title"><span class="fs-5 fw-bold">Rangkaian Rencana Kegiatan</span></div>
                <div class="card-toolbar">
                    <div class="position-relative my-1 me-3">
                        <i class="ki-outline ki-magnifier fs-3 position-absolute top-50 translate-middle-y ms-4"></i>
                        <input type="text" id="rundownSearch" class="form-control form-control-solid form-control-sm w-200px w-md-250px ps-11" placeholder="Cari tanggal / label..." autocomplete="off" />
                    </div>
                    @can('rundown.create')
                    <button type="button" class="btn btn-primary btn-sm" id="btnAddRd"><i class="ki-outline ki-plus fs-3"></i> Tambah Tanggal</button>
                    @endcan
                </div>
            </div>
            <div class="card-body pt-0">
                <table id="rdTable" class="table align-middle table-row-dashed fs-6 gy-4 w-100">
                    <thead>
                        <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                            <th class="text-center">No</th>
                            <th>Tanggal</th>
                            <th>Label</th>
                            <th>Jumlah</th>
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
<div class="modal fade" id="rdFormModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-800px">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="fw-bold" id="rdFormTitle">Tambah Tanggal Rundown</h2>
                <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal"><i class="ki-outline ki-cross fs-1"></i></div>
            </div>
            <form id="rdForm">
                <div class="modal-body py-6 px-lg-10 mh-650px scroll-y">
                    <input type="hidden" name="id" id="rd_id" />
                    <div class="row g-4">
                        <div class="col-md-5">
                            <label class="required fw-semibold fs-7 mb-1">Tanggal</label>
                            <input type="date" name="tanggal" id="rd_tanggal" class="form-control form-control-solid" />
                            <div class="text-danger fs-8 mt-1" data-error="tanggal"></div>
                        </div>
                        <div class="col-md-4">
                            <label class="fw-semibold fs-7 mb-1">Label <span class="text-muted">(opsional)</span></label>
                            <input type="text" name="label" id="rd_label" class="form-control form-control-solid" placeholder="cth: Hari 1" />
                        </div>
                        <div class="col-md-3">
                            <label class="fw-semibold fs-7 mb-1">Urutan</label>
                            <input type="number" name="urut" id="rd_urut" class="form-control form-control-solid" placeholder="0" min="0" />
                        </div>

                        {{-- Kegiatan (child) --}}
                        <div class="col-md-12">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <label class="fw-semibold fs-7 mb-0">Daftar Kegiatan (waktu · kegiatan · lokasi · rincian)</label>
                                <button type="button" class="btn btn-sm btn-light-primary py-1 px-3" id="btnAddKeg"><i class="ki-outline ki-plus fs-5"></i> Tambah Kegiatan</button>
                            </div>
                            <div id="rd_keg" class="d-flex flex-column gap-3"></div>
                            <div class="text-danger fs-8 mt-1" data-error="keg_kegiatan.0"></div>
                        </div>

                        <div class="col-md-12">
                            <label class="form-check form-switch form-check-custom form-check-solid">
                                <input class="form-check-input" type="checkbox" name="is_active" id="rd_is_active" value="1" checked />
                                <span class="form-check-label fw-semibold">Aktif (tampil di halaman publik)</span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" id="rdSubmitBtn" data-kt-indicator="off">
                        <span class="indicator-label">Simpan</span>
                        <span class="indicator-progress">Menyimpan... <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ===== Modal Detail ===== --}}
<div class="modal fade" id="rdViewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-700px">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="fw-bold">Detail Rundown</h2>
                <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal"><i class="ki-outline ki-cross fs-1"></i></div>
            </div>
            <div class="modal-body py-6 px-lg-10" id="rdViewBody"></div>
            <div class="modal-footer"><button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button></div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });
    const URLS = { data: "{{ route('rundown.data') }}", store: "{{ route('rundown.store') }}", base: "{{ url('admin/rundown') }}" };
    let mode = 'create';
    function esc(s) { return (s === null || s === undefined ? '' : String(s)).replace(/"/g, '&quot;'); }

    const table = $('#rdTable').DataTable({
        dom: "<'row align-items-center'<'col-sm-6 d-flex align-items-center'l><'col-sm-6'>>" +
             "<'table-responsive'tr>" +
             "<'row align-items-center mt-3'<'col-sm-12 col-md-5 text-muted'i><'col-sm-12 col-md-7 d-flex justify-content-md-end'p>>",
        processing: true, serverSide: true, order: [], ajax: { url: URLS.data },
        columns: [
            { data: 'DT_RowIndex', orderable: false, searchable: false, className: 'text-center' },
            { data: 'tanggal_fmt', name: 'tanggal' },
            { data: 'label_badge', name: 'label' },
            { data: 'jml', orderable: false, searchable: false },
            { data: 'status', name: 'is_active', className: 'text-center' },
            { data: 'action', orderable: false, searchable: false, className: 'text-end' },
        ],
        language: { search: 'Cari:', searchPlaceholder: 'tanggal / label', lengthMenu: 'Tampilkan _MENU_', info: 'Menampilkan _START_–_END_ dari _TOTAL_', infoEmpty: 'Tidak ada data', zeroRecords: 'Data tidak ditemukan', paginate: { previous: '‹', next: '›' } }
    });

    $('#rundownSearch').on('keyup', function () { table.search(this.value).draw(); });

    const formModal = () => bootstrap.Modal.getOrCreateInstance(document.getElementById('rdFormModal'));
    const viewModal = () => bootstrap.Modal.getOrCreateInstance(document.getElementById('rdViewModal'));
    function clearErrors() { document.querySelectorAll('#rdForm [data-error]').forEach(el => el.textContent = ''); }
    function setLoading(on) { document.getElementById('rdSubmitBtn').setAttribute('data-kt-indicator', on ? 'on' : 'off'); document.getElementById('rdSubmitBtn').disabled = on; }

    function kegRow(k) {
        k = k || {};
        const w = document.createElement('div');
        w.className = 'border border-gray-300 rounded position-relative p-3';
        w.innerHTML =
            '<button type="button" class="btn btn-icon btn-sm btn-light-danger position-absolute top-0 end-0 m-1 btn-rm-keg" title="Hapus"><i class="ki-outline ki-cross fs-5"></i></button>' +
            '<div class="row g-2">' +
            '<div class="col-md-4"><input type="text" name="keg_waktu[]" class="form-control form-control-sm form-control-solid" placeholder="Waktu (cth: 08.00 – 09.30)" value="' + esc(k.waktu) + '"></div>' +
            '<div class="col-md-8"><input type="text" name="keg_kegiatan[]" class="form-control form-control-sm form-control-solid" placeholder="Nama kegiatan" value="' + esc(k.kegiatan) + '"></div>' +
            '<div class="col-md-5"><input type="text" name="keg_lokasi[]" class="form-control form-control-sm form-control-solid" placeholder="Lokasi pelaksanaan" value="' + esc(k.lokasi) + '"></div>' +
            '<div class="col-md-7"><input type="text" name="keg_rincian[]" class="form-control form-control-sm form-control-solid" placeholder="Rincian (opsional)" value="' + esc(k.rincian) + '"></div>' +
            '</div>';
        return w;
    }
    function resetKeg(items) {
        const c = document.getElementById('rd_keg'); c.innerHTML = '';
        if (items && items.length) items.forEach(k => c.appendChild(kegRow(k)));
        else c.appendChild(kegRow({}));
    }
    document.getElementById('btnAddKeg').addEventListener('click', () => document.getElementById('rd_keg').appendChild(kegRow({})));
    document.getElementById('rd_keg').addEventListener('click', function (e) {
        const b = e.target.closest('.btn-rm-keg'); if (b) b.closest('.border').remove();
    });

    $('#btnAddRd').on('click', function () {
        mode = 'create';
        document.getElementById('rdForm').reset();
        document.getElementById('rd_id').value = '';
        document.getElementById('rd_is_active').checked = true;
        resetKeg([]);
        clearErrors();
        document.getElementById('rdFormTitle').textContent = 'Tambah Tanggal Rundown';
        formModal().show();
    });

    $('#rdTable').on('click', '.btn-edit', function () {
        const id = $(this).data('id');
        $.get(URLS.base + '/' + id + '/edit', function (res) {
            const d = res.data; mode = 'edit'; clearErrors();
            document.getElementById('rd_id').value = d.id;
            document.getElementById('rd_tanggal').value = d.tanggal_input ?? '';
            document.getElementById('rd_label').value = d.label ?? '';
            document.getElementById('rd_urut').value = d.urut ?? '';
            document.getElementById('rd_is_active').checked = !!d.is_active;
            resetKeg(res.kegiatan || []);
            document.getElementById('rdFormTitle').textContent = 'Edit Rundown';
            formModal().show();
        }).fail(() => Swal.fire('Gagal', 'Tidak dapat memuat data.', 'error'));
    });

    $('#rdTable').on('click', '.btn-view', function () {
        const id = $(this).data('id');
        $.get(URLS.base + '/' + id, function (res) {
            const d = res.data;
            let rows = (res.kegiatan || []).map(k =>
                '<tr><td class="py-2 pe-3 text-muted text-nowrap align-top">' + (k.waktu || '-') + '</td>' +
                '<td class="py-2 align-top"><div class="fw-bold text-gray-800">' + k.kegiatan + '</div>' +
                (k.lokasi ? '<div class="text-muted fs-8"><i class="ki-outline ki-geolocation fs-8"></i> ' + k.lokasi + '</div>' : '') +
                (k.rincian ? '<div class="text-gray-600 fs-8 mt-1">' + k.rincian + '</div>' : '') + '</td></tr>').join('');
            document.getElementById('rdViewBody').innerHTML =
                '<div class="mb-3"><div class="fs-4 fw-bold text-gray-900">' + (d.tanggal_format || '') + '</div>' + (d.label ? '<span class="badge badge-light-primary">' + d.label + '</span>' : '') + '</div>' +
                (rows ? '<table class="table table-row-bordered align-middle">' + rows + '</table>' : '<span class="text-muted">Belum ada kegiatan.</span>');
            viewModal().show();
        }).fail(() => Swal.fire('Gagal', 'Tidak dapat memuat data.', 'error'));
    });

    $('#rdForm').on('submit', function (e) {
        e.preventDefault(); clearErrors(); setLoading(true);
        const fd = new FormData(this);
        fd.set('is_active', document.getElementById('rd_is_active').checked ? '1' : '0');
        let url = URLS.store;
        if (mode === 'edit') { url = URLS.base + '/' + document.getElementById('rd_id').value; fd.append('_method', 'PUT'); }
        $.ajax({
            url: url, method: 'POST', data: fd, processData: false, contentType: false,
            success: function (res) {
                if (res.errors) {
                    Object.keys(res.errors).forEach(k => { const el = document.querySelector('#rdForm [data-error="' + k + '"]'); if (el) el.textContent = res.errors[k][0]; });
                    return;
                }
                formModal().hide(); table.ajax.reload(null, false);
                Swal.fire({ icon: 'success', title: res.judul || 'Berhasil', text: res.success, timer: 1800, showConfirmButton: false });
            },
            error: function (xhr) { const r = xhr.responseJSON || {}; Swal.fire('Gagal', r.errorMessage || r.error || 'Terjadi kesalahan.', 'error'); },
            complete: function () { setLoading(false); }
        });
    });

    $('#rdTable').on('click', '.btn-delete', function () {
        const id = $(this).data('id'); const nama = $(this).data('nama');
        Swal.fire({ title: 'Hapus rundown ini?', html: 'Data <b>' + nama + '</b> (beserta seluruh kegiatannya) akan dihapus permanen.', icon: 'warning', showCancelButton: true, confirmButtonText: 'Ya, hapus', cancelButtonText: 'Batal', confirmButtonColor: '#d33' })
            .then((r) => {
                if (!r.isConfirmed) return;
                $.ajax({
                    url: URLS.base + '/' + id, method: 'POST', data: { _method: 'DELETE' },
                    success: function (res) { if (res.success) { table.ajax.reload(null, false); Swal.fire({ icon: 'success', title: 'Berhasil', text: res.success, timer: 1600, showConfirmButton: false }); } else Swal.fire('Gagal', res.error || 'Gagal menghapus.', 'error'); },
                    error: function (xhr) { const r = xhr.responseJSON || {}; Swal.fire('Gagal', r.errorMessage || r.error || 'Gagal menghapus.', 'error'); }
                });
            });
    });
</script>
@endpush

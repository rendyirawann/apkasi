@extends('backend.layout.app')

@section('title', 'Data PIC')

@push('stylesheets')
    <meta name="csrf-token" content="{{ csrf_token() }}" />
@endpush

@section('content')
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-0">
    <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
        <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
            <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">Data PIC</h1>
            <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                <li class="breadcrumb-item text-muted"><a href="{{ route('dashboard') }}" class="text-muted text-hover-primary">Home</a></li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted">Data Master</li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-gray-900">PIC</li>
            </ul>
        </div>
    </div>
</div>

<div id="kt_app_content" class="app-content flex-column-fluid">
    <div id="kt_app_content_container" class="app-container container-xxl">
        <div class="card card-flush">
            <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                <div class="card-title">
                    <span class="fs-5 fw-bold">Daftar PIC Kegiatan APKASI per Provinsi</span>
                </div>
                <div class="card-toolbar">
                    @can('pic.create')
                    <button type="button" class="btn btn-primary btn-sm" id="btnAddPic">
                        <i class="ki-outline ki-plus fs-3"></i> Tambah PIC
                    </button>
                    @endcan
                </div>
            </div>
            <div class="card-body pt-0">
                <table id="picTable" class="table align-middle table-row-dashed fs-6 gy-4 w-100">
                    <thead>
                        <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                            <th class="text-center">No</th>
                            <th>Provinsi</th>
                            <th>Nama PIC</th>
                            <th>No HP</th>
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
<div class="modal fade" id="picFormModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-550px">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="fw-bold" id="picFormTitle">Tambah PIC</h2>
                <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal"><i class="ki-outline ki-cross fs-1"></i></div>
            </div>
            <form id="picForm">
                <div class="modal-body py-6 px-lg-10">
                    <input type="hidden" name="id" id="p_id" />
                    <div class="row g-4">
                        <div class="col-md-12">
                            <label class="required fw-semibold fs-7 mb-1">Provinsi</label>
                            <select name="provinsi_id" id="p_provinsi_id" class="form-select form-select-solid" data-control="select2" data-dropdown-parent="#picFormModal">
                                <option value="">— Pilih Provinsi —</option>
                                @foreach ($provinsi as $prov)
                                    <option value="{{ $prov->id }}">{{ $prov->nama }}</option>
                                @endforeach
                            </select>
                            <div class="text-danger fs-8 mt-1" data-error="provinsi_id"></div>
                        </div>
                        <div class="col-md-12">
                            <label class="required fw-semibold fs-7 mb-1">Nama PIC</label>
                            <input type="text" name="nama" id="p_nama" class="form-control form-control-solid" placeholder="Nama lengkap PIC" />
                            <div class="text-danger fs-8 mt-1" data-error="nama"></div>
                        </div>
                        <div class="col-md-8">
                            <label class="fw-semibold fs-7 mb-1">No HP</label>
                            <input type="text" name="no_hp" id="p_no_hp" class="form-control form-control-solid" placeholder="cth: 0812xxxxxxx" />
                            <div class="text-danger fs-8 mt-1" data-error="no_hp"></div>
                        </div>
                        <div class="col-md-4">
                            <label class="fw-semibold fs-7 mb-1">Urutan</label>
                            <input type="number" name="urut" id="p_urut" class="form-control form-control-solid" placeholder="0" min="0" />
                            <div class="text-danger fs-8 mt-1" data-error="urut"></div>
                        </div>
                        <div class="col-md-12">
                            <label class="form-check form-switch form-check-custom form-check-solid">
                                <input class="form-check-input" type="checkbox" name="is_active" id="p_is_active" value="1" checked />
                                <span class="form-check-label fw-semibold">Aktif (tampil di halaman publik)</span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" id="picSubmitBtn" data-kt-indicator="off">
                        <span class="indicator-label">Simpan</span>
                        <span class="indicator-progress">Menyimpan... <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ===== Modal Detail ===== --}}
<div class="modal fade" id="picViewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-500px">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="fw-bold">Detail PIC</h2>
                <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal"><i class="ki-outline ki-cross fs-1"></i></div>
            </div>
            <div class="modal-body py-6 px-lg-10" id="picViewBody"></div>
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
        data:  "{{ route('pics.data') }}",
        store: "{{ route('pics.store') }}",
        base:  "{{ url('admin/pics') }}",
    };
    let mode = 'create';

    const table = $('#picTable').DataTable({
        processing: true,
        serverSide: true,
        order: [],
        ajax: { url: URLS.data },
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, className: 'text-center' },
            { data: 'provinsi', name: 'provinsi' },
            { data: 'nama', name: 'nama' },
            { data: 'no_hp_badge', name: 'no_hp' },
            { data: 'status', name: 'is_active', className: 'text-center' },
            { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-end' },
        ],
        language: {
            search: 'Cari:', searchPlaceholder: 'provinsi / nama',
            lengthMenu: 'Tampilkan _MENU_', info: 'Menampilkan _START_–_END_ dari _TOTAL_',
            infoEmpty: 'Tidak ada data', infoFiltered: '(disaring dari _MAX_)', zeroRecords: 'Data tidak ditemukan',
            paginate: { first: '«', previous: '‹', next: '›', last: '»' }
        }
    });

    function clearErrors() { document.querySelectorAll('#picForm [data-error]').forEach(el => el.textContent = ''); }
    function setLoading(on) {
        document.getElementById('picSubmitBtn').setAttribute('data-kt-indicator', on ? 'on' : 'off');
        document.getElementById('picSubmitBtn').disabled = on;
    }
    const formModal = () => bootstrap.Modal.getOrCreateInstance(document.getElementById('picFormModal'));
    const viewModal = () => bootstrap.Modal.getOrCreateInstance(document.getElementById('picViewModal'));
    function setProvinsi(val) {
        const sel = document.getElementById('p_provinsi_id');
        sel.value = val ?? '';
        if (window.jQuery && $(sel).data('select2')) $(sel).val(val ?? '').trigger('change');
    }

    $('#btnAddPic').on('click', function () {
        mode = 'create';
        document.getElementById('picForm').reset();
        document.getElementById('p_id').value = '';
        setProvinsi('');
        document.getElementById('p_is_active').checked = true;
        clearErrors();
        document.getElementById('picFormTitle').textContent = 'Tambah PIC';
        formModal().show();
    });

    $('#picTable').on('click', '.btn-edit', function () {
        const id = $(this).data('id');
        $.get(URLS.base + '/' + id + '/edit', function (res) {
            const d = res.data;
            mode = 'edit';
            clearErrors();
            document.getElementById('p_id').value = d.id;
            document.getElementById('p_nama').value = d.nama ?? '';
            document.getElementById('p_no_hp').value = d.no_hp ?? '';
            document.getElementById('p_urut').value = d.urut ?? '';
            document.getElementById('p_is_active').checked = !!d.is_active;
            setProvinsi(String(d.provinsi_id ?? ''));
            document.getElementById('picFormTitle').textContent = 'Edit PIC';
            formModal().show();
        }).fail(() => Swal.fire('Gagal', 'Tidak dapat memuat data.', 'error'));
    });

    $('#picTable').on('click', '.btn-view', function () {
        const id = $(this).data('id');
        $.get(URLS.base + '/' + id, function (res) {
            const d = res.data;
            const row = (l, v) => '<div class="d-flex justify-content-between py-2 border-bottom border-gray-200"><span class="text-muted">' + l + '</span><span class="fw-bold text-end ms-4">' + (v ?? '-') + '</span></div>';
            let contact = d.no_hp ? '<div class="pt-3 d-flex gap-2"><a href="https://wa.me/' + String(d.no_hp).replace(/[^0-9]/g, '').replace(/^0/, '62') + '" target="_blank" class="btn btn-sm btn-light-success flex-grow-1"><i class="ki-outline ki-whatsapp fs-5"></i> WhatsApp</a><a href="tel:' + d.no_hp + '" class="btn btn-sm btn-light-primary flex-grow-1"><i class="ki-outline ki-phone fs-5"></i> Telepon</a></div>' : '';
            document.getElementById('picViewBody').innerHTML =
                row('Provinsi', res.provinsi) + row('Nama PIC', d.nama) + row('No HP', d.no_hp) + row('Status', d.is_active ? 'Aktif' : 'Nonaktif') + contact;
            viewModal().show();
        }).fail(() => Swal.fire('Gagal', 'Tidak dapat memuat data.', 'error'));
    });

    $('#picForm').on('submit', function (e) {
        e.preventDefault();
        clearErrors();
        setLoading(true);

        const fd = new FormData(this);
        fd.set('is_active', document.getElementById('p_is_active').checked ? '1' : '0');

        let url = URLS.store;
        if (mode === 'edit') { url = URLS.base + '/' + document.getElementById('p_id').value; fd.append('_method', 'PUT'); }

        $.ajax({
            url: url, method: 'POST', data: fd, processData: false, contentType: false,
            success: function (res) {
                if (res.errors) {
                    Object.keys(res.errors).forEach(k => {
                        const el = document.querySelector('#picForm [data-error="' + k + '"]');
                        if (el) el.textContent = res.errors[k][0];
                    });
                    return;
                }
                formModal().hide();
                table.ajax.reload(null, false);
                Swal.fire({ icon: 'success', title: res.judul || 'Berhasil', text: res.success, timer: 1800, showConfirmButton: false });
            },
            error: function (xhr) { const r = xhr.responseJSON || {}; Swal.fire('Gagal', r.errorMessage || r.error || 'Terjadi kesalahan.', 'error'); },
            complete: function () { setLoading(false); }
        });
    });

    $('#picTable').on('click', '.btn-delete', function () {
        const id = $(this).data('id');
        const nama = $(this).data('nama');
        Swal.fire({
            title: 'Hapus PIC ini?', html: 'Data <b>' + nama + '</b> akan dihapus permanen.',
            icon: 'warning', showCancelButton: true, confirmButtonText: 'Ya, hapus', cancelButtonText: 'Batal', confirmButtonColor: '#d33'
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

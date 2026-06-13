@extends('backend.layout.app')

@section('title', 'Data Rental Mobil')

@push('stylesheets')
    <meta name="csrf-token" content="{{ csrf_token() }}" />
@endpush

@section('content')
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-0">
    <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
        <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
            <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">Data Rental Mobil</h1>
            <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                <li class="breadcrumb-item text-muted"><a href="{{ route('dashboard') }}" class="text-muted text-hover-primary">Home</a></li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted">Data Master</li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-gray-900">Rental Mobil</li>
            </ul>
        </div>
    </div>
</div>

<div id="kt_app_content" class="app-content flex-column-fluid">
    <div id="kt_app_content_container" class="app-container container-xxl">
        <div class="card card-flush">
            <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                <div class="card-title"><span class="fs-5 fw-bold">Daftar Rental Mobil</span></div>
                <div class="card-toolbar">
                    <div class="position-relative my-1 me-3">
                        <i class="ki-outline ki-magnifier fs-3 position-absolute top-50 translate-middle-y ms-4"></i>
                        <input type="text" id="rentalSearch" class="form-control form-control-solid form-control-sm w-200px w-md-250px ps-11" placeholder="Cari rental..." autocomplete="off" />
                    </div>
                    @can('rental.create')
                    <button type="button" class="btn btn-primary btn-sm" id="btnAddRental"><i class="ki-outline ki-plus fs-3"></i> Tambah Rental</button>
                    @endcan
                </div>
            </div>
            <div class="card-body pt-0">
                <table id="rentalTable" class="table align-middle table-row-dashed fs-6 gy-4 w-100">
                    <thead>
                        <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                            <th class="text-center">No</th>
                            <th>Nama Rental</th>
                            <th>Alamat</th>
                            <th>Kontak</th>
                            <th>Armada</th>
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
<div class="modal fade" id="rentalFormModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-700px">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="fw-bold" id="rentalFormTitle">Tambah Rental</h2>
                <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal"><i class="ki-outline ki-cross fs-1"></i></div>
            </div>
            <form id="rentalForm">
                <div class="modal-body py-6 px-lg-10 mh-650px scroll-y">
                    <input type="hidden" name="id" id="r_id" />
                    <div class="row g-4">
                        <div class="col-md-8">
                            <label class="required fw-semibold fs-7 mb-1">Nama Rental</label>
                            <input type="text" name="nama" id="r_nama" class="form-control form-control-solid" placeholder="cth: PT. Seribu Nusantara Rental" />
                            <div class="text-danger fs-8 mt-1" data-error="nama"></div>
                        </div>
                        <div class="col-md-4">
                            <label class="fw-semibold fs-7 mb-1">Urutan</label>
                            <input type="number" name="urut" id="r_urut" class="form-control form-control-solid" placeholder="0" min="0" />
                        </div>
                        <div class="col-md-12">
                            <label class="fw-semibold fs-7 mb-1">Alamat</label>
                            <input type="text" name="alamat" id="r_alamat" class="form-control form-control-solid" placeholder="Alamat lengkap" />
                        </div>
                        <div class="col-md-6">
                            <label class="fw-semibold fs-7 mb-1">Kontak WhatsApp <span class="text-muted">(opsional)</span></label>
                            <input type="text" name="kontak_wa" id="r_kontak_wa" class="form-control form-control-solid" placeholder="cth: 0812xxxxxxx" />
                        </div>
                        <div class="col-md-6">
                            <label class="fw-semibold fs-7 mb-1">Telepon <span class="text-muted">(opsional)</span></label>
                            <input type="text" name="telepon" id="r_telepon" class="form-control form-control-solid" placeholder="cth: 061-xxxxxxx" />
                        </div>
                        <div class="col-md-12">
                            <label class="fw-semibold fs-7 mb-1">Keterangan <span class="text-muted">(opsional)</span></label>
                            <textarea name="deskripsi" id="r_deskripsi" rows="2" class="form-control form-control-solid" placeholder="cth: Melayani drop bandara, sewa harian + driver"></textarea>
                        </div>

                        {{-- Armada mobil (child) --}}
                        <div class="col-md-12">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <label class="fw-semibold fs-7 mb-0">Mobil Tersedia <span class="text-muted">(jumlah unit opsional)</span></label>
                                <button type="button" class="btn btn-sm btn-light-primary py-1 px-3" id="btnAddMobil"><i class="ki-outline ki-plus fs-5"></i> Tambah Mobil</button>
                            </div>
                            <div id="r_mobil" class="d-flex flex-column gap-2"></div>
                            <div class="text-danger fs-8 mt-1" data-error="mobil_nama.0"></div>
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
                    <button type="submit" class="btn btn-primary" id="rentalSubmitBtn" data-kt-indicator="off">
                        <span class="indicator-label">Simpan</span>
                        <span class="indicator-progress">Menyimpan... <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ===== Modal Detail ===== --}}
<div class="modal fade" id="rentalViewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-600px">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="fw-bold">Detail Rental</h2>
                <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal"><i class="ki-outline ki-cross fs-1"></i></div>
            </div>
            <div class="modal-body py-6 px-lg-10" id="rentalViewBody"></div>
            <div class="modal-footer"><button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button></div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });
    const URLS = { data: "{{ route('rentals.data') }}", store: "{{ route('rentals.store') }}", base: "{{ url('admin/rentals') }}" };
    const FIELDS = ['nama', 'urut', 'alamat', 'kontak_wa', 'telepon', 'deskripsi'];
    let mode = 'create';

    const table = $('#rentalTable').DataTable({
        dom: "<'row align-items-center'<'col-sm-6 d-flex align-items-center'l><'col-sm-6'>>" +
             "<'table-responsive'tr>" +
             "<'row align-items-center mt-3'<'col-sm-12 col-md-5 text-muted'i><'col-sm-12 col-md-7 d-flex justify-content-md-end'p>>",
        processing: true, serverSide: true, order: [], ajax: { url: URLS.data },
        columns: [
            { data: 'DT_RowIndex', orderable: false, searchable: false, className: 'text-center' },
            { data: 'nama', name: 'nama' },
            { data: 'alamat', name: 'alamat' },
            { data: 'kontak', name: 'kontak_wa' },
            { data: 'armada', orderable: false, searchable: false },
            { data: 'status', name: 'is_active', className: 'text-center' },
            { data: 'action', orderable: false, searchable: false, className: 'text-end' },
        ],
        language: { search: 'Cari:', searchPlaceholder: 'nama / alamat', lengthMenu: 'Tampilkan _MENU_', info: 'Menampilkan _START_–_END_ dari _TOTAL_', infoEmpty: 'Tidak ada data', zeroRecords: 'Data tidak ditemukan', paginate: { previous: '‹', next: '›' } }
    });

    $('#rentalSearch').on('keyup', function () { table.search(this.value).draw(); });

    const formModal = () => bootstrap.Modal.getOrCreateInstance(document.getElementById('rentalFormModal'));
    const viewModal = () => bootstrap.Modal.getOrCreateInstance(document.getElementById('rentalViewModal'));
    function clearErrors() { document.querySelectorAll('#rentalForm [data-error]').forEach(el => el.textContent = ''); }
    function setLoading(on) { document.getElementById('rentalSubmitBtn').setAttribute('data-kt-indicator', on ? 'on' : 'off'); document.getElementById('rentalSubmitBtn').disabled = on; }

    function mobilRow(n, u) {
        const w = document.createElement('div');
        w.className = 'input-group input-group-sm';
        w.innerHTML = '<input type="text" name="mobil_nama[]" class="form-control form-control-solid" placeholder="Nama mobil (cth: Toyota Innova Reborn)" value="' + (n ? n.replace(/"/g, '&quot;') : '') + '" />' +
            '<input type="number" name="mobil_unit[]" class="form-control form-control-solid" style="max-width:130px" placeholder="Unit (ops.)" min="0" value="' + (u !== undefined && u !== null ? u : '') + '" />' +
            '<button type="button" class="btn btn-light-danger btn-rm-mobil"><i class="ki-outline ki-trash fs-6"></i></button>';
        return w;
    }
    function resetMobil(items) {
        const c = document.getElementById('r_mobil'); c.innerHTML = '';
        if (items && items.length) items.forEach(m => c.appendChild(mobilRow(m.nama_mobil, m.jumlah_unit)));
        else c.appendChild(mobilRow('', ''));
    }
    document.getElementById('btnAddMobil').addEventListener('click', () => document.getElementById('r_mobil').appendChild(mobilRow('', '')));
    document.getElementById('r_mobil').addEventListener('click', function (e) {
        const b = e.target.closest('.btn-rm-mobil'); if (b) b.closest('.input-group').remove();
    });

    $('#btnAddRental').on('click', function () {
        mode = 'create';
        document.getElementById('rentalForm').reset();
        document.getElementById('r_id').value = '';
        document.getElementById('r_is_active').checked = true;
        resetMobil([]);
        clearErrors();
        document.getElementById('rentalFormTitle').textContent = 'Tambah Rental';
        formModal().show();
    });

    $('#rentalTable').on('click', '.btn-edit', function () {
        const id = $(this).data('id');
        $.get(URLS.base + '/' + id + '/edit', function (res) {
            const d = res.data; mode = 'edit'; clearErrors();
            document.getElementById('r_id').value = d.id;
            FIELDS.forEach(f => { document.getElementById('r_' + f).value = (d[f] ?? ''); });
            document.getElementById('r_is_active').checked = !!d.is_active;
            resetMobil(res.mobil || []);
            document.getElementById('rentalFormTitle').textContent = 'Edit Rental';
            formModal().show();
        }).fail(() => Swal.fire('Gagal', 'Tidak dapat memuat data.', 'error'));
    });

    $('#rentalTable').on('click', '.btn-view', function () {
        const id = $(this).data('id');
        $.get(URLS.base + '/' + id, function (res) {
            const d = res.data;
            const row = (l, v) => '<div class="d-flex justify-content-between py-2 border-bottom border-gray-200"><span class="text-muted">' + l + '</span><span class="fw-bold text-end ms-4">' + (v ?? '-') + '</span></div>';
            let total = 0;
            let mobil = (res.mobil || []).map(function (m) { total += parseInt(m.jumlah_unit || 0, 10); var u = (m.jumlah_unit != null && m.jumlah_unit !== '') ? '<span class="badge badge-light-primary">' + m.jumlah_unit + ' unit</span>' : '<span class="badge badge-light-success">tersedia</span>'; return '<div class="d-flex justify-content-between py-1"><span>' + m.nama_mobil + '</span>' + u + '</div>'; }).join('');
            document.getElementById('rentalViewBody').innerHTML =
                row('Nama', d.nama) + row('Alamat', d.alamat) + row('WhatsApp', d.kontak_wa) + row('Telepon', d.telepon) + row('Keterangan', d.deskripsi) +
                '<div class="pt-3"><div class="text-muted mb-2 d-flex justify-content-between">Armada Mobil' + (total > 0 ? ' <span class="fw-bold text-gray-800">Total ' + total + ' unit</span>' : '') + '</div>' + (mobil || '<span class="text-muted">-</span>') + '</div>';
            viewModal().show();
        }).fail(() => Swal.fire('Gagal', 'Tidak dapat memuat data.', 'error'));
    });

    $('#rentalForm').on('submit', function (e) {
        e.preventDefault(); clearErrors(); setLoading(true);
        const fd = new FormData(this);
        fd.set('is_active', document.getElementById('r_is_active').checked ? '1' : '0');
        let url = URLS.store;
        if (mode === 'edit') { url = URLS.base + '/' + document.getElementById('r_id').value; fd.append('_method', 'PUT'); }
        $.ajax({
            url: url, method: 'POST', data: fd, processData: false, contentType: false,
            success: function (res) {
                if (res.errors) {
                    Object.keys(res.errors).forEach(k => { const el = document.querySelector('#rentalForm [data-error="' + k + '"]'); if (el) el.textContent = res.errors[k][0]; });
                    return;
                }
                formModal().hide(); table.ajax.reload(null, false);
                Swal.fire({ icon: 'success', title: res.judul || 'Berhasil', text: res.success, timer: 1800, showConfirmButton: false });
            },
            error: function (xhr) { const r = xhr.responseJSON || {}; Swal.fire('Gagal', r.errorMessage || r.error || 'Terjadi kesalahan.', 'error'); },
            complete: function () { setLoading(false); }
        });
    });

    $('#rentalTable').on('click', '.btn-delete', function () {
        const id = $(this).data('id'); const nama = $(this).data('nama');
        Swal.fire({ title: 'Hapus rental ini?', html: 'Data <b>' + nama + '</b> (beserta daftar mobilnya) akan dihapus permanen.', icon: 'warning', showCancelButton: true, confirmButtonText: 'Ya, hapus', cancelButtonText: 'Batal', confirmButtonColor: '#d33' })
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

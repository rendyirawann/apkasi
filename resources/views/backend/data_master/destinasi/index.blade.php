@extends('backend.layout.app')

@section('title', 'Data Destinasi Wisata')

@push('stylesheets')
    <meta name="csrf-token" content="{{ csrf_token() }}" />
@endpush

@section('content')
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-0">
    <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
        <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
            <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">Data Destinasi Wisata</h1>
            <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                <li class="breadcrumb-item text-muted"><a href="{{ route('dashboard') }}" class="text-muted text-hover-primary">Home</a></li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted">Data Master</li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-gray-900">Destinasi Wisata</li>
            </ul>
        </div>
    </div>
</div>

<div id="kt_app_content" class="app-content flex-column-fluid">
    <div id="kt_app_content_container" class="app-container container-xxl">
        <div class="card card-flush">
            <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                <div class="card-title"><span class="fs-5 fw-bold">Daftar Destinasi Wisata Deli Serdang</span></div>
                <div class="card-toolbar">
                    @can('destinasi.create')
                    <button type="button" class="btn btn-primary btn-sm" id="btnAddDest"><i class="ki-outline ki-plus fs-3"></i> Tambah Destinasi</button>
                    @endcan
                </div>
            </div>
            <div class="card-body pt-0">
                <table id="destTable" class="table align-middle table-row-dashed fs-6 gy-4 w-100">
                    <thead>
                        <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                            <th class="text-center">No</th>
                            <th>Foto</th>
                            <th>Nama Destinasi</th>
                            <th>Alamat</th>
                            <th class="text-center">Rating</th>
                            <th>Tiket</th>
                            <th class="text-center">Lokasi Acara</th>
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
<div class="modal fade" id="destFormModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-750px">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="fw-bold" id="destFormTitle">Tambah Destinasi</h2>
                <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal"><i class="ki-outline ki-cross fs-1"></i></div>
            </div>
            <form id="destForm">
                <div class="modal-body py-6 px-lg-10 mh-650px scroll-y">
                    <input type="hidden" name="id" id="d_id" />
                    <div class="row g-4">
                        <div class="col-md-8">
                            <label class="required fw-semibold fs-7 mb-1">Nama Destinasi</label>
                            <input type="text" name="nama" id="d_nama" class="form-control form-control-solid" placeholder="Nama destinasi" />
                            <div class="text-danger fs-8 mt-1" data-error="nama"></div>
                        </div>
                        <div class="col-md-4">
                            <label class="fw-semibold fs-7 mb-1">Harga Tiket</label>
                            <input type="text" name="harga_tiket" id="d_harga_tiket" class="form-control form-control-solid" placeholder="cth: Rp10.000 / Gratis" />
                            <div class="text-danger fs-8 mt-1" data-error="harga_tiket"></div>
                        </div>
                        <div class="col-md-12">
                            <label class="fw-semibold fs-7 mb-1">Alamat</label>
                            <input type="text" name="alamat" id="d_alamat" class="form-control form-control-solid" placeholder="Alamat lengkap" />
                            <div class="text-danger fs-8 mt-1" data-error="alamat"></div>
                        </div>
                        <div class="col-md-12">
                            <label class="fw-semibold fs-7 mb-1">Deskripsi</label>
                            <textarea name="deskripsi" id="d_deskripsi" rows="2" class="form-control form-control-solid" placeholder="Deskripsi singkat"></textarea>
                            <div class="text-danger fs-8 mt-1" data-error="deskripsi"></div>
                        </div>
                        <div class="col-md-4">
                            <label class="fw-semibold fs-7 mb-1">Rating (0-5)</label>
                            <input type="number" name="rating" id="d_rating" class="form-control form-control-solid" placeholder="cth: 4.3" step="0.1" min="0" max="5" />
                            <div class="text-danger fs-8 mt-1" data-error="rating"></div>
                        </div>
                        <div class="col-md-4">
                            <label class="fw-semibold fs-7 mb-1">Latitude</label>
                            <input type="text" name="lat" id="d_lat" class="form-control form-control-solid" placeholder="cth: 3.229593" />
                            <div class="text-danger fs-8 mt-1" data-error="lat"></div>
                        </div>
                        <div class="col-md-4">
                            <label class="fw-semibold fs-7 mb-1">Longitude</label>
                            <input type="text" name="lng" id="d_lng" class="form-control form-control-solid" placeholder="cth: 98.723565" />
                            <div class="text-danger fs-8 mt-1" data-error="lng"></div>
                        </div>
                        <div class="col-md-8">
                            <label class="fw-semibold fs-7 mb-1">Gambar Thumbnail (URL)</label>
                            <input type="text" name="thumbnail" id="d_thumbnail" class="form-control form-control-solid" placeholder="https://..." />
                            <div class="text-danger fs-8 mt-1" data-error="thumbnail"></div>
                        </div>
                        <div class="col-md-4">
                            <label class="fw-semibold fs-7 mb-1">Urutan</label>
                            <input type="number" name="urut" id="d_urut" class="form-control form-control-solid" placeholder="0" min="0" />
                        </div>
                        <div class="col-md-12">
                            <label class="fw-semibold fs-7 mb-1">Link Google Maps <span class="text-muted">(opsional)</span></label>
                            <input type="text" name="maps_url" id="d_maps_url" class="form-control form-control-solid" placeholder="https://maps.google.com/?q=..." />
                            <div class="text-danger fs-8 mt-1" data-error="maps_url"></div>
                        </div>

                        {{-- Galeri (child) --}}
                        <div class="col-md-12">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <label class="fw-semibold fs-7 mb-0">Galeri Gambar Lain (URL)</label>
                                <button type="button" class="btn btn-sm btn-light-primary py-1 px-3" id="btnAddGallery"><i class="ki-outline ki-plus fs-5"></i> Tambah Gambar</button>
                            </div>
                            <div id="d_gallery" class="d-flex flex-column gap-2"></div>
                            <div class="text-danger fs-8 mt-1" data-error="gambar.0"></div>
                        </div>

                        <div class="col-md-12">
                            <label class="form-check form-switch form-check-custom form-check-solid">
                                <input class="form-check-input" type="checkbox" name="is_lokasi_acara" id="d_is_lokasi_acara" value="1" />
                                <span class="form-check-label fw-semibold">Tandai sebagai <b>Lokasi Acara</b> (ikut tampil di tab Lokasi Acara pada peta)</span>
                            </label>
                        </div>
                        <div class="col-md-12">
                            <label class="form-check form-switch form-check-custom form-check-solid">
                                <input class="form-check-input" type="checkbox" name="is_active" id="d_is_active" value="1" checked />
                                <span class="form-check-label fw-semibold">Aktif (tampil di halaman publik)</span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" id="destSubmitBtn" data-kt-indicator="off">
                        <span class="indicator-label">Simpan</span>
                        <span class="indicator-progress">Menyimpan... <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ===== Modal Detail ===== --}}
<div class="modal fade" id="destViewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-650px">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="fw-bold">Detail Destinasi</h2>
                <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal"><i class="ki-outline ki-cross fs-1"></i></div>
            </div>
            <div class="modal-body py-6 px-lg-10" id="destViewBody"></div>
            <div class="modal-footer"><button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button></div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });
    const URLS = { data: "{{ route('destinasi.data') }}", store: "{{ route('destinasi.store') }}", base: "{{ url('admin/destinasi') }}" };
    const FIELDS = ['nama', 'harga_tiket', 'alamat', 'deskripsi', 'rating', 'lat', 'lng', 'thumbnail', 'urut', 'maps_url'];
    let mode = 'create';

    const table = $('#destTable').DataTable({
        processing: true, serverSide: true, order: [], ajax: { url: URLS.data },
        columns: [
            { data: 'DT_RowIndex', orderable: false, searchable: false, className: 'text-center' },
            { data: 'thumb', name: 'thumbnail', orderable: false, searchable: false, className: 'text-center' },
            { data: 'nama', name: 'nama' },
            { data: 'alamat', name: 'alamat' },
            { data: 'rating_badge', name: 'rating', className: 'text-center' },
            { data: 'harga', name: 'harga_tiket' },
            { data: 'lokasi_acara', name: 'is_lokasi_acara', className: 'text-center' },
            { data: 'status', name: 'is_active', className: 'text-center' },
            { data: 'action', orderable: false, searchable: false, className: 'text-end' },
        ],
        language: { search: 'Cari:', searchPlaceholder: 'nama / alamat', lengthMenu: 'Tampilkan _MENU_', info: 'Menampilkan _START_–_END_ dari _TOTAL_', infoEmpty: 'Tidak ada data', zeroRecords: 'Data tidak ditemukan', paginate: { previous: '‹', next: '›' } }
    });

    const formModal = () => bootstrap.Modal.getOrCreateInstance(document.getElementById('destFormModal'));
    const viewModal = () => bootstrap.Modal.getOrCreateInstance(document.getElementById('destViewModal'));
    function clearErrors() { document.querySelectorAll('#destForm [data-error]').forEach(el => el.textContent = ''); }
    function setLoading(on) { document.getElementById('destSubmitBtn').setAttribute('data-kt-indicator', on ? 'on' : 'off'); document.getElementById('destSubmitBtn').disabled = on; }

    // Galeri dinamis
    function galleryRow(val) {
        const wrap = document.createElement('div');
        wrap.className = 'input-group input-group-sm';
        wrap.innerHTML = '<input type="text" name="gambar[]" class="form-control form-control-solid" placeholder="https://... (URL gambar)" value="' + (val ? val.replace(/"/g, '&quot;') : '') + '" />' +
            '<button type="button" class="btn btn-light-danger btn-rm-gallery"><i class="ki-outline ki-trash fs-6"></i></button>';
        return wrap;
    }
    function resetGallery(items) {
        const c = document.getElementById('d_gallery'); c.innerHTML = '';
        if (items && items.length) items.forEach(v => c.appendChild(galleryRow(v)));
        else c.appendChild(galleryRow(''));
    }
    document.getElementById('btnAddGallery').addEventListener('click', () => document.getElementById('d_gallery').appendChild(galleryRow('')));
    document.getElementById('d_gallery').addEventListener('click', function (e) {
        const b = e.target.closest('.btn-rm-gallery'); if (b) b.closest('.input-group').remove();
    });

    $('#btnAddDest').on('click', function () {
        mode = 'create';
        document.getElementById('destForm').reset();
        document.getElementById('d_id').value = '';
        document.getElementById('d_is_active').checked = true;
        document.getElementById('d_is_lokasi_acara').checked = false;
        resetGallery([]);
        clearErrors();
        document.getElementById('destFormTitle').textContent = 'Tambah Destinasi';
        formModal().show();
    });

    $('#destTable').on('click', '.btn-edit', function () {
        const id = $(this).data('id');
        $.get(URLS.base + '/' + id + '/edit', function (res) {
            const d = res.data; mode = 'edit'; clearErrors();
            document.getElementById('d_id').value = d.id;
            FIELDS.forEach(f => { document.getElementById('d_' + f).value = (d[f] ?? ''); });
            document.getElementById('d_is_active').checked = !!d.is_active;
            document.getElementById('d_is_lokasi_acara').checked = !!d.is_lokasi_acara;
            resetGallery(res.gambar || []);
            document.getElementById('destFormTitle').textContent = 'Edit Destinasi';
            formModal().show();
        }).fail(() => Swal.fire('Gagal', 'Tidak dapat memuat data.', 'error'));
    });

    $('#destTable').on('click', '.btn-view', function () {
        const id = $(this).data('id');
        $.get(URLS.base + '/' + id, function (res) {
            const d = res.data;
            const row = (l, v) => '<div class="d-flex justify-content-between py-2 border-bottom border-gray-200"><span class="text-muted">' + l + '</span><span class="fw-bold text-end ms-4">' + (v ?? '-') + '</span></div>';
            let gal = (res.gambar || []).map(g => '<img src="' + g + '" class="rounded" style="width:80px;height:60px;object-fit:cover" />').join('');
            document.getElementById('destViewBody').innerHTML =
                (d.thumbnail ? '<img src="' + d.thumbnail + '" class="rounded w-100 mb-4" style="height:180px;object-fit:cover" />' : '') +
                row('Nama', d.nama) + row('Alamat', d.alamat) + row('Deskripsi', d.deskripsi) +
                row('Rating', d.rating ?? '-') + row('Harga Tiket', d.harga_tiket) +
                row('Koordinat', (d.lat && d.lng) ? (d.lat + ', ' + d.lng) : '-') +
                row('Lokasi Acara', d.is_lokasi_acara ? 'Ya' : 'Tidak') + row('Status', d.is_active ? 'Aktif' : 'Nonaktif') +
                (gal ? '<div class="pt-3"><div class="text-muted mb-2">Galeri</div><div class="d-flex flex-wrap gap-2">' + gal + '</div></div>' : '') +
                (d.maps_url ? '<div class="pt-3"><a href="' + d.maps_url + '" target="_blank" class="btn btn-sm btn-light-primary w-100"><i class="ki-outline ki-geolocation fs-5"></i> Buka di Google Maps</a></div>' : '');
            viewModal().show();
        }).fail(() => Swal.fire('Gagal', 'Tidak dapat memuat data.', 'error'));
    });

    $('#destForm').on('submit', function (e) {
        e.preventDefault(); clearErrors(); setLoading(true);
        const fd = new FormData(this);
        fd.set('is_active', document.getElementById('d_is_active').checked ? '1' : '0');
        fd.set('is_lokasi_acara', document.getElementById('d_is_lokasi_acara').checked ? '1' : '0');
        let url = URLS.store;
        if (mode === 'edit') { url = URLS.base + '/' + document.getElementById('d_id').value; fd.append('_method', 'PUT'); }
        $.ajax({
            url: url, method: 'POST', data: fd, processData: false, contentType: false,
            success: function (res) {
                if (res.errors) {
                    Object.keys(res.errors).forEach(k => { const el = document.querySelector('#destForm [data-error="' + k + '"]'); if (el) el.textContent = res.errors[k][0]; });
                    return;
                }
                formModal().hide(); table.ajax.reload(null, false);
                Swal.fire({ icon: 'success', title: res.judul || 'Berhasil', text: res.success, timer: 1800, showConfirmButton: false });
            },
            error: function (xhr) { const r = xhr.responseJSON || {}; Swal.fire('Gagal', r.errorMessage || r.error || 'Terjadi kesalahan.', 'error'); },
            complete: function () { setLoading(false); }
        });
    });

    $('#destTable').on('click', '.btn-delete', function () {
        const id = $(this).data('id'); const nama = $(this).data('nama');
        Swal.fire({ title: 'Hapus destinasi ini?', html: 'Data <b>' + nama + '</b> (beserta galerinya) akan dihapus permanen.', icon: 'warning', showCancelButton: true, confirmButtonText: 'Ya, hapus', cancelButtonText: 'Batal', confirmButtonColor: '#d33' })
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

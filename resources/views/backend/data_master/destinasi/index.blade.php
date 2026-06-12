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
                    <div class="position-relative my-1 me-3">
                        <i class="ki-outline ki-magnifier fs-3 position-absolute top-50 translate-middle-y ms-4"></i>
                        <input type="text" id="destinasiSearch" class="form-control form-control-solid form-control-sm w-200px w-md-250px ps-11" placeholder="Cari destinasi..." autocomplete="off" />
                    </div>
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
                        </div>
                        <div class="col-md-12">
                            <label class="fw-semibold fs-7 mb-1">Alamat</label>
                            <input type="text" name="alamat" id="d_alamat" class="form-control form-control-solid" placeholder="Alamat lengkap" />
                        </div>
                        <div class="col-md-12">
                            <label class="fw-semibold fs-7 mb-1">Deskripsi</label>
                            <textarea name="deskripsi" id="d_deskripsi" rows="2" class="form-control form-control-solid" placeholder="Deskripsi singkat"></textarea>
                        </div>
                        <div class="col-md-4">
                            <label class="fw-semibold fs-7 mb-1">Rating (0-5)</label>
                            <input type="number" name="rating" id="d_rating" class="form-control form-control-solid" placeholder="cth: 4.3" step="0.1" min="0" max="5" />
                            <div class="text-danger fs-8 mt-1" data-error="rating"></div>
                        </div>
                        <div class="col-md-4">
                            <label class="fw-semibold fs-7 mb-1">Latitude</label>
                            <input type="text" name="lat" id="d_lat" class="form-control form-control-solid" placeholder="cth: 3.229593" />
                        </div>
                        <div class="col-md-4">
                            <label class="fw-semibold fs-7 mb-1">Longitude</label>
                            <input type="text" name="lng" id="d_lng" class="form-control form-control-solid" placeholder="cth: 98.723565" />
                        </div>
                        <div class="col-md-8">
                            <label class="fw-semibold fs-7 mb-1">Gambar Thumbnail <span class="text-muted">(maks 3MB)</span></label>
                            <input type="file" name="thumbnail_file" id="d_thumbnail_file" accept=".jpg,.jpeg,.png,.webp" data-max-mb="3" class="form-control form-control-solid" />
                            <div class="text-danger fs-8 mt-1" data-error="thumbnail_file"></div>
                            <div id="d_thumb_preview" class="mt-2"></div>
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

                        {{-- Galeri (child, upload file) --}}
                        <div class="col-md-12">
                            <label class="fw-semibold fs-7 mb-1">Galeri Gambar Lain <span class="text-muted">(bisa pilih beberapa file, maks 3MB/gambar)</span></label>
                            <div id="d_gallery_existing" class="d-flex flex-wrap gap-2 mb-2"></div>
                            <input type="file" name="gambar_files[]" id="d_gambar_files" accept=".jpg,.jpeg,.png,.webp" data-max-mb="3" multiple class="form-control form-control-solid" />
                            <div class="text-danger fs-8 mt-1" data-error="gambar_files.0"></div>
                            <div id="d_hapus_gambar" class="d-none"></div>
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
    const FIELDS = ['nama', 'harga_tiket', 'alamat', 'deskripsi', 'rating', 'lat', 'lng', 'urut', 'maps_url'];
    let mode = 'create';

    const table = $('#destTable').DataTable({
        dom: "<'row align-items-center'<'col-sm-6 d-flex align-items-center'l><'col-sm-6'>>" +
             "<'table-responsive'tr>" +
             "<'row align-items-center mt-3'<'col-sm-12 col-md-5 text-muted'i><'col-sm-12 col-md-7 d-flex justify-content-md-end'p>>",
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

    $('#destinasiSearch').on('keyup', function () { table.search(this.value).draw(); });

    const formModal = () => bootstrap.Modal.getOrCreateInstance(document.getElementById('destFormModal'));
    const viewModal = () => bootstrap.Modal.getOrCreateInstance(document.getElementById('destViewModal'));
    function clearErrors() { document.querySelectorAll('#destForm [data-error]').forEach(el => el.textContent = ''); }
    function setLoading(on) { document.getElementById('destSubmitBtn').setAttribute('data-kt-indicator', on ? 'on' : 'off'); document.getElementById('destSubmitBtn').disabled = on; }

    function resetMedia() {
        document.getElementById('d_thumbnail_file').value = '';
        document.getElementById('d_thumb_preview').innerHTML = '';
        document.getElementById('d_gambar_files').value = '';
        document.getElementById('d_gallery_existing').innerHTML = '';
        document.getElementById('d_hapus_gambar').innerHTML = '';
    }
    function renderExistingGallery(items) {
        const c = document.getElementById('d_gallery_existing'); c.innerHTML = '';
        (items || []).forEach(function (it) {
            const div = document.createElement('div');
            div.className = 'position-relative';
            div.innerHTML = '<img src="' + it.url + '" class="rounded" style="width:84px;height:64px;object-fit:cover" />' +
                '<button type="button" class="btn btn-icon btn-danger position-absolute top-0 end-0 rounded-circle btn-rm-existing" data-id="' + it.id + '" style="transform:translate(35%,-35%);width:22px;height:22px"><i class="ki-outline ki-cross fs-7"></i></button>';
            c.appendChild(div);
        });
    }
    document.getElementById('d_gallery_existing').addEventListener('click', function (e) {
        const b = e.target.closest('.btn-rm-existing'); if (!b) return;
        const h = document.createElement('input'); h.type = 'hidden'; h.name = 'hapus_gambar[]'; h.value = b.dataset.id;
        document.getElementById('d_hapus_gambar').appendChild(h);
        b.closest('.position-relative').remove();
    });

    $('#btnAddDest').on('click', function () {
        mode = 'create';
        document.getElementById('destForm').reset();
        document.getElementById('d_id').value = '';
        document.getElementById('d_is_active').checked = true;
        document.getElementById('d_is_lokasi_acara').checked = false;
        resetMedia();
        clearErrors();
        document.getElementById('destFormTitle').textContent = 'Tambah Destinasi';
        formModal().show();
    });

    $('#destTable').on('click', '.btn-edit', function () {
        const id = $(this).data('id');
        $.get(URLS.base + '/' + id + '/edit', function (res) {
            const d = res.data; mode = 'edit'; clearErrors(); resetMedia();
            document.getElementById('d_id').value = d.id;
            FIELDS.forEach(f => { document.getElementById('d_' + f).value = (d[f] ?? ''); });
            document.getElementById('d_is_active').checked = !!d.is_active;
            document.getElementById('d_is_lokasi_acara').checked = !!d.is_lokasi_acara;
            document.getElementById('d_thumb_preview').innerHTML = d.thumbnail_url ? '<img src="' + d.thumbnail_url + '" class="rounded" style="height:80px" /> <div class="text-muted fs-8 mt-1">Biarkan kosong jika tidak ingin mengganti.</div>' : '<span class="text-muted fs-8">Belum ada thumbnail.</span>';
            renderExistingGallery(res.gambar || []);
            document.getElementById('destFormTitle').textContent = 'Edit Destinasi';
            formModal().show();
        }).fail(() => Swal.fire('Gagal', 'Tidak dapat memuat data.', 'error'));
    });

    $('#destTable').on('click', '.btn-view', function () {
        const id = $(this).data('id');
        $.get(URLS.base + '/' + id, function (res) {
            const d = res.data;
            const row = (l, v) => '<div class="d-flex justify-content-between py-2 border-bottom border-gray-200"><span class="text-muted">' + l + '</span><span class="fw-bold text-end ms-4">' + (v ?? '-') + '</span></div>';
            let gal = (res.gambar || []).map(g => '<img src="' + g.url + '" class="rounded" style="width:84px;height:64px;object-fit:cover" />').join('');
            document.getElementById('destViewBody').innerHTML =
                (d.thumbnail_url ? '<img src="' + d.thumbnail_url + '" class="rounded w-100 mb-4" style="height:180px;object-fit:cover" />' : '') +
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

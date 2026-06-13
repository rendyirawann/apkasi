@extends('backend.layout.app')

@section('title', 'Data Hotel')

@push('stylesheets')
    <meta name="csrf-token" content="{{ csrf_token() }}" />
@endpush

@section('content')
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-0">
    <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
        <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
            <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">Data Hotel</h1>
            <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                <li class="breadcrumb-item text-muted"><a href="{{ route('dashboard') }}" class="text-muted text-hover-primary">Home</a></li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted">Data Master</li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-gray-900">Hotel</li>
            </ul>
        </div>
    </div>
</div>

<div id="kt_app_content" class="app-content flex-column-fluid">
    <div id="kt_app_content_container" class="app-container container-xxl">
        <div class="card card-flush">
            <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                <div class="card-title">
                    <span class="fs-5 fw-bold">Daftar Hotel (Deli Serdang &amp; Kota Medan)</span>
                </div>
                <div class="card-toolbar">
                    <div class="position-relative my-1 me-3">
                        <i class="ki-outline ki-magnifier fs-3 position-absolute top-50 translate-middle-y ms-4"></i>
                        <input type="text" id="hotelSearch" class="form-control form-control-solid form-control-sm w-200px w-md-250px ps-11" placeholder="Cari hotel..." autocomplete="off" />
                    </div>
                    @can('hotel.create')
                    <button type="button" class="btn btn-primary btn-sm" id="btnAddHotel">
                        <i class="ki-outline ki-plus fs-3"></i> Tambah Hotel
                    </button>
                    @endcan
                </div>
            </div>
            <div class="card-body pt-0">
                <table id="hotelTable" class="table align-middle table-row-dashed fs-6 gy-4 w-100">
                    <thead>
                        <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                            <th class="text-center">No</th>
                            <th>Nama Hotel</th>
                            <th>Alamat</th>
                            <th>Kamar</th>
                            <th class="text-center">Rating</th>
                            <th>Kontak</th>
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
<div class="modal fade" id="hotelFormModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-700px">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="fw-bold" id="hotelFormTitle">Tambah Hotel</h2>
                <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal"><i class="ki-outline ki-cross fs-1"></i></div>
            </div>
            <form id="hotelForm">
                <div class="modal-body py-6 px-lg-10">
                    <input type="hidden" name="id" id="h_id" />
                    <div class="row g-4">
                        <div class="col-md-8">
                            <label class="required fw-semibold fs-7 mb-1">Nama Hotel</label>
                            <input type="text" name="nama" id="h_nama" class="form-control form-control-solid" placeholder="Nama hotel" />
                            <div class="text-danger fs-8 mt-1" data-error="nama"></div>
                        </div>
                        <div class="col-md-4">
                            <label class="required fw-semibold fs-7 mb-1">Kategori</label>
                            <select name="kategori" id="h_kategori" class="form-select form-select-solid">
                                <option value="deli_serdang">Kab. Deli Serdang</option>
                                <option value="medan">Kota Medan</option>
                            </select>
                            <div class="text-danger fs-8 mt-1" data-error="kategori"></div>
                        </div>
                        <div class="col-md-12">
                            <label class="fw-semibold fs-7 mb-1">Alamat</label>
                            <input type="text" name="alamat" id="h_alamat" class="form-control form-control-solid" placeholder="Alamat lengkap" />
                            <div class="text-danger fs-8 mt-1" data-error="alamat"></div>
                        </div>
                        <div class="col-md-4">
                            <label class="fw-semibold fs-7 mb-1">Ketersediaan Kamar</label>
                            <input type="number" name="ketersediaan_kamar" id="h_ketersediaan_kamar" class="form-control form-control-solid" placeholder="cth: 79" min="0" />
                            <div class="text-danger fs-8 mt-1" data-error="ketersediaan_kamar"></div>
                        </div>
                        <div class="col-md-4">
                            <label class="fw-semibold fs-7 mb-1">Rating (0-5)</label>
                            <input type="number" name="rating" id="h_rating" class="form-control form-control-solid" placeholder="cth: 4.4" step="0.1" min="0" max="5" />
                            <div class="text-danger fs-8 mt-1" data-error="rating"></div>
                        </div>
                        <div class="col-md-4">
                            <label class="fw-semibold fs-7 mb-1">Urutan</label>
                            <input type="number" name="urut" id="h_urut" class="form-control form-control-solid" placeholder="0" min="0" />
                            <div class="text-danger fs-8 mt-1" data-error="urut"></div>
                        </div>
                        <div class="col-md-6">
                            <label class="fw-semibold fs-7 mb-1">Kontak WhatsApp</label>
                            <input type="text" name="contact_wa" id="h_contact_wa" class="form-control form-control-solid" placeholder="cth: 0812xxxxxxx" />
                            <div class="text-danger fs-8 mt-1" data-error="contact_wa"></div>
                        </div>
                        <div class="col-md-6">
                            <label class="fw-semibold fs-7 mb-1">Email</label>
                            <input type="text" name="contact_email" id="h_contact_email" class="form-control form-control-solid" placeholder="email@hotel.com" />
                            <div class="text-danger fs-8 mt-1" data-error="contact_email"></div>
                        </div>
                        <div class="col-md-6">
                            <label class="fw-semibold fs-7 mb-1">Contact Person <span class="text-muted">(opsional)</span></label>
                            <input type="text" name="contact_person" id="h_contact_person" class="form-control form-control-solid" placeholder="cth: Irwan" />
                            <div class="text-danger fs-8 mt-1" data-error="contact_person"></div>
                        </div>
                        <div class="col-md-6">
                            <label class="fw-semibold fs-7 mb-1">Jarak ke Lokasi Acara <span class="text-muted">(opsional)</span></label>
                            <input type="text" name="jarak" id="h_jarak" class="form-control form-control-solid" placeholder="cth: 11 Km (13 Menit)" />
                            <div class="text-danger fs-8 mt-1" data-error="jarak"></div>
                        </div>
                        <div class="col-md-6">
                            <label class="fw-semibold fs-7 mb-1">Latitude</label>
                            <input type="text" name="lat" id="h_lat" class="form-control form-control-solid" placeholder="cth: 3.599515" />
                            <div class="text-danger fs-8 mt-1" data-error="lat"></div>
                        </div>
                        <div class="col-md-6">
                            <label class="fw-semibold fs-7 mb-1">Longitude</label>
                            <input type="text" name="lng" id="h_lng" class="form-control form-control-solid" placeholder="cth: 98.833088" />
                            <div class="text-danger fs-8 mt-1" data-error="lng"></div>
                        </div>
                        <div class="col-md-12">
                            <label class="fw-semibold fs-7 mb-1">Link Google Maps <span class="text-muted">(opsional)</span></label>
                            <input type="text" name="maps_url" id="h_maps_url" class="form-control form-control-solid" placeholder="https://maps.google.com/?q=..." />
                            <div class="text-danger fs-8 mt-1" data-error="maps_url"></div>
                        </div>
                        <div class="col-md-12">
                            <label class="fw-semibold fs-7 mb-1">Foto Hotel <span class="text-muted">(opsional, maks 10MB — jpg/png/webp)</span></label>
                            <input type="file" name="image_file" id="h_image_file" accept=".jpg,.jpeg,.png,.webp" data-max-mb="10" class="form-control form-control-solid" />
                            <div class="text-danger fs-8 mt-1" data-error="image_file"></div>
                            <div id="h_image_preview" class="mt-2"></div>
                        </div>

                        {{-- Tipe kamar + harga (child berulang) --}}
                        <div class="col-md-12">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <label class="fw-semibold fs-7 mb-0">Tipe Kamar &amp; Harga <span class="text-muted">(per malam)</span></label>
                                <button type="button" class="btn btn-sm btn-light-primary py-1 px-3" id="btnAddKamar"><i class="ki-outline ki-plus fs-5"></i> Tambah Tipe</button>
                            </div>
                            <div id="h_kamar" class="d-flex flex-column gap-2"></div>
                        </div>

                        <div class="col-md-12">
                            <label class="form-check form-switch form-check-custom form-check-solid">
                                <input class="form-check-input" type="checkbox" name="is_lokasi_acara" id="h_is_lokasi_acara" value="1" />
                                <span class="form-check-label fw-semibold">Tandai sebagai <b>Lokasi Acara</b> (ikut tampil di tab Lokasi Acara pada peta)</span>
                            </label>
                        </div>
                        <div class="col-md-12">
                            <label class="form-check form-switch form-check-custom form-check-solid">
                                <input class="form-check-input" type="checkbox" name="is_active" id="h_is_active" value="1" checked />
                                <span class="form-check-label fw-semibold">Aktif (tampil di halaman publik)</span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" id="hotelSubmitBtn" data-kt-indicator="off">
                        <span class="indicator-label">Simpan</span>
                        <span class="indicator-progress">Menyimpan... <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ===== Modal Detail ===== --}}
<div class="modal fade" id="hotelViewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-650px">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="fw-bold">Detail Hotel</h2>
                <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal"><i class="ki-outline ki-cross fs-1"></i></div>
            </div>
            <div class="modal-body py-6 px-lg-10" id="hotelViewBody"></div>
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
        data:  "{{ route('hotels.data') }}",
        store: "{{ route('hotels.store') }}",
        base:  "{{ url('admin/hotels') }}",
    };
    const FIELDS = ['nama','kategori','alamat','ketersediaan_kamar','rating','urut','contact_wa','contact_person','contact_email','jarak','lat','lng','maps_url'];
    let mode = 'create';

    const table = $('#hotelTable').DataTable({
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
            { data: 'kamar', name: 'ketersediaan_kamar', className: 'text-nowrap' },
            { data: 'rating_badge', name: 'rating', className: 'text-center' },
            { data: 'kontak', name: 'contact_wa' },
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

    $('#hotelSearch').on('keyup', function () { table.search(this.value).draw(); });

    function clearErrors() {
        document.querySelectorAll('#hotelForm [data-error]').forEach(el => el.textContent = '');
    }
    function setLoading(on) {
        document.getElementById('hotelSubmitBtn').setAttribute('data-kt-indicator', on ? 'on' : 'off');
        document.getElementById('hotelSubmitBtn').disabled = on;
    }
    const formModal = () => bootstrap.Modal.getOrCreateInstance(document.getElementById('hotelFormModal'));
    const viewModal = () => bootstrap.Modal.getOrCreateInstance(document.getElementById('hotelViewModal'));

    // Tipe kamar + harga (child berulang)
    function kamarRow(t, h) {
        const w = document.createElement('div');
        w.className = 'input-group input-group-sm';
        w.innerHTML = '<input type="text" name="kamar_tipe[]" class="form-control form-control-solid" placeholder="Tipe kamar (cth: Deluxe King)" value="' + (t ? String(t).replace(/"/g, '&quot;') : '') + '" />' +
            '<span class="input-group-text">Rp</span>' +
            '<input type="number" name="kamar_harga[]" class="form-control form-control-solid" style="max-width:150px" placeholder="Harga/malam" min="0" value="' + (h !== undefined && h !== null ? h : '') + '" />' +
            '<button type="button" class="btn btn-light-danger btn-rm-kamar"><i class="ki-outline ki-trash fs-6"></i></button>';
        return w;
    }
    function resetKamar(items) {
        const c = document.getElementById('h_kamar'); c.innerHTML = '';
        if (items && items.length) items.forEach(k => c.appendChild(kamarRow(k.tipe, k.harga)));
        else c.appendChild(kamarRow('', ''));
    }
    document.getElementById('btnAddKamar').addEventListener('click', () => document.getElementById('h_kamar').appendChild(kamarRow('', '')));
    document.getElementById('h_kamar').addEventListener('click', function (e) {
        const b = e.target.closest('.btn-rm-kamar'); if (b) b.closest('.input-group').remove();
    });

    // Tambah
    $('#btnAddHotel').on('click', function () {
        mode = 'create';
        document.getElementById('hotelForm').reset();
        document.getElementById('h_id').value = '';
        document.getElementById('h_is_active').checked = true;
        document.getElementById('h_is_lokasi_acara').checked = false;
        document.getElementById('h_image_preview').innerHTML = '';
        document.getElementById('h_kategori').value = 'deli_serdang';
        resetKamar([]);
        clearErrors();
        document.getElementById('hotelFormTitle').textContent = 'Tambah Hotel';
        formModal().show();
    });

    // Edit
    $('#hotelTable').on('click', '.btn-edit', function () {
        const id = $(this).data('id');
        $.get(URLS.base + '/' + id + '/edit', function (res) {
            const d = res.data;
            mode = 'edit';
            clearErrors();
            document.getElementById('h_id').value = d.id;
            FIELDS.forEach(f => { document.getElementById('h_' + f).value = (d[f] ?? ''); });
            document.getElementById('h_is_active').checked = !!d.is_active;
            document.getElementById('h_is_lokasi_acara').checked = !!d.is_lokasi_acara;
            document.getElementById('h_image_file').value = '';
            document.getElementById('h_image_preview').innerHTML = d.image_url ? '<img src="' + d.image_url + '" class="rounded mt-1" style="height:90px" /> <div class="text-muted fs-8 mt-1">Biarkan kosong jika tidak ingin mengganti foto.</div>' : '<span class="text-muted fs-8">Belum ada foto.</span>';
            resetKamar(res.kamar || []);
            document.getElementById('hotelFormTitle').textContent = 'Edit Hotel';
            formModal().show();
        }).fail(() => Swal.fire('Gagal', 'Tidak dapat memuat data.', 'error'));
    });

    // Detail
    $('#hotelTable').on('click', '.btn-view', function () {
        const id = $(this).data('id');
        $.get(URLS.base + '/' + id, function (res) {
            const d = res.data;
            const row = (l, v) => '<div class="d-flex justify-content-between py-2 border-bottom border-gray-200"><span class="text-muted">' + l + '</span><span class="fw-bold text-end ms-4">' + (v ?? '-') + '</span></div>';
            let kamar = (res.kamar || []).map(function (k) {
                return '<div class="d-flex justify-content-between py-1"><span>' + k.tipe + '</span><span class="fw-bold text-gray-800">' + (k.harga != null ? 'Rp ' + Number(k.harga).toLocaleString('id-ID') : '-') + '</span></div>';
            }).join('');
            document.getElementById('hotelViewBody').innerHTML =
                (d.image_url ? '<img src="' + d.image_url + '" class="rounded w-100 mb-4" style="height:170px;object-fit:cover" />' : '') +
                row('Nama', d.nama) + row('Kategori', d.kategori === 'medan' ? 'Kota Medan' : 'Kab. Deli Serdang') + row('Alamat', d.alamat) +
                row('Contact Person', d.contact_person) + row('WhatsApp', d.contact_wa) + row('Email', d.contact_email) +
                row('Jarak ke Lokasi', d.jarak) +
                row('Ketersediaan Kamar', d.ketersediaan_kamar != null ? d.ketersediaan_kamar + ' kamar' : '-') +
                row('Rating', d.rating ?? '-') +
                row('Koordinat', (d.lat && d.lng) ? (d.lat + ', ' + d.lng) : '-') +
                row('Lokasi Acara', d.is_lokasi_acara ? 'Ya' : 'Tidak') +
                row('Status', d.is_active ? 'Aktif' : 'Nonaktif') +
                (kamar ? '<div class="pt-3"><div class="text-muted mb-2">Tipe Kamar &amp; Harga</div>' + kamar + '</div>' : '') +
                (d.maps_url ? '<div class="pt-3"><a href="' + d.maps_url + '" target="_blank" class="btn btn-sm btn-light-primary w-100"><i class="ki-outline ki-geolocation fs-5"></i> Buka di Google Maps</a></div>' : '');
            viewModal().show();
        }).fail(() => Swal.fire('Gagal', 'Tidak dapat memuat data.', 'error'));
    });

    // Submit (create / update)
    $('#hotelForm').on('submit', function (e) {
        e.preventDefault();
        clearErrors();
        setLoading(true);

        const form = this;
        const fd = new FormData(form);
        fd.set('is_active', document.getElementById('h_is_active').checked ? '1' : '0');
        fd.set('is_lokasi_acara', document.getElementById('h_is_lokasi_acara').checked ? '1' : '0');

        let url = URLS.store;
        if (mode === 'edit') { url = URLS.base + '/' + document.getElementById('h_id').value; fd.append('_method', 'PUT'); }

        $.ajax({
            url: url, method: 'POST', data: fd, processData: false, contentType: false,
            success: function (res) {
                if (res.errors) {
                    Object.keys(res.errors).forEach(k => {
                        const el = document.querySelector('#hotelForm [data-error="' + k + '"]');
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
    $('#hotelTable').on('click', '.btn-delete', function () {
        const id = $(this).data('id');
        const nama = $(this).data('nama');
        Swal.fire({
            title: 'Hapus hotel ini?', html: 'Data <b>' + nama + '</b> akan dihapus permanen.',
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

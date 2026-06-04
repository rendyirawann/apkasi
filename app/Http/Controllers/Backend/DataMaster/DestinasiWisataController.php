<?php

namespace App\Http\Controllers\Backend\DataMaster;

use App\Http\Controllers\Controller;
use App\Models\DestinasiWisata;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class DestinasiWisataController extends Controller
{
    public function index()
    {
        return view('backend.data_master.destinasi.index');
    }

    public function data(Request $request)
    {
        if (! $request->ajax()) {
            abort(404);
        }

        $query = DestinasiWisata::query()->withCount('gambar')->orderBy('urut')->orderBy('id');

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('thumb', function ($row) {
                return $row->thumbnail
                    ? '<img src="' . e($row->thumbnail) . '" class="rounded" style="width:54px;height:42px;object-fit:cover" />'
                    : '<span class="badge badge-light-secondary">—</span>';
            })
            ->addColumn('rating_badge', function ($row) {
                return $row->rating
                    ? '<span class="badge badge-light-warning fw-bold"><i class="ki-outline ki-star fs-7 me-1"></i>' . $row->rating . '</span>'
                    : '<span class="text-muted">-</span>';
            })
            ->addColumn('harga', function ($row) {
                return $row->harga_tiket ? '<span class="fw-bold">' . e($row->harga_tiket) . '</span>' : '<span class="text-muted">-</span>';
            })
            ->addColumn('lokasi_acara', function ($row) {
                return $row->is_lokasi_acara
                    ? '<span class="badge badge-light-success">Ya</span>'
                    : '<span class="badge badge-light-secondary">Tidak</span>';
            })
            ->addColumn('status', function ($row) {
                return $row->is_active
                    ? '<span class="badge badge-light-success">Aktif</span>'
                    : '<span class="badge badge-light-danger">Nonaktif</span>';
            })
            ->addColumn('action', function ($row) {
                $u = auth()->user();
                $btn = '<div class="d-flex justify-content-end flex-shrink-0 gap-1">';
                if ($u->can('destinasi.show')) {
                    $btn .= '<button type="button" class="btn btn-sm btn-icon btn-light-info btn-view" data-id="' . $row->id . '" title="Detail"><i class="ki-outline ki-eye fs-4"></i></button>';
                }
                if ($u->can('destinasi.edit')) {
                    $btn .= '<button type="button" class="btn btn-sm btn-icon btn-light-primary btn-edit" data-id="' . $row->id . '" title="Edit"><i class="ki-outline ki-pencil fs-5"></i></button>';
                }
                if ($u->can('destinasi.delete')) {
                    $btn .= '<button type="button" class="btn btn-sm btn-icon btn-light-danger btn-delete" data-id="' . $row->id . '" data-nama="' . e($row->nama) . '" title="Hapus"><i class="ki-outline ki-trash fs-5"></i></button>';
                }
                return $btn . '</div>';
            })
            ->rawColumns(['thumb', 'rating_badge', 'harga', 'lokasi_acara', 'status', 'action'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), $this->rules(), $this->messages());
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        try {
            $d = DestinasiWisata::create($this->payload($request));
            $this->syncGallery($d, $request);
            return response()->json(['success' => 'Destinasi wisata berhasil ditambahkan.', 'judul' => 'Berhasil'], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Terjadi kesalahan di aplikasi.', 'judul' => 'Gagal', 'errorMessage' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        $d = DestinasiWisata::with('gambar')->findOrFail($id);
        return response()->json(['data' => $d, 'gambar' => $d->gambar->pluck('gambar')]);
    }

    public function edit($id)
    {
        $d = DestinasiWisata::with('gambar')->findOrFail($id);
        return response()->json(['data' => $d, 'gambar' => $d->gambar->pluck('gambar')]);
    }

    public function update(Request $request, $id)
    {
        $d = DestinasiWisata::findOrFail($id);

        $validator = Validator::make($request->all(), $this->rules(), $this->messages());
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        try {
            $d->update($this->payload($request));
            $this->syncGallery($d, $request);
            return response()->json(['success' => 'Destinasi wisata berhasil diperbarui.', 'judul' => 'Berhasil']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Terjadi kesalahan di aplikasi.', 'judul' => 'Gagal', 'errorMessage' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            DestinasiWisata::findOrFail($id)->delete(); // galeri ikut terhapus (cascade)
            return response()->json(['success' => 'Destinasi wisata berhasil dihapus.', 'judul' => 'Berhasil']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Data gagal dihapus.', 'judul' => 'Gagal', 'errorMessage' => $e->getMessage()], 500);
        }
    }

    private function syncGallery(DestinasiWisata $d, Request $request): void
    {
        $d->gambar()->delete();
        $imgs = array_values(array_filter((array) $request->input('gambar', []), fn ($u) => filled($u)));
        foreach ($imgs as $i => $u) {
            $d->gambar()->create(['gambar' => $u, 'urut' => $i + 1]);
        }
    }

    private function rules(): array
    {
        return [
            'nama'            => 'required|string|max:255',
            'alamat'          => 'nullable|string|max:255',
            'deskripsi'       => 'nullable|string|max:1000',
            'rating'          => 'nullable|numeric|min:0|max:5',
            'harga_tiket'     => 'nullable|string|max:100',
            'lat'             => 'nullable|numeric|between:-90,90',
            'lng'             => 'nullable|numeric|between:-180,180',
            'thumbnail'       => 'nullable|url|max:255',
            'maps_url'        => 'nullable|url|max:255',
            'is_lokasi_acara' => 'nullable|boolean',
            'urut'            => 'nullable|integer|min:0',
            'gambar'          => 'nullable|array',
            'gambar.*'        => 'nullable|url|max:255',
        ];
    }

    private function messages(): array
    {
        return [
            'nama.required'  => 'Nama destinasi wajib diisi.',
            'rating.max'     => 'Rating maksimal 5.',
            'thumbnail.url'  => 'Link thumbnail harus berupa URL valid.',
            'maps_url.url'   => 'Link Google Maps harus berupa URL valid.',
            'gambar.*.url'   => 'Setiap link galeri harus berupa URL valid.',
        ];
    }

    private function payload(Request $request): array
    {
        return [
            'nama'            => $request->nama,
            'alamat'          => $request->alamat,
            'deskripsi'       => $request->deskripsi,
            'rating'          => $request->rating !== null && $request->rating !== '' ? (float) $request->rating : null,
            'harga_tiket'     => $request->harga_tiket,
            'lat'             => $request->lat !== null && $request->lat !== '' ? (float) $request->lat : null,
            'lng'             => $request->lng !== null && $request->lng !== '' ? (float) $request->lng : null,
            'thumbnail'       => $request->thumbnail,
            'maps_url'        => $request->maps_url,
            'is_lokasi_acara' => $request->boolean('is_lokasi_acara'),
            'urut'            => (int) ($request->urut ?? 0),
            'is_active'       => $request->boolean('is_active'),
        ];
    }
}

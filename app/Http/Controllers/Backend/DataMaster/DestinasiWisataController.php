<?php

namespace App\Http\Controllers\Backend\DataMaster;

use App\Http\Controllers\Controller;
use App\Models\DestinasiWisata;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
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
                return $row->thumbnail_url
                    ? '<img src="' . e($row->thumbnail_url) . '" class="rounded" style="width:54px;height:42px;object-fit:cover" />'
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
            if ($request->hasFile('thumbnail_file')) {
                $d->thumbnail = $request->file('thumbnail_file')->store('destinasi', 'public');
                $d->save();
            }
            $this->addGalleryFiles($d, $request);
            return response()->json(['success' => 'Destinasi wisata berhasil ditambahkan.', 'judul' => 'Berhasil'], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Terjadi kesalahan di aplikasi.', 'judul' => 'Gagal', 'errorMessage' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        $d = DestinasiWisata::with('gambar')->findOrFail($id);
        return response()->json([
            'data'   => $d,
            'gambar' => $d->gambar->map(fn ($g) => ['id' => $g->id, 'url' => $g->gambar_url]),
        ]);
    }

    public function edit($id)
    {
        $d = DestinasiWisata::with('gambar')->findOrFail($id);
        return response()->json([
            'data'   => $d,
            'gambar' => $d->gambar->map(fn ($g) => ['id' => $g->id, 'url' => $g->gambar_url]),
        ]);
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

            if ($request->hasFile('thumbnail_file')) {
                $this->deleteFile($d->thumbnail);
                $d->thumbnail = $request->file('thumbnail_file')->store('destinasi', 'public');
                $d->save();
            }

            // Hapus galeri yang dipilih
            $hapus = (array) $request->input('hapus_gambar', []);
            if ($hapus) {
                foreach ($d->gambar()->whereIn('id', $hapus)->get() as $g) {
                    $this->deleteFile($g->gambar);
                    $g->delete();
                }
            }

            $this->addGalleryFiles($d, $request);

            return response()->json(['success' => 'Destinasi wisata berhasil diperbarui.', 'judul' => 'Berhasil']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Terjadi kesalahan di aplikasi.', 'judul' => 'Gagal', 'errorMessage' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $d = DestinasiWisata::with('gambar')->findOrFail($id);
            $this->deleteFile($d->thumbnail);
            foreach ($d->gambar as $g) {
                $this->deleteFile($g->gambar);
            }
            $d->delete(); // baris galeri ikut terhapus (cascade)
            return response()->json(['success' => 'Destinasi wisata berhasil dihapus.', 'judul' => 'Berhasil']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Data gagal dihapus.', 'judul' => 'Gagal', 'errorMessage' => $e->getMessage()], 500);
        }
    }

    private function addGalleryFiles(DestinasiWisata $d, Request $request): void
    {
        $files = $request->file('gambar_files', []);
        if (! is_array($files)) {
            $files = [$files];
        }
        $start = (int) $d->gambar()->max('urut');
        foreach (array_values(array_filter($files)) as $i => $file) {
            $d->gambar()->create([
                'gambar' => $file->store('destinasi', 'public'),
                'urut'   => $start + $i + 1,
            ]);
        }
    }

    private function deleteFile(?string $path): void
    {
        if ($path && ! Str::startsWith($path, ['http://', 'https://']) && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
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
            'maps_url'        => 'nullable|url|max:255',
            'is_lokasi_acara' => 'nullable|boolean',
            'urut'            => 'nullable|integer|min:0',
            'thumbnail_file'  => 'nullable|image|mimes:jpg,jpeg,png,webp|max:10240',
            'gambar_files'    => 'nullable|array',
            'gambar_files.*'  => 'nullable|image|mimes:jpg,jpeg,png,webp|max:10240',
            'hapus_gambar'    => 'nullable|array',
        ];
    }

    private function messages(): array
    {
        return [
            'nama.required'        => 'Nama destinasi wajib diisi.',
            'rating.max'           => 'Rating maksimal 5.',
            'maps_url.url'         => 'Link Google Maps harus berupa URL valid.',
            'thumbnail_file.image' => 'Thumbnail harus berupa gambar.',
            'thumbnail_file.mimes' => 'Format thumbnail: jpg, jpeg, png, webp.',
            'thumbnail_file.max'   => 'Ukuran thumbnail maksimal 10 MB.',
            'gambar_files.*.image' => 'Setiap galeri harus berupa gambar.',
            'gambar_files.*.mimes' => 'Format galeri: jpg, jpeg, png, webp.',
            'gambar_files.*.max'   => 'Ukuran tiap gambar galeri maksimal 10 MB.',
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
            'maps_url'        => $request->maps_url,
            'is_lokasi_acara' => $request->boolean('is_lokasi_acara'),
            'urut'            => (int) ($request->urut ?? 0),
            'is_active'       => $request->boolean('is_active'),
        ];
    }
}

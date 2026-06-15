<?php

namespace App\Http\Controllers\Backend\DataMaster;

use App\Http\Controllers\Controller;
use App\Models\Kuliner;
use App\Models\Setting;
use App\Support\HandlesUrut;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class KulinerController extends Controller
{
    use HandlesUrut;

    public function index()
    {
        return view('backend.data_master.kuliner.index', [
            'halalLogoUrl' => \App\Support\Media::url(Setting::get('kuliner_halal_logo') ?: 'logos/logo_halal.png'),
        ]);
    }

    public function data(Request $request)
    {
        if (! $request->ajax()) {
            abort(404);
        }

        $query = Kuliner::query()->orderBy('urut')->orderBy('id');

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('jenis', function ($row) {
                return $row->jenis_kuliner
                    ? '<span class="badge badge-light-primary">' . e($row->jenis_kuliner) . '</span>'
                    : '<span class="text-muted">-</span>';
            })
            ->addColumn('halal_badge', function ($row) {
                return $row->halal
                    ? '<span class="badge badge-light-success"><i class="ki-outline ki-check-circle fs-7 me-1"></i>Halal</span>'
                    : '<span class="text-muted">-</span>';
            })
            ->addColumn('rating_badge', function ($row) {
                return $row->rating
                    ? '<span class="badge badge-light-warning fw-bold"><i class="ki-outline ki-star fs-7 me-1"></i>' . $row->rating . '</span>'
                    : '<span class="text-muted">-</span>';
            })
            ->addColumn('status', function ($row) {
                return $row->is_active
                    ? '<span class="badge badge-light-success">Aktif</span>'
                    : '<span class="badge badge-light-danger">Nonaktif</span>';
            })
            ->addColumn('action', function ($row) {
                $u = auth()->user();
                $btn = '<div class="d-flex justify-content-end flex-shrink-0 gap-1">';
                if ($u->can('kuliner.show')) {
                    $btn .= '<button type="button" class="btn btn-sm btn-icon btn-light-info btn-view" data-id="' . $row->id . '" title="Detail"><i class="ki-outline ki-eye fs-4"></i></button>';
                }
                if ($u->can('kuliner.edit')) {
                    $btn .= '<button type="button" class="btn btn-sm btn-icon btn-light-primary btn-edit" data-id="' . $row->id . '" title="Edit"><i class="ki-outline ki-pencil fs-5"></i></button>';
                }
                if ($u->can('kuliner.delete')) {
                    $btn .= '<button type="button" class="btn btn-sm btn-icon btn-light-danger btn-delete" data-id="' . $row->id . '" data-nama="' . e($row->nama) . '" title="Hapus"><i class="ki-outline ki-trash fs-5"></i></button>';
                }
                return $btn . '</div>';
            })
            ->rawColumns(['jenis', 'halal_badge', 'rating_badge', 'status', 'action'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), $this->rules(), $this->messages());
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        try {
            $payload = $this->payload($request);
            $payload['urut'] = $this->resolveUrut(Kuliner::class, $request);
            $kuliner = Kuliner::create($payload);
            if ($request->hasFile('image_file')) {
                $kuliner->image = $request->file('image_file')->store('kuliner', 'public');
                $kuliner->save();
            }
            return response()->json(['success' => 'Data kuliner berhasil ditambahkan.', 'judul' => 'Berhasil'], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Terjadi kesalahan di aplikasi.', 'judul' => 'Gagal', 'errorMessage' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        return response()->json(['data' => Kuliner::findOrFail($id)]);
    }

    public function edit($id)
    {
        return response()->json(['data' => Kuliner::findOrFail($id)]);
    }

    public function update(Request $request, $id)
    {
        $kuliner = Kuliner::findOrFail($id);

        $validator = Validator::make($request->all(), $this->rules(), $this->messages());
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        try {
            $payload = $this->payload($request);
            $payload['urut'] = $this->resolveUrut(Kuliner::class, $request, $kuliner);
            $kuliner->update($payload);
            if ($request->hasFile('image_file')) {
                $this->deleteFile($kuliner->image);
                $kuliner->image = $request->file('image_file')->store('kuliner', 'public');
                $kuliner->save();
            }
            return response()->json(['success' => 'Data kuliner berhasil diperbarui.', 'judul' => 'Berhasil']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Terjadi kesalahan di aplikasi.', 'judul' => 'Gagal', 'errorMessage' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $kuliner = Kuliner::findOrFail($id);
            $this->deleteFile($kuliner->image);
            $kuliner->delete();
            return response()->json(['success' => 'Data kuliner berhasil dihapus.', 'judul' => 'Berhasil']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Data gagal dihapus.', 'judul' => 'Gagal', 'errorMessage' => $e->getMessage()], 500);
        }
    }

    /**
     * Ganti logo "Halal" global (dipakai semua kuliner berlabel halal di halaman peta).
     * Disimpan ke storage/app/public/kuliner dan dirujuk lewat Setting 'kuliner_halal_logo'.
     */
    public function halalLogo(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'halal_logo' => 'required|image|mimes:jpg,jpeg,png,webp,svg|max:5120',
        ], [
            'halal_logo.required' => 'Pilih file gambar logo halal.',
            'halal_logo.image'    => 'File harus berupa gambar.',
            'halal_logo.mimes'    => 'Format: jpg, jpeg, png, webp, svg.',
            'halal_logo.max'      => 'Ukuran gambar maksimal 5 MB.',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        try {
            // Hapus logo lama bila sebelumnya tersimpan di storage (bukan aset bawaan public/logos).
            $old = Setting::get('kuliner_halal_logo');
            if ($old && ! Str::startsWith($old, ['logos/', 'http://', 'https://'])) {
                $this->deleteFile($old);
            }
            $path = $request->file('halal_logo')->store('kuliner', 'public');
            Setting::set('kuliner_halal_logo', $path);
            return response()->json([
                'success' => 'Logo halal berhasil diganti.',
                'judul'   => 'Berhasil',
                'url'     => \App\Support\Media::url($path),
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Terjadi kesalahan di aplikasi.', 'judul' => 'Gagal', 'errorMessage' => $e->getMessage()], 500);
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
            'nama'          => 'required|string|max:255',
            'jenis_kuliner' => 'nullable|string|max:100',
            'alamat'        => 'nullable|string|max:255',
            'rating'        => 'nullable|numeric|min:0|max:5',
            'lat'           => 'nullable|numeric|between:-90,90',
            'lng'           => 'nullable|numeric|between:-180,180',
            'maps_url'      => 'nullable|url|max:255',
            'image_file'    => 'nullable|image|mimes:jpg,jpeg,png,webp|max:10240',
            'urut'          => 'nullable|integer|min:0',
            'halal'         => 'nullable|boolean',
            'is_active'     => 'nullable|boolean',
        ];
    }

    private function messages(): array
    {
        return [
            'nama.required'    => 'Nama kuliner wajib diisi.',
            'rating.numeric'   => 'Rating harus berupa angka.',
            'rating.max'       => 'Rating maksimal 5.',
            'lat.numeric'      => 'Latitude harus berupa angka.',
            'lng.numeric'      => 'Longitude harus berupa angka.',
            'maps_url.url'     => 'Link Google Maps harus berupa URL valid.',
            'image_file.image' => 'File harus berupa gambar.',
            'image_file.mimes' => 'Format gambar: jpg, jpeg, png, webp.',
            'image_file.max'   => 'Ukuran gambar maksimal 10 MB.',
        ];
    }

    private function payload(Request $request): array
    {
        return [
            'nama'          => $request->nama,
            'jenis_kuliner' => $request->jenis_kuliner ?: null,
            'alamat'        => $request->alamat,
            'rating'        => $request->rating !== null && $request->rating !== '' ? (float) $request->rating : null,
            'lat'           => $request->lat !== null && $request->lat !== '' ? (float) $request->lat : null,
            'lng'           => $request->lng !== null && $request->lng !== '' ? (float) $request->lng : null,
            'maps_url'      => $request->maps_url,
            'urut'          => (int) ($request->urut ?? 0),
            'halal'         => $request->boolean('halal'),
            'is_active'     => $request->boolean('is_active'),
        ];
    }
}

<?php

namespace App\Http\Controllers\Backend\DataMaster;

use App\Http\Controllers\Controller;
use App\Models\Gedung;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class GedungController extends Controller
{
    public function index()
    {
        return view('backend.data_master.gedung.index');
    }

    public function data(Request $request)
    {
        if (! $request->ajax()) {
            abort(404);
        }

        $query = Gedung::query()->orderBy('urut')->orderBy('id');

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('lokasi_acara', function ($row) {
                return $row->is_lokasi_acara
                    ? '<span class="badge badge-light-primary fw-bold"><i class="ki-outline ki-geolocation fs-7 me-1"></i>Lokasi Acara</span>'
                    : '<span class="text-muted">-</span>';
            })
            ->addColumn('koordinat', function ($row) {
                return ($row->lat !== null && $row->lng !== null)
                    ? '<span class="text-muted fs-8">' . $row->lat . ', ' . $row->lng . '</span>'
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
                if ($u->can('gedung.show')) {
                    $btn .= '<button type="button" class="btn btn-sm btn-icon btn-light-info btn-view" data-id="' . $row->id . '" title="Detail"><i class="ki-outline ki-eye fs-4"></i></button>';
                }
                if ($u->can('gedung.edit')) {
                    $btn .= '<button type="button" class="btn btn-sm btn-icon btn-light-primary btn-edit" data-id="' . $row->id . '" title="Edit"><i class="ki-outline ki-pencil fs-5"></i></button>';
                }
                if ($u->can('gedung.delete')) {
                    $btn .= '<button type="button" class="btn btn-sm btn-icon btn-light-danger btn-delete" data-id="' . $row->id . '" data-nama="' . e($row->nama) . '" title="Hapus"><i class="ki-outline ki-trash fs-5"></i></button>';
                }
                return $btn . '</div>';
            })
            ->rawColumns(['lokasi_acara', 'koordinat', 'status', 'action'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), $this->rules(), $this->messages());
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        try {
            $gedung = Gedung::create($this->payload($request));
            if ($request->hasFile('image_file')) {
                $gedung->image = $request->file('image_file')->store('gedung', 'public');
                $gedung->save();
            }
            return response()->json(['success' => 'Data gedung berhasil ditambahkan.', 'judul' => 'Berhasil'], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Terjadi kesalahan di aplikasi.', 'judul' => 'Gagal', 'errorMessage' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        $data = Gedung::findOrFail($id);
        return response()->json(['data' => $data]);
    }

    public function edit($id)
    {
        $data = Gedung::findOrFail($id);
        return response()->json(['data' => $data]);
    }

    public function update(Request $request, $id)
    {
        $gedung = Gedung::findOrFail($id);

        $validator = Validator::make($request->all(), $this->rules(), $this->messages());
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        try {
            $gedung->update($this->payload($request));
            if ($request->hasFile('image_file')) {
                $this->deleteFile($gedung->image);
                $gedung->image = $request->file('image_file')->store('gedung', 'public');
                $gedung->save();
            }
            return response()->json(['success' => 'Data gedung berhasil diperbarui.', 'judul' => 'Berhasil']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Terjadi kesalahan di aplikasi.', 'judul' => 'Gagal', 'errorMessage' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $gedung = Gedung::findOrFail($id);
            $this->deleteFile($gedung->image);
            $gedung->delete();
            return response()->json(['success' => 'Data gedung berhasil dihapus.', 'judul' => 'Berhasil']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Data gagal dihapus.', 'judul' => 'Gagal', 'errorMessage' => $e->getMessage()], 500);
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
            'lat'             => 'nullable|numeric|between:-90,90',
            'lng'             => 'nullable|numeric|between:-180,180',
            'maps_url'        => 'nullable|url|max:255',
            'image_file'      => 'nullable|image|mimes:jpg,jpeg,png,webp|max:3072',
            'urut'            => 'nullable|integer|min:0',
            'is_lokasi_acara' => 'nullable|boolean',
        ];
    }

    private function messages(): array
    {
        return [
            'nama.required'    => 'Nama gedung wajib diisi.',
            'lat.numeric'      => 'Latitude harus berupa angka.',
            'lng.numeric'      => 'Longitude harus berupa angka.',
            'maps_url.url'     => 'Link Google Maps harus berupa URL valid.',
            'image_file.image' => 'File harus berupa gambar.',
            'image_file.mimes' => 'Format gambar: jpg, jpeg, png, webp.',
            'image_file.max'   => 'Ukuran gambar maksimal 3 MB.',
        ];
    }

    private function payload(Request $request): array
    {
        return [
            'nama'            => $request->nama,
            'alamat'          => $request->alamat,
            'lat'             => $request->lat !== null && $request->lat !== '' ? (float) $request->lat : null,
            'lng'             => $request->lng !== null && $request->lng !== '' ? (float) $request->lng : null,
            'maps_url'        => $request->maps_url,
            'urut'            => (int) ($request->urut ?? 0),
            'is_lokasi_acara' => $request->boolean('is_lokasi_acara'),
            'is_active'       => $request->boolean('is_active'),
        ];
    }
}

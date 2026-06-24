<?php

namespace App\Http\Controllers\Backend\DataMaster;

use App\Http\Controllers\Controller;
use App\Models\RekayasaLaluLintas;
use App\Support\HandlesUrut;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class RekayasaController extends Controller
{
    use HandlesUrut;

    public function index()
    {
        return view('backend.data_master.rekayasa.index');
    }

    public function data(Request $request)
    {
        if (! $request->ajax()) {
            abort(404);
        }

        $query = RekayasaLaluLintas::query()->withCount('lokasi')->orderBy('urut')->orderBy('id');

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('gambar_thumb', function ($row) {
                return $row->gambar_url
                    ? '<img src="' . e($row->gambar_url) . '" class="rounded" style="width:64px;height:44px;object-fit:cover" alt="" />'
                    : '<span class="text-muted">-</span>';
            })
            ->addColumn('lokasi_badge', function ($row) {
                return $row->lokasi_count > 0
                    ? '<span class="badge badge-light-primary"><i class="ki-outline ki-geolocation fs-7 me-1"></i>' . $row->lokasi_count . ' lokasi</span>'
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
                if ($u->can('rekayasa.show')) {
                    $btn .= '<button type="button" class="btn btn-sm btn-icon btn-light-info btn-view" data-id="' . $row->id . '" title="Detail"><i class="ki-outline ki-eye fs-4"></i></button>';
                }
                if ($u->can('rekayasa.edit')) {
                    $btn .= '<button type="button" class="btn btn-sm btn-icon btn-light-primary btn-edit" data-id="' . $row->id . '" title="Edit"><i class="ki-outline ki-pencil fs-5"></i></button>';
                }
                if ($u->can('rekayasa.delete')) {
                    $btn .= '<button type="button" class="btn btn-sm btn-icon btn-light-danger btn-delete" data-id="' . $row->id . '" data-nama="' . e($row->judul) . '" title="Hapus"><i class="ki-outline ki-trash fs-5"></i></button>';
                }
                return $btn . '</div>';
            })
            ->rawColumns(['gambar_thumb', 'lokasi_badge', 'status', 'action'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), $this->rules(true), $this->messages());
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        try {
            $payload = $this->payload($request);
            $payload['urut'] = $this->resolveUrut(RekayasaLaluLintas::class, $request);
            $rekayasa = RekayasaLaluLintas::create($payload);
            if ($request->hasFile('gambar_file')) {
                $rekayasa->gambar = $request->file('gambar_file')->store('rekayasa', 'public');
                $rekayasa->save();
            }
            $this->syncLokasi($rekayasa, $request);
            return response()->json(['success' => 'Data rekayasa lalu lintas berhasil ditambahkan.', 'judul' => 'Berhasil'], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Terjadi kesalahan di aplikasi.', 'judul' => 'Gagal', 'errorMessage' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        $r = RekayasaLaluLintas::with('lokasi')->findOrFail($id);
        return response()->json(['data' => $r, 'lokasi' => $r->lokasi, 'gambar_url' => $r->gambar_url]);
    }

    public function edit($id)
    {
        $r = RekayasaLaluLintas::with('lokasi')->findOrFail($id);
        return response()->json(['data' => $r, 'lokasi' => $r->lokasi, 'gambar_url' => $r->gambar_url]);
    }

    public function update(Request $request, $id)
    {
        $rekayasa = RekayasaLaluLintas::findOrFail($id);

        $validator = Validator::make($request->all(), $this->rules(false), $this->messages());
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        try {
            $payload = $this->payload($request);
            $payload['urut'] = $this->resolveUrut(RekayasaLaluLintas::class, $request, $rekayasa);
            $rekayasa->update($payload);
            if ($request->hasFile('gambar_file')) {
                $this->deleteFile($rekayasa->gambar);
                $rekayasa->gambar = $request->file('gambar_file')->store('rekayasa', 'public');
                $rekayasa->save();
            }
            $this->syncLokasi($rekayasa, $request);
            return response()->json(['success' => 'Data rekayasa lalu lintas berhasil diperbarui.', 'judul' => 'Berhasil']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Terjadi kesalahan di aplikasi.', 'judul' => 'Gagal', 'errorMessage' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $rekayasa = RekayasaLaluLintas::findOrFail($id);
            $this->deleteFile($rekayasa->gambar);
            $rekayasa->delete(); // lokasi ikut terhapus (cascade)
            return response()->json(['success' => 'Data rekayasa lalu lintas berhasil dihapus.', 'judul' => 'Berhasil']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Data gagal dihapus.', 'judul' => 'Gagal', 'errorMessage' => $e->getMessage()], 500);
        }
    }

    private function syncLokasi(RekayasaLaluLintas $rekayasa, Request $request): void
    {
        $rekayasa->lokasi()->delete();
        $namas = (array) $request->input('lokasi_nama', []);
        $i = 0;
        foreach ($namas as $nama) {
            if (! filled($nama)) continue;
            $rekayasa->lokasi()->create(['nama' => $nama, 'urut' => ++$i]);
        }
    }

    private function deleteFile(?string $path): void
    {
        // Jangan hapus aset publik bawaan (rekayasa/.. di public, http) - hanya hasil upload di storage disk.
        if ($path && ! Str::startsWith($path, ['http://', 'https://'])
            && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }

    private function rules(bool $creating): array
    {
        return [
            'judul'        => 'required|string|max:255',
            'deskripsi'    => 'nullable|string|max:2000',
            'gambar_file'  => ($creating ? 'required' : 'nullable') . '|image|mimes:jpg,jpeg,png,webp|max:10240',
            'urut'         => 'nullable|integer|min:0',
            'is_active'    => 'nullable|boolean',
            'lokasi_nama'   => 'nullable|array',
            'lokasi_nama.*' => 'nullable|string|max:255',
        ];
    }

    private function messages(): array
    {
        return [
            'judul.required'    => 'Judul wajib diisi.',
            'gambar_file.required' => 'Gambar wajib diunggah.',
            'gambar_file.image' => 'File harus berupa gambar.',
            'gambar_file.mimes' => 'Format gambar: jpg, jpeg, png, webp.',
            'gambar_file.max'   => 'Ukuran gambar maksimal 10 MB.',
        ];
    }

    private function payload(Request $request): array
    {
        return [
            'judul'     => $request->judul,
            'deskripsi' => $request->deskripsi ?: null,
            'urut'      => (int) ($request->urut ?? 0),
            'is_active' => $request->boolean('is_active'),
        ];
    }
}

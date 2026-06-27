<?php

namespace App\Http\Controllers\Backend\DataMaster;

use App\Http\Controllers\Controller;
use App\Models\RentalBanner;
use App\Support\HandlesUrut;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class RentalBannerController extends Controller
{
    use HandlesUrut;

    public function index()
    {
        return view('backend.data_master.rental_banner.index');
    }

    public function data(Request $request)
    {
        if (! $request->ajax()) {
            abort(404);
        }

        $query = RentalBanner::query()->orderBy('urut')->orderBy('id');

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('gambar_thumb', function ($row) {
                return $row->gambar_url
                    ? '<img src="' . e($row->gambar_url) . '" class="rounded" style="width:120px;height:46px;object-fit:cover" alt="" />'
                    : '<span class="text-muted">-</span>';
            })
            ->addColumn('judul_col', function ($row) {
                return $row->judul ? e($row->judul) : '<span class="text-muted">(tanpa judul)</span>';
            })
            ->addColumn('status', function ($row) {
                return $row->is_active
                    ? '<span class="badge badge-light-success">Aktif</span>'
                    : '<span class="badge badge-light-danger">Nonaktif</span>';
            })
            ->addColumn('action', function ($row) {
                $u = auth()->user();
                $btn = '<div class="d-flex justify-content-end flex-shrink-0 gap-1">';
                if ($u->can('rental_banner.show')) {
                    $btn .= '<button type="button" class="btn btn-sm btn-icon btn-light-info btn-view" data-id="' . $row->id . '" title="Detail"><i class="ki-outline ki-eye fs-4"></i></button>';
                }
                if ($u->can('rental_banner.edit')) {
                    $btn .= '<button type="button" class="btn btn-sm btn-icon btn-light-primary btn-edit" data-id="' . $row->id . '" title="Edit"><i class="ki-outline ki-pencil fs-5"></i></button>';
                }
                if ($u->can('rental_banner.delete')) {
                    $btn .= '<button type="button" class="btn btn-sm btn-icon btn-light-danger btn-delete" data-id="' . $row->id . '" data-nama="' . e($row->judul ?: ('Banner #' . $row->id)) . '" title="Hapus"><i class="ki-outline ki-trash fs-5"></i></button>';
                }
                return $btn . '</div>';
            })
            ->rawColumns(['gambar_thumb', 'judul_col', 'status', 'action'])
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
            $payload['urut'] = $this->resolveUrut(RentalBanner::class, $request);
            $banner = RentalBanner::create($payload);
            if ($request->hasFile('gambar_file')) {
                $banner->gambar = $request->file('gambar_file')->store('rental_banner', 'public');
                $banner->save();
            }
            return response()->json(['success' => 'Banner rental berhasil ditambahkan.', 'judul' => 'Berhasil'], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Terjadi kesalahan di aplikasi.', 'judul' => 'Gagal', 'errorMessage' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        $b = RentalBanner::findOrFail($id);
        return response()->json(['data' => $b, 'gambar_url' => $b->gambar_url]);
    }

    public function edit($id)
    {
        $b = RentalBanner::findOrFail($id);
        return response()->json(['data' => $b, 'gambar_url' => $b->gambar_url]);
    }

    public function update(Request $request, $id)
    {
        $banner = RentalBanner::findOrFail($id);

        $validator = Validator::make($request->all(), $this->rules(false), $this->messages());
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        try {
            $payload = $this->payload($request);
            $payload['urut'] = $this->resolveUrut(RentalBanner::class, $request, $banner);
            $banner->update($payload);
            if ($request->hasFile('gambar_file')) {
                $this->deleteFile($banner->gambar);
                $banner->gambar = $request->file('gambar_file')->store('rental_banner', 'public');
                $banner->save();
            }
            return response()->json(['success' => 'Banner rental berhasil diperbarui.', 'judul' => 'Berhasil']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Terjadi kesalahan di aplikasi.', 'judul' => 'Gagal', 'errorMessage' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $banner = RentalBanner::findOrFail($id);
            $this->deleteFile($banner->gambar);
            $banner->delete();
            return response()->json(['success' => 'Banner rental berhasil dihapus.', 'judul' => 'Berhasil']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Data gagal dihapus.', 'judul' => 'Gagal', 'errorMessage' => $e->getMessage()], 500);
        }
    }

    private function deleteFile(?string $path): void
    {
        // Hanya hapus file hasil upload di storage; aset bawaan public/assets & URL eksternal aman.
        if ($path && ! Str::startsWith($path, ['http://', 'https://', 'assets/'])
            && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }

    private function rules(bool $creating): array
    {
        return [
            'judul'       => 'nullable|string|max:255',
            'gambar_file' => ($creating ? 'required' : 'nullable') . '|image|mimes:jpg,jpeg,png,webp|max:5120',
            'urut'        => 'nullable|integer|min:0',
            'is_active'   => 'nullable|boolean',
        ];
    }

    private function messages(): array
    {
        return [
            'gambar_file.required' => 'Gambar banner wajib diunggah.',
            'gambar_file.image'    => 'File harus berupa gambar.',
            'gambar_file.mimes'    => 'Format gambar: jpg, jpeg, png, webp.',
            'gambar_file.max'      => 'Ukuran gambar maksimal 5 MB.',
        ];
    }

    private function payload(Request $request): array
    {
        return [
            'judul'     => $request->judul ?: null,
            'urut'      => (int) ($request->urut ?? 0),
            'is_active' => $request->boolean('is_active'),
        ];
    }
}

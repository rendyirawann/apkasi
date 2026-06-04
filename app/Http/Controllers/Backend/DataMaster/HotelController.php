<?php

namespace App\Http\Controllers\Backend\DataMaster;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class HotelController extends Controller
{
    public function index()
    {
        return view('backend.data_master.hotel.index');
    }

    public function data(Request $request)
    {
        if (! $request->ajax()) {
            abort(404);
        }

        $query = Hotel::query()->orderBy('urut')->orderBy('id');

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('rating_badge', function ($row) {
                return $row->rating
                    ? '<span class="badge badge-light-warning fw-bold"><i class="ki-outline ki-star fs-7 me-1"></i>' . $row->rating . '</span>'
                    : '<span class="text-muted">-</span>';
            })
            ->addColumn('kamar', function ($row) {
                return $row->ketersediaan_kamar !== null
                    ? '<span class="fw-bold">' . $row->ketersediaan_kamar . '</span> <span class="text-muted fs-8">kamar</span>'
                    : '<span class="text-muted">-</span>';
            })
            ->addColumn('kontak', function ($row) {
                $out = [];
                if ($row->contact_wa) {
                    $out[] = '<span class="d-block"><i class="ki-outline ki-phone fs-7 me-1 text-success"></i>' . e($row->contact_wa) . '</span>';
                }
                if ($row->contact_email) {
                    $out[] = '<span class="d-block text-muted fs-8"><i class="ki-outline ki-sms fs-7 me-1"></i>' . e($row->contact_email) . '</span>';
                }
                return $out ? implode('', $out) : '<span class="text-muted">-</span>';
            })
            ->addColumn('status', function ($row) {
                return $row->is_active
                    ? '<span class="badge badge-light-success">Aktif</span>'
                    : '<span class="badge badge-light-danger">Nonaktif</span>';
            })
            ->addColumn('action', function ($row) {
                $u = auth()->user();
                $btn = '<div class="d-flex justify-content-end flex-shrink-0 gap-1">';
                if ($u->can('hotel.show')) {
                    $btn .= '<button type="button" class="btn btn-sm btn-icon btn-light-info btn-view" data-id="' . $row->id . '" title="Detail"><i class="ki-outline ki-eye fs-4"></i></button>';
                }
                if ($u->can('hotel.edit')) {
                    $btn .= '<button type="button" class="btn btn-sm btn-icon btn-light-primary btn-edit" data-id="' . $row->id . '" title="Edit"><i class="ki-outline ki-pencil fs-5"></i></button>';
                }
                if ($u->can('hotel.delete')) {
                    $btn .= '<button type="button" class="btn btn-sm btn-icon btn-light-danger btn-delete" data-id="' . $row->id . '" data-nama="' . e($row->nama) . '" title="Hapus"><i class="ki-outline ki-trash fs-5"></i></button>';
                }
                return $btn . '</div>';
            })
            ->rawColumns(['rating_badge', 'kamar', 'kontak', 'status', 'action'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), $this->rules(), $this->messages());
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        try {
            $hotel = Hotel::create($this->payload($request));
            if ($request->hasFile('image_file')) {
                $hotel->image = $request->file('image_file')->store('hotel', 'public');
                $hotel->save();
            }
            return response()->json(['success' => 'Data hotel berhasil ditambahkan.', 'judul' => 'Berhasil'], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Terjadi kesalahan di aplikasi.', 'judul' => 'Gagal', 'errorMessage' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        $data = Hotel::findOrFail($id);
        return response()->json(['data' => $data]);
    }

    public function edit($id)
    {
        $data = Hotel::findOrFail($id);
        return response()->json(['data' => $data]);
    }

    public function update(Request $request, $id)
    {
        $hotel = Hotel::findOrFail($id);

        $validator = Validator::make($request->all(), $this->rules(), $this->messages());
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        try {
            $hotel->update($this->payload($request));
            if ($request->hasFile('image_file')) {
                $this->deleteFile($hotel->image);
                $hotel->image = $request->file('image_file')->store('hotel', 'public');
                $hotel->save();
            }
            return response()->json(['success' => 'Data hotel berhasil diperbarui.', 'judul' => 'Berhasil']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Terjadi kesalahan di aplikasi.', 'judul' => 'Gagal', 'errorMessage' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $hotel = Hotel::findOrFail($id);
            $this->deleteFile($hotel->image);
            $hotel->delete();
            return response()->json(['success' => 'Data hotel berhasil dihapus.', 'judul' => 'Berhasil']);
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
            'nama'               => 'required|string|max:255',
            'alamat'             => 'nullable|string|max:255',
            'ketersediaan_kamar' => 'nullable|integer|min:0|max:100000',
            'rating'             => 'nullable|numeric|min:0|max:5',
            'contact_wa'         => 'nullable|string|max:30',
            'contact_email'      => 'nullable|email|max:255',
            'lat'                => 'nullable|numeric|between:-90,90',
            'lng'                => 'nullable|numeric|between:-180,180',
            'maps_url'           => 'nullable|url|max:255',
            'image_file'         => 'nullable|image|mimes:jpg,jpeg,png,webp|max:3072',
            'urut'               => 'nullable|integer|min:0',
            'is_lokasi_acara'    => 'nullable|boolean',
        ];
    }

    private function messages(): array
    {
        return [
            'nama.required'       => 'Nama hotel wajib diisi.',
            'rating.numeric'      => 'Rating harus berupa angka.',
            'rating.max'          => 'Rating maksimal 5.',
            'contact_email.email' => 'Format email tidak valid.',
            'lat.numeric'         => 'Latitude harus berupa angka.',
            'lng.numeric'         => 'Longitude harus berupa angka.',
            'maps_url.url'        => 'Link Google Maps harus berupa URL valid.',
            'image_file.image'    => 'File harus berupa gambar.',
            'image_file.mimes'    => 'Format gambar: jpg, jpeg, png, webp.',
            'image_file.max'      => 'Ukuran gambar maksimal 3 MB.',
            'ketersediaan_kamar.integer' => 'Ketersediaan kamar harus berupa angka bulat.',
        ];
    }

    private function payload(Request $request): array
    {
        return [
            'nama'               => $request->nama,
            'alamat'             => $request->alamat,
            'ketersediaan_kamar' => $request->ketersediaan_kamar !== null && $request->ketersediaan_kamar !== '' ? (int) $request->ketersediaan_kamar : null,
            'rating'             => $request->rating !== null && $request->rating !== '' ? (float) $request->rating : null,
            'contact_wa'         => $request->contact_wa,
            'contact_email'      => $request->contact_email,
            'lat'                => $request->lat !== null && $request->lat !== '' ? (float) $request->lat : null,
            'lng'                => $request->lng !== null && $request->lng !== '' ? (float) $request->lng : null,
            'maps_url'           => $request->maps_url,
            'urut'               => (int) ($request->urut ?? 0),
            'is_lokasi_acara'    => $request->boolean('is_lokasi_acara'),
            'is_active'          => $request->boolean('is_active'),
        ];
    }
}

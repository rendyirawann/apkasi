<?php

namespace App\Http\Controllers\Backend\DataMaster;

use App\Http\Controllers\Controller;
use App\Models\Rental;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class RentalController extends Controller
{
    public function index()
    {
        return view('backend.data_master.rental.index');
    }

    public function data(Request $request)
    {
        if (! $request->ajax()) {
            abort(404);
        }

        $query = Rental::query()->with('mobil')->orderBy('urut')->orderBy('id');

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('kontak', function ($row) {
                $out = [];
                if ($row->kontak_wa) $out[] = '<span class="d-block"><i class="ki-outline ki-phone fs-7 me-1 text-success"></i>' . e($row->kontak_wa) . '</span>';
                if ($row->telepon) $out[] = '<span class="d-block text-muted fs-8">' . e($row->telepon) . '</span>';
                return $out ? implode('', $out) : '<span class="text-muted">-</span>';
            })
            ->addColumn('armada', function ($row) {
                $jenis = $row->mobil->count();
                $unit  = $row->mobil->sum('jumlah_unit');
                return '<span class="fw-bold">' . $jenis . '</span> jenis · <span class="fw-bold">' . $unit . '</span> unit';
            })
            ->addColumn('status', function ($row) {
                return $row->is_active
                    ? '<span class="badge badge-light-success">Aktif</span>'
                    : '<span class="badge badge-light-danger">Nonaktif</span>';
            })
            ->addColumn('action', function ($row) {
                $u = auth()->user();
                $btn = '<div class="d-flex justify-content-end flex-shrink-0 gap-1">';
                if ($u->can('rental.show')) {
                    $btn .= '<button type="button" class="btn btn-sm btn-icon btn-light-info btn-view" data-id="' . $row->id . '" title="Detail"><i class="ki-outline ki-eye fs-4"></i></button>';
                }
                if ($u->can('rental.edit')) {
                    $btn .= '<button type="button" class="btn btn-sm btn-icon btn-light-primary btn-edit" data-id="' . $row->id . '" title="Edit"><i class="ki-outline ki-pencil fs-5"></i></button>';
                }
                if ($u->can('rental.delete')) {
                    $btn .= '<button type="button" class="btn btn-sm btn-icon btn-light-danger btn-delete" data-id="' . $row->id . '" data-nama="' . e($row->nama) . '" title="Hapus"><i class="ki-outline ki-trash fs-5"></i></button>';
                }
                return $btn . '</div>';
            })
            ->rawColumns(['kontak', 'armada', 'status', 'action'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), $this->rules(), $this->messages());
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        try {
            $rental = Rental::create($this->payload($request));
            $this->syncMobil($rental, $request);
            return response()->json(['success' => 'Data rental berhasil ditambahkan.', 'judul' => 'Berhasil'], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Terjadi kesalahan di aplikasi.', 'judul' => 'Gagal', 'errorMessage' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        $r = Rental::with('mobil')->findOrFail($id);
        return response()->json(['data' => $r, 'mobil' => $r->mobil]);
    }

    public function edit($id)
    {
        $r = Rental::with('mobil')->findOrFail($id);
        return response()->json(['data' => $r, 'mobil' => $r->mobil]);
    }

    public function update(Request $request, $id)
    {
        $rental = Rental::findOrFail($id);

        $validator = Validator::make($request->all(), $this->rules(), $this->messages());
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        try {
            $rental->update($this->payload($request));
            $this->syncMobil($rental, $request);
            return response()->json(['success' => 'Data rental berhasil diperbarui.', 'judul' => 'Berhasil']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Terjadi kesalahan di aplikasi.', 'judul' => 'Gagal', 'errorMessage' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            Rental::findOrFail($id)->delete(); // mobil ikut terhapus (cascade)
            return response()->json(['success' => 'Data rental berhasil dihapus.', 'judul' => 'Berhasil']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Data gagal dihapus.', 'judul' => 'Gagal', 'errorMessage' => $e->getMessage()], 500);
        }
    }

    private function syncMobil(Rental $rental, Request $request): void
    {
        $rental->mobil()->delete();
        $namas = (array) $request->input('mobil_nama', []);
        $units = (array) $request->input('mobil_unit', []);
        $i = 0;
        foreach ($namas as $idx => $nama) {
            if (! filled($nama)) continue;
            $rental->mobil()->create([
                'nama_mobil'  => $nama,
                'jumlah_unit' => (int) ($units[$idx] ?? 0),
                'urut'        => ++$i,
            ]);
        }
    }

    private function rules(): array
    {
        return [
            'nama'         => 'required|string|max:255',
            'alamat'       => 'nullable|string|max:255',
            'telepon'      => 'nullable|string|max:30',
            'kontak_wa'    => 'nullable|string|max:30',
            'deskripsi'    => 'nullable|string|max:1000',
            'urut'         => 'nullable|integer|min:0',
            'mobil_nama'   => 'nullable|array',
            'mobil_nama.*' => 'nullable|string|max:255',
            'mobil_unit'   => 'nullable|array',
            'mobil_unit.*' => 'nullable|integer|min:0',
        ];
    }

    private function messages(): array
    {
        return [
            'nama.required'    => 'Nama rental wajib diisi.',
            'mobil_unit.*.integer' => 'Jumlah unit harus berupa angka.',
        ];
    }

    private function payload(Request $request): array
    {
        return [
            'nama'      => $request->nama,
            'alamat'    => $request->alamat,
            'telepon'   => $request->telepon,
            'kontak_wa' => $request->kontak_wa,
            'deskripsi' => $request->deskripsi,
            'urut'      => (int) ($request->urut ?? 0),
            'is_active' => $request->boolean('is_active'),
        ];
    }
}

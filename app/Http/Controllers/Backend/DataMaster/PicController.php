<?php

namespace App\Http\Controllers\Backend\DataMaster;

use App\Http\Controllers\Controller;
use App\Models\Pic;
use App\Models\WilayahProvinsi;
use App\Support\HandlesUrut;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class PicController extends Controller
{
    use HandlesUrut;

    public function index()
    {
        $provinsi = WilayahProvinsi::orderBy('nama')->get(['id', 'nama']);
        return view('backend.data_master.pic.index', compact('provinsi'));
    }

    public function data(Request $request)
    {
        if (! $request->ajax()) {
            abort(404);
        }

        $query = Pic::query()->with('provinsi')->orderBy('urut')->orderBy('id');

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('provinsi', function ($row) {
                return optional($row->provinsi)->nama ?? '<span class="text-muted">-</span>';
            })
            ->addColumn('lo', function ($row) {
                if (! $row->lo_kecamatan && ! $row->lo_instansi) {
                    return '<span class="text-muted">-</span>';
                }
                $kec = $row->lo_kecamatan ? '<span class="fw-semibold">' . e($row->lo_kecamatan) . '</span>' : '';
                $ins = $row->lo_instansi ? '<div class="text-muted fs-8">' . e($row->lo_instansi) . '</div>' : '';
                return $kec . $ins;
            })
            ->addColumn('no_hp_badge', function ($row) {
                return $row->no_hp
                    ? '<span class="fw-bold"><i class="ki-outline ki-phone fs-7 me-1 text-success"></i>' . e($row->no_hp) . '</span>'
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
                if ($u->can('pic.show')) {
                    $btn .= '<button type="button" class="btn btn-sm btn-icon btn-light-info btn-view" data-id="' . $row->id . '" title="Detail"><i class="ki-outline ki-eye fs-4"></i></button>';
                }
                if ($u->can('pic.edit')) {
                    $btn .= '<button type="button" class="btn btn-sm btn-icon btn-light-primary btn-edit" data-id="' . $row->id . '" title="Edit"><i class="ki-outline ki-pencil fs-5"></i></button>';
                }
                if ($u->can('pic.delete')) {
                    $btn .= '<button type="button" class="btn btn-sm btn-icon btn-light-danger btn-delete" data-id="' . $row->id . '" data-nama="' . e($row->nama) . '" title="Hapus"><i class="ki-outline ki-trash fs-5"></i></button>';
                }
                return $btn . '</div>';
            })
            ->rawColumns(['provinsi', 'lo', 'no_hp_badge', 'status', 'action'])
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
            $payload['urut'] = $this->resolveUrut(Pic::class, $request);
            Pic::create($payload);
            return response()->json(['success' => 'Data PIC berhasil ditambahkan.', 'judul' => 'Berhasil'], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Terjadi kesalahan di aplikasi.', 'judul' => 'Gagal', 'errorMessage' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        $data = Pic::with('provinsi')->findOrFail($id);
        return response()->json(['data' => $data, 'provinsi' => optional($data->provinsi)->nama]);
    }

    public function edit($id)
    {
        $data = Pic::findOrFail($id);
        return response()->json(['data' => $data]);
    }

    public function update(Request $request, $id)
    {
        $pic = Pic::findOrFail($id);

        $validator = Validator::make($request->all(), $this->rules(), $this->messages());
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        try {
            $payload = $this->payload($request);
            $payload['urut'] = $this->resolveUrut(Pic::class, $request, $pic);
            $pic->update($payload);
            return response()->json(['success' => 'Data PIC berhasil diperbarui.', 'judul' => 'Berhasil']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Terjadi kesalahan di aplikasi.', 'judul' => 'Gagal', 'errorMessage' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            Pic::findOrFail($id)->delete();
            return response()->json(['success' => 'Data PIC berhasil dihapus.', 'judul' => 'Berhasil']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Data gagal dihapus.', 'judul' => 'Gagal', 'errorMessage' => $e->getMessage()], 500);
        }
    }

    private function rules(): array
    {
        return [
            'provinsi_id'  => 'required|integer|exists:wilayah_provinsi,id',
            'nama'         => 'required|string|max:255',
            'lo_kecamatan' => 'nullable|string|max:120',
            'lo_instansi'  => 'nullable|string|max:200',
            'no_hp'        => 'nullable|string|max:30',
            'urut'         => 'nullable|integer|min:0',
        ];
    }

    private function messages(): array
    {
        return [
            'provinsi_id.required' => 'Provinsi wajib dipilih.',
            'provinsi_id.exists'   => 'Provinsi tidak valid.',
            'nama.required'        => 'Nama PIC wajib diisi.',
        ];
    }

    private function payload(Request $request): array
    {
        return [
            'provinsi_id'  => (int) $request->provinsi_id,
            'nama'         => $request->nama,
            'lo_kecamatan' => $request->lo_kecamatan ?: null,
            'lo_instansi'  => $request->lo_instansi ?: null,
            'no_hp'        => $request->no_hp,
            'urut'         => (int) ($request->urut ?? 0),
            'is_active'    => $request->boolean('is_active'),
        ];
    }
}

<?php

namespace App\Http\Controllers\Backend\DataMaster;

use App\Http\Controllers\Controller;
use App\Models\Rundown;
use App\Support\HandlesUrut;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class RundownController extends Controller
{
    use HandlesUrut;

    public function index()
    {
        return view('backend.data_master.rundown.index');
    }

    public function data(Request $request)
    {
        if (! $request->ajax()) {
            abort(404);
        }

        $query = Rundown::query()->with('kegiatan')->orderBy('urut')->orderBy('tanggal');

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('tanggal_fmt', fn ($row) => '<span class="fw-bold">' . e($row->tanggal_format) . '</span>')
            ->addColumn('label_badge', fn ($row) => $row->label ? '<span class="badge badge-light-primary">' . e($row->label) . '</span>' : '<span class="text-muted">-</span>')
            ->addColumn('jml', fn ($row) => '<span class="fw-bold">' . $row->kegiatan->count() . '</span> kegiatan')
            ->addColumn('status', fn ($row) => $row->is_active
                ? '<span class="badge badge-light-success">Aktif</span>'
                : '<span class="badge badge-light-danger">Nonaktif</span>')
            ->addColumn('action', function ($row) {
                $u = auth()->user();
                $btn = '<div class="d-flex justify-content-end flex-shrink-0 gap-1">';
                if ($u->can('rundown.show')) {
                    $btn .= '<button type="button" class="btn btn-sm btn-icon btn-light-info btn-view" data-id="' . $row->id . '" title="Detail"><i class="ki-outline ki-eye fs-4"></i></button>';
                }
                if ($u->can('rundown.edit')) {
                    $btn .= '<button type="button" class="btn btn-sm btn-icon btn-light-primary btn-edit" data-id="' . $row->id . '" title="Edit"><i class="ki-outline ki-pencil fs-5"></i></button>';
                }
                if ($u->can('rundown.delete')) {
                    $btn .= '<button type="button" class="btn btn-sm btn-icon btn-light-danger btn-delete" data-id="' . $row->id . '" data-nama="' . e($row->tanggal_format) . '" title="Hapus"><i class="ki-outline ki-trash fs-5"></i></button>';
                }
                return $btn . '</div>';
            })
            ->rawColumns(['tanggal_fmt', 'label_badge', 'jml', 'status', 'action'])
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
            $payload['urut'] = $this->resolveUrut(Rundown::class, $request);
            $rundown = Rundown::create($payload);
            $this->syncKegiatan($rundown, $request);
            return response()->json(['success' => 'Rundown berhasil ditambahkan.', 'judul' => 'Berhasil'], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Terjadi kesalahan di aplikasi.', 'judul' => 'Gagal', 'errorMessage' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        $r = Rundown::with('kegiatan')->findOrFail($id);
        return response()->json(['data' => $r, 'kegiatan' => $r->kegiatan]);
    }

    public function edit($id)
    {
        $r = Rundown::with('kegiatan')->findOrFail($id);
        return response()->json(['data' => $r, 'kegiatan' => $r->kegiatan]);
    }

    public function update(Request $request, $id)
    {
        $rundown = Rundown::findOrFail($id);

        $validator = Validator::make($request->all(), $this->rules(), $this->messages());
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        try {
            $payload = $this->payload($request);
            $payload['urut'] = $this->resolveUrut(Rundown::class, $request, $rundown);
            $rundown->update($payload);
            $this->syncKegiatan($rundown, $request);
            return response()->json(['success' => 'Rundown berhasil diperbarui.', 'judul' => 'Berhasil']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Terjadi kesalahan di aplikasi.', 'judul' => 'Gagal', 'errorMessage' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            Rundown::findOrFail($id)->delete(); // kegiatan ikut terhapus (cascade)
            return response()->json(['success' => 'Rundown berhasil dihapus.', 'judul' => 'Berhasil']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Data gagal dihapus.', 'judul' => 'Gagal', 'errorMessage' => $e->getMessage()], 500);
        }
    }

    private function syncKegiatan(Rundown $rundown, Request $request): void
    {
        $rundown->kegiatan()->delete();
        $waktu    = (array) $request->input('keg_waktu', []);
        $kegiatan = (array) $request->input('keg_kegiatan', []);
        $lokasi   = (array) $request->input('keg_lokasi', []);
        $rincian  = (array) $request->input('keg_rincian', []);
        $i = 0;
        foreach ($kegiatan as $idx => $keg) {
            if (! filled($keg)) continue;
            $rundown->kegiatan()->create([
                'waktu'    => $waktu[$idx] ?? null,
                'kegiatan' => $keg,
                'lokasi'   => $lokasi[$idx] ?? null,
                'rincian'  => $rincian[$idx] ?? null,
                'urut'     => ++$i,
            ]);
        }
    }

    private function rules(): array
    {
        return [
            'tanggal'        => 'required|date',
            'label'          => 'nullable|string|max:100',
            'urut'           => 'nullable|integer|min:0',
            'keg_waktu'      => 'nullable|array',
            'keg_waktu.*'    => 'nullable|string|max:100',
            'keg_kegiatan'   => 'nullable|array',
            'keg_kegiatan.*' => 'nullable|string|max:255',
            'keg_lokasi'     => 'nullable|array',
            'keg_lokasi.*'   => 'nullable|string|max:255',
            'keg_rincian'    => 'nullable|array',
            'keg_rincian.*'  => 'nullable|string|max:1000',
        ];
    }

    private function messages(): array
    {
        return [
            'tanggal.required' => 'Tanggal wajib diisi.',
            'tanggal.date'     => 'Format tanggal tidak valid.',
        ];
    }

    private function payload(Request $request): array
    {
        return [
            'tanggal'   => $request->tanggal,
            'label'     => $request->label,
            'urut'      => (int) ($request->urut ?? 0),
            'is_active' => $request->boolean('is_active'),
        ];
    }
}

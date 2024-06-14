<?php

namespace Modules\Finance\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Modules\Krs\Models\TKrsMatakuliahTab;
use Modules\Krs\Models\TKrsPeriodeTab;
use Modules\Krs\Models\TKrsTab;
use Modules\Mahasiswa\Models\TMahasiswaSemesterTab;
use Modules\Mahasiswa\Models\TMahasiswaTab;

class FinanceController extends Controller
{
    protected $controller;
    protected $tMahasiswaSemesterTab;
    protected $tMahasiswaTab;
    protected $tKrsTab;
    protected $tKrsPeriodeTab;
    protected $tKrsMatakuliahTab;
    public function __construct(
        Controller $controller,
        TMahasiswaTab $tMahasiswaTab,
        TMahasiswaSemesterTab $tMahasiswaSemesterTab,
        TKrsTab $tKrsTab,
        TKrsPeriodeTab $tKrsPeriodeTab,
        TKrsMatakuliahTab $tKrsMatakuliahTab,
    ) {
        $this->controller = $controller;
        $this->tKrsTab = $tKrsTab;
        $this->tMahasiswaTab = $tMahasiswaTab;
        $this->tMahasiswaSemesterTab = $tMahasiswaSemesterTab;
        $this->tKrsPeriodeTab = $tKrsPeriodeTab;
        $this->tKrsMatakuliahTab = $tKrsMatakuliahTab;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $krstabs = $this->tKrsTab->detail($request)->paginate(10);
        foreach ($krstabs as $krstab) {
            $i = 0;
            foreach ($krstab->matakuliah as $value) {
                $i = $i + $value->sks;
            }
            $krstab->total_sks = $i;
        }
        return $this->controller->responsList(
            'KRS ALL',
            $krstabs,
            array(
                [ 'name' => 'Informasi','key' => 'informasi' ,'type' => 'array', 'child' => array(
                        ['key' => 'mahasiswa.name', 'type' => 'string', 'className' => 'font-interbold'],
                        ['key' => 'mahasiswa.nim', 'type' => 'string', 'className' => 'font-interregular text-xs'],
                    )
                ],
                [ 'name' => 'Jurusan', 'key' => 'jurusan' ,'type' => 'array','child' => array(
                        ['key' => 'mahasiswa.jurusan.title', 'type' => 'string', 'className' => 'font-interbold text-sm'],
                        ['key' => 'mahasiswa.fakultas.title', 'type' => 'string', 'className' => 'font-interregular'],
                    )
                ],
                [ 'name' => 'Semester', 'key' => 'semester' ,'type' => 'object','child' => array(
                        ['key' => 'mahasiswa.semester_active.semester.title', 'type' => 'string', 'className' => 'font-interbold text-xs'],
                        ['key' => 'mahasiswa.semester_active.semester_periode.title', 'type' => 'string', 'className' => 'italic font-interregular'],
                    )
                ],
                [ 'name' => 'Total SKS','key' => 'total_sks', 'type' => 'string', 'className' => 'font-interbold text-xs' ],
                [ 'name' => 'Tagihan','key' => 'tagihan', 'type' => 'string', 'className' => 'font-interbold text-xs' ],
                [ 'name' => 'Uang Masuk','key' => 'uang_masuk', 'type' => 'string', 'className' => 'font-interbold text-xs' ],
                [ 'name' => 'Status','key' => 'status', 'type' => 'status' ],
                [ 'type' => 'action', 'ability' => ['SHOW']]
            )
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return $this->controller->respons(
            'FORM CREATE',
            array(
                [   'key' => 't_mahasiswa_tabs_id', 
                    't_mahasiswa_tabs_id' => null,
                    'type' => 'select-search',
                    'label' => 'Nama Mahasiswa',
                    'isRequired' => true,
                    'list' => [
                        'url' => '',
                        'keyValue' => 'id',
                        'keyoption' => 'name',
                        'keyprefix' => 'nim'
                    ]
                ],
                [ 'key' => 'nim', 'placeholder' => null, 'type' => 'text', 'label' => 'NIM', 'visible' => false, 'readonly' => true ],
                [ 'key' => 'semester', 'placeholder' => null, 'type' => 'text', 'label' => 'Semester', 'visible' => false, 'readonly' => true ],
                [ 'key' => 'periode_semester', 'placeholder' => null, 'type' => 'text', 'label' => 'Periode Semester', 'visible' => false, 'readonly' => true ],
            )
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->controller->validasi($request->all(),[
            't_periode_tabs_id' => 'required',
        ]);

        try {
            DB::beginTransaction();
            $user = $request->form[0];
            $periode = $this->tKrsPeriodeTab->find($request->t_periode_tabs_id);
            $mahasiswa = $this->tMahasiswaTab->where('id',$user[$user['key']])->first();
            $semesterMahasiswa = $this->tMahasiswaSemesterTab
                ->where('t_mahasiswa_tabs_id', $mahasiswa->id)
                ->where('active',1)
                ->first();
            $krs = $this->tKrsTab->create([
                't_krs_periode_tabs_id' => $request->t_periode_tabs_id,
                't_mahasiswa_tabs_id' => $mahasiswa->id,
                't_mahasiswa_semester_tabs_id' => $semesterMahasiswa->id,
                'm_status_tabs_id' => 1,
                'active_date' => $periode->end
            ]);
            foreach ($request->matakuliah as $value) {
                $this->tKrsMatakuliahTab->create([
                    't_krs_tabs_id' => $krs->id,
                    't_mata_kuliah_tabs_id' => $value['id'],
                ]);
            }
            DB::commit();
            return $this->controller->respons("CREATED", "KRS baru berhasil ditambahkan", [
                'title' => "KRS baru berhasil ditambahkan",
                'body' => 'Data KRS yang anda buat berhasil di tambahkan ke system',
                'theme' => 'success'
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            abort(400, $th->getMessage());
        }
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return $this->controller->respons('USER DETAIL', auth()->user());
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $krstabs = $this->tKrsTab->find($id);
        return $this->controller->respons(
            'FORM TAGIHAN',
            array(
                [ 'key' => 'tagihan', 'tagihan' => $krstabs->tagihan, 'type' => 'number', 'label' => 'Besar Tagihan' ],
            )
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $this->controller->validasi($request->all(),[
            'tagihan' => 'required',
        ]);

        try {
            DB::beginTransaction();
            foreach ($request->tagihan as $value) {
                if($value[$value['key']] == 0) {
                    return $this->controller->respons("UPDATED", "Tagihan gagal di update", [
                        'title' => "Informasi Tagihan gagal diubah",
                        'body' => 'Data Tagihan gagal di perbaharui di system karena nilainya 0',
                        'theme' => 'error'
                    ]);
                }
                $this->tKrsTab->where('id',$id)->update([
                    'tagihan' => $value[$value['key']]
                ]);
            }
            // $this->tKrsMatakuliahTab->where('t_krs_tabs_id', $request->t_krs_tabs_id)->delete();
            // foreach ($request->matakuliah as $value) {
            //     $this->tKrsMatakuliahTab->create([
            //         't_krs_tabs_id' => $request->t_krs_tabs_id,
            //         't_mata_kuliah_tabs_id' => $value['id'],
            //     ]);
            // }
            DB::commit();
            return $this->controller->respons("UPDATED", "Tagihan gagal berhasil di update", [
                'title' => "Informasi Tagihan gagal berhasil diubah",
                'body' => 'Data Tagihan gagal berhasil di perbaharui di system',
                'theme' => 'success'
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            abort(400, $th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // ...
    }
}

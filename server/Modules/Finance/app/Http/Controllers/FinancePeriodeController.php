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
use Modules\Master\Models\MSemesterPeriodeTabs;
use Modules\Master\Models\MSemesterTab;
use Modules\Master\Models\MStatusTab;

class FinancePeriodeController extends Controller
{
    protected $controller;
    protected $mStatusTab;
    protected $tKrsPeriodeTab;
    protected $tKrsMatakuliahTab;
    protected $tKrsTab;
    protected $mSemesterTab;
    protected $mSemesterPeriodeTabs;
    public function __construct(
        Controller $controller,
        MStatusTab $mStatusTab,
        MSemesterPeriodeTabs $mSemesterPeriodeTabs,
        MSemesterTab $mSemesterTab,
        TKrsMatakuliahTab $tKrsMatakuliahTab,
        TKrsPeriodeTab $tKrsPeriodeTab,
        TKrsTab $tKrsTab,
    ) {
        $this->controller = $controller;
        $this->mStatusTab = $mStatusTab;
        $this->tKrsMatakuliahTab = $tKrsMatakuliahTab;
        $this->mSemesterTab = $mSemesterTab;
        $this->mSemesterPeriodeTabs = $mSemesterPeriodeTabs;
        $this->tKrsPeriodeTab = $tKrsPeriodeTab;
        $this->tKrsTab = $tKrsTab;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return $this->controller->responsList(
            'KRS PERIODE ALL',
            $this->tKrsPeriodeTab->whereIn('m_status_tabs_id',[2,3])->with('status','semester_periode')->paginate(10),
            array(
                [ 'name' => 'Nama Periode','key' => 'title', 'type' => 'string', 'className' => 'font-interbold' ],
                [ 'name' => 'Semester','key' => 'semester_periode.title', 'type' => 'string' ],
                [ 'name' => 'Mulai','key' => 'start', 'type' => 'string' ],
                [ 'name' => 'Selesai','key' => 'end', 'type' => 'string' ],
                [ 'name' => 'Status','key' => 'custom_status', 'type' => 'custom' ],
                [ 'type' => 'action', 'ability' => ['SHOW']]
            )
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $krstabs = $this->tKrsTab
            ->where('t_krs_periode_tabs_id',$request->periodeId)
            ->where('t_mahasiswa_tabs_id',$request->mahasiswaId)
            ->first();
            
        return $this->controller->responsList(
            "FINANCE MATAKULIAH LIST", 
            $this->tKrsMatakuliahTab->where('t_krs_tabs_id', $krstabs->id)->with([
                'detail_matakuliah' => function($a){
                    $a->with('dosen');
                }
            ])->paginate(10),
            array(
                [ 'name' => 'Code','key' => 'detail_matakuliah.code', 'type' => 'string' ],
                [ 'name' => 'Mata Kuliah','key' => 'detail_matakuliah.title', 'type' => 'string', 'className' => 'font-interbold' ],
                [ 'name' => 'Dosen','key' => 'detail_matakuliah.dosen.name', 'type' => 'string' ],
                [ 'name' => 'Bobot SKS','key' => 'detail_matakuliah.bobot_sks', 'type' => 'string', 'className' => 'text-center', 'classNameColumn' => 'text-center' ],
                [ 'name' => 'Jadwal','key' => 'jadwal' ,'type' => 'array', 'child' => array(
                        ['key' => 'detail_matakuliah.days', 'type' => 'string', 'className' => 'font-interbold text-center'],
                        ['key' => 'detail_matakuliah.times', 'type' => 'string', 'className' => 'font-interregular text-xs text-center'],
                    )
                ],
            )
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->controller->validasi($request->all(),[
            'title' => 'required|unique:t_krs_periode_tabs,title',
            'start' => 'required',
            'end' => 'required',
            'm_semester_periode_tabs_id' => 'required',
        ]);

        try {
            DB::beginTransaction();
            $request['m_status_tabs_id'] = 1;
            $this->tKrsPeriodeTab->create($request->all());
            DB::commit();
            return $this->controller->respons("CREATED", "Periode baru berhasil ditambahkan", [
                'title' => "Periode baru berhasil ditambahkan",
                'body' => 'Data Periode yang anda buat berhasil di tambahkan ke system',
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
        return $this->controller->respons("PERIODE", $this->tKrsPeriodeTab->where('id', $id)->with('krs')->first());
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $this->controller->validasi($request->all(),[
            'm_status_tabs_id' => 'required',
        ]);

        try {
            DB::beginTransaction();
            $this->tKrsTab->where('id',$id)->update($request->all());
            DB::commit();
            return $this->controller->respons("UPDATED", "KRS Mahasiswa berhasil di update", [
                'title' => "Informasi KRS Mahasiswa berhasil diubah",
                'body' => 'Data KRS Mahasiswa berhasil di perbaharui di system',
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
        try {
            DB::beginTransaction();
            $this->tKrsPeriodeTab->where('id',$id)->delete();
            DB::commit();
            return $this->controller->respons("DELETED", "Periode berhasil di hapus", [
                'title' => "Menghapus Periode berhasil",
                'body' => 'Data Periode yang anda hapus berhasil di bersihkan dari system',
                'theme' => 'success'
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            abort(400, $th->getMessage());
        }
    }

}

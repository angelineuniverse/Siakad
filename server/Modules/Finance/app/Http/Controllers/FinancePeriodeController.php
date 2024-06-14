<?php

namespace Modules\Finance\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
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
    protected $tKrsTab;
    protected $mSemesterTab;
    protected $mSemesterPeriodeTabs;
    public function __construct(
        Controller $controller,
        MStatusTab $mStatusTab,
        MSemesterPeriodeTabs $mSemesterPeriodeTabs,
        MSemesterTab $mSemesterTab,
        TKrsPeriodeTab $tKrsPeriodeTab,
        TKrsTab $tKrsTab,
    ) {
        $this->controller = $controller;
        $this->mStatusTab = $mStatusTab;
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
    public function create()
    {
        return $this->controller->respons(
            'FORM CREATE',
            array(
                [ 'key' => 'title', 'title' => null, 'type' => 'text','label' => 'Nama Periode', 'isRequired' => true ],
                [ 'key' => 'm_semester_periode_tabs_id', 'm_semester_periode_tabs_id' => null, 
                        'type' => 'select' ,'label' => 'Semester', 
                        'isRequired' => true,
                        'list' => [
                            'options' => $this->mSemesterPeriodeTabs->all(),
                            'keyValue' => 'id',
                            'keyoption' => 'title'
                        ]
                    ],
                [ 'key' => 'start', 'start' => null, 'type' => 'date','label' => 'Mulai Periode', 'isRequired' => true ],
                [ 'key' => 'end', 'end' => null, 'type' => 'date','label' => 'Selesai Periode', 'isRequired' => true ],
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
        $detail = $this->tKrsPeriodeTab->where('id', $id)->first();
        return $this->controller->respons(
            'FORM EDIT',
            array(
                [ 'key' => 'title', 'title' => $detail->title, 'type' => 'text','label' => 'Nama Periode', 'isRequired' => true ],
                [ 'key' => 'm_semester_periode_tabs_id', 'm_semester_periode_tabs_id' => $detail->m_semester_periode_tabs_id, 
                        'type' => 'select' ,'label' => 'Semester',
                        'placeholder' => $this->mSemesterPeriodeTabs->where('id',$detail->m_semester_periode_tabs_id)->pluck('title')->first(),
                        'isRequired' => true,
                        'list' => [
                            'options' => $this->mSemesterPeriodeTabs->all(),
                            'keyValue' => 'id',
                            'keyoption' => 'title'
                        ]
                    ],
                [ 'key' => 'start', 'start' => $detail->start, 'type' => 'date','label' => 'Mulai Periode', 'isRequired' => true ],
                [ 'key' => 'end', 'end' => $detail->end, 'type' => 'date','label' => 'Selesai Periode', 'isRequired' => true ],
                [ 'key' => 'm_status_tabs_id', 'm_status_tabs_id' => $detail->m_status_tabs_id, 'type' => 'select',
                    'placeholder' => $this->mStatusTab->where('id',$detail->m_status_tabs_id)->pluck('title')->first(),
                    'label' => 'Dosen', 'isRequired' => true,
                    'list' => [
                        'options' => $this->mStatusTab->all(),
                        'keyValue' => 'id',
                        'keyoption' => 'title'
                    ]
                ],
            )
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $this->controller->validasi($request->all(),[
            'title' => 'required',
            'start' => 'required',
            'end' => 'required',
            'm_status_tabs_id' => 'required',
        ]);

        try {
            DB::beginTransaction();
            $this->tKrsPeriodeTab->where('id',$id)->update($request->all());
            DB::commit();
            return $this->controller->respons("UPDATED", "Periode berhasil di update", [
                'title' => "Informasi Periode berhasil diubah",
                'body' => 'Data Periode berhasil di perbaharui di system',
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

    public function matakuliahList($periodeId){
        $periode = $this->tKrsPeriodeTab->find($periodeId);
        return $this->controller->respons(
            "MATAKULIAH LIST", 
            $this->mSemesterTab->krs($periode->m_semester_periode_tabs_id)->get()
        );
    }

    public function selectedmatakuliahList($periodeId,$mahasiswaId){
        $krstabs = $this->tKrsTab
            ->where('t_krs_periode_tabs_id',$periodeId)
            ->where('t_mahasiswa_tabs_id',$mahasiswaId)
            ->currentKrs()
            ->first();
            
        $i = 0;
        foreach ($krstabs->matakuliah as $value) {
            $i = $i + $value->sks;
        }
        $krstabs->total_sks = $i;
        return $this->controller->respons(
            "MATAKULIAH LIST", 
            $krstabs
        );
    }
}

<?php

namespace Modules\MataKuliah\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Modules\Dosen\Models\TDosenTabs;
use Modules\Krs\Models\TKrsMatakuliahTab;
use Modules\Master\Models\MCodeTab;
use Modules\Master\Models\MFakultasTab;
use Modules\Master\Models\MJurusanTab;
use Modules\Master\Models\MSemesterPeriodeTabs;
use Modules\Master\Models\MSemesterTab;
use Modules\Master\Models\MStatusTab;
use Modules\MataKuliah\Models\TMataKuliahTab;

class MataKuliahController extends Controller
{
    protected $controller;
    protected $tMataKuliahTab;
    protected $tDosenTabs;
    protected $mSemesterTab;
    protected $mSemesterPeriodeTabs;
    protected $mFakultasTab;
    protected $tKrsMatakuliahTab;
    protected $mJurusanTab;
    public function __construct(
        Controller $controller,
        TMataKuliahTab $tMataKuliahTab,
        TDosenTabs $tDosenTabs,
        MSemesterTab $mSemesterTab,
        MSemesterPeriodeTabs $mSemesterPeriodeTabs,
        MFakultasTab $mFakultasTab,
        TKrsMatakuliahTab $tKrsMatakuliahTab,
        MJurusanTab $mJurusanTab,
    ) {
        $this->controller = $controller;
        $this->tMataKuliahTab = $tMataKuliahTab;
        $this->tDosenTabs = $tDosenTabs;
        $this->mSemesterTab = $mSemesterTab;
        $this->tKrsMatakuliahTab = $tKrsMatakuliahTab;
        $this->mSemesterPeriodeTabs = $mSemesterPeriodeTabs;
        $this->mFakultasTab = $mFakultasTab;
        $this->mJurusanTab = $mJurusanTab;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return $this->controller->responsList(
            'MATAKULIAH ALL',
            $this->tMataKuliahTab->detail($request)->paginate(10),
            array(
                [ 'name' => 'Mata Kuliah','key' => 'informasi' ,'type' => 'array', 'child' => array(
                        ['key' => 'title', 'type' => 'string', 'className' => 'font-interbold'],
                        ['key' => 'code', 'type' => 'string', 'className' => 'font-interregular text-xs'],
                    )
                ],
                [ 'name' => 'Dosen','key' => 'dosen.name', 'type' => 'string' ],
                [ 'name' => 'Jurusan', 'key' => 'jurusan' ,'type' => 'array','child' => array(
                        ['key' => 'jurusan.title', 'type' => 'string', 'className' => 'font-interbold text-sm'],
                        ['key' => 'fakultas.title', 'type' => 'string', 'className' => 'font-interregular'],
                    )
                ],
                [ 'name' => 'Periode', 'key' => 'periode' ,'type' => 'object','child' => array(
                        ['key' => 'semester.title', 'type' => 'string', 'className' => 'font-interbold text-xs'],
                        ['key' => 'semester_periode.title', 'type' => 'string', 'className' => 'italic font-interregular'],
                    )
                ],
                [ 'name' => 'Jadwal', 'key' => 'jadwal' ,'type' => 'array','child' => array(
                        ['key' => 'days', 'type' => 'string', 'className' => 'font-interbold text-sm'],
                        ['key' => 'times', 'type' => 'string', 'className' => 'italic font-interregular'],
                    )
                ],
                [ 'name' => 'SKS','key' => 'bobot_sks', 'type' => 'string' ],
                [ 'name' => 'Status','key' => 'status', 'type' => 'status' ],
                [ 'type' => 'action', 'ability' => ['EDIT','DELETE']]
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
                [ 'key' => 'title', 'title' => null, 'type' => 'text','label' => 'Nama Mata Kuliah', 'isRequired' => true ],
                [ 'key' => 't_dosen_tabs_id', 't_dosen_tabs_id' => null, 'type' => 'select', 'label' => 'Dosen', 'isRequired' => true,
                    'list' => [
                        'options' => $this->tDosenTabs->all(),
                        'keyValue' => 'id',
                        'keyoption' => 'name'
                    ]
                ],
                [ 'key' => 'm_semester_tabs_id', 'm_semester_tabs_id' => null, 'type' => 'select', 'label' => 'Semester', 'isRequired' => true,
                    'list' => [
                        'options' => $this->mSemesterTab->where('id','<',9)->get(),
                        'keyValue' => 'id',
                        'keyoption' => 'title'
                    ]
                ],
                [ 'key' => 'm_semester_periode_tabs_id', 'm_semester_periode_tabs_id' => null, 'type' => 'select', 'label' => 'Periode Semester', 'isRequired' => true,
                    'list' => [
                        'options' => $this->mSemesterPeriodeTabs->all(),
                        'keyValue' => 'id',
                        'keyoption' => 'title'
                    ]
                ],
                [ 'key' => 'm_fakultas_tabs_id', 'm_fakultas_tabs_id' => null, 'type' => 'select', 'label' => 'Fakultas', 'isRequired' => true,
                    'list' => [
                        'options' => $this->mFakultasTab->all(),
                        'keyValue' => 'id',
                        'keyoption' => 'title'
                    ]
                ],
                [ 'key' => 'm_jurusan_tabs_id', 'm_jurusan_tabs_id' => null, 'type' => 'select', 'label' => 'Jurusan', 'isRequired' => true,
                    'list' => [
                        'options' => $this->mJurusanTab->all(),
                        'keyValue' => 'id',
                        'keyoption' => 'title'
                    ]
                ],
                [ 'key' => 'days', 'days' => null, 'type' => 'select', 'label' => 'Hari Jadwal', 'isRequired' => true,
                    'list' => [
                        'options' => array(
                            ['title' => 'Senin'],
                            ['title' => 'Selasa'],
                            ['title' => 'Rabu'],
                            ['title' => 'Kamis'],
                            ['title' => 'Jumat'],
                        ),
                        'keyValue' => 'title',
                        'keyoption' => 'title'
                    ]
                ],
                [ 'key' => 'times', 'times' => null, 'type' => 'time', 'label' => 'Jam Masuk', 'isRequired' => true ],
                [ 'key' => 'bobot_sks', 'bobot_sks' => null, 'type' => 'number', 'label' => 'Bobot SKS', 'isRequired' => true ],
            )
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->controller->validasi($request->all(),[
            'title' => 'required|unique:t_mata_kuliah_tabs,title',
            'days' => 'required',
            'times' => 'required',
            'bobot_sks' => 'required',
            't_dosen_tabs_id' => 'required',
            'm_semester_tabs_id' => 'required',
            'm_semester_periode_tabs_id' => 'required',
            'm_fakultas_tabs_id' => 'required',
            'm_jurusan_tabs_id' => 'required',
        ]);

        try {
            DB::beginTransaction();
            $request['code'] = MCodeTab::generateCode('MKL');
            $this->tMataKuliahTab->create($request->all());
            DB::commit();
            return $this->controller->respons("CREATED", "Mata Kuliah baru berhasil ditambahkan", [
                'title' => "Mata Kuliah baru berhasil ditambahkan",
                'body' => 'Data Mata Kuliah yang anda buat berhasil di tambahkan ke system',
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
        $detail = $this->tMataKuliahTab->where('id', $id)->first();
        return $this->controller->respons(
            'FORM EDIT',
            array(
                [ 'key' => 'code', 'code' => $detail->code, 'type' => 'text','label' => 'Code Mata Kuliah', 'readonly' => true ],
                [ 'key' => 'm_status_tabs_id', 'm_status_tabs_id' => null, 
                    'placeholder' => MStatusTab::where('id',$detail->m_status_tabs_id)->pluck('title')->first(),
                    'type' => 'select', 'label' => 'Status', 'isRequired' => true,
                    'list' => [
                        'options' => MStatusTab::whereIn('id',[4,5])->get(),
                        'keyValue' => 'id',
                        'keyoption' => 'title'
                    ]
                ],
                [ 'key' => 'title', 'title' => $detail->title, 'type' => 'text','label' => 'Nama Mata Kuliah', 'isRequired' => true ],
                [ 'key' => 't_dosen_tabs_id', 't_dosen_tabs_id' => $detail->t_dosen_tabs_id, 'type' => 'select',
                    'placeholder' => $this->tDosenTabs->where('id',$detail->t_dosen_tabs_id)->pluck('name')->first(),
                    'label' => 'Dosen', 'isRequired' => true,
                    'list' => [
                        'options' => $this->tDosenTabs->all(),
                        'keyValue' => 'id',
                        'keyoption' => 'name'
                    ]
                ],
                [ 'key' => 'm_semester_tabs_id', 'm_semester_tabs_id' => $detail->m_semester_tabs_id, 'type' => 'select', 
                    'label' => 'Semester', 'isRequired' => true,
                    'placeholder' => $this->mSemesterTab->where('id',$detail->m_semester_tabs_id)->pluck('title')->first(),
                    'list' => [
                        'options' => $this->mSemesterTab->all(),
                        'keyValue' => 'id',
                        'keyoption' => 'title'
                    ]
                ],
                [ 'key' => 'm_semester_periode_tabs_id', 'm_semester_periode_tabs_id' => $detail->m_semester_periode_tabs_id, 'type' => 'select', 
                    'label' => 'Periode Semester', 'isRequired' => true,
                    'placeholder' => $this->mSemesterPeriodeTabs->where('id',$detail->m_semester_periode_tabs_id)->pluck('title')->first(),
                    'list' => [
                        'options' => $this->mSemesterPeriodeTabs->all(),
                        'keyValue' => 'id',
                        'keyoption' => 'title'
                    ]
                ],
                [ 'key' => 'm_fakultas_tabs_id', 'm_fakultas_tabs_id' => $detail->m_fakultas_tabs_id, 'type' => 'select', 
                    'label' => 'Fakultas', 'isRequired' => true,
                    'placeholder' => $this->mFakultasTab->where('id',$detail->m_fakultas_tabs_id)->pluck('title')->first(),
                    'list' => [
                        'options' => $this->mFakultasTab->all(),
                        'keyValue' => 'id',
                        'keyoption' => 'title'
                    ]
                ],
                [ 'key' => 'm_jurusan_tabs_id', 'm_jurusan_tabs_id' => $detail->m_jurusan_tabs_id, 'type' => 'select', 
                    'label' => 'Jurusan', 'isRequired' => true,
                    'placeholder' => $this->mJurusanTab->where('id',$detail->m_jurusan_tabs_id)->pluck('title')->first(),
                    'list' => [
                        'options' => $this->mJurusanTab->all(),
                        'keyValue' => 'id',
                        'keyoption' => 'title'
                    ]
                ],
                [ 'key' => 'days', 'days' => $detail->days, 'type' => 'select', 
                    'label' => 'Hari Jadwal', 'isRequired' => true,
                    'placeholder' => $detail->days,
                    'list' => [
                        'options' => array(
                            ['title' => 'Senin'],
                            ['title' => 'Selasa'],
                            ['title' => 'Rabu'],
                            ['title' => 'Kamis'],
                            ['title' => 'Jumat'],
                        ),
                        'keyValue' => 'title',
                        'keyoption' => 'title'
                    ]
                ],
                [ 'key' => 'times', 'times' => $detail->times, 'type' => 'time','label' => 'Jam Masuk', 'isRequired' => true ],
                [ 'key' => 'bobot_sks', 'bobot_sks' => $detail->bobot_sks, 'type' => 'number','label' => 'Bobot SKS', 'isRequired' => true ],
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
            'days' => 'required',
            'times' => 'required',
            'bobot_sks' => 'required',
            't_dosen_tabs_id' => 'required',
            'm_semester_tabs_id' => 'required',
            'm_semester_periode_tabs_id' => 'required',
            'm_fakultas_tabs_id' => 'required',
            'm_jurusan_tabs_id' => 'required',
        ]);

        try {
            DB::beginTransaction();
            $this->tMataKuliahTab->where('id',$id)->update($request->all());
            DB::commit();
            return $this->controller->respons("UPDATED", "Mata Kuliah berhasil di update", [
                'title' => "Informasi Mata Kuliah berhasil diubah",
                'body' => 'Data Mata Kuliah berhasil di perbaharui di system',
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
            $this->tMataKuliahTab->where('id',$id)->delete();
            DB::commit();
            return $this->controller->respons("DELETED", "Mata Kuliah berhasil di hapus", [
                'title' => "Menghapus Mata Kuliah berhasil",
                'body' => 'Data Mata Kuliah yang anda hapus berhasil di bersihkan dari system',
                'theme' => 'success'
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            abort(400, $th->getMessage());
        }
    }

    public function matakuliah_form_krs(Request $request)
    {
        $forms = $this->tMataKuliahTab->krs($request)->get();
        return $this->controller->respons("msg",$forms);
        // return $this->controller->respons(
        //     'FORM CREATE',
        //     array(
        //         [   'key' => 't_mahasiswa_tabs_id', 
        //             't_mahasiswa_tabs_id' => null,
        //             'type' => 'select-search',
        //             'label' => 'Nama Mahasiswa',
        //             'isRequired' => true,
        //             'list' => [
        //                 'url' => '',
        //                 'keyValue' => 'id',
        //                 'keyoption' => 'name',
        //                 'keyprefix' => 'nim'
        //             ]
        //         ],
        //         [ 'key' => 'nim', 'nim' => null, 'type' => 'text', 'label' => 'NIM', 'visible' => false, 'readonly' => true ],
        //         [ 'key' => 'semester', 'semester' => null, 'type' => 'text', 'label' => 'Semester', 'visible' => false, 'readonly' => true ],
        //         [ 'key' => 'periode_semester', 'periode_semester' => null, 'type' => 'text', 'label' => 'Periode Semester', 'visible' => false, 'readonly' => true ],
        //     )
        // );
    }

    public function ipk(){
        $listAll = $this->tKrsMatakuliahTab
            ->with('krs','nilai','detail_matakuliah')
            ->whereHas('krs', function($a){
                $a->where('t_mahasiswa_tabs_id', auth()->user()->id);
            })->get();
        $bobotTotal = 0;
        $sksTotal = 0;
        foreach ($listAll as $value) {
            $sksTotal = $sksTotal + $value->detail_matakuliah->bobot_sks;
            if(isset($value->nilai->nilai)) {
                $bobotTotal = $bobotTotal + ($value->nilai->nilai * $value->detail_matakuliah->bobot_sks);
            }
        }
        $ipk = 0;
        if($bobotTotal != 0 || $sksTotal != 0) {
            $ipk = $bobotTotal / $sksTotal;
        }
        
        return $this->controller->respons('IPK NOW', $ipk);
    }
}

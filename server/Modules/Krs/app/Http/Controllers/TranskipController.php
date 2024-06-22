<?php

namespace Modules\Krs\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Krs\Models\TKrsMatakuliahTab;
use Modules\Krs\Models\TKrsPeriodeTab;
use Modules\Krs\Models\TKrsTab;
use Modules\Mahasiswa\Models\TMahasiswaSemesterTab;
use Modules\Mahasiswa\Models\TMahasiswaTab;
use Modules\Master\Models\MSemesterPeriodeTabs;
use Modules\Master\Models\MSemesterTab;
use Modules\Master\Models\MStatusTab;

class TranskipController extends Controller
{
    protected $controller;
    protected $mStatusTab;
    protected $tKrsPeriodeTab;
    protected $tMahasiswaTab;
    protected $tKrsTab;
    protected $tKrsMatakuliahTab;
    protected $tMahasiswaSemesterTab;
    public function __construct(
        Controller $controller,
        MStatusTab $mStatusTab,
        TKrsTab $tKrsTab,
        TMahasiswaTab $tMahasiswaTab,
        TMahasiswaSemesterTab $tMahasiswaSemesterTab,
        TKrsPeriodeTab $tKrsPeriodeTab,
        TKrsMatakuliahTab $tKrsMatakuliahTab,
    ) {
        $this->controller = $controller;
        $this->mStatusTab = $mStatusTab;
        $this->tKrsTab = $tKrsTab;
        $this->tKrsMatakuliahTab = $tKrsMatakuliahTab;
        $this->tMahasiswaTab = $tMahasiswaTab;
        $this->tMahasiswaSemesterTab = $tMahasiswaSemesterTab;
        $this->tKrsPeriodeTab = $tKrsPeriodeTab;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return $this->controller->responsList(
            'KRS TRANSKIP ALL',
            $this->tMahasiswaTab->where('deleted',0)->detail($request)->paginate(20),
            array(
                [ 'name' => 'Informasi','key' => 'informasi' ,'type' => 'array', 'child' => array(
                        ['key' => 'name', 'type' => 'string', 'className' => 'text-sm font-interbold'],
                        ['key' => 'nim', 'type' => 'string', 'className' => 'font-interregular text-xs'],
                        ['key' => 'email', 'type' => 'string', 'className' => 'font-interregular italic mt-1 text-xs'],
                    )
                ],
                [ 'name' => 'Jurusan', 'key' => 'jurusan' ,'type' => 'array','child' => array(
                        ['key' => 'jurusan.title', 'type' => 'string', 'className' => 'font-interbold text-sm'],
                        ['key' => 'fakultas.title', 'type' => 'string', 'className' => 'font-interregular'],
                    )
                ],
                [ 'name' => 'Semester', 'key' => 'semester' ,'type' => 'object','child' => array(
                        ['key' => 'semester_active.semester.title', 'type' => 'string', 'className' => 'font-interbold text-xs'],
                        ['key' => 'semester_active.semester_periode.title', 'type' => 'string', 'className' => 'italic font-interregular'],
                    )
                ],
                [ 'name' => 'Terdaftar','key' => 'terdaftar' ,'type' => 'array', 'child' => array(
                    [ 'key' => 'date_in', 'type' => 'date', 'className' => 'italic font-interregular' ],
                    [ 'key' => 'curiculum', 'type' => 'date-prefix-custom', 'prefix' => 'Curiculum ' ,'dateFormat' => 'MMMM yyyy' ,'className' => 'italic font-intermedium' ],
                )
                ],
                [ 'name' => 'Action', 'type' => 'action_status', 'ability' => array(
                    [ 'title' => 'Print', 'key' => 'print' ,'theme' => 'error', 'show_by' => 'active', 'show_value' => [1]],
                )],
            )
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // ...
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
        $krs = $this->tKrsTab
                ->where('t_mahasiswa_semester_tabs_id', $id)
                ->where('t_mahasiswa_tabs_id', auth()->user()->id)
                ->whereIn('m_status_tabs_id',[6,8])
                ->first();
        $matkul = null;
        if(isset($krs)){
        $matkul = $this->tKrsMatakuliahTab
            ->where('t_krs_tabs_id', $krs->id)
            ->with(['nilai','detail_matakuliah' => function($a){
                $a->with('dosen');
            }])
            ->paginate(20);
        }
        return $this->controller->responsList(
            'JADWAL KRS MAHASISWA',
            $matkul,
            array(
                [ 'name' => 'Code','key' => 'detail_matakuliah.code', 'type' => 'string' ],
                [ 'name' => 'Mata Kuliah','key' => 'detail_matakuliah.title', 'type' => 'string' ],
                [ 'name' => 'Dosen','key' => 'detail_matakuliah.dosen.name', 'type' => 'string' ],
                [ 'name' => 'SKS','key' => 'detail_matakuliah.bobot_sks', 'type' => 'string' ],
                [ 'name' => 'Nilai','key' => 'nilai.title', 'type' => 'string' ],
            )
        );
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        // ...
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // ...
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // ...
    }

    public function formSemesterActive(){
        $listsemester = TMahasiswaSemesterTab::where('t_mahasiswa_tabs_id', auth()->user()->id)
                            ->with(['semester','semester_periode'])->get();
        $options = array();
        foreach ($listsemester as $value) {
            array_push($options,[
                'id' => $value->id,
                'title' => $value->semester->title.' - '.$value->semester_periode->title,
                'semester_id' => $value->semester->id,
            ]);
        }
        return $this->controller->respons(
            'SEMESTER ACTIVE', 
            $options
        );
    }
}

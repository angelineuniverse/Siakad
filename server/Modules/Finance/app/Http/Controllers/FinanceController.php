<?php

namespace Modules\Finance\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Modules\Finance\Models\TKrsTagihanTabs;
use Modules\Krs\Models\TKrsMatakuliahTab;
use Modules\Krs\Models\TKrsPeriodeTab;
use Modules\Krs\Models\TKrsTab;
use Modules\Mahasiswa\Models\TMahasiswaSemesterTab;
use Modules\Mahasiswa\Models\TMahasiswaTab;
use Modules\Master\Models\MCodeTab;

class FinanceController extends Controller
{
    protected $controller;
    protected $tMahasiswaSemesterTab;
    protected $tMahasiswaTab;
    protected $tKrsTab;
    protected $tKrsPeriodeTab;
    protected $tKrsMatakuliahTab;
    protected $tKrsTagihanTabs;
    public function __construct(
        Controller $controller,
        TMahasiswaTab $tMahasiswaTab,
        TMahasiswaSemesterTab $tMahasiswaSemesterTab,
        TKrsTab $tKrsTab,
        TKrsPeriodeTab $tKrsPeriodeTab,
        TKrsMatakuliahTab $tKrsMatakuliahTab,
        TKrsTagihanTabs $tKrsTagihanTabs,
    ) {
        $this->controller = $controller;
        $this->tKrsTab = $tKrsTab;
        $this->tKrsTagihanTabs = $tKrsTagihanTabs;
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
                [ 'name' => 'Uang Masuk','key' => 'uang_detail_sum', 'type' => 'string', 'className' => 'font-interbold text-xs' ],
                [ 'name' => 'Status','key' => 'status', 'type' => 'status' ],
                [ 'name' => 'Action', 'type' => 'action_status', 'ability' => array(
                    [ 'title' => 'Setujui', 'key' => 'setujui' ,'theme' => 'success', 'show_by' => 'm_status_tabs_id', 'show_value' => [1,10]],
                    [ 'title' => 'Bekukan', 'key' => 'bekukan' ,'theme' => 'error', 'show_by' => 'm_status_tabs_id', 'show_value' => [6]],
                    [ 'title' => 'Detail', 'key' => 'detail' ,'theme' => 'warning', 'show_by' => 'm_status_tabs_id', 'show_value' => [1,6,7,10]],
                )],
            )
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return $this->controller->respons(
            'FORM BAYARAN',
            array(
                [ 'key' => 'payment', 'payment' => null, 'type' => 'number', 'label' => 'Uang Masuk' ],
                [ 'key' => 'keterangan', 'keterangan' => null, 'type' => 'text', 'label' => 'Keterangan' ],
            )
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->controller->validasi($request->all(),[
            'payment' => 'required',
            't_krs_tabs_id' => 'required',
        ]);

        try {
            DB::beginTransaction();
            $request['code'] = MCodeTab::generateCode('PMT');
            $this->tKrsTagihanTabs->create($request->all());
            DB::commit();
            return $this->controller->respons("UPDATED", "Pembayaran baru berhasil di tambahkan", [
                'title' => "Pembayaran baru berhasil di tambahkan",
                'body' => 'Data pembayaran berhasil di perbaharui di system',
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
        return $this->controller->responsList(
            'RIWAYAT BAYAR',
            $this->tKrsTagihanTabs->where('t_krs_tabs_id', $id)->with('status')->orderBy('m_status_tabs_id','asc')->orderBy('id','desc')->paginate(10),
            array(
                [ 'name' => 'Code Bayar','key' => 'code', 'type' => 'string', 'className' => 'font-interbold text-xs' ],
                [ 'name' => 'Uang Masuk','key' => 'payment', 'type' => 'currency', 'className' => 'text-xs' ],
                [ 'name' => 'Tanggal','key' => 'created_at', 'type' => 'datetime', 'className' => 'text-xs' ],
                [ 'name' => 'Keterangan','key' => 'keterangan', 'type' => 'string', 'className' => 'text-xs' ],
                [ 'name' => 'Status','key' => 'status', 'type' => 'status' ],
                [ 'name' => 'Action', 'type' => 'action_status', 'ability' => array(
                    [ 'title' => 'Pulihkan', 'key' => 'valid' ,'theme' => 'success', 'show_by' => 'm_status_tabs_id', 'show_value' => [9]],
                    [ 'title' => 'Batalkan', 'key' => 'not_valid' ,'theme' => 'error', 'show_by' => 'm_status_tabs_id', 'show_value' => [8]],
                )],
            )
        );
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
        try {
            DB::beginTransaction();
            $this->tKrsTagihanTabs->where('id',$id)->update([
               'm_status_tabs_id' => $request->m_status_tabs_id === 8 ? 9 : 8
            ]);
            DB::commit();
            return $this->controller->respons("UPDATED", "Status Pembayaran berhasil di ubah", [
                'title' => "Status Pembayaran berhasil di ubah",
                'body' => 'Status pembayaran berhasil di perbaharui di system',
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
            $this->tKrsTagihanTabs->where('t_krs_tabs_id',$id)->update([
                'm_status_tabs_id' => 9
            ]);
            DB::commit();
            return $this->controller->respons("DELETED", "Tagihan berhasil di update", [
                'title' => "Riwayat Tagihan berhasil di batalkan",
                'body' => 'Data Riwayat Tagihan anda batalkan berhasil di udah pada system',
                'theme' => 'success'
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            abort(400, $th->getMessage());
        }
    }

    public function updateTagihan(Request $request){
        try {
            DB::beginTransaction();
            $this->tKrsTab->where('id',$request->id)->update([
                'tagihan' => $request->tagihan
            ]);
            DB::commit();
            return $this->controller->respons("UPDATED", "Tagihan berhasil di update", [
                'title' => "Tagihan berhasil di update",
                'body' => 'Data Tagihan anda berhasil di update pada system',
                'theme' =>'success'
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            abort(400, $th->getMessage());
        }
    }

    public function detailTagihan(Request $request){
        $krsTabs = $this->tKrsTab->find($request->id);
        $krsTagihan = $this->tKrsTagihanTabs->where('t_krs_tabs_id', $krsTabs->id)->where('m_status_tabs_id',8)->get();
        $payment = $krsTabs->tagihan - $krsTagihan->sum('payment');
        return $this->controller->respons("DETAIL TAGIHAN", [
            'status' => $payment < 0 ? 2 : ($payment === 0 ? 1 : 0),
            'tagihan' => $payment,
        ]);
    }

    public function menunggak(){
        $krstab = $this->tKrsTab->menunggak()->get();
        $arrays = array();
        foreach ($krstab as $value) {
            if($value->tagihan > (int) $value->uang_detail_sum || $value->m_status_tabs_id == 10 ) {
                array_push($arrays, $value);
            }
        }
        return $this->controller->respons("MENUNGGAK", $arrays);
    }

    public function infobayaran(){
        $semesterActive = $this->tMahasiswaSemesterTab
            ->where('t_mahasiswa_tabs_id', auth()->user()->id)
            ->where('active',1)
            ->with('semester')
            ->first();
        $krsmahasiswa = $this->tKrsTab
            ->where('t_mahasiswa_tabs_id', auth()->user()->id)
            ->where('t_mahasiswa_semester_tabs_id', $semesterActive->id)
            ->with('periode')
            ->first();
        if(isset($krsmahasiswa)) {
            $krsTagihan = $this->tKrsTagihanTabs->where('t_krs_tabs_id', $krsmahasiswa->id)
                ->where('m_status_tabs_id',8)->get();
            $payment = $krsmahasiswa->tagihan - $krsTagihan->sum('payment');
            return $this->controller->respons("INFO BAYARAN", [
                "sisa" => $payment,
                "semester" => $semesterActive,
                "periode" => $krsmahasiswa,
                "payment" => $krsTagihan->sum('payment'),
                "default" => $krsmahasiswa->tagihan,
            ]);
        } else {
            return $this->controller->respons("INFO BAYARAN", [
                "sisa" => 0,
                "payment" => 0,
                "default" => 0,
            ]);
        }
    }

    public function riwayatBayaran(){
        $tagihan = $this->tKrsTagihanTabs->with(['krs' => function($a){
            $a->with(['status','semester_mahasiswa' => function($a){
                $a->with(['semester']);
            }]);
        },'status'])->whereHas('krs', function($a){
            $a->where('t_mahasiswa_tabs_id', auth()->user()->id);
        })->paginate(20);
        
        return $this->controller->responsList(
            'RIWAYAT BAYAR',
            $tagihan,
            array(
                [ 'name' => 'Code Bayar','key' => 'code', 'type' => 'string', 'className' => 'font-interbold text-xs' ],
                [ 'name' => 'Semester','key' => 'krs.semester_mahasiswa.semester.title', 'type' => 'string', 'className' => 'text-xs' ],
                [ 'name' => 'Uang Masuk','key' => 'payment', 'type' => 'currency', 'className' => 'text-xs' ],
                [ 'name' => 'Tanggal','key' => 'created_at', 'type' => 'datetime', 'className' => 'text-xs' ],
                [ 'name' => 'Keterangan','key' => 'keterangan', 'type' => 'string', 'className' => 'text-xs' ],
                [ 'name' => 'Status','key' => 'status', 'type' => 'status', ],
            )
        );
    }
}

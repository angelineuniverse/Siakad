<?php

namespace Modules\Mahasiswa\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Modules\Mahasiswa\Models\TMahasiswaPeriodeTabs;
use Modules\Master\Models\MCodeTab;
use Modules\Master\Models\MStatusTab;

class MahasiswaPeriodeController extends Controller
{
    protected $controller;
    protected $mStatusTab;
    protected $tMahasiswaPeriodeTabs;
    public function __construct(
        Controller $controller,
        MStatusTab $mStatusTab,
        TMahasiswaPeriodeTabs $tMahasiswaPeriodeTabs,
    ) {
        $this->controller = $controller;
        $this->mStatusTab = $mStatusTab;
        $this->tMahasiswaPeriodeTabs = $tMahasiswaPeriodeTabs;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return $this->controller->responsList(
            'MAHASISWA PERIODE ALL',
            $this->tMahasiswaPeriodeTabs->with('status')->paginate(10),
            array(
                [ 'name' => 'Nama Periode','key' => 'title', 'type' => 'string', 'className' => 'font-interbold' ],
                [ 'name' => 'Mulai','key' => 'start', 'type' => 'string' ],
                [ 'name' => 'Selesai','key' => 'end', 'type' => 'string' ],
                [ 'name' => 'Status','key' => 'custom_status', 'type' => 'custom' ],
                [ 'type' => 'action', 'ability' => ['SHOW','EDIT','DELETE']]
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
            'title' => 'required|unique:t_mahasiswa_periode_tabs,title',
            'start' => 'required',
            'end' => 'required',
        ]);

        try {
            DB::beginTransaction();
            $request['m_status_tabs_id'] = 1;
            $this->tMahasiswaPeriodeTabs->create($request->all());
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
        return $this->controller->respons("PERIODE", $this->tMahasiswaPeriodeTabs->where('id', $id)->with('mahasiswa')->first());
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $detail = $this->tMahasiswaPeriodeTabs->where('id', $id)->first();
        return $this->controller->respons(
            'FORM EDIT',
            array(
                [ 'key' => 'title', 'title' => $detail->title, 'type' => 'text','label' => 'Nama Periode', 'isRequired' => true ],
                [ 'key' => 'start', 'start' => $detail->start, 'type' => 'date','label' => 'Mulai Periode', 'isRequired' => true ],
                [ 'key' => 'end', 'end' => $detail->end, 'type' => 'date','label' => 'Selesai Periode', 'isRequired' => true ],
                [ 'key' => 'm_status_tabs_id', 'm_status_tabs_id' => $detail->m_status_tabs_id, 'type' => 'select',
                    'placeholder' => $this->mStatusTab->where('id',$detail->m_status_tabs_id)->pluck('title')->first(),
                    'label' => 'Status', 'isRequired' => true,
                    'list' => [
                        'options' => $this->mStatusTab->whereIn('id',[1,2,3])->get(),
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
            $this->tMahasiswaPeriodeTabs->where('id',$id)->update($request->all());
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
            $this->tMahasiswaPeriodeTabs->where('id',$id)->delete();
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

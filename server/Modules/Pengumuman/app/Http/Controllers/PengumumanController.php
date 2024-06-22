<?php

namespace Modules\Pengumuman\Http\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Modules\Master\Models\MCodeTab;
use Modules\Master\Models\MStatusTab;
use Modules\Pengumuman\Models\TPengumumanTab;

class PengumumanController extends Controller
{
    protected $controller;
    protected $tPengumumanTab;
    protected $mGenderTab;
    protected $mFakultasTab;
    protected $mJurusanTab;
    protected $mStatusTab;
    public function __construct(
        Controller $controller,
        TPengumumanTab $tPengumumanTab,
        MStatusTab $mStatusTab
    ) {
        $this->controller = $controller;
        $this->tPengumumanTab = $tPengumumanTab;
        $this->mStatusTab = $mStatusTab;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return $this->controller->responsList(
            'DOSEN ALL',
            $this->tPengumumanTab->detail($request)->paginate(10),
            array(
                [ 'name' => 'Code','key' => 'code', 'type' => 'string' ],
                [ 'name' => 'Informasi','key' => 'informasi' ,'type' => 'array', 'child' => array(
                        ['key' => 'title', 'type' => 'string', 'className' => 'max-w-96 font-interbold text-sm'],
                        ['key' => 'description', 'type' => 'string', 'className' => 'max-w-96 text-xs font-interregular'],
                    )
                ],
                [ 'name' => 'File','key' => 'file', 'type' => 'custom' ],
                [ 'name' => 'Start Date','key' => 'start_date', 'type' => 'datetime', 'className' => 'w-44 text-pretty' ],
                [ 'name' => 'End Date','key' => 'end_date', 'type' => 'datetime', 'className' => 'w-44 text-pretty' ],
                [ 'name' => 'Status','key' => 'active', 'type' => 'custom' ],
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
                [ 'key' => 'title', 'title' => null, 'type' => 'text','label' => 'Judul', 'isRequired' => true ],
                [ 'key' => 'deskripsi', 'deskripsi' => null, 'autosize' => true , 'type' => 'textarea', 'label' => 'Deskripsi', 'isRequired' => true ],
                [ 'key' => 'start_date', 'start_date' => null, 'type' => 'datetime-local','label' => 'Tanggal Mulai', 'isRequired' => true ],
                [ 'key' => 'end_date', 'end_date' => null, 'type' => 'datetime-local','label' => 'Tanggal Selesai', 'isRequired' => true ],
                [ 'key' => 'file', 'file' => null, 'type' => 'upload', 'accept' => 'aplication/*' ,'label' => 'File' ],
            )
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->controller->validasi($request->all(),[
            'title' => 'required',
            'deskripsi' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
        ]);

        try {
            DB::beginTransaction();
            $request['code'] = MCodeTab::generateCode('PGM');
            $request['description'] = $request->deskripsi;
            $request['t_admin_tabs_id'] = auth()->user()->id;
            $tPengumumanTab = $this->tPengumumanTab->create($request->all());
            if ($request->hasFile('file')){
                $file = $request->file('file');
                $filename = $request->code.'.'.$file->getClientOriginalExtension();
                $this->controller->unlinkFile('files', $filename);
                $file->move(public_path('files'), $filename);
                $tPengumumanTab->update([
                    'file' => $filename
                ]);
            }
            DB::commit();
            return $this->controller->respons("Pengumuman CREATED", "Pengumuman baru berhasil ditambahkan", [
                'title' => "Pengumuman baru berhasil ditambahkan",
                'body' => 'Data akun yang anda buat berhasil di tambahkan ke system',
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
        return $this->controller->respons('LAST PENGUMUMAN', $this->tPengumumanTab->orderBy('id','desc')->limit(2)->get());
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $detail = $this->tPengumumanTab->where('id', $id)->first();
        return $this->controller->respons(
            'FORM EDIT',
            array(
                [ 'key' => 'code', 'code' => $detail->code, 'type' => 'text','label' => 'Code Dosen', 'readonly' => true ],
                [ 'key' => 'title', 'title' => $detail->title, 'type' => 'text','label' => 'Judul', 'isRequired' => true ],
                [ 'key' => 'deskripsi', 'deskripsi' => $detail->description, 'type' => 'textarea', 'label' => 'Deskripsi', 'isRequired' => true ],
                [ 'key' => 'start_date', 'start_date' => $detail->start_date, 'type' => 'datetime-local', 'label' => 'Tanggal Mulai','isRequired' => true ],
                [ 'key' => 'end_date', 'end_date' => $detail->end_date, 'type' => 'datetime-local', 'label' => 'Tanggal Selesai','isRequired' => true ],
                [ 'key' => 'file', 'file' => $detail->file, 'filename' => $detail->file ,'type' => 'upload', 'accept' => 'aplication/*' ,'label' => 'File', 'isRequired' => true ],
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
            'deskripsi' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
        ]);

        try {
            DB::beginTransaction();
            $tPengumumanTab = $this->tPengumumanTab->where('id',$id)->first();
            $tPengumumanTab->update($request->all());
            if ($request->hasFile('file')){
                $file = $request->file('file');
                $filename = $request->code.'.'.$file->getClientOriginalExtension();
                $this->controller->unlinkFile('files', $filename);
                $file->move(public_path('files'), $filename);
                $tPengumumanTab->update([
                    'file' => $filename
                ]);
            }
            DB::commit();
            return $this->controller->respons("Pengumuman UPDATED", "Pengumuman berhasil di update", [
                'title' => "Informasi Pengumuman berhasil diubah",
                'body' => 'Data Pengumuman berhasil di perbaharui di system',
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
            $tPengumumanTab = $this->tPengumumanTab->where('id',$id)->first();
            $this->controller->unlinkFile('files', $tPengumumanTab->file);
            $tPengumumanTab->delete();
            DB::commit();
            return $this->controller->respons("Pengumuman DELETED", "Pengumuman berhasil di hapus", [
                'title' => "Menghapus Pengumuman berhasil",
                'body' => 'Data Pengumuman yang anda hapus berhasil di bersihkan dari system',
                'theme' => 'success'
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            abort(400, $th->getMessage());
        }
    }

    public function pengumumanUser(){
        return $this->controller->respons(
            'LAST PENGUMUMAN', 
            $this->tPengumumanTab
                ->where('start_date','<',Carbon::now())
                ->where('end_date','>',Carbon::now())
                ->where('active',1)
                ->orderBy('id','desc')
                ->get()
        );
    }
}

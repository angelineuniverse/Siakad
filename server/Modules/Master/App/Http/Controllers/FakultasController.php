<?php

namespace Modules\Master\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Modules\Master\App\Models\MFakultasTab;
use Modules\Master\App\Models\MGenerateCodeTab;

class FakultasController extends Controller
{
    protected $controller;
    protected $mFakultasTab;
    protected $mGenerateCodeTab;
    public function __construct(
        Controller $controller,
        MFakultasTab $mFakultasTab,
        MGenerateCodeTab $mGenerateCodeTab
    ) {
        $this->controller = $controller;
        $this->mGenerateCodeTab = $mGenerateCodeTab;
        $this->mFakultasTab = $mFakultasTab;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->controller->responses('JURUSAN ALL',200, $this->mFakultasTab->all(),null);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('master::create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->controller->validasi($request,[
            'title' => 'required|unique:m_fakultas_tabs,title',
        ]);

        try {
            DB::beginTransaction();
            $request['code'] = $this->mGenerateCodeTab->generateCode('FKS');
            $fakultas =  $this->mFakultasTab->create($request->all());
            DB::commit();
            return $this->controller->responses(
                'FAKULTAS CREATE',
                200,
                $fakultas,
                [
                    'type' => 'success',
                    'title' => 'Fakultas Created Success',
                    'message' => 'New Fakultas succesfully created',
                ]
            );
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
        return view('master::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('master::edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $this->controller->validasi($request,[
            'title' => 'required'
        ]);
        try {
            DB::beginTransaction();
            $fakultas =  $this->mFakultasTab->where('id',$id)->update($request->all());
            DB::commit();
            return $this->controller->responses(
                'FAKULTAS UPDATED',
                200,
                $fakultas,
                [
                    'type' => 'success',
                    'title' => 'Fakultas Updated Success',
                    'message' => 'New Fakultas succesfully Updated',
                ]
            );
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
        $fakultas = $this->mFakultasTab->find($id);
        try {
            DB::beginTransaction();
            $fakultas->delete();
            DB::commit();
            return $this->controller->responses(
                "FAKULTAS DELETE",
                200,
                $fakultas,
                [
                    'type' => 'success',
                    'title' => 'Fakultas Delete Success',
                    'message' => 'New Fakultas succesfully deleted',
                ]
            );
        } catch (\Throwable $th) {
            DB::rollBack();
            abort(400, $th->getMessage());
        }
    }
}

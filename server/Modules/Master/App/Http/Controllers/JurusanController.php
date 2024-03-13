<?php

namespace Modules\Master\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Modules\Master\App\Models\MGenerateCodeTab;
use Modules\Master\App\Models\MJurusanTab;

class JurusanController extends Controller
{

    protected $mJurusanTab;
    protected $controller;
    protected $mGenerateCodeTab;
    public function __construct(
        MJurusanTab $mJurusanTab, 
        MGenerateCodeTab $mGenerateCodeTab,
        Controller $controller) {
        $this->mJurusanTab = $mJurusanTab;
        $this->mGenerateCodeTab = $mGenerateCodeTab;
        $this->controller = $controller;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->controller->responses('JURUSAN ALL',200, $this->mJurusanTab->all(),null);
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
            'title' => 'required'
        ]);

        try {
            DB::beginTransaction();
            $request['code'] = $this->mGenerateCodeTab->generateCode('JRS');
            $jurusan = $this->mJurusanTab->create($request->all());
            DB::commit();
            return $this->controller->responses(
                'JURUSAN CREATE',
                200,
                $jurusan,
                [
                    'type' => 'success',
                    'title' => 'Jurusan Created Success',
                    'message' => 'New Jurusan succesfully created',
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
            $jurusan =  $this->mJurusanTab->where('id',$id)->update($request->all());
            DB::commit();
            return $this->controller->responses(
                'JURUSAN UPDATED',
                200,
                $jurusan,
                [
                    'type' => 'success',
                    'title' => 'Jurusan Updated Success',
                    'message' => 'New Jurusan succesfully Updated',
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
        $jurusan = $this->mJurusanTab->find($id);
        try {
            DB::beginTransaction();
            $jurusan->delete();
            DB::commit();
            return $this->controller->responses(
                "JURUSAN DELETE",
                200,
                $jurusan,
                [
                    'type' => 'success',
                    'title' => 'Jurusan Delete Success',
                    'message' => 'New Jurusan succesfully deleted',
                ]
            );
        } catch (\Throwable $th) {
            DB::rollBack();
            abort(400, $th->getMessage());
        }
    }
}

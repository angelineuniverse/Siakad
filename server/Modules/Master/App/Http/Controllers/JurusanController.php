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

    protected $jurusan;
    protected $controller;
    protected $mGenerateCodeTab;
    public function __construct(
        MJurusanTab $mJurusanTab, 
        MGenerateCodeTab $mGenerateCodeTab,
        Controller $controller) {
        $this->jurusan = $mJurusanTab;
        $this->mGenerateCodeTab = $mGenerateCodeTab;
        $this->controller = $controller;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->controller->responses('JURUSAN ALL',200, $this->jurusan->all(),null);
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
            $request['code'] = $this->mGenerateCodeTab->generateCode('JSN');
            $this->jurusan->create($request->all());
            DB::commit();
            return $this->controller->responses('JURUSAN ADD',200, null,null);
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
    }
}

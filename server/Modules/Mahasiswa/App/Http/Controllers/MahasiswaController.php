<?php

namespace Modules\Mahasiswa\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Modules\Mahasiswa\App\Models\TMahasiswaTab;
use Modules\Master\App\Models\MGenerateCodeTab;
use Modules\Master\App\Models\MGenerateNimTab;

class MahasiswaController extends Controller
{
    protected $tMahasiswaTab;
    protected $controller;
    protected $mGenerateCodeTab;
    protected $mGenerateNimTab;
    public function __construct(
        TMahasiswaTab $tMahasiswaTab,
        Controller $controller,
        MGenerateCodeTab $mGenerateCodeTab,
        MGenerateNimTab $mGenerateNimTab
    ) {
        $this->tMahasiswaTab = $tMahasiswaTab;
        $this->mGenerateCodeTab = $mGenerateCodeTab;
        $this->mGenerateNimTab = $mGenerateNimTab;
        $this->controller = $controller;
    }


    public function login(Request $request)
    {
        $this->controller->validasi($request,[
            'nim' => 'required|exists:t_mahasiswa_tabs,nim',
            'password' => 'required|min:6',
        ]);
        if(!Auth::attempt($request->only('nim','password'))){
            abort(400, "Mohon maaf akun anda tidak ditemukan");
        }
        try {
            DB::beginTransaction();
            $users = $this->tMahasiswaTab->where('nim', $request->nim)->first();
            $token = $users->createToken('siakad',['mahasiswa'])->plainTextToken;
            DB::commit();
            return $this->controller->responses('USERS FIND', 200, [
                'token' => $token
            ],null);
        } catch (\Throwable $th) {
            DB::rollBack();
            abort(401, $th->getMessage());
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('user::index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('user::create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->controller->validasi($request,[
            'email' => 'required|email|unique:t_mahasiswa_tabs,email',
            'name' => 'required',
            'm_mahasiswa_register_type_tabs_id' => 'required',
            'm_mahasiswa_register_enroll_tabs_id' => 'required',
            'fakultas' => 'required',
            'jurusan' => 'required',
            'password' => 'required|min:6',
        ]);

        try {
            DB::beginTransaction();
            $request['password'] = Hash::make($request->password);
            $request['code'] = $this->mGenerateCodeTab->generateCode('MHS');
            $request['nim'] = $this->mGenerateNimTab->generateCode($request->jurusan);
            $mahasiswa = $this->tMahasiswaTab->create($request->all());
            DB::commit();
            return $this->controller->responses(
                'MAHASISWA CREATED',200,
                $mahasiswa,
                [
                    'title' => "Mahasiswa berhasil di buat",
                    'body' => "Selamat Mahasiswa baru untuk Siakad tersedia, akun baru berhasil dibuat.",
                    'type' => 'success'
                ]
            );
        } catch (\Throwable $th) {
            DB::rollBack(); // tarik kembali data
            abort(400, $th->getMessage());
        }
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return $this->controller->responses("ME DETAIL",200, auth()->user(),null);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('user::edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $this->controller->validasi($request,[
            'email' => 'required|email|unique:t_mahasiswa_tabs,email',
            'name' => 'required',
        ]);

        try {
            DB::beginTransaction();
            if(isset($request->password)){
                $request['password'] = Hash::make($request->password);
            }
            $this->tMahasiswaTab->where($id)->update($request->all());
            DB::commit();
            return $this->controller->responses('USER UPDATED',200, 'USER UPDADED SUCCESSFULLY',null);
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
        //
    }
}

<?php

namespace Modules\User\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Modules\Master\App\Models\MGenerateCodeTab;
use Modules\Master\App\Models\MGenerateNimTab;
use Modules\User\App\Models\UsersTab;

class UserController extends Controller
{
    protected $usersTab;
    protected $controller;
    protected $mGenerateCodeTab;
    protected $mGenerateNimTab;
    public function __construct(
        UsersTab $user, Controller $controller,
        MGenerateCodeTab $mGenerateCodeTab,
        MGenerateNimTab $mGenerateNimTab
    ) {
        $this->usersTab = $user;
        $this->mGenerateCodeTab = $mGenerateCodeTab;
        $this->mGenerateNimTab = $mGenerateNimTab;
        $this->controller = $controller;
    }

    public function login(Request $request){
        $this->controller->validasi($request,[
            'email' => 'required|exists:users_tabs,email',
            'password' => 'required|min:6',
        ]);

        if(!Auth::attempt($request->only('email','password'))){
            abort(400, "Mohon maaf akun anda tidak ditemukan");
        }
        try {
            $users = $this->usersTab->where('email', $request->email)
                    ->where('deleted','=',0)
                    ->first();
            $token = $users->createToken('siakad')->plainTextToken;
            return $this->controller->responses('USERS FIND', 200, [
                'token' => $token
            ],null);
        } catch (\Throwable $th) {
            abort(401,$th->getMessage());
        }
    }
    
    public function register(Request $request){
        $this->controller->validasi($request,[
            'email' => 'required|email|unique:users_tabs,email',
            'name' => 'required',
            'password' => 'required|min:6',
        ]);

        try {
            DB::beginTransaction();
            $request['password'] = Hash::make($request->password);
            $request['code'] = $this->mGenerateCodeTab->generateCode('USR');
            $user = $this->usersTab->create($request->all());
            DB::commit();
            return $this->controller->responses(
                'USER CREATED',200,
                [
                    'token' => $user->createToken('siakad')->plainTextToken
                ],
                [
                    'title' => "User berhasil di buat",
                    'body' => "Selamat datang di Angeline Siakad, akun baru berhasil dibuat.",
                    'type' => 'success'
                ]
            );
        } catch (\Throwable $th) {
            DB::rollBack(); // tarik kembali data
            abort(400, $th->getMessage());
        }
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->controller->responses('USERS ALL',200,$this->usersTab->all(),null);
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
        //
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return $this->controller->responses("USERS DETAIL",200,auth()->user(),null);
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
            'email' => 'required|exists:users_tabs,email'
        ]);


        try {
            DB::beginTransaction();
            if(isset($request->password)){ // mengecek apakah key/nilai nya ada ?
                $request['password'] = Hash::make($request->password);
            }
            $users = $this->usersTab->where('id', auth()->user()->id)->update($request->all());
            DB::commit();
            return $this->controller->responses('USERS UPDATE', 200, $users,null);
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
        auth()->user()->currentAccessToken()->delete();
        return $this->controller->responses('USERS LOGOUT',200,true,null);
    }
}

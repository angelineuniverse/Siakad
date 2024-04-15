<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Modules\Admin\Emails\MailActivatedAdmin;
use Modules\Admin\Models\TAdminTab;

class AdminController extends Controller
{

    protected $controller;
    protected $tAdminTab;
    public function __construct(
        Controller $controller,
        TAdminTab $tAdminTab
    ) {
        $this->controller = $controller;
        $this->tAdminTab = $tAdminTab;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->controller->responsList(
            'USER ALL',
            $this->tAdminTab->paginate(10),
            array(
                [ 'name' => 'Informasi','type' => 'array', 'child' => array(
                        ['key' => 'name', 'type' => 'string', 'className' => 'font-interbold text-sm'],
                        ['key' => 'email', 'type' => 'string', 'className' => 'italic font-interregular'],
                    )
                ],
                [ 'name' => 'Phone','key' => 'phone', 'type' => 'string' ],
                [ 'name' => 'Status Akun','key' => 'status_active', 'type' => 'string', 
                    'className' => 'uppercase text-center font-intersemibold' ],
                [ 'type' => 'action', 'ability' => ['EDIT','DELETE']]
            )
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin::create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->controller->validasi($request->all(),[
            'name' => 'required',
            'email' => 'required|email|unique:t_admin_tabs,email',
            'password' => 'required|min:6',
        ]);

        try {
            DB::beginTransaction();
            $request['password'] = Hash::make($request->password);
            $admin = $this->tAdminTab->create($request->all());
            Mail::to($request->email)->send(new MailActivatedAdmin($admin));
            DB::commit();
            return $this->controller->respons("USER CREATED", "Admin baru berhasil ditambahkan", [
                'title' => "Admin baru berhasil ditambahkan",
                'body' => 'Data akun yang anda buat berhasil di tambahkan ke system',
                'theme' => 'success'
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            abort(400, $th->getMessage());
        }
    }

    public function login(Request $request){
        $this->controller->validasi($request->all(),[
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        try {
            $credential = array(
                'email' => $request->email,
                'password' => $request->password
            );
            if(!Auth::attempt($credential)){
                abort(400, "Data akun yang anda masukan tidak cocok");
            }
            $user = $this->tAdminTab->where('email', $request->email)->first();
            $token = $user->createToken('4ngel1n3',['admin'])->plainTextToken;
            return $this->controller->respons("User Login", [
                'token' => $token
            ]);
        } catch (\Throwable $th) {
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
        return view('admin::edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $this->controller->validasi($request->all(),[
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        try {
            DB::beginTransaction();
            $request['password'] = Hash::make($request->password);
            $this->tAdminTab->where('id',$id)->update($request->all());
            DB::commit();
            return $this->controller->respons("USER UPDATED", "Data berhasil di update", [
                'title' => "Informasi akun berhasil diubah",
                'body' => 'Data akun berhasil di perbaharui di system',
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
            $this->tAdminTab->where('id',$id)->delete();
            DB::commit();
            return $this->controller->respons("USER DELETED", "Akun berhasil di hapus", [
                'title' => "Menghapus akun berhasil",
                'body' => 'Data akun yang anda hapus berhasil di bersihkan dari system',
                'theme' => 'success'
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            abort(400, $th->getMessage());
        }
    }

    public function logout(){
        auth()->user()->currentAccessToken()->delete();
        return $this->controller->respons('LOGOUT USER', 'Anda berhasil keluar !');
    }

    public function activatedAccount(){
        try {
            DB::beginTransaction();
            if(auth()->user()){
                $this->tAdminTab->where('id', auth()->user()->id)->update([
                    'active' => 1
                ]);
                DB::commit();
                return $this->controller->respons('ACTIVATED USER',"Akun berhasil di aktivasi", [
                    'title' => "Aktivasi akun berhasil",
                    'body' => 'Data akun anda telah berhasil di aktivasi',
                    'theme' => 'success'
                ]);
            }
            return $this->controller->respons('FAILURE ACTIVATED USER',"Akun gagal di aktivasi", [
                    'title' => "Aktivasi akun gagal",
                    'body' => 'Data akun anda tidak ditemukan dan aktivasi akun gagal',
                    'theme' => 'error'
                ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            abort(400, $th->getMessage());
        }
    }
}

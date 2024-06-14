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
use Modules\Master\Models\MRoleTab;

class AdminController extends Controller
{

    protected $controller;
    protected $tAdminTab;
    protected $mRoleTab;
    public function __construct(
        Controller $controller,
        TAdminTab $tAdminTab,
        MRoleTab $mRoleTab
    ) {
        $this->controller = $controller;
        $this->tAdminTab = $tAdminTab;
        $this->mRoleTab = $mRoleTab;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->controller->responsList(
            'USER ALL',
            $this->tAdminTab->latest()->paginate(10),
            array(
                [ 'name' => 'Informasi','type' => 'array', 'child' => array(
                        ['key' => 'name', 'type' => 'string', 'className' => 'font-interbold text-sm'],
                        ['key' => 'email', 'type' => 'string', 'className' => 'italic font-interregular'],
                    )
                ],
                [ 'name' => 'Phone','key' => 'phone', 'type' => 'string' ],
                [ 'name' => 'Role','key' => 'role', 'type' => 'string' ],
                [ 'name' => 'Status Akun','key' => 'custom_status', 'type' => 'custom' ],
                [ 'type' => 'action', 'ability' => ['EDIT','DELETE']]
            )
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $role = $this->mRoleTab->where('id','>',auth()->user()->m_role_tab_id)->get();
        return $this->controller->respons(
            'FORM CREATE',
            array(
                [ 'key' => 'name', 'name' => null, 'type' => 'text','label' => 'Nama', 'isRequired' => true ],
                [ 'key' => 'email', 'email' => null, 'type' => 'text', 'label' => 'Email', 'isRequired' => true ],
                [ 'key' => 'password', 'password' => null, 'type' => 'password', 'label' => 'Password', 'isRequired' => true ],
                [ 'key' => 'phone', 'phone' => null, 'type' => 'number', 'label' => 'Whatsapp' ],
                [ 'key' => 'm_role_tab_id', 'm_role_tab_id' => null, 'type' => 'select', 'label' => 'Role', 'isRequired' => true,
                    'list' => [
                        'options' => $role,
                        'keyValue' => 'id',
                        'keyoption' => 'title'
                    ]
                ]
            )
        );
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
        $detail = $this->tAdminTab->where('id', $id)->first();
        $role = $this->mRoleTab->where('id','>=',auth()->user()->m_role_tab_id)->get();
        return $this->controller->respons(
            'FORM EDIT',
            array(
                [ 'key' => 'name', 'name' => $detail->name, 'type' => 'text','label' => 'Nama', 'isRequired' => true ],
                [ 'key' => 'email', 'email' => $detail->email, 'type' => 'text', 'label' => 'Email', 'isRequired' => true ],
                [ 'key' => 'phone', 'phone' => $detail->phone, 'type' => 'number', 'label' => 'Whatsapp' ],
                [ 'key' => 'm_role_tab_id', 'm_role_tab_id' => null, 
                    'placeholder' => $this->mRoleTab->where('id',$detail->m_role_tab_id)->pluck('title')->first(),
                    'type' => 'select', 'label' => 'Role', 'isRequired' => true,
                    'list' => [
                        'options' => $role,
                        'keyValue' => 'id',
                        'keyoption' => 'title'
                    ]
                ]
            )
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $this->controller->validasi($request->all(),[
            'name' => 'required',
            'email' => 'required|email',
        ]);

        try {
            DB::beginTransaction();
            if(isset($request->password)) {$request['password'] = Hash::make($request->password);}
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

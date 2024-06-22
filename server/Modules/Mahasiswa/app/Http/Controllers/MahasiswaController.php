<?php

namespace Modules\Mahasiswa\Http\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Modules\Mahasiswa\Models\TMahasiswaDetailTab;
use Modules\Mahasiswa\Models\TMahasiswaPeriodeTabs;
use Modules\Mahasiswa\Models\TMahasiswaSemesterTab;
use Modules\Mahasiswa\Models\TMahasiswaTab;
use Modules\Master\Models\MBloodTab;
use Modules\Master\Models\MCityTab;
use Modules\Master\Models\MFakultasTab;
use Modules\Master\Models\MGenderTab;
use Modules\Master\Models\MJurusanTab;
use Modules\Master\Models\MMarriedTab;
use Modules\Master\Models\MProvinceTab;
use Modules\Master\Models\MRegisterEnrollTab;
use Modules\Master\Models\MRegisterTypeTab;
use Modules\Master\Models\MReligionTab;
use Modules\Master\Models\MStatusTab;

class MahasiswaController extends Controller
{
    protected $controller;
    protected $tMahasiswaTab;
    protected $tMahasiswaDetailTab;
    protected $tMahasiswaSemesterTab;
    protected $mFakultasTab;
    protected $mJurusanTab;
    protected $mStatusTab;
    public function __construct(
        Controller $controller,
        TMahasiswaTab $tMahasiswaTab,
        TMahasiswaDetailTab $tMahasiswaDetailTab,
        TMahasiswaSemesterTab $tMahasiswaSemesterTab,
        MFakultasTab $mFakultasTab,
        MJurusanTab $mJurusanTab,
        MStatusTab $mStatusTab
    ) {
        $this->controller = $controller;
        $this->tMahasiswaTab = $tMahasiswaTab;
        $this->tMahasiswaDetailTab = $tMahasiswaDetailTab;
        $this->tMahasiswaSemesterTab = $tMahasiswaSemesterTab;
        $this->mFakultasTab = $mFakultasTab;
        $this->mJurusanTab = $mJurusanTab;
        $this->mStatusTab = $mStatusTab;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return $this->controller->responsList(
            'MAHASISWA ALL',
            $this->tMahasiswaTab->where('deleted',0)->detail($request)->paginate(10),
            array(
                [ 'name' => 'Informasi','key' => 'informasi' ,'type' => 'array', 'child' => array(
                        ['key' => 'name', 'type' => 'string', 'className' => 'text-sm font-interbold'],
                        ['key' => 'nim', 'type' => 'string', 'className' => 'font-interregular text-xs'],
                        ['key' => 'email', 'type' => 'string', 'className' => 'font-interregular italic mt-1 text-xs'],
                    )
                ],
                [ 'name' => 'Jurusan', 'key' => 'jurusan' ,'type' => 'array','child' => array(
                        ['key' => 'jurusan.title', 'type' => 'string', 'className' => 'font-interbold text-sm'],
                        ['key' => 'fakultas.title', 'type' => 'string', 'className' => 'font-interregular'],
                    )
                ],
                [ 'name' => 'Semester', 'key' => 'semester' ,'type' => 'object','child' => array(
                        ['key' => 'semester_active.semester.title', 'type' => 'string', 'className' => 'font-interbold text-xs'],
                        ['key' => 'semester_active.semester_periode.title', 'type' => 'string', 'className' => 'italic font-interregular'],
                    )
                ],
                [ 'name' => 'Terdaftar','key' => 'terdaftar' ,'type' => 'array', 'child' => array(
                    [ 'key' => 'date_in', 'type' => 'date', 'className' => 'italic font-interregular' ],
                    [ 'key' => 'curiculum', 'type' => 'date-prefix-custom', 'prefix' => 'Curiculum ' ,'dateFormat' => 'MMMM yyyy' ,'className' => 'italic font-intermedium' ],
                )
                ],
                [ 'name' => 'IPK','key' => 'bobot_sks', 'type' => 'string' ],
                [ 'name' => 'Status','key' => 'custom_active', 'type' => 'custom' ],
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
            [
                'basic' => array(
                    [ 'key' => 'name', 'name' => null, 'type' => 'text','label' => 'Nama Mahasiswa', 'isRequired' => true ],
                    [ 'key' => 'email', 'email' => null, 'type' => 'text','label' => 'Email Mahasiswa', 'isRequired' => true ],
                    [ 'key' => 'curiculum', 'curiculum' => null, 'type' => 'month' ,'label' => 'Tahun Curiculum', 'isRequired' => true ],
                    [ 'key' => 'date_in', 'date_in' => null, 'type' => 'date' ,'label' => 'Tanggal Masuk', 'isRequired' => true ],
                    [ 'key' => 'm_register_type_tabs_id', 'm_register_type_tabs_id' => null, 
                        'type' => 'select' ,'label' => 'Jenis Pendaftaran', 
                        'isRequired' => true,
                        'list' => [
                            'options' => MRegisterTypeTab::all(),
                            'keyValue' => 'id',
                            'keyoption' => 'title'
                        ]
                    ],
                    [ 'key' => 'm_register_enroll_tabs_id', 'm_register_enroll_tabs_id' => null, 
                        'type' => 'select' ,'label' => 'Jalur Pendaftaran', 
                        'isRequired' => true,
                        'list' => [
                            'options' => MRegisterEnrollTab::all(),
                            'keyValue' => 'id',
                            'keyoption' => 'title'
                        ]
                    ],
                    [ 'key' => 'm_fakultas_tabs_id', 'm_fakultas_tabs_id' => null, 
                        'type' => 'select' ,'label' => 'Fakultas', 
                        'isRequired' => true,
                        'list' => [
                            'options' => MFakultasTab::where('active',1)->get(),
                            'keyValue' => 'id',
                            'keyoption' => 'title'
                        ]
                    ],
                    [ 'key' => 'm_jurusan_tabs_id', 'm_jurusan_tabs_id' => null, 
                        'type' => 'select' ,'label' => 'Jurusan', 
                        'isRequired' => true,
                        'list' => [
                            'options' => MJurusanTab::where('active',1)->get(),
                            'keyValue' => 'id',
                            'keyoption' => 'title'
                        ]
                    ],
                    [ 'key' => 'foto', 'foto' => null, 'type' => 'upload', 'accept' => 'image/*' ,'label' => 'Foto Mahasiswa' ],
                ),
                'detail' => array(
                    [ 'key' => 'birthday_city', 'birthday_city' => null, 'type' => 'text','label' => 'Kota Lahir', 'isRequired' => true ],
                    [ 'key' => 'birthday_date', 'birthday_date' => null, 'type' => 'date','label' => 'Tanggal Lahir', 'isRequired' => true ],
                    [ 'key' => 'no_nik', 'no_nik' => null, 'type' => 'number','label' => 'NIK', 'isRequired' => true ],
                    [ 'key' => 'no_kk', 'no_kk' => null, 'type' => 'number','label' => 'No Kartu Keluarga', 'isRequired' => true ],
                    [ 'key' => 'phone', 'phone' => null, 'type' => 'number','label' => 'No Whatsapp', 'isRequired' => true ],
                    [ 'key' => 'm_gender_tabs_id', 'm_gender_tabs_id' => null, 
                        'type' => 'select' ,'label' => 'Jenis Kelamin', 'isRequired' => true,
                        'list' => [
                            'options' => MGenderTab::all(),
                            'keyValue' => 'id',
                            'keyoption' => 'title'
                        ]
                    ],
                    [ 'key' => 'm_religion_tabs_id', 'm_religion_tabs_id' => null, 
                        'type' => 'select' ,'label' => 'Agama', 'isRequired' => true,
                        'list' => [
                            'options' => MReligionTab::all(),
                            'keyValue' => 'id',
                            'keyoption' => 'title'
                        ]
                    ],
                    [ 'key' => 'm_married_tabs_id', 'm_married_tabs_id' => null, 
                        'type' => 'select' ,'label' => 'Status Perkawinan',
                        'list' => [
                            'options' => MMarriedTab::all(),
                            'keyValue' => 'id',
                            'keyoption' => 'title'
                        ]
                    ],
                    [ 'key' => 'm_blood_tabs_id', 'm_blood_tabs_id' => null, 
                        'type' => 'select' ,'label' => 'Golongan Darah',
                        'list' => [
                            'options' => MBloodTab::all(),
                            'keyValue' => 'id',
                            'keyoption' => 'title'
                        ]
                    ],
                    [ 'key' => 'address', 'address' => null, 'type' => 'textarea','label' => 'Alamat Lengkap'],
                )
            ]
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->controller->validasi($request->all(),[
            't_mahasiswa_periode_tabs_id' => 'required',
            'name' => 'required',
            'email' => 'required',
            'curiculum' => 'required',
            'date_in' => 'required',
            'foto' => 'required',
            'm_fakultas_tabs_id' => 'required',
            'm_jurusan_tabs_id' => 'required',
            'm_register_type_tabs_id' => 'required',
            'm_register_enroll_tabs_id' => 'required',
            'birthday_city' => 'required',
            'birthday_date' => 'required',
            'no_nik' => 'required',
            'no_kk' => 'required',
            'm_gender_tabs_id' => 'required',
            'm_religion_tabs_id' => 'required',
            'm_blood_tabs_id' => 'required',
            'm_married_tabs_id' => 'required',
            'address' => 'required',
        ]);

        try {
            DB::beginTransaction();
            $request['nim'] = $this->tMahasiswaTab->generateNim(
                $request->t_mahasiswa_periode_tabs_id,
                $request->m_jurusan_tabs_id,
                $request->date_in
            );
            $request['password'] = Hash::make(123123);
            // Create Mahasiswa
            $mahasiswa = $this->tMahasiswaTab->create($request->all());
            if ($request->hasFile('foto')){
                $file = $request->file('foto');
                $filename = $request->nim.'.'.$file->getClientOriginalExtension();
                $this->controller->unlinkFile('avatar', $filename);
                $file->move(public_path('avatar'), $filename);
                $mahasiswa->update([
                    'avatar' => $filename
                ]);
            }
            // Create Detail Mahasiswa
            $mahasiswa->informasi()->create($request->all());
            // Create Mahasiswa Semester Active
            $this->tMahasiswaSemesterTab->create([
                't_mahasiswa_tabs_id' => $mahasiswa->id,
                'm_semester_tabs_id' => 1,
                'm_semester_periode_tabs_id' => 1,
                'active' => 1
            ]);
            DB::commit();
            return $this->controller->respons("CREATED", "Mahasiwa baru berhasil ditambahkan", [
                'title' => "Mahasiwa baru berhasil ditambahkan",
                'body' => 'Data Mahasiwa yang anda buat berhasil di tambahkan ke system',
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
        return $this->controller->respons(
            'MAHASISWA DETAIL', 
            $this->tMahasiswaTab
                ->where('id',$id)
                ->with(['semester_active' => function($a){
                    $a->with('semester','semester_periode');
                }])
                ->first()
        );
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $detail = $this->tMahasiswaTab->where('id', $id)->detail()->first();
        return $this->controller->respons(
            'FORM EDIT',
            [
                'basic' => array(
                    [ 'key' => 'active', 'active' => $detail->active,
                        'placeholder' => $detail->active ? 'Active' : 'Tidak Active',
                        'type' => 'select' ,'label' => 'Status Mahasiswa', 
                        'isRequired' => true,
                        'list' => [
                            'options' => array(
                                [ 'id' => 0, 'title' => 'Tidak Active' ],
                                [ 'id' => 1, 'title' => 'Active' ],
                            ),
                            'keyValue' => 'id',
                            'keyoption' => 'title'
                        ]
                    ],
                    [ 'key' => 'graduation', 'graduation' => $detail->graduation,
                        'placeholder' => $detail->graduation ? 'Sudah Lulus' : 'Belum Lulus',
                        'type' => 'select' ,'label' => 'Status Lulus', 
                        'isRequired' => true,
                        'list' => [
                            'options' => array(
                                [ 'id' => 0, 'title' => 'Belum Lulus' ],
                                [ 'id' => 1, 'title' => 'Sudah Lulus' ],
                            ),
                            'keyValue' => 'id',
                            'keyoption' => 'title'
                        ]
                    ],
                    [ 'key' => 'name', 'name' => $detail->name, 'type' => 'text','label' => 'Nama Mahasiswa', 'isRequired' => true ],
                    [ 'key' => 'email', 'email' => $detail->email, 'type' => 'text','label' => 'Email Mahasiswa', 'isRequired' => true ],
                    [ 'key' => 'curiculum', 'curiculum' => $detail->curiculum, 'type' => 'month' ,'label' => 'Tahun Curiculum', 'isRequired' => true ],
                    [ 'key' => 'date_in', 'date_in' => $detail->date_in, 'type' => 'date' ,'label' => 'Tanggal Masuk', 'isRequired' => true ],
                    [ 'key' => 'm_register_type_tabs_id', 'm_register_type_tabs_id' => $detail->m_register_type_tabs_id, 
                        'placeholder' => MRegisterTypeTab::where('id', $detail->m_register_type_tabs_id)->pluck('title')->first(),
                        'type' => 'select' ,'label' => 'Jenis Pendaftaran', 
                        'isRequired' => true,
                        'list' => [
                            'options' => MRegisterTypeTab::all(),
                            'keyValue' => 'id',
                            'keyoption' => 'title'
                        ]
                    ],
                    [ 'key' => 'm_register_enroll_tabs_id', 'm_register_enroll_tabs_id' => $detail->m_register_enroll_tabs_id,
                        'placeholder' => MRegisterEnrollTab::where('id', $detail->m_register_enroll_tabs_id)->pluck('title')->first(),
                        'type' => 'select' ,'label' => 'Jalur Pendaftaran', 
                        'isRequired' => true,
                        'list' => [
                            'options' => MRegisterEnrollTab::all(),
                            'keyValue' => 'id',
                            'keyoption' => 'title'
                        ]
                    ],
                    [ 'key' => 'm_fakultas_tabs_id', 'm_fakultas_tabs_id' => $detail->m_fakultas_tabs_id, 
                        'placeholder' => MFakultasTab::where('id', $detail->m_fakultas_tabs_id)->pluck('title')->first(),
                        'type' => 'select' ,'label' => 'Fakultas', 
                        'isRequired' => true,
                        'list' => [
                            'options' => MFakultasTab::where('active',1)->get(),
                            'keyValue' => 'id',
                            'keyoption' => 'title'
                        ]
                    ],
                    [ 'key' => 'm_jurusan_tabs_id', 'm_jurusan_tabs_id' => $detail->m_jurusan_tabs_id,
                        'placeholder' => MJurusanTab::where('id', $detail->m_jurusan_tabs_id)->pluck('title')->first(),
                        'type' => 'select' ,'label' => 'Jurusan', 
                        'isRequired' => true,
                        'list' => [
                            'options' => MJurusanTab::where('active',1)->get(),
                            'keyValue' => 'id',
                            'keyoption' => 'title'
                        ]
                    ],
                    [ 'key' => 'foto', 'foto' => $detail->avatar, 'filename' => $detail->avatar, 'type' => 'upload', 'accept' => 'image/*' ,'label' => 'Foto Mahasiswa' ],
                ),
                'detail' => array(
                    [ 'key' => 'birthday_city', 'birthday_city' => $detail->informasi->birthday_city, 'type' => 'text','label' => 'Kota Lahir', 'isRequired' => true ],
                    [ 'key' => 'birthday_date', 'birthday_date' => $detail->informasi->birthday_date, 'type' => 'date','label' => 'Tanggal Lahir', 'isRequired' => true ],
                    [ 'key' => 'no_nik', 'no_nik' => $detail->informasi->no_nik, 'type' => 'number','label' => 'NIK', 'isRequired' => true ],
                    [ 'key' => 'no_kk', 'no_kk' => $detail->informasi->no_kk, 'type' => 'number','label' => 'No Kartu Keluarga', 'isRequired' => true ],
                    [ 'key' => 'phone', 'phone' => $detail->informasi->phone, 'type' => 'number','label' => 'No Whatsapp', 'isRequired' => true ],
                    [ 'key' => 'm_gender_tabs_id', 'm_gender_tabs_id' => $detail->informasi->m_gender_tabs_id, 
                        'placeholder' => MGenderTab::where('id', $detail->m_gender_tabs_id)->pluck('title')->first(),
                        'type' => 'select' ,'label' => 'Jenis Kelamin', 'isRequired' => true,
                        'list' => [
                            'options' => MGenderTab::all(),
                            'keyValue' => 'id',
                            'keyoption' => 'title'
                        ]
                    ],
                    [ 'key' => 'm_religion_tabs_id', 'm_religion_tabs_id' => $detail->informasi->m_religion_tabs_id, 
                        'placeholder' => MReligionTab::where('id', $detail->informasi->m_religion_tabs_id)->pluck('title')->first(),
                        'type' => 'select' ,'label' => 'Agama', 'isRequired' => true,
                        'list' => [
                            'options' => MReligionTab::all(),
                            'keyValue' => 'id',
                            'keyoption' => 'title'
                        ]
                    ],
                    [ 'key' => 'm_married_tabs_id', 'm_married_tabs_id' => $detail->informasi->m_married_tabs_id,
                        'placeholder' => MMarriedTab::where('id', $detail->informasi->m_married_tabs_id)->pluck('title')->first(),
                        'type' => 'select' ,'label' => 'Status Perkawinan',
                        'list' => [
                            'options' => MMarriedTab::all(),
                            'keyValue' => 'id',
                            'keyoption' => 'title'
                        ]
                    ],
                    [ 'key' => 'm_blood_tabs_id', 'm_blood_tabs_id' => $detail->informasi->m_blood_tabs_id,
                        'placeholder' => MBloodTab::where('id', $detail->informasi->m_blood_tabs_id)->pluck('title')->first(),
                        'type' => 'select' ,'label' => 'Golongan Darah',
                        'list' => [
                            'options' => MBloodTab::all(),
                            'keyValue' => 'id',
                            'keyoption' => 'title'
                        ]
                    ],
                    [ 'key' => 'address', 'address' => $detail->informasi->address, 'type' => 'textarea','label' => 'Alamat Lengkap'],
                )
            ]
            
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $this->controller->validasi($request->all(),[
            'name' => 'required',
            'email' => 'required',
            'curiculum' => 'required',
            'date_in' => 'required',
            'foto' => 'required',
            'm_fakultas_tabs_id' => 'required',
            'm_jurusan_tabs_id' => 'required',
            'm_register_type_tabs_id' => 'required',
            'm_register_enroll_tabs_id' => 'required',
            'birthday_city' => 'required',
            'birthday_date' => 'required',
            'no_nik' => 'required',
            'no_kk' => 'required',
            'm_gender_tabs_id' => 'required',
            'm_religion_tabs_id' => 'required',
            'm_blood_tabs_id' => 'required',
            'm_married_tabs_id' => 'required',
            'address' => 'required',
        ]);

        try {
            DB::beginTransaction();
            // Update Mahasiswa
            $mahasiswa = $this->tMahasiswaTab->where('id',$id)->first();
            $mahasiswa->update($request->all());
            if ($request->hasFile('foto')){
                $file = $request->file('foto');
                $filename = $mahasiswa->nim.'.'.$file->getClientOriginalExtension();
                $this->controller->unlinkFile('avatar', $filename);
                $file->move(public_path('avatar'), $filename);
                $mahasiswa->update([
                    'avatar' => $filename
                ]);
            }
            // Update Detail Mahasiswa
            $this->tMahasiswaDetailTab->where('t_mahasiswa_tabs_id',$id)->update([
                'birthday_city' => $request->birthday_city,
                'birthday_date'=> $request->birthday_date,
                'no_kk' => $request->no_kk,
                'no_nik' => $request->no_nik,
                'phone' => $request->phone,
                'm_gender_tabs_id' => $request->m_gender_tabs_id,
                'm_blood_tabs_id' => $request->m_blood_tabs_id,
                'm_married_tabs_id' => $request->m_married_tabs_id,
                'm_religion_tabs_id' => $request->m_religion_tabs_id,
                'address' => $request->address,
            ]);
            DB::commit();
            return $this->controller->respons("UPDATED", "Mahasiswa berhasil di update", [
                'title' => "Informasi Mahasiswa berhasil diubah",
                'body' => 'Data Mahasiswa berhasil di perbaharui di system',
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
            $this->tMahasiswaTab->where('id',$id)->update(['deleted' => 1]);
            DB::commit();
            return $this->controller->respons("DELETED", "Mahasiswa berhasil di hapus", [
                'title' => "Menghapus Mahasiswa berhasil",
                'body' => 'Data Mahasiswa yang anda hapus berhasil di bersihkan dari system',
                'theme' => 'success'
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            abort(400, $th->getMessage());
        }
    }

    public function login(Request $request){
        $this->controller->validasi($request->all(),[
            'nim' => 'required',
            'password' => 'required|min:6',
        ]);

        try {
            $credentials = $request->only('nim', 'password');
            if(!Auth::guard('mahasiswa')->attempt($credentials)){
                abort(400, "Data akun yang anda masukan tidak cocok");
            }
            $user = $this->tMahasiswaTab->where('nim', $request->nim)->first();
            $token = $user->createToken('4ngel1n3',['mahasiswa'])->plainTextToken;
            return $this->controller->respons("Mahasiswa Login", [
                'token' => $token
            ]);
        } catch (\Throwable $th) {
            abort(400, $th->getMessage());
        }
    }

    public function searching(Request $request){
        return $this->controller->respons(
            "SEACRH", 
            $this->tMahasiswaTab->detail($request)->get()
        );
    }

    public function mahasiswaActive(){
        return $this->controller->respons("MAHASISWA AKTIF", $this->tMahasiswaTab->where('active',1)->where('deleted',0)->get() );
    }

    public function mahasiswaLulus(){
        return $this->controller->respons("MAHASISWA LULUS", $this->tMahasiswaTab->where('graduation',1)->where('deleted',0)->get() );
    }

    public function mahasiswaActiveList(){
        return $this->controller->responsList("MAHASISWA AKTIF", $this->tMahasiswaTab->where('active',1)->where('deleted',0)->detail()->paginate(10), 
            array(
                [ 'name' => 'NIM','key' => 'nim', 'type' => 'string' ],
                [ 'name' => 'Informasi','key' => 'informasi' ,'type' => 'array', 'child' => array(
                        ['key' => 'name', 'type' => 'string', 'className' => 'text-sm font-interbold'],
                        ['key' => 'email', 'type' => 'string', 'className' => 'font-interregular italic mt-1 text-xs'],
                    )
                ],
                [ 'name' => 'Jurusan', 'key' => 'jurusan' ,'type' => 'array','child' => array(
                        ['key' => 'jurusan.title', 'type' => 'string', 'className' => 'font-interbold text-sm'],
                        ['key' => 'fakultas.title', 'type' => 'string', 'className' => 'font-interregular'],
                    )
                ],
                [ 'name' => 'Semester', 'key' => 'semester' ,'type' => 'object','child' => array(
                        ['key' => 'semester_active.semester.title', 'type' => 'string', 'className' => 'font-interbold text-xs'],
                        ['key' => 'semester_active.semester_periode.title', 'type' => 'string', 'className' => 'italic font-interregular'],
                    )
                ],
                [ 'name' => 'Terdaftar','key' => 'terdaftar' ,'type' => 'array', 'child' => array(
                    [ 'key' => 'date_in', 'type' => 'date', 'className' => 'italic font-interregular' ],
                    [ 'key' => 'curiculum', 'type' => 'date-prefix-custom', 'prefix' => 'Curiculum ' ,'dateFormat' => 'MMMM yyyy' ,'className' => 'italic font-intermedium' ],
                )
                ],
            )
        );
    }

    public function mahasiswaTerdaftarChart(){
        $labels = $this->tMahasiswaTab->select(DB::raw('YEAR(date_in) as datein'))->orderBy('datein','asc')->groupBy('datein')->pluck('datein');
        $data = $this->tMahasiswaTab->select(DB::raw('YEAR(date_in) as datein'))->orderBy('datein','asc')->get()->groupBy('date_in');
        $arrays = [];
        foreach ($data as $item) {
            array_push($arrays, count($item));
        }
        return $this->controller->respons(
            "MAHASISWA TERDAFTAR", 
            [
               'labels' => $labels, 
               'dataset' => $arrays
            ]
        );
    }

    public function mahasiswaProfile()
    {
        $detail = $this->tMahasiswaTab->where('id', auth()->user()->id)->detail()->first();
        return $this->controller->respons(
            'FORM EDIT',
            [
                'basic' => array(
                    [ 'key' => 'active', 'readonly' => true, 'active' => $detail->active ? 'Active' : 'Tidak Active', 'type' => 'text','label' => 'Status Mahasiswa', 'isRequired' => true ],
                    [ 'key' => 'graduation', 'readonly' => true, 'graduation' => $detail->graduation ? 'Sudah Lulus' : 'Belum Lulus', 'type' => 'text','label' => 'Status Lulus', 'isRequired' => true ],
                    [ 'key' => 'name', 'readonly' => true, 'name' => $detail->name, 'type' => 'text','label' => 'Nama Mahasiswa', 'isRequired' => true ],
                    [ 'key' => 'email', 'readonly' => true, 'email' => $detail->email, 'type' => 'text','label' => 'Email Mahasiswa', 'isRequired' => true ],
                    [ 'key' => 'curiculum', 'readonly' => true, 'curiculum' => $detail->curiculum, 'type' => 'month' ,'label' => 'Tahun Curiculum', 'isRequired' => true ],
                    [ 'key' => 'date_in', 'readonly' => true, 'date_in' => $detail->date_in, 'type' => 'date' ,'label' => 'Tanggal Masuk', 'isRequired' => true ],
                    [ 
                        'key' => 'm_register_type_tabs_id',
                        'readonly' => true, 
                        'm_register_type_tabs_id' => MRegisterTypeTab::where('id', $detail->m_register_type_tabs_id)->pluck('title')->first(), 
                        'type' => 'text',
                        'label' => 'Jenis Pendaftaran',
                        'isRequired' => true 
                    ],
                    [ 
                        'key' => 'm_register_enroll_tabs_id',
                        'readonly' => true,
                        'm_register_enroll_tabs_id' => MRegisterEnrollTab::where('id', $detail->m_register_enroll_tabs_id)->pluck('title')->first(), 
                        'type' => 'text',
                        'label' => 'Jalur Pendaftaran',
                        'isRequired' => true 
                    ],
                    [ 
                        'key' => 'm_fakultas_tabs_id',
                        'readonly' => true,
                        'm_fakultas_tabs_id' => MFakultasTab::where('id', $detail->m_fakultas_tabs_id)->pluck('title')->first(), 
                        'type' => 'text',
                        'label' => 'Fakultas',
                        'isRequired' => true 
                    ],
                    [ 
                        'key' => 'm_jurusan_tabs_id',
                        'readonly' => true,
                        'm_jurusan_tabs_id' => MJurusanTab::where('id', $detail->m_jurusan_tabs_id)->pluck('title')->first(), 
                        'type' => 'text',
                        'label' => 'Jurusan',
                        'isRequired' => true 
                    ],
                ),
                'detail' => array(
                    [ 'key' => 'birthday_city', 'birthday_city' => $detail->informasi->birthday_city, 'type' => 'text','label' => 'Kota Lahir', 'isRequired' => true ],
                    [ 'key' => 'birthday_date', 'birthday_date' => $detail->informasi->birthday_date, 'type' => 'date','label' => 'Tanggal Lahir', 'isRequired' => true ],
                    [ 'key' => 'no_nik', 'no_nik' => $detail->informasi->no_nik, 'type' => 'number','label' => 'NIK', 'isRequired' => true ],
                    [ 'key' => 'no_kk', 'no_kk' => $detail->informasi->no_kk, 'type' => 'number','label' => 'No Kartu Keluarga', 'isRequired' => true ],
                    [ 'key' => 'phone', 'phone' => $detail->informasi->phone, 'type' => 'number','label' => 'No Whatsapp', 'isRequired' => true ],
                    [ 'key' => 'm_gender_tabs_id', 'm_gender_tabs_id' => $detail->informasi->m_gender_tabs_id, 
                        'placeholder' => MGenderTab::where('id', $detail->m_gender_tabs_id)->pluck('title')->first(),
                        'type' => 'select' ,'label' => 'Jenis Kelamin', 'isRequired' => true,
                        'list' => [
                            'options' => MGenderTab::all(),
                            'keyValue' => 'id',
                            'keyoption' => 'title'
                        ]
                    ],
                    [ 'key' => 'm_religion_tabs_id', 'm_religion_tabs_id' => $detail->informasi->m_religion_tabs_id, 
                        'placeholder' => MReligionTab::where('id', $detail->informasi->m_religion_tabs_id)->pluck('title')->first(),
                        'type' => 'select' ,'label' => 'Agama', 'isRequired' => true,
                        'list' => [
                            'options' => MReligionTab::all(),
                            'keyValue' => 'id',
                            'keyoption' => 'title'
                        ]
                    ],
                    [ 'key' => 'm_married_tabs_id', 'm_married_tabs_id' => $detail->informasi->m_married_tabs_id,
                        'placeholder' => MMarriedTab::where('id', $detail->informasi->m_married_tabs_id)->pluck('title')->first(),
                        'type' => 'select' ,'label' => 'Status Perkawinan',
                        'list' => [
                            'options' => MMarriedTab::all(),
                            'keyValue' => 'id',
                            'keyoption' => 'title'
                        ]
                    ],
                    [ 'key' => 'm_blood_tabs_id', 'm_blood_tabs_id' => $detail->informasi->m_blood_tabs_id,
                        'placeholder' => MBloodTab::where('id', $detail->informasi->m_blood_tabs_id)->pluck('title')->first(),
                        'type' => 'select' ,'label' => 'Golongan Darah',
                        'list' => [
                            'options' => MBloodTab::all(),
                            'keyValue' => 'id',
                            'keyoption' => 'title'
                        ]
                    ],
                    [ 'key' => 'address', 'address' => $detail->informasi->address, 'type' => 'textarea','label' => 'Alamat Lengkap'],
                )
            ]
            
        );
    }

    public function profileUpdate(Request $request)
    {
        $this->controller->validasi($request->all(),[
            'name' => 'required',
            'email' => 'required',
            'curiculum' => 'required',
            'date_in' => 'required',
            'm_fakultas_tabs_id' => 'required',
            'm_jurusan_tabs_id' => 'required',
            'm_register_type_tabs_id' => 'required',
            'm_register_enroll_tabs_id' => 'required',
            'birthday_city' => 'required',
            'birthday_date' => 'required',
            'no_nik' => 'required',
            'no_kk' => 'required',
            'm_gender_tabs_id' => 'required',
            'm_religion_tabs_id' => 'required',
            'm_blood_tabs_id' => 'required',
            'm_married_tabs_id' => 'required',
            'address' => 'required',
        ]);

        try {
            DB::beginTransaction();
            // Update Detail Mahasiswa
            $this->tMahasiswaDetailTab->where('t_mahasiswa_tabs_id',auth()->user()->id)->update([
                'birthday_city' => $request->birthday_city,
                'birthday_date'=> $request->birthday_date,
                'no_kk' => $request->no_kk,
                'no_nik' => $request->no_nik,
                'phone' => $request->phone,
                'm_gender_tabs_id' => $request->m_gender_tabs_id,
                'm_blood_tabs_id' => $request->m_blood_tabs_id,
                'm_married_tabs_id' => $request->m_married_tabs_id,
                'm_religion_tabs_id' => $request->m_religion_tabs_id,
                'address' => $request->address,
            ]);
            DB::commit();
            return $this->controller->respons("UPDATED", "Mahasiswa berhasil di update", [
                'title' => "Informasi Mahasiswa berhasil diubah",
                'body' => 'Data Mahasiswa berhasil di perbaharui di system',
                'theme' => 'success'
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            abort(400, $th->getMessage());
        }
    }

    public function mahasiswaDetail()
    {
        return $this->controller->respons(
            'MAHASISWA DETAIL', 
            $this->tMahasiswaTab
                ->where('id',auth()->user()->id)
                ->with(['semester_active' => function($a){
                    $a->with('semester','semester_periode');
                }])
                ->first()
        );
    }

    public function logout(){
        Auth::guard('mahasiswa')->logout();
        return $this->controller->respons("LOGOUT", "Anda berhasil logout", [
            'title' => "Logout Berhasil",
            'body' => 'Anda berhasil logout dari sistem',
            'theme' =>'success'
        ]);
    }
}


<?php

namespace Modules\Dosen\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Modules\Dosen\Models\TDosenTabs;
use Modules\Master\Models\MCodeTab;
use Modules\Master\Models\MFakultasTab;
use Modules\Master\Models\MGenderTab;
use Modules\Master\Models\MJurusanTab;
use Modules\Master\Models\MStatusTab;

class DosenController extends Controller
{
    protected $controller;
    protected $tDosenTabs;
    protected $mGenderTab;
    protected $mFakultasTab;
    protected $mJurusanTab;
    protected $mStatusTab;
    public function __construct(
        Controller $controller,
        TDosenTabs $tDosenTabs,
        MGenderTab $mGenderTab,
        MFakultasTab $mFakultasTab,
        MJurusanTab $mJurusanTab,
        MStatusTab $mStatusTab
    ) {
        $this->controller = $controller;
        $this->tDosenTabs = $tDosenTabs;
        $this->mGenderTab = $mGenderTab;
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
            'DOSEN ALL',
            $this->tDosenTabs->detail($request)->paginate(10),
            array(
                [ 'name' => 'Code','key' => 'code', 'type' => 'string' ],
                [ 'name' => 'Informasi','key' => 'informasi' ,'type' => 'array', 'child' => array(
                        ['key' => 'name', 'type' => 'string', 'className' => 'font-interbold text-sm'],
                        ['key' => 'email', 'type' => 'string', 'className' => 'italic font-interregular'],
                        ['key' => 'gender.title', 'type' => 'string', 'className' => 'font-intermedium mt-1'],
                    )
                ],
                [ 'name' => 'Phone Whatsapp','key' => 'phone', 'type' => 'string' ],
                [ 'name' => 'Jurusan', 'key' => 'jurusan' ,'type' => 'array','child' => array(
                        ['key' => 'jurusan.title', 'type' => 'string', 'className' => 'font-interbold text-sm'],
                        ['key' => 'fakultas.title', 'type' => 'string', 'className' => 'italic font-interregular'],
                    )
                ],
                [ 'name' => 'Alamat','key' => 'address', 'type' => 'string', 'className' => 'w-44 text-pretty' ],
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
                [ 'key' => 'name', 'name' => null, 'type' => 'text','label' => 'Nama Lengkap & Gelar', 'isRequired' => true ],
                [ 'key' => 'email', 'email' => null, 'type' => 'text', 'label' => 'Email', 'isRequired' => true ],
                [ 'key' => 'phone', 'phone' => null, 'type' => 'number', 'label' => 'Whatsapp' ],
                [ 'key' => 'm_gender_tabs_id', 'm_gender_tabs_id' => null, 'type' => 'select', 'label' => 'Gender', 'isRequired' => true,
                    'list' => [
                        'options' => $this->mGenderTab->all(),
                        'keyValue' => 'id',
                        'keyoption' => 'title'
                    ]
                ],
                [ 'key' => 'm_fakultas_tabs_id', 'm_fakultas_tabs_id' => null, 'type' => 'select', 'label' => 'Fakultas', 'isRequired' => true,
                    'list' => [
                        'options' => $this->mFakultasTab->all(),
                        'keyValue' => 'id',
                        'keyoption' => 'title'
                    ]
                ],
                [ 'key' => 'm_jurusan_tabs_id', 'm_jurusan_tabs_id' => null, 'type' => 'select', 'label' => 'Jurusan', 'isRequired' => true,
                    'list' => [
                        'options' => $this->mJurusanTab->all(),
                        'keyValue' => 'id',
                        'keyoption' => 'title'
                    ]
                ],
                [ 'key' => 'address', 'address' => null, 'autosize' => true ,'type' => 'textarea','label' => 'Alamat Lengkap', 'isRequired' => true ],
                [ 'key' => 'avatar', 'avatar' => null, 'type' => 'upload', 'accept' => 'image/*' ,'label' => 'Foto', 'isRequired' => true ],
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
            'phone' => 'required|min:6',
            'm_gender_tabs_id' => 'required',
            'm_fakultas_tabs_id' => 'required',
            'm_jurusan_tabs_id' => 'required',
            'address' => 'required',
        ]);

        try {
            DB::beginTransaction();
            $request['code'] = MCodeTab::generateCode('DSN');
            $request['m_status_tabs_id'] = 1;
            $dosen = $this->tDosenTabs->create($request->all());
            if ($request->hasFile('avatar')){
                $file = $request->file('avatar');
                $filename = $request->code.'.'.$file->getClientOriginalExtension();
                $this->controller->unlinkFile('avatar', $filename);
                $file->move(public_path('avatar'), $filename);
                $dosen->update([
                    'avatar' => $filename
                ]);
            }
            DB::commit();
            return $this->controller->respons("DOSEN CREATED", "Dosen baru berhasil ditambahkan", [
                'title' => "Dosen baru berhasil ditambahkan",
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
        return $this->controller->respons('USER DETAIL', auth()->user());
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $detail = $this->tDosenTabs->where('id', $id)->first();
        return $this->controller->respons(
            'FORM EDIT',
            array(
                [ 'key' => 'code', 'code' => $detail->code, 'type' => 'text','label' => 'Code Dosen', 'readonly' => true ],
                [ 'key' => 'name', 'name' => $detail->name, 'type' => 'text','label' => 'Nama Lengkap & Gelar', 'isRequired' => true ],
                [ 'key' => 'email', 'email' => $detail->email, 'type' => 'text', 'label' => 'Email', 'isRequired' => true ],
                [ 'key' => 'phone', 'phone' => $detail->phone, 'type' => 'number', 'label' => 'Whatsapp','isRequired' => true ],
                [ 'key' => 'm_gender_tabs_id', 'm_gender_tabs_id' => $detail->m_gender_tabs_id, 'type' => 'select',
                    'placeholder' => $this->mGenderTab->where('id',$detail->m_gender_tabs_id)->pluck('title')->first(),
                    'label' => 'Gender', 'isRequired' => true,
                    'list' => [
                        'options' => $this->mGenderTab->all(),
                        'keyValue' => 'id',
                        'keyoption' => 'title'
                    ]
                ],
                [ 'key' => 'm_fakultas_tabs_id', 'm_fakultas_tabs_id' => $detail->m_fakultas_tabs_id, 'type' => 'select', 
                    'label' => 'Fakultas', 'isRequired' => true,
                    'placeholder' => $this->mFakultasTab->where('id',$detail->m_fakultas_tabs_id)->pluck('title')->first(),
                    'list' => [
                        'options' => $this->mFakultasTab->all(),
                        'keyValue' => 'id',
                        'keyoption' => 'title'
                    ]
                ],
                [ 'key' => 'm_jurusan_tabs_id', 'm_jurusan_tabs_id' => $detail->m_jurusan_tabs_id, 'type' => 'select', 
                    'label' => 'Jurusan', 'isRequired' => true,
                    'placeholder' => $this->mJurusanTab->where('id',$detail->m_jurusan_tabs_id)->pluck('title')->first(),
                    'list' => [
                        'options' => $this->mJurusanTab->all(),
                        'keyValue' => 'id',
                        'keyoption' => 'title'
                    ]
                ],
                [ 'key' => 'address', 'address' => $detail->address, 'autosize' => true ,'type' => 'textarea','label' => 'Alamat Lengkap', 'isRequired' => true ],
                [ 'key' => 'avatar', 'avatar' => $detail->avatar, 'filename' => $detail->avatar ,'type' => 'upload', 'accept' => 'image/*' ,'label' => 'Foto', 'isRequired' => true ],
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
            'phone' => 'required',
            'm_gender_tabs_id' => 'required',
            'm_fakultas_tabs_id' => 'required',
            'm_jurusan_tabs_id' => 'required',
            'address' => 'required',
        ]);

        try {
            DB::beginTransaction();
            $dosen = $this->tDosenTabs->where('id',$id)->first();
            $dosen->update($request->all());
            if ($request->hasFile('avatar')){
                $file = $request->file('avatar');
                $filename = $request->code.'.'.$file->getClientOriginalExtension();
                $this->controller->unlinkFile('avatar', $filename);
                $file->move(public_path('avatar'), $filename);
                $dosen->update([
                    'avatar' => $filename
                ]);
            }
            DB::commit();
            return $this->controller->respons("USER UPDATED", "Dosen berhasil di update", [
                'title' => "Informasi Dosen berhasil diubah",
                'body' => 'Data Dosen berhasil di perbaharui di system',
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
            $this->tDosenTabs->where('id',$id)->delete();
            DB::commit();
            return $this->controller->respons("DOSEN DELETED", "Dosen berhasil di hapus", [
                'title' => "Menghapus dosen berhasil",
                'body' => 'Data dosen yang anda hapus berhasil di bersihkan dari system',
                'theme' => 'success'
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            abort(400, $th->getMessage());
        }
    }
}

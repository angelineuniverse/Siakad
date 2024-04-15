<?php

namespace Modules\Master\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Modules\Master\Models\MMenuTab;
use Modules\Master\Models\TMenuRoleTab;

class MenuController extends Controller
{
    protected $mMenuTab;
    protected $tMenuRoleTab;
    protected $controller;
    public function __construct(
        MMenuTab $mMenuTab,
        TMenuRoleTab $tMenuRoleTab,
        Controller $controller
    ) {
        $this->controller = $controller;
        $this->mMenuTab = $mMenuTab;
        $this->tMenuRoleTab = $tMenuRoleTab;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $menu = $this->mMenuTab->where('active',1)
            ->where('parent_id',0)
            ->with('child')
            ->orderBy('order','asc')
            ->get();
        // foreach ($menu as $value) {
        //     $value['icon'] = env('APP_URL') . 'icon/' . $value['icon'];
        // }
        return $this->controller->respons('MENU ALL', $menu);
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
        $this->controller->validasi($request->all(),[
            'icon' => 'required',
            'title' => 'required',
            'url' => 'required',
            'order' => 'required',
        ]);

        try {
            DB::beginTransaction();
            
            $menu = $this->mMenuTab->create($request->all());
            if ($request->hasFile('icon')){
                $file = $request->file('icon');
                $filename = 'icon_'.$request->title.'.'.$file->getClientOriginalExtension();
                $this->controller->unlinkFile('icon', $filename);
                $file->move(public_path('icon'), $filename);
                $menu->update([
                    'icon' => $filename
                ]);
            }
            DB::commit();
            return $this->controller->respons('STORE SUCCESSFUL', $menu);
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
        $this->controller->validasi($request->all(),[
            'title' => 'required',
        ]);

        try {
            DB::beginTransaction();
            $menu = $this->mMenuTab->where('id',$id)->first();
            $icon = $menu->icon;
            $menu->update($request->all());
            if ($request->hasFile('icon')){
                $file = $request->file('icon');
                $filename = 'icon_'.$request->title.'.'.$file->getClientOriginalExtension();
                $this->controller->unlinkFile('icon', $icon);
                $file->move(public_path('icon'), $filename);
                $menu->update([
                    'icon' => $filename
                ]);
            }
            DB::commit();
            return $this->controller->respons('UPDATED SUCCESSFUL', $menu);
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
            $this->mMenuTab->where('id',$id)->delete();
            DB::commit();
            return $this->controller->respons('DELETED SUCCESSFUL', null);
        } catch (\Throwable $th) {
            DB::rollBack();
            abort(400, $th->getMessage());
        }
    }
}

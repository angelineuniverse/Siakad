<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Validator;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function validasi($request,$rules){
        $validate = Validator::make($request->all(),$rules);
        if($validate->fails()){
            abort(400, implode(',', $validate->errors()->all()) . '.');
        } else { return null; }
    }

    public function responses($message,$code,$data,$notifikasi){
        return response()->json([
            'message' => $message,
            'data' => $data,
            'notifikasi' => $notifikasi
        ],$code);
    }
}
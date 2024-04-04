<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;

class Controller
{
    public function validasi($request, $rule){
        $validasi = Validator::make($request,$rule);
        if($validasi->fails()) abort(400, implode(',', $validasi->errors()->all()));
        else return null;
    }

    public function respons($message, $data, $notifikasi = null ,$code = 200){
        return response()->json([
            'response_message' => $message,
            'response_data' => $data,
            'response_notifikasi' => $notifikasi
        ],$code);
    }
}

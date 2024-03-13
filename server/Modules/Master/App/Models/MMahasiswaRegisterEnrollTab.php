<?php

namespace Modules\Master\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Master\Database\factories\MMahasiswaRegisterEnrollTabFactory;

class MMahasiswaRegisterEnrollTab extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [];
    
    protected static function newFactory(): MMahasiswaRegisterEnrollTabFactory
    {
        //return MMahasiswaRegisterEnrollTabFactory::new();
    }
}

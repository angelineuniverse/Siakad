<?php

namespace Modules\Master\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Master\Database\factories\MMahasiswaRegisterTypeTabFactory;

class MMahasiswaRegisterTypeTab extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [];
    
    protected static function newFactory(): MMahasiswaRegisterTypeTabFactory
    {
        //return MMahasiswaRegisterTypeTabFactory::new();
    }
}

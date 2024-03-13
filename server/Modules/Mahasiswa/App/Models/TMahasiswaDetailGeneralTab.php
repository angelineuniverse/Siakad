<?php

namespace Modules\Mahasiswa\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Mahasiswa\Database\factories\TMahasiswaDetailGeneralTabFactory;

class TMahasiswaDetailGeneralTab extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [];
    
    protected static function newFactory()
    {
        return TMahasiswaDetailGeneralTabFactory::new();
    }
}

<?php

namespace Modules\Mahasiswa\App\Models;

use Modules\Mahasiswa\Database\factories\TMahasiswaTabFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class TMahasiswaTab extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        "email",
        "name",
        "password",
        "avatar",
        "nim",
        "code",
        "status_administration",
        "status_active",
        "deleted",
        "m_mahasiswa_register_type_tabs_id",
        "m_mahasiswa_register_enroll_tabs_id",
    ];

    protected $hidden = ['password'];
    
    protected static function newFactory()
    {
        return TMahasiswaTabFactory::new();
    }
}

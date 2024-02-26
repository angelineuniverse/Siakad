<?php

namespace Modules\User\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\User\Database\factories\MUserDetailTabFactory;

class MUserDetailTab extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'users_tabs_id',
        'nik',
        'religion',
        'contact',
        'married',
        'ttl',
        'birthday',
        'address',
        'city',
        'prov',
        'm_jurusan_tabs_id',
        'm_gender_tabs_id',
        'm_jurusan_tabs_id',
        'm_jurusan_tabs_id',
    ];
    
    protected static function newFactory()
    {
        return MUserDetailTabFactory::new();
    }
}

<?php

namespace Modules\Mahasiswa\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Mahasiswa\Database\factories\TMahasiswaDetailTabFactory;

class TMahasiswaDetailTab extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    public $timestamps = false;
    protected $fillable = [
        't_mahasiswa_tabs_id',
        'old_school',
        'birthday_city',
        'birthday_date',
        'no_kk',
        'no_nik',
        'phone',
        'm_gender_tabs_id',
        'm_blood_tabs_id',
        'm_married_tabs_id',
        'm_religion_tabs_id',
        'm_city_tabs_id',
        'm_province_tabs_id',
        'address',
    ];
    
    protected static function newFactory()
    {
        // Create a new
    }
}

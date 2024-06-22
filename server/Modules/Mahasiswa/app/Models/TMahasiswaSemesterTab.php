<?php

namespace Modules\Mahasiswa\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Mahasiswa\Database\factories\TMahasiswaSemesterTabFactory;
use Modules\Master\Models\MSemesterPeriodeTabs;
use Modules\Master\Models\MSemesterTab;

class TMahasiswaSemesterTab extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    public $timestamps = false;
    protected $fillable = [
        't_mahasiswa_tabs_id',
        'm_semester_tabs_id',
        'm_semester_periode_tabs_id',
        'active',
    ];
    
    protected static function newFactory()
    {
        // Create a new factory
    }

    public function semester(){
        return $this->hasOne(MSemesterTab::class,'id','m_semester_tabs_id');
    }

    public function semester_periode(){
        return $this->hasOne(MSemesterPeriodeTabs::class,'id','m_semester_periode_tabs_id');
    }
}

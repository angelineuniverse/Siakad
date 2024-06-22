<?php

namespace Modules\Krs\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Krs\Database\factories\TKrsPeriodeTabFactory;
use Modules\Master\Models\MSemesterPeriodeTabs;
use Modules\Master\Models\MStatusTab;

class TKrsPeriodeTab extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'title',
        'start',
        'end',
        'm_status_tabs_id',
        'm_semester_periode_tabs_id'
    ];
    
    protected static function newFactory()
    {
        // Create a new instance
    }

    public function status(){
        return $this->hasOne(MStatusTab::class,'id','m_status_tabs_id');
    }
    public function semester_periode(){
        return $this->hasOne(MSemesterPeriodeTabs::class,'id','m_semester_periode_tabs_id');
    }
    public function krs(){
        return $this->hasMany(TKrsTab::class,'t_krs_periode_tabs_id','id');
    }

}

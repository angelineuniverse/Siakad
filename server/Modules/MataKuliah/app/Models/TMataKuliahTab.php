<?php

namespace Modules\MataKuliah\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Dosen\Models\TDosenTabs;
use Modules\Master\Models\MFakultasTab;
use Modules\Master\Models\MJurusanTab;
use Modules\Master\Models\MSemesterPeriodeTabs;
use Modules\Master\Models\MSemesterTab;
use Modules\Master\Models\MStatusTab;
use Modules\MataKuliah\Database\factories\TMataKuliahTabFactory;

class TMataKuliahTab extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'code',
        'title',
        't_dosen_tabs_id',
        'm_semester_tabs_id',
        'm_semester_periode_tabs_id',
        'm_fakultas_tabs_id',
        'm_jurusan_tabs_id',
        'days',
        'times',
        'bobot_sks',
        'm_status_tabs_id',
    ];

    protected $appends = [
        'selected'
    ];

    public function dosen(){
        return $this->hasOne(TDosenTabs::class,'id','t_dosen_tabs_id');
    }
    public function fakultas(){
        return $this->hasOne(MFakultasTab::class,'id','m_fakultas_tabs_id');
    }
    public function jurusan(){
        return $this->hasOne(MJurusanTab::class,'id','m_jurusan_tabs_id');
    }
    public function status(){
        return $this->hasOne(MStatusTab::class,'id','m_status_tabs_id');
    }
    public function semester(){
        return $this->hasOne(MSemesterTab::class,'id','m_semester_tabs_id');
    }
    public function semester_periode(){
        return $this->hasOne(MSemesterPeriodeTabs::class,'id','m_semester_periode_tabs_id');
    }

    public function getSelectedAttribute(){
        return false;
    }
    
    protected static function newFactory()
    {
        // Create a new factory
    }

    public function scopeDetail($query,$request){
        $query->with(['dosen','fakultas','jurusan','semester','semester_periode','status'])->latest();
        return $query;
    }

    public function scopeKrs($query,$request = null){
        $query->with(['dosen','fakultas','jurusan','semester','semester_periode'])->latest();
        return $query;
    }
}

<?php

namespace Modules\Mahasiswa\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Mahasiswa\Database\factories\TMahasiswaPeriodeTabsFactory;
use Modules\Master\Models\MStatusTab;

class TMahasiswaPeriodeTabs extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'title',
        'start',
        'end',
        'm_status_tabs_id'
    ];

    public function status(){
        return $this->hasOne(MStatusTab::class,'id','m_status_tabs_id');
    }

    public function mahasiswa(){
        return $this->hasMany(TMahasiswaTab::class,'t_mahasiswa_periode_tabs_id','id')->where('deleted',0);
    }
    
    protected static function newFactory()
    {
        // Create
    }
}

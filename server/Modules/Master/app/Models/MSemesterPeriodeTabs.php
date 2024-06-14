<?php

namespace Modules\Master\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Master\Database\factories\MSemesterPeriodeTabsFactory;
use Modules\MataKuliah\Models\TMataKuliahTab;

class MSemesterPeriodeTabs extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [];
    
    protected static function newFactory()
    {
        // Create a new instance
    }

    public function matakuliah(){
        return $this->hasMany(TMataKuliahTab::class,'m_semester_periode_tabs_id');
    }
}

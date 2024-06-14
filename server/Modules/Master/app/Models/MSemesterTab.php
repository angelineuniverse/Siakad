<?php

namespace Modules\Master\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Master\Database\factories\MSemesterTabFactory;
use Modules\MataKuliah\Models\TMataKuliahTab;

class MSemesterTab extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'title',
        'type'
    ];
    
    protected static function newFactory()
    {
        // Create a new instance
    }

    public function matakuliah(){
        return $this->hasMany(TMataKuliahTab::class, 'm_semester_tabs_id','id');
    }

    public function scopeKrs($query, $semesterPeriode = null){
        if($semesterPeriode === 1){
            $query->whereIn('id',[1,3,5,7]);
        } elseif($semesterPeriode === 2) {
            $query->whereIn('id',[2,4,6,8]);
        }
        $query->with(['matakuliah' => function($a)use($semesterPeriode){
            $a->where('m_semester_periode_tabs_id',$semesterPeriode)
            ->with('dosen');
        }]);
        return $query;
    }

    public function scopeSeletedkrs($query, $semesterPeriode = null){
        if($semesterPeriode === 1){
            $query->whereIn('id',[1,3,5,7]);
        } elseif($semesterPeriode === 2) {
            $query->whereIn('id',[2,4,6,8]);
        }
        $query->with(['matakuliah' => function($a)use($semesterPeriode){
            $a->where('m_semester_periode_tabs_id',$semesterPeriode)
            ->with('dosen');
        }]);
        return $query;
    }
}

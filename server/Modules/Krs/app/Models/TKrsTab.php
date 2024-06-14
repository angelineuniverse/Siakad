<?php

namespace Modules\Krs\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Modules\Krs\Database\factories\TKrsTabFactory;
use Modules\Mahasiswa\Models\TMahasiswaTab;
use Modules\Master\Models\MStatusTab;
use Modules\MataKuliah\Models\TMataKuliahTab;

class TKrsTab extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        't_krs_periode_tabs_id',
        't_mahasiswa_tabs_id',
        'm_status_tabs_id',
        'active_date',
        'tagihan',
        't_mahasiswa_semester_tabs_id'
    ];
    
    protected static function newFactory()
    {
        // Create a new instance
    }
    public function matakuliah(){
        return $this->hasMany(TKrsMatakuliahTab::class,'t_krs_tabs_id','id');
    }
    public function periode(){
        return $this->hasOne(TKrsPeriodeTab::class,'id','t_krs_periode_tabs_id');
    }
    public function mahasiswa(){
        return $this->hasOne(TMahasiswaTab::class,'id','t_mahasiswa_tabs_id');
    }
    public function status(){
        return $this->hasOne(MStatusTab::class,'id','m_status_tabs_id');
    }

    public function scopeDetail($query, $request = null){
        $query->with([
            'periode',
            'matakuliah' => function($a){
                $a->with(['detail_matakuliah' => function($b){
                    $b->with('dosen');
                }]);
            },
            'mahasiswa' => function($a){
                $a->with([
                    'fakultas',
                    'jurusan',
                    'semester_active' => function($b){
                        $b->with('semester','semester_periode');
                    }
                ]);
            },
            'status',
        ]);
        return $query;
    }

    public function scopeCurrentkrs($query, $request = null){
        $query->with([
            'periode',
            'matakuliah' => function($a){
                $a->with(['detail_matakuliah' => function($b){
                    $b->with('dosen');
                }]);
            },
        ]);
        return $query;
    }
}

<?php

namespace Modules\Krs\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Modules\Finance\Models\TKrsTagihanTabs;
use Modules\Krs\Database\factories\TKrsTabFactory;
use Modules\Mahasiswa\Models\TMahasiswaSemesterTab;
use Modules\Mahasiswa\Models\TMahasiswaTab;
use Modules\Master\Models\MNilaiTabs;
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
    public function uang_detail(){
        return $this->hasMany(TKrsTagihanTabs::class,'t_krs_tabs_id','id')->where('m_status_tabs_id',8);
    }

    public function semester_mahasiswa(){
        return $this->hasOne(TMahasiswaSemesterTab::class,'id','t_mahasiswa_semester_tabs_id');
    }

    public function scopeDetail($query, $request = null){
        $query->with([
            'periode',
            'uang_detail',
            'matakuliah' => function($a){
                $a->with(['nilai','detail_matakuliah' => function($b){
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
        ])->withSum('uang_detail as uang_detail_sum', 'payment');
        return $query;
    }

    public function scopeCurrentkrs($query, $request = null){
        $query->with([
            'periode',
            'semester_mahasiswa' => function($a){
                $a->with('semester','semester_periode');
            },
            'matakuliah' => function($a){
                $a->with(['nilai','detail_matakuliah' => function($b){
                    $b->with('dosen');
                }]);
            },
        ]);
        return $query;
    }

    public function scopetagihan($query){
        $query
            ->where('m_status_tabs_id',6)
            ->where('tagihan','!=',0)
            ->with(['uang_detail'])
            ->withSum('uang_detail as uang_detail_sum', 'payment');
        return $query;
    }

    public function scopeMenunggak($query){
        $query
            ->where('tagihan','!=',0)
            ->orWhere('m_status_tabs_id',10)
            ->with(['uang_detail'])
            ->withSum('uang_detail as uang_detail_sum', 'payment');
        return $query;
    }
}

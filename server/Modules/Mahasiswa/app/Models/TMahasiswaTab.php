<?php

namespace Modules\Mahasiswa\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Mahasiswa\Database\factories\TMahasiswaTabFactory;
use Modules\Master\Models\MCodeTab;
use Modules\Master\Models\MFakultasTab;
use Modules\Master\Models\MJurusanTab;

class TMahasiswaTab extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'email',
        'name',
        'password',
        'avatar',
        'nim',
        'active',
        'deleted',
        'curiculum',
        'date_in',
        't_mahasiswa_periode_tabs_id',
        'm_register_type_tabs_id',
        'm_register_enroll_tabs_id',
        'm_fakultas_tabs_id',
        'm_jurusan_tabs_id',
    ];

    public function generateNim($periodeid,$jurusanId,$dateIn){
        $serial = MCodeTab::find('MHS');
        if ($serial->year != date("y")) {
            $serial->update([
                'year' => date("y"),
                'order' => 1
            ]);
        }
        $next_value = $serial->order;
        $serial->increment('order', 1);
        return $periodeid.$jurusanId.Carbon::parse($dateIn)->format('dmy').str_pad($next_value, $serial->length, '0', STR_PAD_LEFT);
    }

    public function informasi(){
        return $this->hasOne(TMahasiswaDetailTab::class,'t_mahasiswa_tabs_id','id');
    }
    public function fakultas(){
        return $this->hasOne(MFakultasTab::class,'id','m_fakultas_tabs_id');
    }
    public function jurusan(){
        return $this->hasOne(MJurusanTab::class,'id','m_jurusan_tabs_id');
    }
    public function periode(){
        return $this->hasOne(TMahasiswaPeriodeTabs::class,'id','t_mahasiswa_periode_tabs_id');
    }
    public function semester_active(){
        return $this->hasOne(TMahasiswaSemesterTab::class,'t_mahasiswa_tabs_id','id')->where('active',1);
    }

    
    protected static function newFactory()
    {
        // Create a new factory
    }

    public function scopeDetail($query,$request = null){
        if(isset($request->name)){
            $query->where('name','like','%'.$request->name.'%');
        }
        $query->with(['informasi','jurusan','fakultas',
            'semester_active' => function($a){
                $a->with('semester','semester_periode');
            }
        ])->latest();
        return $query;
    }
    
}

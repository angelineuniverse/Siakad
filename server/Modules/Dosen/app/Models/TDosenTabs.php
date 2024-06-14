<?php

namespace Modules\Dosen\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Dosen\Database\factories\TDosenTabsFactory;
use Modules\Master\Models\MFakultasTab;
use Modules\Master\Models\MGenderTab;
use Modules\Master\Models\MJurusanTab;
use Modules\Master\Models\MStatusTab;

class TDosenTabs extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'code',
        'name',
        'email',
        'avatar',
        'phone',
        'm_gender_tabs_id',
        'm_fakultas_tabs_id',
        'm_jurusan_tabs_id',
        'm_status_tabs_id',
        'address',
    ];

    public function gender(){
        return $this->hasOne(MGenderTab::class,'id','m_gender_tabs_id');
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
    
    protected static function newFactory()
    {
        return TDosenTabsFactory::new();
    }

    public function scopeDetail($query,$request){
        $query->with(['gender','fakultas','jurusan','status'])->latest();
        return $query;
    }
}

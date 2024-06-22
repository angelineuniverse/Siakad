<?php

namespace Modules\Finance\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Finance\Database\factories\TKrsTagihanTabsFactory;
use Modules\Krs\Models\TKrsTab;
use Modules\Master\Models\MStatusTab;

class TKrsTagihanTabs extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        't_krs_tabs_id',
        'payment',
        'keterangan',
        'm_status_tabs_id',
        'code',
    ];

    public function status(){
        return $this->hasOne(MStatusTab::class,'id', 'm_status_tabs_id');
    }

    public function krs(){
        return $this->hasOne(TKrsTab::class,'id', 't_krs_tabs_id');
    }
    
    protected static function newFactory()
    {
        //return ..
    }
}

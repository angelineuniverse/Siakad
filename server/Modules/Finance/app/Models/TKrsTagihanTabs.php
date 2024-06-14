<?php

namespace Modules\Finance\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Finance\Database\factories\TKrsTagihanTabsFactory;

class TKrsTagihanTabs extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        't_krs_tabs_id',
        'payment',
        'validation',
    ];
    
    protected static function newFactory()
    {
        //return ..
    }
}

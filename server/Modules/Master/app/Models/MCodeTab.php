<?php

namespace Modules\Master\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Master\Database\factories\MCodeTabFactory;

class MCodeTab extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    public $timestamps = false;
    protected $primaryKey = 'prefix';
    public $incrementing = false;
    protected $fillable = [
        'prefix',
        'order',
        'length',
        'year',
        'description',
    ];
    
    protected static function newFactory(){
        //class factory
    }

    public static function generateCode($prefix) {
        $serial = self::find($prefix);
        if ($serial->year != date("y")) {
            $serial->update([
                'year' => date("y"),
                'order' => 1
            ]);
        }
        $next_value = $serial->order;
        $serial->increment('order', 1);
        return $serial->prefix . $serial->year . str_pad($next_value, $serial->length, '0', STR_PAD_LEFT);
    }
}

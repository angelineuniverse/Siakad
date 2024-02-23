<?php

namespace Modules\Master\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Master\Database\factories\MGenerateCodeTabFactory;

class MGenerateCodeTab extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $primaryKey = 'serial'; // increment
    public $incrementing = false;
    public $timestamps = false;
    protected $fillable = [
        'prefix',
        'serial',
        'start',
        'length',
        'years',
        'description'
    ];
    
    protected static function newFactory()
    {
        return MGenerateCodeTabFactory::new();
    }

    public function generateCode($serial){
        $serial = self::find($serial); // row yang serial = $serial -> output object
        if($serial->years != date('y')){
            $serial->update([
                'years' => date('y'),
                'start' => 1
            ]);
        }
        $nextValue = $serial->start;
        $serial->increment('start',1);
        return $serial->prefix . $serial->years . str_pad($nextValue, $serial->length,0,STR_PAD_LEFT);
    }


}

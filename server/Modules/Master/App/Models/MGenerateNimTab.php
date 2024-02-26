<?php

namespace Modules\Master\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Master\Database\factories\MGenerateNimTabFactory;

class MGenerateNimTab extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $primaryKey = 'm_jurusan_tabs_id';
    public $timestamps = false;
    public $incementing = false;
    protected $fillable = [
        'm_jurusan_tabs_id',
        'fakultas',
        'jurusan',
        'start',
        'length',
        'years',
        'description'
    ];
    
    protected static function newFactory()
    {
        return MGenerateNimTabFactory::new();
    }

    public function generateCode($m_jurusan_tabs_id){
        $serial = self::find($m_jurusan_tabs_id);
        if($serial->years != date('y')){
            $serial->update([
                'years' => date('y'),
                'start' => 1
            ]);
        }
        $nextValue = $serial->start;
        $serial->increment('start',1);
        return $serial->fakultas . 
                $serial->jurusan . 
                $serial->years . 
                str_pad($nextValue, $serial->length,0,STR_PAD_LEFT);
        // 3301240001
    }
}

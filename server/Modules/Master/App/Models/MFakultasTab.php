<?php

namespace Modules\Master\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Master\Database\factories\MFakultasTabFactory;

class MFakultasTab extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    public $timestamps = false;
    protected $fillable = [
        'code',
        'title'
    ];
    
    protected static function newFactory()
    {
        return MFakultasTabFactory::new();
    }
}

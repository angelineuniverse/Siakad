<?php

namespace Modules\Pengumuman\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Admin\Models\TAdminTab;
use Modules\Pengumuman\Database\factories\TPengumumanTabFactory;

class TPengumumanTab extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'title',
        'code',
        't_admin_tabs_id',
        'description',
        'file',
        'active',
        'start_date',
        'end_date',
    ];

    public function admin(){
        return $this->hasOne(TAdminTab::class,'id','t_admin_tabs_id');
    }
    
    protected static function newFactory()
    {
        // Create a new instance
    }

    public function scopeDetail($query, $response = null){
        $query->with(['admin']);
        return $query;
    }
}

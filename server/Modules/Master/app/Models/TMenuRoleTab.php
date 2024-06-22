<?php

namespace Modules\Master\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Master\Database\factories\TMenuRoleTabFactory;

class TMenuRoleTab extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'm_menu_tab_id',
        'm_role_tab_id',
    ];
    
    protected static function newFactory()
    {
        return TMenuRoleTabFactory::new();
    }
}

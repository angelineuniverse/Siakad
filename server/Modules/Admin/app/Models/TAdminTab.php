<?php

namespace Modules\Admin\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Admin\Database\factories\TAdminTabFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Modules\Master\Models\MRoleTab;

class TAdminTab extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ['name','email','password','phone', 'm_role_tab_id','active','avatar'];
    protected $hidden = ['password'];
    protected $appends = ['role'];
    
    protected static function newFactory()
    {
        return TAdminTabFactory::new();
    }

    public function getRoleAttribute(){
        return $this->roletab->title ?? 'Belum Diatur';
    }
    
    public function roletab(){
        return $this->hasOne(MRoleTab::class, 'id', 'm_role_tab_id');
    }
}

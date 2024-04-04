<?php

namespace Modules\Admin\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Admin\Database\factories\TAdminTabFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class TAdminTab extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ['name','email','password','phone'];
    protected $hidden = ['password'];
    
    protected static function newFactory()
    {
        return TAdminTabFactory::new();
    }
}

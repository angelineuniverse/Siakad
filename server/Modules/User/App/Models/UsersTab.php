<?php

namespace Modules\User\App\Models;

use Modules\User\Database\factories\UsersTabFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class UsersTab extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'email',
        'name',
        'password',
        'avatar',
        'nim',
        'code',
        'active',
        'deleted',
    ];
    // users_tab
    // users_detail_tab

    protected $hidden = ['password'];
    
    protected static function newFactory()
    {
        return UsersTabFactory::new();
    }
}

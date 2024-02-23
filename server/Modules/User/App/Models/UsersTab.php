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
        'birthday',
        'code',
        'm_gender_tabs_id',
        'active',
        'deleted',
    ];

    protected $hidden = ['password'];
    
    protected static function newFactory()
    {
        return UsersTabFactory::new();
    }
}

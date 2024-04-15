<?php

namespace Modules\Master\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Master\Database\factories\MMenuTabFactory;

class MMenuTab extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    public $timestamps = false;
    protected $fillable = [
        'title',
        'icon',
        'url',
        'order',
        'parent_id',
        'active'
    ];
    protected $hidden = ['icon'];
    public $appends = ['icon_url','dropdown'];
    
    protected static function newFactory()
    {
        return MMenuTabFactory::new();
    }

    public function getIconUrlAttribute(){
        return env('APP_URL') . 'icon/' . $this->icon;
    }
    public function getDropdownAttribute(){
        return false;
    }

    public function child(){
        return $this->hasMany(MMenuTab::class,'parent_id','id');
    }
}

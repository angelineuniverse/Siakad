<?php

namespace Modules\Krs\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Krs\Database\factories\TKrsMatakuliahTabFactory;
use Modules\MataKuliah\Models\TMataKuliahTab;

class TKrsMatakuliahTab extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    public $timestamps = false;
    protected $fillable = [
        't_krs_tabs_id',
        't_mata_kuliah_tabs_id',
    ];

    public $appends = ['sks'];

    public function getSksAttribute(){
        return $this->detail_matakuliah->bobot_sks;
    }
    protected static function newFactory()
    {
        // Create a new factory
    }
    public function detail_matakuliah(){
        return $this->hasOne(TMataKuliahTab::class,'id','t_mata_kuliah_tabs_id');
    }
}

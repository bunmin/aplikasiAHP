<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class alternatif_bobot extends Model
{
    use SoftDeletes;

    protected $fillable = ['user_id','topik_id','kriteria_id','alternatif_id_baris','alternatif_id_kolom','nilai_bobot','nilai_eigen'];
}

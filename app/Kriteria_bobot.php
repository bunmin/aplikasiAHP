<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Kriteria_bobot extends Model
{
    use SoftDeletes;

    protected $fillable = ['user_id','topik_id','kriteria_id_baris','kriteria_id_kolom','nilai_bobot','nilai_eigen'];
}

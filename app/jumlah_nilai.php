<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class jumlah_nilai extends Model
{
    use SoftDeletes;

    protected $fillable = ['topik_id','kriteria_id','alternatif_id','','jumlah_nilai','rata_rata_nilai'];
}

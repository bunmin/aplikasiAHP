<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class total_keseluruhan extends Model
{
    use SoftDeletes;

    protected $fillable = ['user_id','topik_id','alternatif_id','nilai_bobot'];
}

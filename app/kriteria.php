<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class kriteria extends Model
{
    use SoftDeletes;

    protected $fillable = ['user_id','topik_id','nama'];
}

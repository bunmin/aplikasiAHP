<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class topik extends Model
{
    use SoftDeletes;

    protected $fillable = ['user_id','judul','keterangan'];
}

<?php

namespace App\Models\Teaching;

use Illuminate\Database\Eloquent\Model;

class Teachingadministrator extends Model
{
    //
    protected $fillable = ['user_id', 'number', 'teachingpoint_id'];

    public function user() {
        return $this->hasOne('App\Models\System\User', 'id', 'user_id');
    }

    public function teachingpoint() {
        return $this->hasOne('App\Models\Teaching\Teachingpoint', 'id', 'teachingpoint_id');
    }
}

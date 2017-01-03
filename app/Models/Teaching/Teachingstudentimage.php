<?php

namespace App\Models\Teaching;

use Illuminate\Database\Eloquent\Model;

class Teachingstudentimage extends Model
{
    //
    protected $fillable = ['name', 'path', 'descrip', 'teachingpoint_id'];

    public function teachingpoint() {
        return $this->hasOne('App\Models\Teaching\Teachingpoint', 'id', 'teachingpoint_id');
    }
}

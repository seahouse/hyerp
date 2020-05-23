<?php

namespace App\Models\Basic;

use Illuminate\Database\Eloquent\Model;

class Constructionbidinformation extends Model
{
    //
    protected $fillable = [
        'number',
        'year',
        'digital_number',
        'name',
        'closed',
        'remark',
    ];

    public function constructionbidinformationitems() {
        return $this->hasMany('App\Models\Basic\Constructionbidinformationitem');
    }
}

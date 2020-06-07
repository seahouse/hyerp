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
        'sohead_id',
    ];

    public function constructionbidinformationitems() {
        return $this->hasMany('App\Models\Basic\Constructionbidinformationitem');
    }

    public function sohead() {
        return $this->belongsTo('App\Models\Sales\Salesorder_hxold');
    }
}

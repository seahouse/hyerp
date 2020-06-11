<?php

namespace App\Models\Basic;

use Illuminate\Database\Eloquent\Model;

class Constructionbidinformationitem extends Model
{
    //
    protected $fillable = [
        'constructionbidinformation_id',
        'projecttype',
        'key',
        'purchaser',
        'specification_technicalrequirements',
        'value',
        'multiple',
//        'value_line3',
//        'value_line4',
        'unit',
        'remark',
        'sort',
    ];

    public function constructionbidinformation() {
        return $this->belongsTo('App\Models\Basic\Constructionbidinformation');
    }

    public function constructionbidinformationfield() {
        return Constructionbidinformationfield::where('name', $this->key)->where('projecttype', $this->projecttype)->first();
    }
}

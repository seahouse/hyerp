<?php

namespace App\Models\Basic;

use Illuminate\Database\Eloquent\Model;

class Constructionbidinformationitem extends Model
{
    //
    protected $fillable = [
        'constructionbidinformation_id',
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
}

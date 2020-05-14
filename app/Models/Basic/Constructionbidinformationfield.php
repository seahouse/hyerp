<?php

namespace App\Models\Basic;

use Illuminate\Database\Eloquent\Model;

class Constructionbidinformationfield extends Model
{
    //
    protected $fillable = [
        'name',
        'sort',
        'type',
//        'exceltype',
        'projecttype',
//        'select_strings',
    ];
}

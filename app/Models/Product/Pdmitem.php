<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Model;

class Pdmitem extends Model
{
    //
    protected $table = 'pdmitem';
    protected $connection = 'sqlsrv3';

    const CREATED_AT = 'createtime';
    const UPDATED_AT = 'updatetime';
}

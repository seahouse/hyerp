<?php

namespace App\Models\Approval;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Issuedrawing extends Model
{
    //
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'designdepartment',
        'sohead_id',
        'overview',
        'tonnage',
        'productioncompany',
        'materialsupplier',
        'drawingchecker_id',
        'requestdeliverydate',
        'drawingcount',
        'remark',
        'applicant_id',
        'status',
        'approversetting_id',
    ];
}

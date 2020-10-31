<?php

namespace App\Models\Approval;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customerdeduction extends Model
{
    //
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'customer_id',
        'sohead_id',
        'deductions_for',
        'amount',
        'applicant_id',
        'status',
        'process_instance_id',
        'business_id',
    ];
}

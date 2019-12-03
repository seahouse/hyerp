<?php

namespace App\Models\Approval;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Techpurchase extends Model
{
    //
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'purchasecompany_id',
        'submitdepart',
        'arrivaldate',
        'sohead_id',
        'applicant_id',
        'status',
//        'approversetting_id',
        'process_instance_id',
        'business_id',
    ];

    public function purchasecompany() {
        return $this->belongsTo('\App\Models\Basic\Company_hxold', 'purchasecompany_id');
    }

    public function techpurchaseitems() {
        return $this->hasMany('\App\Models\Approval\Techpurchaseitem');
    }

    public function techpurchaseattachments() {
        return $this->hasMany('\App\Models\Approval\Techpurchaseattachment');
    }
}

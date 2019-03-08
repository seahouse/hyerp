<?php

namespace App\Models\Approval;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Projectsitepurchase extends Model
{
    //
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'sohead_id',
        'purchasetype',
        'purchasereason',
        'remark',
        'freight',
        'totalprice',
        'paymentmethod',
        'invoicesituation',
        'companyname',
        'contact',
        'phonenumber',
        'otherremark',
        'applicant_id',
        'status',
//        'approversetting_id',
        'process_instance_id',
        'business_id',
    ];

    public function approvers() {
        $approvers = config('custom.dingtalk.approversettings.projectsitepurchase.' . $this::getAttribute('purchasetype'), '');
        return $approvers;
    }
}

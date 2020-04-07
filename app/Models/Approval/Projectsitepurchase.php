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
        'purchasecompany_id',
        'sohead_id',
        'purchasetype',
        'outsourcingcompany_id',
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

    public function projectsitepurchaseitems() {
        return $this->hasMany('\App\Models\Approval\Projectsitepurchaseitem', 'projectsitepurchase_id', 'id');
    }

    public function projectsitepurchaseattachments() {
        return $this->hasMany('\App\Models\Approval\Projectsitepurchaseattachment');
    }

    public function approvers() {
        $approvers = config('custom.dingtalk.approversettings.projectsitepurchase.' . $this::getAttribute('purchasetype'), '');
        return $approvers;
    }

    public function applicant() {
        return $this->belongsTo('\App\Models\System\User');
    }
}

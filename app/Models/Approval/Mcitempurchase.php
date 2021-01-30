<?php

namespace App\Models\Approval;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Mcitempurchase extends Model
{
    //
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    private static $approvaltype_name = "制造中心物品申购";

    protected $fillable = [
        'manufacturingcenter',
        'manufacturingcenter_id',
        'itemtype',
        'expirationdate',
        'sohead_id',
        'totalprice',
        'detailuse',
        'applicant_id',
        'status',
        'approversetting_id',
        'process_instance_id',
        'business_id',
        'syncdtdesc',
    ];

    public function mcitempurchaseitems() {
        return $this->hasMany('\App\Models\Approval\Mcitempurchaseitem', 'mcitempurchase_id', 'id');
    }

    public function approvers() {
        if ($this::getAttribute('syncdtdesc') == "许昌")
            $approvers = config('custom.dingtalk.hx_henan.approversettings.mcitempurchase.' . $this::getAttribute('manufacturingcenter'), '');
        else
            $approvers = config('custom.dingtalk.approversettings.mcitempurchase.' . $this::getAttribute('manufacturingcenter'), '');
        return $approvers;
    }
}

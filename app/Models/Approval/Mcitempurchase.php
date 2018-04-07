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
        'itemtype',
        'expirationdate',
        'sohead_id',
        'totalprice',
        'detailuse',
        'applicant_id',
        'status',
        'approversetting_id',
    ];

    public function mcitempurchaseitems() {
        return $this->hasMany('\App\Models\Approval\Mcitempurchaseitem', 'mcitempurchase_id', 'id');
    }

    public function approvers() {
        $approvers = config('custom.dingtalk.approversettings.mcitempurchase.' . $this::getAttribute('manufacturingcenter'), '');
        return $approvers;
    }
}

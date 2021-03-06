<?php

namespace App\Models\Approval;

use App\Models\Sales\Salesorder_hxold;
use App\Models\System\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Epcsecening extends Model
{
    //
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'sohead_id',
        'supplier_id',
        'pohead_id',
        'additional_design_department',
        'additional_source',
        'additional_source_department',
        'additional_reason',
        'need_issuedrawing',
        'design_change_sheet',
        'short_additional_reason',
        'drawing_additional_reason',
        'extra_additional_reason',
        'owner_additional_reason',
        'owner_additional_reasonalreason',
        'coordinate_additional_reason',
        'additional_reason_detaildesc',
        'additional_content',
        'associated_approval_vendordeduction',
        'associated_approval_designchangenotice',
        'remark_whl',
        'amount_whl',
        'applicant_id',
        'status',
        'process_instance_id',
        'business_id',
    ];

    public function sohead() {
        return $this->belongsTo(Salesorder_hxold::class);
    }

    public function applicant() {
        return $this->belongsTo(User::class);
    }

    public function epcseceningoptrecords() {
        return $this->hasMany(Epcseceningoptrecord::class);
    }
}

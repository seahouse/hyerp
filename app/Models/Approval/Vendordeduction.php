<?php

namespace App\Models\Approval;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Log;

class Vendordeduction extends Model
{
    //
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'pohead_id',
        'outsourcing_id',
        'outsourcingtype',
        'techdepart',
        'problemlocation',
        'reason',
        'remark',
        'applicant_id',
        'status',
//        'approversetting_id',
        'process_instance_id',
        'business_id',
    ];

    public function approvers() {
        $outsourcingtype = config('custom.dingtalk.approversettings.vendordeduction.' . $this::getAttribute('techdepart') . '.' . $this::getAttribute('outsourcingtype'));
//        dd($outsourcingtype);
        $approvers = '';
        if (is_string($outsourcingtype))
            $approvers = $outsourcingtype;
        else if (is_array($outsourcingtype))
        {
//            $problemlocation = $outsourcingtype['default'];
            if (array_key_exists($this::getAttribute('problemlocation'), $outsourcingtype))
                $problemlocation = $outsourcingtype[$this::getAttribute('problemlocation')];
            if (is_string($problemlocation))
                $approvers = $problemlocation;
            else if (is_array($problemlocation))
            {
                $outsourcingtype2 = $problemlocation['default'];
                if (array_key_exists($this::getAttribute('outsourcingtype'), $problemlocation))
                    $outsourcingtype2 = $problemlocation[$this::getAttribute('outsourcingtype')];
                if (is_string($outsourcingtype2))
                    $approvers = $outsourcingtype2;
            }
        }
        return $approvers;
    }
}

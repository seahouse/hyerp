<?php

namespace App\Models\Purchase;

use App\Models\Approval\Mcitempurchase;
use App\Models\Sales\Salesorder_hxold;
use App\Models\System\User;
use Illuminate\Database\Eloquent\Model;

class Prhead extends Model
{
    //
    protected $fillable = [
        'number',
        'dayseq',
        'company_id',
        'sohead_id',
        'status',
        'remark',
        'type',
        'applicant_id',
        'approval_type',
        'process_instance_id',
    ];

    public function sohead() {
        return $this->belongsTo(Salesorder_hxold::class);
    }

    public function applicant() {
        return $this->belongsTo(User::class);
    }

    public function pritems() {
        return $this->hasMany(Pritem::class);
    }

    public function associated_business_id() {
        $business_id = '';
        if ($this::getAttribute('approval_type') == 'mcitempurchase')
        {
            $item = Mcitempurchase::where('process_instance_id', $this::getAttribute('process_instance_id'))->first();
            if (isset($item))
                $business_id = $item->business_id;
        }
        return $business_id;
    }
}

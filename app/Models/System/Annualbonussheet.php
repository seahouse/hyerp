<?php

namespace App\Models\System;

use Illuminate\Database\Eloquent\Model;

class Annualbonussheet extends Model
{
    //
    protected $fillable = [
        'salary_date',
        'username',
        'user_id',
        'department',
        'salaryincrease',
        'months',
        'yearend_salary',
        'yearend_bonus',
        'duty_subsidy',
        'duty_allowance',
        'forum_amount',
        'other_amount',
        'boss_prize',
        'amount',
        'goodemployee_amount',
        'totalamount',
        'individualincometax_amount',
        'actual_amount',
        'remark',
    ];

    public function user() {
        return $this->belongsTo('App\Models\System\User');
    }
}

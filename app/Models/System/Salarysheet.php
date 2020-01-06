<?php

namespace App\Models\System;

use Illuminate\Database\Eloquent\Model;

class Salarysheet extends Model
{
    //
    protected $fillable = [
        'salary_date',
        'username',
        'user_id',
        'department',
        'attendance_days',
        'basicsalary',
        'overtime_hours',
        'absenteeism_reduce',
        'paid_hours',
        'overtime_amount',
        'fullfrequently_award',
        'meal_amount',
        'car_amount',
        'business_amount',
        'additional_amount',
        'house_amount',
        'hightemperature_amount',
        'shouldpay_amount',
        'borrowreduce_amount',
        'personalsocial_amount',
        'personalaccumulationfund_amount',
        'individualincometax_amount',
        'actualsalary_amount',
        'remark',
    ];
}

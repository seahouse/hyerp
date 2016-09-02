<?php

namespace App\Models\Approval;

use Illuminate\Database\Eloquent\Model;

class Approvaltype extends Model
{
    //
	protected $fillable = [
        'name',
        'description',
    ];
}

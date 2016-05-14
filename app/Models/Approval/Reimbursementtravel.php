<?php

namespace App\Models\Approval;

use Illuminate\Database\Eloquent\Model;

class Reimbursementtravel extends Model
{
    //
    protected $fillable = [
        'reimbursement_id',
        'datego',
        'dateback',
        'descrip',
        'seq',
    ];
}

<?php

namespace App\Models\Purchase;

use App\Models\System\User;
use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    protected $table = 'vouchers';

    public function creator_user()
    {
        return $this->belongsTo(User::class, 'creator');
    }

    public function updater_user()
    {
        return $this->belongsTo(User::class, 'updater');
    }
}

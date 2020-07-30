<?php

namespace App\Models\Basic;

use Illuminate\Database\Eloquent\Model;

class Biddinginformationeditem extends Model
{
    //
    protected $fillable = [
        'biddinginformation_id',
        'key',
        'value',
        'remark',
        'sort',
        'type',
    ];

    public function biddinginformationitem() {
        return Biddinginformationitem::where('biddinginformation_id', $this->biddinginformation_id)->where('key', $this->key)->first();
    }
}

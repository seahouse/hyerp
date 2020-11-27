<?php

namespace App\Models\Purchase;

use Illuminate\Database\Eloquent\Model;

class PrSupplier extends Model
{
    protected $table = "pr_suppliers";

    protected $fillable = [
        'prhead_id',
        'supplier_id',
        'selected',
    ];

    /**
     * 关联的头信息
     *
     * @return void
     */
    public function prhead()
    {
        return $this->belongsTo(Prhead::class);
    }

    /**
     * 关联的供应商信息
     */
    public function item()
    {
        return $this->belongsTo(Vendinfo_hxold::class, 'supplier_id', 'id');
    }
}

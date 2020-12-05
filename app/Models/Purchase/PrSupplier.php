<?php

namespace App\Models\Purchase;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Exception;

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

    public $incrementing = false;
    protected $primaryKey = ['prhead_id', 'supplier_id'];
    protected function setKeysForSaveQuery(Builder $query)
    {
        foreach ($this->getKeyName() as $key) {
            if ($this->$key)
                $query->where($key, '=', $this->$key);
            else
                throw new Exception(__METHOD__ . 'Missing part of the primary key: ' . $key);
        }

        return $query;
    }
}

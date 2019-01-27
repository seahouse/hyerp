<?php

namespace App\Models\Purchaseorderc;

use Illuminate\Database\Eloquent\Model;

class Poitemc extends Model
{
    //
    protected $fillable = [
        'poheadc_id',
        'fabric_sequence_no',
        'quantity',
        'unit',
        'material_code',
        'fabric_width',
        'fabric_description',
        'shrinkage',
        'hand_feel',
        'for_washing',
        'for_crocking',
        'yarn_count',
        'construction',
        'IO_description',
        'positive_percentage_tolerance',
        'negative_percentage_tolerance',
        'transportation_method_type_code',
        'sample_description',
        'prodn_description',
        'color_sequence',
        'unit_price',
        'color_desc1',
        'color_desc2',
        'color_desc3',
        'color_desc4',
        'color_desc5',
        'quantity_per_color',
        'shipment_date',
    ];

    public function purchaseorderc() {
        return $this->hasOne('\App\Models\Purchaseorderc\Poheadc', 'id', 'poheadc_id');
    }

    public function asnitems() {
        return $this->hasMany('\App\Models\Purchaseorderc\Asnitem', 'poitemc_id', 'id');
    }
}

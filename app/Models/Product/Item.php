<?php

namespace App\models\Product;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    //
    protected $fillable = [
        'item_number',
        'item_name',
        'item_descrip',
        'itemclass_id',
        'itemtype_id',
        'index',
        'marketprice',
    ];
    
    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
//     protected $with = ['itemcalss'];

    public function itemclass() {
        return $this->hasOne('App\models\Product\Itemclass', 'id', 'itemclass_id');
    }    
    
    public function itemtype() {
        return $this->hasOne('App\Itemtype', 'id', 'itemtype_id');
    }
    
    public function itemsite() {
        return $this->hasOne('App\Inventory\Itemsite', 'item_id', 'id');
    }
    
    public function soitems() {
        return $this->hasMany('App\Sales\Soitem', 'item_id', 'id');
    }
    
    public function charasses() {
        return $this->hasMany('App\Product\Charass', 'target_id', 'id');
    }
}

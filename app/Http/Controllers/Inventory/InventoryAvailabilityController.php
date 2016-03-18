<?php

namespace App\Http\Controllers\Inventory;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Sales\Soitem;
use App\Models\Product\Item;

class InventoryAvailabilityController extends Controller
{
    //
    public function listBySalesorder()
    {
        $items = Soitem::latest('created_at')->paginate(10);
        return view('inventory.inventoryavailability.index', compact('items'));
    }
    
    public function listByItems()
    {
        $items = Item::latest('created_at')->paginate(10);
        return view('inventory.inventoryavailability.index_byitems', compact('items'));
    }
}

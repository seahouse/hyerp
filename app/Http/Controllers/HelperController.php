<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class HelperController extends Controller
{
    //

	/**
     * skip empty string value from the given pair array.
     *
     * @param  $items
     * @return $items
     */
    public static function skipEmptyValue($items)
    {
        //
		$items = array_where($items, function ($key, $value) {
			if ($value != "")
				return true;
		});
        
        return $items;
    }
}

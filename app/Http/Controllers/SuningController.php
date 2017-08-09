<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\util\HttpSuning;

class SuningController extends Controller
{
    //
    public function gateway()
    {
        $data = [];
        $response = HttpSuning::post("/gateway.htm",
            array(), json_encode($data));
        dd($response);
    }
}

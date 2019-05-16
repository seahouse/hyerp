<?php

namespace App\Http\Controllers\Product;

// use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Requests\Product\CharassRequest;
use Request;
use App\Models\Product\Charass;

class CharassesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //
    }
    
    public function getCharassesByTargetId($targetType, $targetId) {
        $charasses = Charass::where('target_type', $targetType)->where('target_id', $targetId)->get();
        $data = [];
        foreach ($charasses as $charass)
        {
            $item = array(
                'id' => $charass->id,
                'target_type' => $charass->target_type,
                'target_id' => $charass->target_id,
                'char_id' => $charass->char_id,
                'value' => $charass->value,
                'default' => $charass->default,
                'price' => $charass->price,
                'char_name' => $charass->char->name,
                'default_str' => $charass->default ? '是' : '否',
            );
            array_push($data, $item);
        }
        return $data;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(CharassRequest $request)
    {
        //
        return 'success';
        $charass = new Charass;
        $charass->target_type = $request->input('target_type');
        $charass->target_id = $request->input('item_id');
        $charass->char_id = $request->input('char_id');
        $charass->value = $request->input('value');

        if ($charass->save())
            return 'success';

        return 'error';        
    }
    
    public function addrecord(CharassRequest $request)
    {
        $charass = new Charass;
        $charass->target_type = $request->input('target_type');
        $charass->target_id = $request->input('target_id');
        $charass->char_id = $request->input('char_id');
        $charass->value = $request->input('value');
        $charass->price = $request->input('price');
        
        if ($charass->save())
            return 'success';

        return 'error';
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
    
    public function removerecord($id)
    {
        Charass::destroy($id);
        return 'success';
    }
}

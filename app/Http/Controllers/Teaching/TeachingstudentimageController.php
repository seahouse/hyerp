<?php

namespace App\Http\Controllers\Teaching;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Teaching\Teachingstudentimage;

class TeachingstudentimageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $teachingstudentimages = Teachingstudentimage::latest('created_at')->paginate(10);
        return view('teaching.teachingstudentimage.index', compact('teachingstudentimages'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('teaching.teachingstudentimage.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $sFilename = '';        
        if ($request->hasFile('image'))
        {
            $file = $request->file('image');
            $sFilename = $this->saveImg($file);          

        }
        
        $input = $request->all();
        $input = array_add($input, 'path', $sFilename);
        Teachingstudentimage::create($input);        
        return redirect('/teaching/teachingstudentimage');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $teachingstudentimage = Teachingstudentimage::findOrFail($id);
        return view('teaching.teachingstudentimage.edit', compact('teachingstudentimage'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $teachingstudentimage = Teachingstudentimage::findOrFail($id);
        $teachingstudentimage->update($request->all());
        
        return redirect('teaching/teachingstudentimage');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        Teachingstudentimage::destroy($id);
        return redirect('teaching/teachingstudentimage');
    }

    private function saveImg($file)
    {
        $fileOriginalName = $file->getClientOriginalName();
        $sExtension = substr($fileOriginalName, strrpos($fileOriginalName, '.') + 1);
        $sFilename = date('YmdHis').rand(100, 200) . '.' . $sExtension;
        $file->move('images/teaching', $sFilename);
        return 'images/teaching/' . $sFilename;
    }
}

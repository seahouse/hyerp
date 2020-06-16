<?php

namespace App\Http\Controllers\Basic;

use App\Models\Basic\Biddinginformation;
use App\Models\Basic\Biddingproject;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class BiddingprojectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $biddingprojects = Biddingproject::latest('created_at')->paginate(15);
        $inputs = $request->all();
        return view('basic.biddingprojects.index', compact('biddingprojects','inputs'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('basic.biddingprojects.create');
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
        $this->validate($request, [
            'name' => 'required',
        ]);
        $input = $request->all();
//        dd($input);
        Biddingproject::create($input);

        return redirect('basic/biddingprojects');
    }

    public function getitemsbykey($key)
    {

        $query = Biddingproject::where('name', 'like', '%'.$key.'%')->orderBy('id', 'desc');

        $biddingprojects = $query->paginate(20);

        return response($biddingprojects);

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
        $biddingproject = Biddingproject::findOrFail($id);
        return view('basic.biddingprojects.edit', compact('biddingproject'));
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
//        dd($request->all());
        $this->validate($request, [
            'name' => 'required',
        ]);
        $biddingproject = Biddingproject::findOrFail($id);
        $biddingproject->update($request->all());

        return redirect('basic/biddingprojects');
    }

    public function showbiddinginformation($id)
    {
        $inputs='';
        $biddinginformations =Biddinginformation::where('biddingprojectid','=',$id)->paginate(15);
        return view('basic.biddingprojects.showbiddinginformation',compact('biddinginformations','id','inputs'));
    }

    public function deletebiddinginformation($informationid)
    {
//        dd($informationid);
        $biddinginformation = Biddinginformation::findOrFail($informationid);
        $id=$biddinginformation->biddingprojectid;
//        dd($id);
        $biddinginformation->biddingprojectid='';
        $biddinginformation->save();
        return redirect('/basic/biddingprojects/'.$id.'/showbiddinginformation');
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
        $biddingproject = Biddingproject::findOrFail($id);
        $relatebiddingcnt=$biddingproject->biddinginformation->count();
        if ($relatebiddingcnt>0)

        Biddingproject::destroy($id);
        return redirect('basic/biddingprojects');
    }
}

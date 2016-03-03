<?php

namespace App\Http\Controllers\Sales;

// use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Sales\Term;
use App\Http\Requests\Sales\TermRequest;
use Request;

class TermsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //
        $terms = Term::latest('created_at')->paginate(10);
        return view('sales.terms.index', compact('terms'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
        return view('sales.terms.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(TermRequest $request)
    {
        //
        $input = Request::all();
        Term::create($input);
        return redirect('sales/terms');
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
        $term = Term::findOrFail($id);
        return view('sales.terms.edit', compact('term'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(TermRequest $request, $id)
    {
        //
        $term = Term::findOrFail($id);
        $term->update($request->all());
        return redirect('sales/terms');
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
        Term::destroy($id);
        return redirect('sales/terms');
    }
}

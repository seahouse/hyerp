<?php

namespace App\Http\Controllers\Crm;

// use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Crm\Contact;
use App\Http\Requests\ContactRequest;
use Request;

class ContactsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //
        $contacts = Contact::latest('created_at')->with('addr')->paginate(10);
        return view('crm.contacts.index', compact('contacts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
        return view('crm.contacts.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(ContactRequest $request)
    {
        //
        $input = Request::all();
        Contact::create($input);
        return redirect('crm/contacts');
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
        $contact = Contact::findOrFail($id);
        return view('crm.contacts.edit', compact('contact'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(ContactRequest $request, $id)
    {
        //
        $contact = Contact::findOrFail($id);
        $contact->update($request->all());
        return redirect('crm/contacts');
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
        Contact::destroy($id);
        return redirect('crm/contacts');
    }
}

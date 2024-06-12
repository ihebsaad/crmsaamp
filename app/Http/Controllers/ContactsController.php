<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
#use DB;
use App\Models\Client;
use App\Models\CompteClient;
use App\Models\RetourClient;
use App\Models\Contact;
use App\Services\PhoneService;
use Illuminate\Support\Facades\DB;


class ContactsController extends Controller
{
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware(['auth']);
	}

	/**
	 * Show the application dashboard.
	 *
	 * @return \Illuminate\Http\Response
	 */



	public function create()
	{
		return view('contacts.create');
	}

	public function show($id)
	{
		//$contact=Contact::where('old_id',$id)->first();
		$contact=Contact::find($id);
		//Contact::updateWithSequentialIds();
		return view('contacts.show',compact('contact'));
	}


	public function update(Request $request, $id)
    {
        $request->validate([
            'Nom' => 'required',
         ]);

		$contact = Contact::find($id);
		$contact->update($request->all());

		return redirect()->route('contacts.show', $id)
				->with('success', 'Contact modifié');
	}

	public function store(Request $request)
    {
        $request->validate([
            'Nom' => 'required',

        ]);

        $client=RetourClient::create($request->all());
		return redirect()->route('show', $client->id)
		->with('success',' Contact ajouté');
	}


} // end class

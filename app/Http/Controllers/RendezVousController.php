<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
#use DB;
use App\Models\Client;
use App\Models\CompteClient;
use App\Models\RendezVous;
use App\Models\Contact;
use App\Models\User;
use Illuminate\Support\Facades\DB;


class RendezVousController extends Controller
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


	public function index()
	{
		//$rendezvous=RendezVous::orderBy('id','desc')->limit(50)->get();

		$rendezvous=RendezVous::where('Attribue_a',auth()->user()->name.' '.auth()->user()->lastname)
		->orWhere('user_id',auth()->user()->id)
		->orderBy('id','desc')->get();
		return view('rendezvous.list',compact('rendezvous'));
	}

	public function mesrendezvous()
	{
		//$rendezvous=RendezVous::orderBy('id','desc')->limit(50)->get();

		$rendezvous=RendezVous::where('Attribue_a',auth()->user()->name.' '.auth()->user()->lastname)
		->orWhere('user_id',auth()->user()->id)
		->orderBy('id','desc')->get();
		return view('rendezvous.list',compact('rendezvous'));
	}

	public function create($id)
	{
		$client=CompteClient::find($id);
		$contacts=Contact::where('cl_ident',$client->cl_ident)->get();
		$users=User::where('user_type','<>','')->get();
		return view('rendezvous.create',compact('client','contacts','users'));
	}

	public function show($id)
	{
		$rendezvous=RendezVous::find($id);
/*
		$contact=Contact::where('old_id',$rendezvous->ID_Contact)
		->orWhere('id',$rendezvous->ID_Contact)
		->first();
*/
		$contact=Contact::where('id',$rendezvous->ID_Contact_Salesforce)->first();

		if(! isset($contact))
			$contact=Contact::where('old_id',$rendezvous->ID_Contact_Salesforce)->first();

		return view('rendezvous.show',compact('rendezvous','contact'));
	}


	public function update(Request $request, $id)
    {
        $request->validate([
            'Subject' => 'required',
         ]);

		$rendezvous = RendezVous::find($id);
		$rendezvous->update($request->all());

		return redirect()->route('rendezvous.show', $id)
				->with('success', 'Rendez vous modifié');
	}

	public function store(Request $request)
    {
        $request->validate([
            'Subject' => 'required',

        ]);

        $rendezvous=RendezVous::create($request->all());
/*
		$contact=Contact::where('old_id',$rendezvous->ID_Contact)
		->orWhere('id',$rendezvous->ID_Contact)
		->first();*/

		$contact=Contact::where('id',$rendezvous->ID_Contact_Salesforce)->first();
		$client=CompteClient::find($rendezvous->AccountId);

		if(! isset($contact))
			$contact=Contact::where('old_id',$rendezvous->ID_Contact_Salesforce)->first();

		$rendezvous->Nom= $contact->Prenom.' '.$contact->Nom;
		$rendezvous->Account_Name=$client->Nom;
		$rendezvous->save();

		return redirect()->route('rendezvous.show', $rendezvous->id)
		->with('success','Rendez vous ajouté');
	}


} // end class

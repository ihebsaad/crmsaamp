<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
#use DB;
use App\Models\Client;
use App\Models\CompteClient;
use App\Models\RetourClient;
use App\Models\Contact;
use App\Models\Tache;
use App\Services\PhoneService;
use Illuminate\Support\Facades\DB;


class TachesController extends Controller
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
		//$taches=Tache::where('id','<>',null)->limit(1000)->orderBy('id','desc')->get();


		$taches=Tache::where(function ($query) {
			$query->where('type', 'Appel téléphonique')
				->orWhere('type', 'Envoyer email')
				->orWhere('type', 'Envoyer courrier');
		})->where('id','<>',null)
		->limit(1000)->orderBy('id','desc')->get();
		return view('taches.list',compact('taches'));
	}


	public function create($id)
	{
		$client=CompteClient::find($id);
		$contact=Contact::where('cl_ident',$client->cl_ident)->first();
		return view('taches.create',compact('client','contact'));
	}

	public function client_list($id)
	{
		$client=CompteClient::find($id);
		if($client->Id_Salesforce!='')
			$taches=Tache::where('ID_Compte',$client->Id_Salesforce)->get();
		else
			$taches=Tache::where('ID_Compte',$client->id)->get();

		return view('taches.list',compact('taches','client'));
	}

	public function contact_list($id)
	{
		$contact=Contact::find($id);
		$taches=Tache::where('ID_Contact',$id)->get();
		return view('taches.list',compact('taches','contact'));
	}

	public function show($id)
	{
		$tache=Tache::find($id);
		//ID_Compte
		$client=CompteClient::find($tache->ID_Compte);
		$contact=Contact::where('cl_ident',$client->cl_ident)->first();

		return view('taches.show',compact('tache','contact','contact','client'));
	}


	public function update(Request $request, $id)
    {
        $request->validate([
            'Subject' => 'required',
         ]);

		$tache = Tache::find($id);
		$tache->update($request->all());

		return redirect()->route('taches.show', $id)
				->with('success', 'Tâche modifiée');
	}

	public function store(Request $request)
    {
        $request->validate([
            'Subject' => 'required',

        ]);

        $retour=Tache::create($request->all());
		return redirect()->route('taches.show', $retour->id)
		->with('success','Tache ajoutée');
	}


} // end class

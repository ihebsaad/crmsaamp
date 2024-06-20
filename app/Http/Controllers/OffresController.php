<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
#use DB;
use App\Models\Client;
use App\Models\CompteClient;
use App\Models\RetourClient;
use App\Models\Contact;
use App\Models\Offre;
use App\Services\PhoneService;
use Illuminate\Support\Facades\DB;


class OffresController extends Controller
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
		//$offres=Offre::where('id','<>',null)->limit(1000)->orderBy('id','desc')->get();
		$offres=Offre::get();
		return view('offres.list',compact('offres'));
	}

	public function client_list($id)
	{
		$client=CompteClient::find($id);
		$offres=Offre::where('cl_id',$client->cl_ident)->get();

		return view('offres.list',compact('offres','client'));
	}

	public function create($id)
	{
		$client=CompteClient::find($id);
		$contact=Contact::where('cl_ident',$client->cl_ident)->first();
		return view('offres.create',compact('client','contact'));
	}

	public function show($id)
	{
		$offre=Offre::find($id);
		//$offre=Offre::where('old_id',$id)->first();
		//Offre::updateWithSequentialIds();
		return view('offres.show',compact('offre'));
	}


	public function update(Request $request, $id)
    {
        $request->validate([
            'Subject' => 'required',
         ]);

		$offre = Offre::find($id);
		$offre->update($request->all());

		return redirect()->route('offres.show', $id)
				->with('success', 'Tâche modifiée');
	}

	public function store(Request $request)
    {
        $request->validate([
            'Subject' => 'required',

        ]);

        $retour=Offre::create($request->all());
		return redirect()->route('offres.show', $retour->id)
		->with('success','Offre ajoutée');
	}


} // end class

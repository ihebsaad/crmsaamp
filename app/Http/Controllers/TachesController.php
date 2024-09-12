<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
#use DB;
use App\Models\Client;
use App\Models\CompteClient;
use App\Models\RetourClient;
use App\Models\Tache;
use App\Models\Agence;
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

	public function mestaches()
	{
		$taches=Tache::where('user_id',auth()->user()->id)
		->get();
		return view('taches.list',compact('taches'));
	}


	public function create($id)
	{
		$client=CompteClient::find($id);
		return view('taches.create',compact('client'));
	}

	public function client_list($id)
	{
		$client=CompteClient::find($id);
		$taches=Tache::where('mycl_id',$client->cl_ident)->get();
		return view('taches.list',compact('taches','client'));
	}

	public function show($id)
	{
		$tache=Tache::find($id);
		$client=CompteClient::find($tache->ID_Compte);
		return view('taches.show',compact('tache','client'));
	}


	public function update(Request $request, $id)
    {
        $request->validate([
            'Subject' => 'required',
         ]);

		$tache = Tache::find($id);
		$tache->update($request->all());


		$tache->save();

		return redirect()->route('taches.show', $id)
				->with('success', 'Tâche modifiée');
	}

	public function store(Request $request)
    {
        $request->validate([
            'Subject' => 'required',

        ]);

        $tache=Tache::create($request->all());


		$client=CompteClient::find($tache->ID_Compte);
		$agence_id=$client->agence_ident;
		$agence_name=Agence::where('agence_ident',$agence_id)->first()->agence_lib ?? '';
		$tache->Nom_de_compte=$client->Nom;
		$tache->Agence=$agence_name;


		$tache->save();

		return redirect()->route('taches.show', $tache->id)
		->with('success','Tache ajoutée');
	}


	public function destroy($id)
	{
 		$tache = Tache::find($id);

		if ($tache) {
			if(intval($tache->ID_Compte)>0)
				$client_id=$tache->ID_Compte;
			else
				$client_id=Client::where('cl_ident',$tache->mycl_id)->first();

			$tache->delete();

			$previousUrl = url()->previous();

			if (str_contains($previousUrl, '/show/' . $id)) {
				return redirect()->route('fiche',$client_id)->with('success', 'Supprimée avec succès');
			}
		}

		return back()->with('success', 'Supprimée avec succès');
	}


} // end class

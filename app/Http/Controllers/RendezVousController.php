<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
#use DB;
use App\Models\Client;
use App\Models\CompteClient;
use App\Models\RendezVous;
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
		$users=User::where('user_type','<>','')->get();
		return view('rendezvous.create',compact('client','users'));
	}

	public function show($id)
	{
		$rendezvous=RendezVous::find($id);

		$client=CompteClient::where('id',$rendezvous->AccountId)->first();
		$adresse=$client->adresse1.' - '.$client->zip;

		if($client->id==1 && $id!=1){
			$client=DB::table('CRM_CompteCLient')->where('Id_Salesforce',$rendezvous->AccountId)->first();
			$adresse=$client->Rue.' - '.$client->postalCode;
		}


		return view('rendezvous.show',compact('rendezvous','client','adresse'));
	}

	public function print($id)
	{
		$rendezvous=RendezVous::find($id);
		$client=CompteClient::find($rendezvous->AccountId);

		return view('rendezvous.print',compact('rendezvous','client'));
	}


	public function update(Request $request, $id)
    {
		/*
        $request->validate([
            'Subject' => 'required',
         ]);
*/
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

		$client=CompteClient::find($rendezvous->AccountId);

		$rendezvous->Account_Name=$client->Nom;
		$rendezvous->save();

		if($rendezvous->AccountId >0)
			return redirect()->route('fiche', ['id' => $rendezvous->AccountId])->with(['success' => "Rendez Vous ajouté "]);

		return redirect()->route('rendezvous.show', $rendezvous->id)
		->with('success','Rendez vous ajouté');
	}


	public function destroy($id)
	{
 		$rv = RendezVous::find($id);

		if ($rv) {
			$client_id=$rv->AccountId;
			$rv->delete();

			$previousUrl = url()->previous();

			if (str_contains($previousUrl, '/show/' . $id)) {
				return redirect()->route('fiche',$client_id)->with('success', 'Supprimé avec succès');
			}
		}

		return back()->with('success', 'Supprimé avec succès');
	}
} // end class

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
		if($id>0)
			$client=CompteClient::find($id);
		else
			$client=null;
		$users=User::where('user_type','<>','')->get();
		return view('rendezvous.create',compact('client','users'));
	}

	public function show($id)
	{
		$rendezvous=RendezVous::find($id);
		if($rendezvous->AccountId>0){
			$client=CompteClient::where('id',$rendezvous->AccountId)->first();
			$adresse1=$client->adresse1 ?? '';
			$zip=$client->zip ?? '';
			$adresse=$adresse1 .' - '.$zip ;

			if($client && $client->id==1 && $id!=1){
				$client=DB::table('CRM_CompteCLient')->where('Id_Salesforce',$rendezvous->AccountId)->first();
				$rue = $client->Rue ?? '' ;
				$zip =$client->postalCode ?? '';
				$adresse=$rue.' '.$zip;
			}

		}else{
			$client=null;
			$adresse='';
		}

		return view('rendezvous.show',compact('rendezvous','client','adresse'));
	}

	public function print($id)
	{
		$rendezvous=RendezVous::find($id);
		if($rendezvous->AccountId>0){
			$client=CompteClient::find($rendezvous->AccountId);
		}else{
			$client=null;
		}
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

        //$rendezvous=RendezVous::create($request->all());

		$rendezvous = RendezVous::create([
			'AccountId' => $request->input('AccountId') ?? 0,
			'created_by' => $request->input('created_by'),
			'mycl_id' => $request->input('mycl_id'),
			'user_id' => $request->input('user_id'),
			'Account_Name' => $request->input('Account_Name'),
			'Started_at' => $request->input('Started_at'),
			'heure_debut' => $request->input('heure_debut'),
			'End_AT' => $request->input('End_AT'),
			'heure_fin' => $request->input('heure_fin'),
			'Type' => $request->input('Type'),
			'Location' => $request->input('Location'),
			'Subject' => $request->input('Subject'),
			'Description' => $request->input('Description'),
		]);

		$rendezvous->save();
		if($request->input('AccountId') > 0){
		$client=CompteClient::find($rendezvous->AccountId);

		$rendezvous->Account_Name=$client->Nom;
		$rendezvous->save();
		}


		if ($request->hasFile('files')) {
			$fichiers = $request->file('files');
			$fileNames = [];

			foreach ($fichiers as $fichier) {
				$name = $fichier->getClientOriginalName();
				$path = public_path() . "/fichiers";
				$fichier->move($path, $name);
				$fileNames[] = $name;
			}

			// Serialize the filenames array
			$rendezvous->fichier = serialize($fileNames);
			$rendezvous->save();
		}

		if($rendezvous->AccountId >0)
			return redirect()->route('fiche', ['id' => $rendezvous->AccountId])->with(['success' => "Rendez Vous ajouté "]);

		return redirect()->route('rendezvous.show', $rendezvous->id)
		->with('success','Rendez vous ajouté');
	}


	public function deleteFile(Request $request, $id)
	{
		// Find the rendezvous by ID
		$rendezvous = Rendezvous::find($id);

		// Deserialize the stored file names
		$fileNames = unserialize($rendezvous->fichier);

		// Get the file name to delete
		$fileToDelete = $request->input('file_name');

		// Remove the file from the list
		if (($key = array_search($fileToDelete, $fileNames)) !== false) {
			unset($fileNames[$key]);
		}

		// Re-serialize the array and save the updated list
		$rendezvous->fichier = serialize(array_values($fileNames));
		$rendezvous->save();

		// Delete the file from the filesystem
		$filePath = public_path() . "/fichiers/" . $fileToDelete;
		if (file_exists($filePath)) {
			unlink($filePath);  // Delete the file
		}

		return redirect()->back()->with('success', 'Fichier supprimé avec succès.');
	}

	public function destroy($id)
	{
 		$rv = RendezVous::find($id);

		$previousUrl = url()->previous();

		if ($rv) {
			$client_id=$rv->AccountId;
			$rv->delete();

			if (str_contains($previousUrl, '/show/' . $id) && $client_id >0) {
				return redirect()->route('fiche',$client_id)->with('success', 'Supprimé avec succès');
			}
		}

		if (str_contains($previousUrl, 'exterieurs') || $client_id ==0) {
			return redirect()->route('agenda')->with('success', 'Supprimé avec succès');
		}

		return redirect()->route('agenda')->with('success', 'Supprimé avec succès');
		//return back()->with('success', 'Supprimé avec succès');
	}
} // end class

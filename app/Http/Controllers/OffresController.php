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
use App\Services\GEDService;


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
		$offres=Offre::limit(100)->orderBy('id','desc')->get();
		//dd($offres);
		return view('offres.index',compact('offres'));
	}

	public function test()
	{
		$offres = Offre::limit(100)->get();
		return response()->json($offres);
	}

	public function client_list($id)
	{
		$client=CompteClient::find($id);

		if($client->cl_ident!=0)
			$offres=Offre::where('cl_id',$client->cl_ident)->get();
		else
			$offres=array();

		return view('offres.index',compact('offres','client'));
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
		$folders=array();
		$files=false;
		try{
			$folderContent=GEDService::getFolderParent($offre->old_id);
			//dd($folderContent);
			//getFolderList
			//$folderContent=GEDService::getFolderContent($offre->old_id);
		} catch (\Exception $e) {
			\Log::info(' erreur GED '.$e->getMessage());
			return "Erreur : " . $e->getMessage();
		}
		finally {
			\Log::info('GED folder show ' );
		}
		return view('offres.show',compact('offre','folders','files','folderContent'));
	}


	public function update(Request $request, $id)
    {
		/*
        $request->validate([
            'Subject' => 'required',
         ]);
*/
		$offre = Offre::find($id);
		$offre->update($request->all());

		return redirect()->route('offres.show', $id)
				->with('success', 'Tâche modifiée');
	}

	public function store(Request $request)
    {
        $request->validate([
            'Nom_offre' => 'required',
			'fichier.*' => 'file|mimes:pdf|max:26000', // 26 Mo par fichier

        ]);

        //$offre=Offre::create($request->all());

		$offre = Offre::create([
			'cl_id' => $request->input('cl_id'),
			'Nom_offre' => $request->input('Nom_offre'),
			'Date_creation' => $request->input('Date_creation'),
			'Produit_Service' => $request->input('Produit_Service'),
			'Description' => $request->input('Description'),
			// Other fields as necessary
		]);


/*
		if($request->file('fichier')!=null)
        {
			$fichier=$request->file('fichier');
         	$name =  $fichier->getClientOriginalName();
            $path = public_path()."/offres";
           	$fichier->move($path, $name);
			$offre->fichier=$name;
			$offre->save();
        }
*/
		if ($request->hasFile('fichier')) {
			$fichiers = $request->file('fichier');
			$fileNames = [];

			foreach ($fichiers as $fichier) {
				$name = $fichier->getClientOriginalName();
				$path = public_path() . "/offres";
				$fichier->move($path, $name);
				$fileNames[] = $name;
			}

			// Serialize the filenames array
			$offre->fichier = serialize($fileNames);
			$offre->save();
		}

		return redirect()->route('offres.show', $offre->id)
		->with('success','Offre ajoutée');
	}


} // end class

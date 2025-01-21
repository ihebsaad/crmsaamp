<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
#use DB;
use App\Models\Client;
use App\Models\CompteClient;
use App\Models\RetourClient;
use App\Models\Contact;
use App\Models\Offre;
use App\Models\User;
use App\Services\PhoneService;
use Illuminate\Support\Facades\DB;
use App\Services\GEDService;
use App\Services\SendMail;
use App\Models\File;


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
			$offres=Offre::where('nom_compte',trim($client->Nom))->get();

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
		$fichiers=File::where('parent','offres')->where('parent_id',$offre->id)->get();
		$historiques= DB::table('historique_offres')->where('offre',$id)->get();
		try{
			if($offre->old_id!=null)
				$folderContent=GEDService::getFolderParent($offre->old_id);
			else
				$folderContent=GEDService::getFolderParent($offre->id);

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
		return view('offres.show',compact('offre','folders','files','folderContent','fichiers','historiques'));
	}


	public function store(Request $request)
    {
        $request->validate([
            'Nom_offre' => 'required',
			'fichier.*' => 'file|mimes:pdf|max:26000', // 26 Mo par fichier

        ]);

        //$offre=Offre::create($request->all());
		$Date1 = date('Y-m-d');
		$date_relance = date('Y-m-d', strtotime($Date1 . " + 15 days"));

		$offre = Offre::create([
			'cl_id' => $request->input('cl_id') ?? 0,
			'mycl_id' => $request->input('mycl_id') ?? 0,
			'Nom_offre' => $request->input('Nom_offre'),
			'Date_creation' => $request->input('Date_creation'),
			'Produit_Service' => $request->input('Produit_Service'),
			'Description' => $request->input('Description'),
			'user_id' => $request->input('user_id'),
			'nom_compte' => $request->input('nom_compte') ?? '',
			'type' => $request->get('type'),
			'date_relance' => $date_relance,
			//'statut' => '',
			// Other fields as necessary
		]);


		$offre->save();

		$client=Client::find($offre->mycl_id);
		$user=User::find($offre->user_id);
		$agence=DB::table('agence')->where('agence_ident',$client->agence_ident)->first();
		$contenu="Bonjour,<br><br> l'offre N° $offre->id de type $offre->type est créée.<br><br><b>Client:</b> $offre->nom_compte <br><b>Nom:</b> $offre->Nom_offre<br><b>Description:</b> $offre->Description   <br><br><i>l'équipe SAAMP</i>";

		if($offre->type=='TG')
		{
			SendMail::send($user->email,'Offre Créée',$contenu);
			if(isset($agence))
				SendMail::send(trim($agence->mail),'Offre Créée',$contenu);

			$offre->statut='OK';
			$offre->save();
		}

		if($offre->type=='Hors TG')		//user_id 10
		{
			SendMail::send(env('Email_sebastien'),"Demande de validation de l'offre ",$contenu);
			SendMail::send(env('Email_said'),"Demande de validation de l'offre ",$contenu);
			SendMail::send(env('Email_elisabeth'),"Demande de validation de l'offre ",$contenu);

			//$offre->statut='OK';
			//$offre->save();
		}

		if($offre->type=='Apprêts/Bij/DP')		//user_id 39
		{
			SendMail::send(env('Email_christelle'),"Demande de validation de l'offre",$contenu);
			SendMail::send(env('Email_said'),"Demande de validation de l'offre ",$contenu);
			SendMail::send(env('Email_elisabeth'),"Demande de validation de l'offre ",$contenu);

			//$offre->statut='OK';
			//$offre->save();
		}

		if($request->input('cl_id') > 0)//{
			$result=GEDService::OffreDocs($request->input('cl_id'),$offre->id,$request->input('mycl_id'));

		//}else{
			if ($request->hasFile('files')) {
				$fichiers = $request->file('files');

				foreach ($fichiers as $fichier) {
					$name = $fichier->getClientOriginalName();
					$path = public_path("fichiers/offres");
					$fichier->move($path, $name);

					// Store each file in the files table
					File::create([
						'name' => $name,
						'parent_id' => $offre->id,
						'parent' => 'offres'
					]);
				}
			}
		//}

		return redirect()->route('offres.client_list', $client->id)
		->with('success','Offre ajoutée');
	}



	public function update(Request $request, $id)
    {
		/*
        $request->validate([
            'Subject' => 'required',
         ]);
*/
		$offre = Offre::find($id);
		if($offre->mycl_id > 0){
			$client=Client::find($offre->mycl_id);
			$agence=DB::table('agence')->where('agence_ident',$client->agence_ident)->first();
		}

		if($offre->user_id > 0)
			$user=User::find($offre->user_id);


		if($offre->type=='Hors TG' && auth()->user()->id==10)
		{
			//$offre->update($request->all());
			$offre->statut= $request->get('statut');
			if($offre->statut=='OK'){
				$offre->valide_par=auth()->user()->id;
				$offre->date_valide=date('d/m/Y').' à '.date('H:i');
			}
			$offre->save();

			if($request->statut=='OK'){
				$contenu="Bonjour,<br><br> l'offre N° $offre->id de type $offre->type est validée.<br><br><b>Client:</b> $offre->nom_compte <br><b>Nom:</b> $offre->Nom_offre<br><b>Description:</b> $offre->Description   <br><br><i>l'équipe SAAMP</i>";
				SendMail::send($user->email,'Offre validée',$contenu);
				if(isset($agence))
					SendMail::send(trim($agence->mail),'Offre validée',$contenu);

			}
			elseif($request->statut=='KO'){
				$contenu="Bonjour,<br><br> l'offre N° $offre->id de type $offre->type est à refaire.<br><br><b>Client:</b> $offre->nom_compte <br><b>Nom:</b> $offre->Nom_offre<br><b>Description:</b> $offre->Description   <br><br><i>l'équipe SAAMP</i>";
				SendMail::send($user->email,'Offre à refaire',$contenu);
				if(isset($agence))
					SendMail::send(trim($agence->mail),'Offre à refaire',$contenu);
			}

		}
		if($offre->type=='Apprêts/Bij/DP' && auth()->user()->id==39)
		{
			//$offre->update($request->all());
			$offre->statut= $request->get('statut');
			if($offre->statut=='OK'){
				$offre->valide_par=auth()->user()->id;
				$offre->date_valide=date('d/m/Y').' à '.date('H:i');
			}
			$offre->save();

			if($request->statut=='OK'){
				$contenu="Bonjour,<br><br> l'offre N° $offre->id de type $offre->type est validée.<br><br><b>Client:</b> $offre->nom_compte <br><b>Nom:</b> $offre->Nom_offre<br><b>Description:</b> $offre->Description   <br><br><i>l'équipe SAAMP</i>";
				SendMail::send($user->email,'Offre validée',$contenu);
				if(isset($agence))
					SendMail::send(trim($agence->mail),'Offre validée',$contenu);

			}
			elseif($request->statut=='KO'){
				$contenu="Bonjour,<br><br> l'offre N° $offre->id de type $offre->type est à refaire.<br><br><b>Client:</b> $offre->nom_compte <br><b>Nom:</b> $offre->Nom_offre<br><b>Description:</b> $offre->Description   <br><br><i>l'équipe SAAMP</i>";
				SendMail::send($user->email,'Offre à refaire',$contenu);
				if(isset($agence))
					SendMail::send(trim($agence->mail),'Offre à refaire',$contenu);
			}

		}
		if($offre->type=='TG'){
			//$offre->update($request->all());
			$offre->statut= $request->get('statut');
			if($offre->statut=='OK'){
				$offre->valide_par=auth()->user()->id;
				$offre->date_valide=date('d/m/Y').' à '.date('H:i');
			}
			$offre->save();
		}

		$offre->date_relance=$request->date_relance;
		$offre->commentaire=$request->commentaire;
		$offre->save();

		//$offre->update($request->all());

		if ($request->hasFile('files')) {
			$fichiers = $request->file('files');

			foreach ($fichiers as $fichier) {
				$name = $fichier->getClientOriginalName();
				$path = public_path("fichiers/offres");
				$fichier->move($path, $name);

				// Store each file in the files table
				File::create([
					'name' => $name,
					'parent_id' => $offre->id,
					'parent' => 'offres'
				]);
			}
		}

		return redirect()->route('offres.client_list', $client->id)
			->with('success','Offre modifiée');
	}

	/** VIEW **/
	public function edit_file($item,$id,$name)
	{
		$offre= Offre::find($id);
		return view('offres.edit_file',compact('offre','item','id','name'));

	}

	public function relancer(Request $request)
	{
		$id= $request->get('id');
		$offre= Offre::find($id);
		//$client=Client::find($offre->mycl_id);
		$user=User::find($offre->user_id);
		$contenu="Bonjour,<br><br>Offre <a href='https://crm.mysaamp.com/offres/show/$offre->id'> $offre->id</a> de type $offre->type.<br><br><b>Client:</b> $offre->nom_compte <br><b>Nom:</b> $offre->Nom_offre<br><b>Description:</b> $offre->Description   <br><br><i>l'équipe SAAMP</i>";
		if(isset($user->email))
			SendMail::send($user->email,"Relance de l'offre $offre->Nom_offre",$contenu);

		return 1;
	}

		public function editFile(Request $request)
	{
		$itemId= $request->get('item_id');
		$attachment=$request->file('file');
		$id=$request->get('id');

		try{
			$result = GEDService::editItem($itemId, $attachment, $id,'offre');
			return $result ;
		} catch (\Exception $e) {
			\Log::info(' erreur GED replacement '.$e->getMessage());
			return "Erreur modification de fichier : " . $e->getMessage();
		}
	}



	public function destroy($id)
	{
 		$offre = Offre::find($id);

		if ($offre) {
			$cl_id=$offre->cl_id;
			if($cl_id>0){
				$client=Client::where('cl_ident',$cl_id)->first();
			}else{
				$client=Client::where('Nom',$offre->nom_compte)->first();
			}

			$offre->delete();

			$previousUrl = url()->previous();

			if (str_contains($previousUrl, '/show/' . $id)) {
				return redirect()->route('fiche',$client->id)->with('success', 'Supprimée avec succès');
			}
		}

		return back()->with('success', 'Supprimée avec succès');
	}


	public function add_hist(Request $request)
    {

		DB::table('historique_offres')->insert([
			'offre'=>$request->get('offre'),
			'details'=>$request->get('details'),
			'statut'=>$request->get('statut'),
			'date_point'=>$request->get('date_point'),
		]);

		$data=array();
		if($request->get('date_point')!='')
			$data['date_point']= date('d/m/Y', strtotime($request->get('date_point')));
		else
			$data['date_point']=' ';

		return $data;
	}

	public function delete_hist(Request $request)
    {
		DB::table('historique_offres')->where(
			'id',$request->get('hist'),
		)->delete();

		return 1;
	}

} // end class

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
#use DB;
use App\Models\Client;
use App\Models\CompteClient;
use App\Models\Agence;
use App\Models\Contact;
use App\Models\Offre;
use App\Models\User;
use App\Services\PhoneService;
use Illuminate\Support\Facades\DB;
use App\Services\GEDService;
use App\Services\SendMail;
use App\Models\File;
use App\Models\Consultation;
use Yajra\DataTables\Facades\DataTables;


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
        Consultation::create(['user' => auth()->id(),'app' => 2,'page' => "liste des offres"]);
		return view('offres.index',compact('offres'));
	}


	
	    public function getData(Request $request)
    {
        if ($request->ajax()) {

			
            $query = Offre::with(['user', 'validator', 'client.agence'])
                ->select('CRM_OffrePrix.*');
			
			// Responsable d'agence
			if(auth()->user()->user_role == 4){
                $query->whereHas('client', function ($q)  {
                    $q->where('agence_ident', auth()->user()->agence_ident);
                });
			}
			// ADV
			if(auth()->user()->user_role == 6){
                $query->whereHas('client', function ($q)  {
                    $q->where('agence_ident', auth()->user()->agence_ident)
					->orWhere('ADV', trim(auth()->user()->name.' '.auth()->user()->lastname));
                });
			}


			// Commercial
			if(auth()->user()->user_role == 7){

				$Rep = DB::table('representant')->where('users_id',auth()->id())->first();
				// créé par lui
				$query->where('user_id', auth()->id());
				// et pour ses clients
                $query->whereHas('client', function ($q) use ($Rep) {
                    $q->where('commercial', $Rep->id)
					->orWhere('commercial_support', $Rep->id);
                });
			}
			

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('nom_offre', function ($offre) {
					$fichiers = File::where('parent', 'offres')->where('parent_id', $offre->id)->count();
					if(  $offre->fichier!= null){
						$fichiers++;
					}
                    return '<a href="' . route('offres.show', ['id' => $offre->id]) . '" class="text-primary font-weight-bold">' . $offre->Nom_offre . '('.$fichiers.')</a>';
                })
                ->addColumn('created_by', function ($offre) {
                    return $offre->user ? $offre->user->name . ' ' . $offre->user->lastname : '';
                })
                ->addColumn('agence', function ($offre) {
                    return $offre->client && $offre->client->agence ? $offre->client->agence->agence_lib : '';
                })
                ->addColumn('status', function ($offre) {
                    $class = $offre->statut == 'OK' ? 'badge-success' : 'badge-danger';
                    return '<span class="badge ' . $class . '">' . $offre->statut . '</span>';
                })
			->addColumn('validation', function ($offre) {
				$validatorName = '';
				if ($offre->validator) {
					$validatorName = $offre->validator->name . ' ' . $offre->validator->lastname;
				}
				
				$result = e($validatorName);
				if ($offre->date_valide) {
					$result .= '<br><small class="text-muted">' . e($offre->date_valide) . '</small>';
				}
				
				return $result;
			})
                ->addColumn('is_validated', function ($offre) {
                    return $offre->Offre_validee ? 'Oui' : 'Non';
                })
                ->addColumn('commentaire', function ($offre) {
                    return $offre->commentaire ;
                })			
				/*
                ->addColumn('actions', function ($offre) {
                    return '<div class="btn-group" role="group">
                        <a href="' . route('offres.show', $offre->id) . '" class="btn btn-sm btn-outline-primary" title="Voir">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="' . route('offres.edit', $offre->id) . '" class="btn btn-sm btn-outline-warning" title="Modifier">
                            <i class="fas fa-edit"></i>
                        </a>
                    </div>';
                })*/
                ->filter(function ($query) use ($request) {
                    // Filtre par validation
                    if ($request->has('validation_filter') && $request->validation_filter != '') {
                        if ($request->validation_filter == 'validated') {
                            $query->where('date_valide','<>','');
                        } elseif ($request->validation_filter == 'not_validated') {
                            $query->whereNull('date_valide');
                        }
                    }

                    // Filtre par agence
                    if ($request->has('agence_filter') && $request->agence_filter != '') {
                        $query->whereHas('client', function ($q) use ($request) {
                            $q->where('agence_ident', $request->agence_filter);
                        });
                    }

                    // Filtre par créateur
                    if ($request->has('user_filter') && $request->user_filter != '') {
                        $query->where('user_id', $request->user_filter);
                    }

                    // Filtre par statut
                    if ($request->has('status_filter')  && $request->status_filter != '' ) {
						if($request->status_filter=='vide')
                        	$query->whereNull('statut');
						else
                        	$query->where('statut', $request->status_filter);
                    }
                })
                ->rawColumns(['nom_offre', 'status', 'validation', 'actions'])
                ->make(true);
			
			}
    }
	    public function liste()
    {
		$user=auth()->user();
        // Données pour les filtres
        $agences = Agence::orderBy('agence_lib')->get();
        $users = User::where('email','like','%@saamp.com')->orderBy('name')->get();
        $statuts = Offre::distinct()->pluck('statut')->filter();

        Consultation::create([
            'user' => auth()->id(),
            'app' => 2,
            'page' => "liste des offres"
        ]);

        return view('offres.liste', compact('agences', 'users', 'statuts','user'));
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

		Consultation::create(['user' => auth()->id(),'app' => 2,'page' => "liste des offres"]);

		return view('offres.index',compact('offres','client'));
	}

	public function create($id)
	{
		$client=CompteClient::find($id);
		$contact=Contact::where('cl_ident',$client->cl_ident)->first();

		Consultation::create(['user' => auth()->id(),'app' => 2,'page' => "Ajouter une offre"]);

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
		Consultation::create(['user' => auth()->id(),'app' => 2,'page' => "Affichage de l'offre $offre->Nom_offre"]);

		return view('offres.show',compact('offre','folders','files','folderContent','fichiers','historiques'));
	}

/*
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
		//$contenu="Bonjour,<br><br> l'offre N° $offre->id de type $offre->type est créée.<br><br><b>Client:</b> $offre->nom_compte <br><b>Nom:</b> $offre->Nom_offre<br><b>Description:</b> $offre->Description   <br><br><i>l'équipe SAAMP</i>";
		
		self::sendOfferMail($user, $client, $agence, $offre,'creation');

		// validation auto TG
		if($offre->type=='TG' || auth()->id()==35)
		{

			$offre->statut='OK';
			$offre->save();
 
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
*/

	
	public function store(Request $request) 
	{
		// Validation renforcée
		$request->validate([
			'Nom_offre' => 'required|string|max:255',
			'Date_creation' => 'required|date',
			'type' => 'required|string',
			'files' => 'required|array|min:1', // Au moins un fichier
			'files.*' => 'required|file|mimes:pdf|max:26624', // 26 Mo = 26624 KB
		]);

		// Vérification supplémentaire des fichiers
		if (!$request->hasFile('files') || empty($request->file('files'))) {
			return back()->withErrors(['files' => 'Au moins un fichier PDF est requis.'])->withInput();
		}

		try {
			// Utilisation d'une transaction pour éviter les données incohérentes
			DB::beginTransaction();

			// Préparation des données
			$Date1 = date('Y-m-d');
			$date_relance = $request->input('date_relance') ?: date('Y-m-d', strtotime($Date1 . " + 15 days"));

			// Création de l'offre
			$offre = Offre::create([
				'cl_id' => $request->input('cl_id') ?? 0,
				'mycl_id' => $request->input('mycl_id') ?? 0,
				'Nom_offre' => $request->input('Nom_offre'),
				'Date_creation' => $request->input('Date_creation'),
				'Description' => $request->input('Description'),
				'user_id' => $request->input('user_id'),
				'nom_compte' => $request->input('nom_compte', ''),
				'type' => $request->input('type'),
				'date_relance' => $date_relance,
			]);

			// Récupération des informations nécessaires
			$client = Client::find($offre->mycl_id);
			$user = User::findOrFail($offre->user_id);
			$agence = DB::table('agence')->where('agence_ident', $client->agence_ident)->first();

			// Traitement des fichiers
			$filesProcessed = 0;
			
			if ($request->input('cl_id') > 0) {
				// Traitement via GED
				$result = GEDService::OffreDocs($request->input('cl_id'), $offre->id, $request->input('mycl_id'));
				$filesProcessed = 1; // Supposons que GED traite les fichiers
			}
			
			// Traitement des fichiers uploadés
			if ($request->hasFile('files')) {
				$fichiers = $request->file('files');
				
				foreach ($fichiers as $fichier) {
					if ($fichier->isValid()) {
						$name = time() . '_' . $fichier->getClientOriginalName(); // Nom unique
						$path = public_path("fichiers/offres");
						
						// Créer le dossier s'il n'existe pas
						if (!file_exists($path)) {
							mkdir($path, 0755, true);
						}
						
						$fichier->move($path, $name);

						// Enregistrer le fichier en base
						File::create([
							'name' => $name,
							'parent_id' => $offre->id,
							'parent' => 'offres'
						]);
						
						$filesProcessed++;
					}
				}
			}

			// Vérification qu'au moins un fichier a été traité
			if ($filesProcessed === 0) {
				throw new \Exception('Aucun fichier n\'a pu être traité.');
			}

			// Envoi de l'email
			self::sendOfferMail($user, $client, $agence, $offre, 'creation');

			// Validation automatique pour TG
			if ($offre->type == 'TG' || auth()->id() == 35) {
				$offre->statut = 'OK';
				$offre->save();
			}

			DB::commit();

			return redirect()->route('offres.client_list', $client->id)
				->with('success', 'Offre ajoutée avec succès');

		} catch (\Exception $e) {
			DB::rollback();
			
			// Log de l'erreur
			\Log::error('Erreur lors de la création de l\'offre: ' . $e->getMessage());
			
			return back()->withErrors(['error' => 'Une erreur est survenue lors de la création de l\'offre: ' . $e->getMessage()])->withInput();
		}
	}


	public function sendOfferMail($user, $client, $agence, $offre ,$mail)
	{
		if($mail=='creation')
 		{ 
			$contenu="Bonjour,<br><br>Une nouvelle offre de prix est créée<br><br>";
			$objet="Une nouvelle offre de prix est créée";
		}
		elseif($mail=='validation')
		{
			$contenu="Bonjour,<br><br>L'offre de prix N° $offre->id est validée<br><br>";
			$objet="L'offre de prix N° $offre->id est validée";
		}
			elseif($mail=='refaire')
		{
			$contenu="Bonjour,<br><br>L'offre de prix N° $offre->id est à refaire<br><br>";
			$objet="L'offre de prix N° $offre->id est à refaire";
		}
		$contenu.="<b>Nom:</b> $offre->Nom_offre <br>";
		$contenu.="<b>Par:</b> $user->name $user->lastname <br>";		
		$contenu.="<b>Type:</b> $offre->type <br>";		
		$contenu.="<b>Client:</b> $offre->nom_compte <br>";
		$contenu.="<b>Description:</b> $offre->Description <br>";

		if($offre->commentaire!='')
			$contenu.="<b>Commentaire:</b> $offre->commentaire <br>";
 
		if($offre->type=='Hors TG - Affinage' && $mail=='creation')
			$contenu.="<br>L'offre est en attente de validation par Mr Sébastien Canesson<br><br>";
		elseif($offre->type=='Hors TG - Apprêts/Bij/DP'  && $mail=='creation')
			$contenu.="<br>L'offre est en attente de validation par Mme Christelle Correia<br><br>";

		$contenu.="Vous pouvez accéder à l'offre en cliquant sur le lien suivant :<br>";
		$contenu.="<a href='https://crm.mysaamp.com/offres/show/$offre->id' target='_blank'>Voir l'offre </a>";		
		$contenu.="<br><br><i>Cordialement<br>";
		$contenu.="L'équipe CRM SAAMP</i>";

 		//Cet e-mail est envoyé à la personne qui a créé l'offre, au responsable d'agence du client, à la direction (Jean-Luc Escard, Elisabeth Escard, Said El Marouani), à nous les administrateurs, ainsi qu'au commercial principal de ce client s'il est différent de celui qui a créé l'offre, et bien sûr à la personne qui doit valider l'offre (Sébastien ou Christelle).
		
		// direction
		SendMail::send(env('Email_jean'),$objet,$contenu);
		SendMail::send(env('Email_elisabeth'),$objet,$contenu);
		SendMail::send(env('Email_said'),$objet,$contenu);
		// admins
		SendMail::send(env('Admin_iheb'),$objet,$contenu);
		SendMail::send(env('Admin_remy'),$objet,$contenu);
		SendMail::send(env('Admin_reyad'),$objet,$contenu);

		if($offre->type=='Hors TG - Affinage')		//user_id 10 sebastien
		{
			SendMail::send(env('Email_sebastien'),$objet,$contenu);
		}

		if($offre->type=='Hors TG - Apprêts/Bij/DP')		//user_id 39 christelle
		{
			SendMail::send(env('Email_christelle'),$objet,$contenu);
		}	

		// agence responsable
		if(isset($agence) && isset($agence->email_responsable) && $agence->email_responsable!='' && $agence->email_responsable!=env('Email_sebastien') && $agence->email_responsable!=env('Email_said')) 
		{
			SendMail::send(trim($agence->email_responsable),$objet,$contenu);
		}

		//commercial
		$rep  = DB::table('representant')->find($client->commercial);
		if(isset($rep)){ 
			$user_comm =  User::find($rep->users_id);

			// si cerateur != commercial
			if(isset($user_comm) && $offre->user_id!=$user_comm->id  )
			{
				SendMail::send(trim($user_comm->email),$objet,$contenu);
			}
		}

		//ceateur
		SendMail::send(trim($user->email),$objet,$contenu);

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

		$offre->date_relance=$request->date_relance;
		$offre->commentaire=$request->commentaire;
		$offre->save();

		if($offre->type=='Hors TG - Affinage' && auth()->user()->id==10)
		{
			//$offre->update($request->all());
			$offre->statut= $request->get('statut');
			if($offre->statut=='OK'){
				$offre->valide_par=auth()->user()->id;
				$offre->date_valide=date('d/m/Y').' - '.date('H:i');
			}
			$offre->save();

			if($request->statut=='OK'){
				/*$contenu="Bonjour,<br><br> l'offre N° $offre->id de type $offre->type est validée.<br><br><b>Client:</b> $offre->nom_compte <br><b>Nom:</b> $offre->Nom_offre<br><b>Description:</b> $offre->Description   <br><br><i>l'équipe SAAMP</i>";
				SendMail::send($user->email,'Offre validée',$contenu);
				if(isset($agence))
					SendMail::send(trim($agence->mail),'Offre validée',$contenu);
*/
				self::sendOfferMail($user, $client, $agence, $offre,'validation');

			}
			elseif($request->statut=='KO'){
				/*$contenu="Bonjour,<br><br> l'offre N° $offre->id de type $offre->type est à refaire.<br><br><b>Client:</b> $offre->nom_compte <br><b>Nom:</b> $offre->Nom_offre<br><b>Description:</b> $offre->Description   <br><br><i>l'équipe SAAMP</i>";
				SendMail::send($user->email,'Offre à refaire',$contenu);
				if(isset($agence))
					SendMail::send(trim($agence->mail),'Offre à refaire',$contenu);
					*/
				self::sendOfferMail($user, $client, $agence, $offre,'refaire');

			}

		}
		if($offre->type=='Hors TG - Apprêts/Bij/DP' && auth()->user()->id==39)
		{
			//$offre->update($request->all());
			$offre->statut= $request->get('statut');
			if($offre->statut=='OK'){
				$offre->valide_par=auth()->user()->id;
				$offre->date_valide=date('d/m/Y').' à '.date('H:i');
			}
			$offre->save();

			if($request->statut=='OK'){/*
				$contenu="Bonjour,<br><br> l'offre N° $offre->id de type $offre->type est validée.<br><br><b>Client:</b> $offre->nom_compte <br><b>Nom:</b> $offre->Nom_offre<br><b>Description:</b> $offre->Description   <br><br><i>l'équipe SAAMP</i>";
				SendMail::send($user->email,'Offre validée',$contenu);
				if(isset($agence))
					SendMail::send(trim($agence->mail),'Offre validée',$contenu);
				*/
				self::sendOfferMail($user, $client, $agence, $offre,'validation');


			}
			elseif($request->statut=='KO'){
				/*
				$contenu="Bonjour,<br><br> l'offre N° $offre->id de type $offre->type est à refaire.<br><br><b>Client:</b> $offre->nom_compte <br><b>Nom:</b> $offre->Nom_offre<br><b>Description:</b> $offre->Description   <br><br><i>l'équipe SAAMP</i>";
				SendMail::send($user->email,'Offre à refaire',$contenu);
				if(isset($agence))
					SendMail::send(trim($agence->mail),'Offre à refaire',$contenu);
*/
				self::sendOfferMail($user, $client, $agence, $offre,'refaire');

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

		$offre=Offre::find($request->get('offre'));
		$user=User::find($offre->user_id);

		if($request->get('statut')==3){
			$offre->statut='OK';
		}
		else{
			$offre->statut='KO';
		}
		$offre->save();

		$data=array();
		if($request->get('date_point')!=''){
			$data['date_point']= date('d/m/Y', strtotime($request->get('date_point')));
		}
		else{
			$data['date_point']=' ';
		}

		$data['satut']=$offre->statut;

		$message="Bonjour $user->name $user->lastname,<br><br>Nouveau staut pour l'offre de prix <a href='https://crm.mysaamp.com/offres/show/$offre->id'>$offre->Nom_offre</a><br><br>";
		$message.="<b>Client:</b> ".$offre->nom_compte ."<br>";
		$message.="<b>Date de création:</b> ".date('d/m/Y', strtotime($offre->Date_creation)) ."<br>";
		$message.="<b>Type:</b>  $offre->type <br>";
		$message.="<b>Produit:</b> $offre->Produit_Service  <br><br>";
		$message.="<b>Statut:</b> ". $offre->statut ."  <br>";
		$message.="<b>Détails:</b> ".  $request->get('details') ." <br>";
		$message.="<br><br><br>";
		$message.="<i>Cordialement<br>";
		$message.="L'équipe CRM SAAMP</i>";

		SendMail::send($user->email,"Nouveau statut pour l'offre de prix $offre->Nom_offre",$message);

		return $data;
	}

	public function delete_hist(Request $request)
    {
		DB::table('historique_offres')->where(
			'id',$request->get('hist'),
		)->delete();

		return 1;
	}

/*
	public static function countOfrresGed(){
		$apiUrl = "https://ged.maileva.com/api/folder/name/DOCUMENTS%20OFFRES%20DE%20PRIX";

		$response = GEDService::curlExecute($apiUrl);

		$headers = array(
			'Content-Type: application/json',
			'Auth-Token: ' . GEDService::getToken()
		);
		// Décodage de la réponse JSON
		$data = json_decode($response, true);

		if ($data !== null && $data['success'] === true) {
			return 1;
		}else
		{
			return 0;
		}
	}
*/
} // end class

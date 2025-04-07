<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
#use DB;
use App\Models\Client;
use App\Models\CompteClient;
use App\Models\RetourClient;
use App\Models\Agence;
use App\Models\Contact;
use App\Models\File;
use App\Services\SendMail;
use Illuminate\Support\Facades\DB;
use App\Models\Consultation;


class RetoursController extends Controller
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
		if( auth()->user()->user_role== 1 || auth()->user()->user_role== 2 ||auth()->user()->user_role== 3 || auth()->user()->user_role == 5){
			$retours = RetourClient::orderBy('id', 'desc')->limit(1000)->get();
		}else{
			//$retours = RetourClient::orderBy('id', 'desc')->get();
			$retours = DB::table('CRM_RetourClient as rc')
			->join('client as c', 'rc.cl_id', '=', 'c.cl_ident')
			->where('c.agence_ident', auth()->user()->agence_ident)
			->select('rc.*', 'c.agence_ident') // Select fields as needed
			->orderBy('rc.name', 'desc') // Adjust as needed; 'name' should be in `rc` or `c`
			->get();

		}
		$agences = DB::table('agence')->get();
        Consultation::create(['user' => auth()->id(),'app' => 2,'page' => "Liste des réclamations"]);

		return view('retours.index', compact('retours','agences'));
	}

	public function create($id)
	{
		$client = CompteClient::find($id);
		$retour = RetourClient::where('cl_id', $client->cl_ident)->first();
		$contacts = Contact::where('cl_ident', $client->cl_ident)->get();
		$agences = DB::table('agence')->get();

		Consultation::create(['user' => auth()->id(),'app' => 2,'page' => "Création du réclamation"]);

		return view('retours.create', compact('retour', 'client', 'contacts', 'agences'));
	}

	public function show($id)
	{
		$agences = DB::table('agence')->get();
		$retour = RetourClient::find($id);
		$class = '';
		switch ($retour->Type_retour) {
			case 'Négatif':
				$class = 'danger';
				break;
			case 'Positif':
				$class = 'success';
				break;
			case 'Information générale':
				$class = 'primary';
				break;

			default:
				$class = '';
		}

		//orWhere
		$contact = Contact::where('id', $retour->mycontact_id)->first();
		$files = File::where('parent', 'retours')->where('parent_id', $retour->id)->get();

		// Récupérer le retour précédent
		$previousRetour = RetourClient::where('id', '<', $retour->id)->orderBy('id', 'desc')->first();

		// Récupérer le retour suivant
		$nextRetour = RetourClient::where('id', '>', $retour->id)->orderBy('id', 'asc')->first();

		Consultation::create(['user' => auth()->id(),'app' => 2,'page' => "Réclamation"]);

		return view('retours.show', compact('retour', 'contact', 'class', 'agences', 'files','previousRetour','nextRetour'));
	}




	public function store(Request $request)
	{
		$request->validate([
			'Type_retour' => 'required',
		]);

		//$retour=RetourClient::create($request->all());

		$retour = RetourClient::create([
			'idclient' => $request->input('idclient') ?? 0,
			'user_id' => $request->input('user_id') ?? 0,
			'Type_retour' => $request->input('Type_retour'),
			'Motif_retour' => $request->input('Motif_retour'),
			'Nom_du_compte' => $request->input('Nom_du_compte'),
			'Division' => $request->input('Division'),
			'Date_ouverture' => $request->input('Date_ouverture'),
			'Date_cloture' => $request->input('Date_cloture'),
			'cl_id' => $request->input('cl_id'),
			'Details_des_causes' => $request->input('Details_des_causes'),
			'Ref_produit_lot_commande_facture' => $request->input('Ref_produit_lot_commande_facture'),
			'Depot_concerne' => ucfirst($request->input('Depot_concerne')),
			//'Responsable_de_resolution' => $request->input('Responsable_de_resolution'),
			'Une_reponse_a_ete_apportee_au_client' => $request->input('Une_reponse_a_ete_apportee_au_client'),
			'Description_c' => $request->input('Description_c'),
			//'Departement' => $request->input('Departement'),
		]);

		$retour->save();

		$status='';
		if (trim($retour->Type_retour) == 'Négatif')
		{
			$status='infos';
		}
		// retour positif ou info
		if (trim($retour->Type_retour) == 'Positif' || trim($retour->Type_retour) == 'Information générale') {
			$client = CompteClient::find($request->input('idclient'));
			if (isset($client)) {
				$agence = Agence::where('agence_ident', $client->agence_ident)->first();
				if (isset($agence))
					$retour->Depot_concerne = $agence->agence_lib;
				$retour->Responsable_de_resolution = $agence->agence_lib;
			}
			$retour->Date_cloture = date('Y-m-d');

			$retour->save();
			$status='cloture';
		}


		$retour->name = 'RC-' . sprintf('%05d', $retour->id);
		$retour->save();


		if ($request->hasFile('files')) {
			$fichiers = $request->file('files');

			foreach ($fichiers as $fichier) {
				$name = $fichier->getClientOriginalName();
				$path = public_path("fichiers/retours");
				$fichier->move($path, $name);

				// Store each file in the files table
				File::create([
					'name' => $name,
					'parent_id' => $retour->id,
					'parent' => 'retours'
				]);
			}
		}

		// Admins
		self::send_mail($retour, env('Admin_Email'),$status);
		self::send_mail($retour, env('Admin_reyad'),$status);
		self::send_mail($retour, env('Admin_iheb'),$status);

		// Direction
		self::send_mail($retour,  env('Email_jean'),$status);
		self::send_mail($retour,  env('Email_elisabeth'),$status);
		self::send_mail($retour,  env('Email_said'),$status);
		// Dir qualité
 		self::send_mail($retour, env('Email_qualite'),$status);

		if ($retour->idclient > 0)
			return redirect()->route('fiche', ['id' => $retour->idclient])->with(['success' => "Réclamation ajoutée "]);

		return redirect()->route('retours.show', $retour->id)
			->with('success', 'Réclamtion ajoutée');
	}


	public function update(Request $request, $id)
	{
		/*
        $request->validate([
            'Name' => 'required',
         ]);
*/
		$retour = RetourClient::find($id);
		$agence_lib = $retour->Responsable_de_resolution;
		//$retour->update($request->all());
		$reponse=$retour->Une_reponse_a_ete_apportee_au_client;

		$retour->update([
			'edited_by' => $request->input('edited_by') ?? 0,
			'Responsable_de_resolution' => $request->input('Responsable_de_resolution'),
			'Date_cloture' => $request->input('Date_cloture'),
			'Details_des_causes' => $request->input('Details_des_causes'),
			'Une_reponse_a_ete_apportee_au_client' => $request->input('Une_reponse_a_ete_apportee_au_client'),
			'Description_c' => $request->input('Description_c'),
			'Departement' => $request->input('Departement'),
		]);

		if ($request->hasFile('files')) {
			$fichiers = $request->file('files');

			foreach ($fichiers as $fichier) {
				$name = $fichier->getClientOriginalName();
				$path = public_path("fichiers/retours");
				$fichier->move($path, $name);

				// Store each file in the files table
				File::create([
					'name' => $name,
					'parent_id' => $retour->id,
					'parent' => 'retours'
				]);
			}
		}

		// email agence
		$agence = DB::table('agence')->where('agence_lib', trim($retour->Responsable_de_resolution))->first();


		//if (isset($agence) && isset($agence->mail2) && $agence_lib != $request->get('Responsable_de_resolution'))
			//self::send_mail($retour, $agence->mail2);

			if ($retour->Responsable_de_resolution == 'LIMONEST') {

				switch ($retour->Departement) {
					case 'FRET':
						self::send_mail($retour, 'fret@saamp.com','interv');
						break;
					case 'Laboratoire':
						self::send_mail($retour, 'laboratoire@saamp.com','interv');
						break;
					case 'Fonte':
						self::send_mail($retour,'franck.parent@saamp.com','interv');
						break;
					case 'Production':
						self::send_mail($retour, 'jose.dias@saamp.com','interv');
						break;
					case 'Qualité':
						self::send_mail($retour, 'directeur.qualite@saamp.com','interv');
						break;
				}
			} else {
				if(isset($agence))
					self::send_mail($retour, trim($agence->email_responsable),'');
			}

			if(trim($reponse)!=  trim($request->input('Une_reponse_a_ete_apportee_au_client')) ){
				$status='suite';
			// Admins
			self::send_mail($retour, env('Admin_Email'),$status);
			self::send_mail($retour, env('Admin_reyad'),$status);
			self::send_mail($retour, env('Admin_iheb'),$status);

			// Direction
			self::send_mail($retour,  env('Email_jean'),$status);
			self::send_mail($retour,  env('Email_elisabeth'),$status);
			self::send_mail($retour,  env('Email_said'),$status);
			// Dir qualité
			self::send_mail($retour, env('Email_qualite'),$status);
			}

		return redirect()->route('retours.show', $id)
			->with('success', 'Réclamation modifiée');
	}

	public static function send_mail($retour, $email,$status)
	{
		// envoi de mail
		$sujet = 'Réclamation ' . $retour->id . ' - ' . $retour->name;
		$contenu = 'Bonjour,<br><br>Réclamation: <a href="https://crm.mysaamp.com/retours/show/' . $retour->id . '" target="_blank">' . $retour->id . '</a> - ' . $retour->name . ' par ' . $retour->Nom_du_contact . '<br><br>
		<b>Client:</b> ' . $retour->cl_id . '  -  ' . $retour->Nom_du_compte . '<br>
		<b>Type de retour:</b> ' . $retour->Type_retour . '<br>
		<b>Date d\'ouverture:</b> ' . $retour->Date_ouverture . '<br>
		<b>Motif de retour:</b> ' . $retour->Motif_retour . '<br>
		<b>Division:</b> ' . $retour->Division . '<br>
		<b>Details des causes:</b> ' . $retour->Details_des_causes . '<br>';
		if($status=='cloture')
			$contenu.='<b>Réclamation clôturée</b><br><br>';

		if($status=='interv')
			$contenu.='Cette réclamation nécessite votre intervention.<br><br>';

		if($status=='infos')
			$contenu.='Cette réclamation nécessite une intervention.<br><br>';

		if($status=='suite')
		$contenu.='Une suite a été apportée à cette réclamation et il faut la clôturer.<br><br>';

		$contenu.='<i>Cordialement</i><br>
		<i><b>CRM SAAMP</b></i>';

		SendMail::send($email, $sujet, $contenu);
	}
	/*
		<b>Agence assignée:</b> '. $retour->Responsable_de_resolution.'<br>

	public function destroy($id)
	{
		$retour = RetourClient::find($id);
		$retour->delete();

		return back()->with('success', ' Supprimé avec succès');
	}
*/
	public function destroy($id)
	{
		$retour = RetourClient::find($id);

		if ($retour) {
			$cl_id = $retour->cl_id;
			$client = Client::where('cl_ident', $cl_id)->first();
			$retour->delete();

			$previousUrl = url()->previous();

			if (str_contains($previousUrl, '/show/' . $id)) {
				return redirect()->route('fiche', $client->id)->with('success', 'Supprimée avec succès');
			}
		}

		return back()->with('success', 'Supprimée avec succès');
	}
} // end class

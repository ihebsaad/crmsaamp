<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
#use DB;
use App\Models\Client;
use App\Models\CompteClient;
use App\Models\RetourClient;
use App\Models\Contact;
use App\Models\File;
use App\Services\SendMail;
use Illuminate\Support\Facades\DB;


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
		//dd('test');
		$retours=RetourClient::orderBy('id','desc')->limit(1000)->get();
		return view('retours.index',compact('retours'));
	}

	public function create($id)
	{
		$client=CompteClient::find($id);
		$retour=RetourClient::where('cl_id',$client->cl_ident)->first();
		$contacts=Contact::where('cl_ident',$client->cl_ident)->get();
		$agences = DB::table('agence')->get();

		return view('retours.create',compact('retour','client','contacts','agences'));
	}

	public function show($id)
	{
		$agences = DB::table('agence')->get();
		$retour=RetourClient::find($id);
		$class='';
		switch ( $retour->Type_retour ) {
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
			$contact=Contact::where('id',$retour->mycontact_id)->first();
			$files=File::where('parent','retours')->where('parent_id',$retour->id)->get();

		return view('retours.show',compact('retour','contact','class','agences','files'));
	}


	public function update(Request $request, $id)
    {
		/*
        $request->validate([
            'Name' => 'required',
         ]);
*/
		$retour = RetourClient::find($id);
		$agence_lib=$retour->Responsable_de_resolution;
		//$retour->update($request->all());

		$retour->update([
			'edited_by' => $request->input('edited_by') ?? 0,
			'Responsable_de_resolution' => $request->input('Responsable_de_resolution') ,
			'Date_cloture' => $request->input('Date_cloture'),
			'Details_des_causes' => $request->input('Details_des_causes'),
			'Une_reponse_a_ete_apportee_au_client' => $request->input('Une_reponse_a_ete_apportee_au_client'),
			'Description_c' => $request->input('Description_c'),
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
		$agence= DB::table('agence')->where('agence_lib',trim($retour->Responsable_de_resolution))->first();


		if(isset($agence) && isset($agence->mail2) && $agence_lib != $request->get('Responsable_de_resolution') )
			self::send_mail($retour,$agence->mail2);

		return redirect()->route('retours.show', $id)
				->with('success', 'Réclamation modifiée');
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
			'Type_retour' => $request->input('Type_retour') ,
			'Motif_retour' => $request->input('Motif_retour'),
			'Nom_du_compte' => $request->input('Nom_du_compte'),
			'Division' => $request->input('Division'),
			'Date_ouverture' => $request->input('Date_ouverture'),
			'Date_cloture' => $request->input('Date_cloture'),
			'cl_id' => $request->input('cl_id'),
			'Details_des_causes' => $request->input('Details_des_causes'),
			'Ref_produit_lot_commande_facture' => $request->input('Ref_produit_lot_commande_facture'),
			'Depot_concerne' => ucfirst($request->input('Depot_concerne')),
			'Une_reponse_a_ete_apportee_au_client' => $request->input('Une_reponse_a_ete_apportee_au_client'),
			'Description_c' => $request->input('Description_c'),
		]);

		$retour->save();
/*
		$contact=Contact::find($retour->mycontact_id);

		$prenom = $contact->Prenom ?? '';
		$nom = $contact->Nom ?? '';
		$retour->Nom_du_contact= $prenom  .' '.$nom;
*/
		$retour->name='RC-'.sprintf('%05d',$retour->id);
		$retour->save();
/*
		if ($request->hasFile('files')) {
			$fichiers = $request->file('files');
			$fileNames = [];

			foreach ($fichiers as $fichier) {
				$name = $fichier->getClientOriginalName();
				$path = public_path() . "/retours";
				$fichier->move($path, $name);
				$fileNames[] = $name;
			}

			// Serialize the filenames array
			$retour->fichier = serialize($fileNames);
			$retour->save();
		}
*/

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

		self::send_mail($retour,'remy.reverbel@saamp.com');
		self::send_mail($retour,'reyad.bouzeboudja@saamp.com');
		self::send_mail($retour,'said.el-marouani@saamp.com');
		self::send_mail($retour,'ihebsaad@gmail.com');

/*
		SendMail::send('remy.reverbel@saamp.com', $sujet, $contenu);
		SendMail::send('reyad.bouzeboudja@saamp.com', $sujet, $contenu);

		SendMail::send('said.el-marouani@saamp.com', $sujet, $contenu);

		// email qualité
		SendMail::send('directeur.qualite@saamp.com', $sujet, $contenu);

		// email agence
		$agence= DB::table('agence')->where('agence_lib',trim($retour->Responsable_de_resolution))->first();
		if(isset($agence))
			SendMail::send($agence->mail, $sujet, $contenu);

		if(isset($agence) && isset($agence->mail2))
			SendMail::send($agence->mail2, $sujet, $contenu);
*/
		if($retour->idclient >0)
		return redirect()->route('fiche', ['id' => $retour->idclient])->with(['success' => "Réclamation ajoutée "]);

		return redirect()->route('retours.show', $retour->id)
		->with('success','Réclamtion ajoutée');


	}

	public static function send_mail($retour,$email){
		// envoi de mail
		$sujet='Réclamation '. $retour->id.' - '.$retour->name;
		$contenu='Bonjour,<br><br>Réclamation: <a href="https://crm.mysaamp.com/retours/show/'.$retour->id.'" target="_blank">'.$retour->id.'</a> - '.$retour->name.' par '.$retour->Nom_du_contact.'<br><br>
		<b>Client:</b> '. $retour->cl_id.'  -  '. $retour->Nom_du_compte .'<br>
		<b>Type de retour:</b> '. $retour->Type_retour.'<br>
		<b>Motif de retour:</b> '. $retour->Motif_retour.'<br>
		<b>Division:</b> '. $retour->Division.'<br>
		<b>Details des causes:</b> '. $retour->Details_des_causes.'<br><br>

		<i>Cordialement</i><br>
		<i><b>CRM SAAMP</b></i>' ;

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
			$cl_id=$retour->cl_id;
			$client=Client::where('cl_ident',$cl_id)->first();
			$retour->delete();

			$previousUrl = url()->previous();

			if (str_contains($previousUrl, '/show/' . $id)) {
				return redirect()->route('fiche',$client->id)->with('success', 'Supprimée avec succès');
			}
		}

		return back()->with('success', 'Supprimée avec succès');
	}

} // end class

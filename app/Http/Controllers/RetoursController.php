<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
#use DB;
use App\Models\Client;
use App\Models\CompteClient;
use App\Models\RetourClient;
use App\Models\Contact;
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
		$retours=RetourClient::orderBy('id','desc')->limit(1000)->get();
		return view('retours.index',compact('retours'));
	}

	public function create($id)
	{
		$client=CompteClient::find($id);
		$retour=RetourClient::where('cl_id',$client->cl_ident)->first();
		$contacts=Contact::where('cl_ident',$client->cl_ident)->get();
		return view('retours.create',compact('retour','client','contacts'));
	}

	public function show($id)
	{
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


		return view('retours.show',compact('retour','contact','class'));
	}


	public function update(Request $request, $id)
    {
		/*
        $request->validate([
            'Name' => 'required',
         ]);
*/
		$retour = RetourClient::find($id);
		$retour->update($request->all());

		return redirect()->route('retours.show', $id)
				->with('success', 'Retour modifié');
	}

	public function store(Request $request)
    {
        $request->validate([
            'Type_retour' => 'required',

        ]);

        $retour=RetourClient::create($request->all());

		$contact=Contact::find($retour->mycontact_id);


		$retour->Nom_du_contact= $contact->Prenom.' '.$contact->Nom;

		$retour->name='RC-'.sprintf('%05d',$retour->id);
		$retour->save();

		// envoi de mail
		$sujet='Réclamation '. $retour->id.' - '.$retour->name;
		$contenu='Bonjour,<br><br>Réclamation: <a href="https://crm.mysaamp.com/retours/show/'.$retour->id.'" target="_blank">'.$retour->id.'</a> - '.$retour->name.' par '.$retour->Nom_du_contact.'<br><br>
		<b>Client:</b> '. $retour->cl_id.'  -  '. $retour->Nom_du_compte .'<br>
		<b>Type de retour:</b> '. $retour->Type_retour.'<br>
		<b>Motif de retour:</b> '. $retour->Motif_retour.'<br>
		<b>Responsable de résolution:</b> '. $retour->Responsable_de_resolution.'<br>
		<b>Division:</b> '. $retour->Division.'<br>
		<b>Details des causes:</b> '. $retour->Details_des_causes.'<br><br>

		<i>Cordialement</i><br>
		<i><b>CRM SAAMP</b></i>' ;


		SendMail::send('ihebsaad@gmail.com', $sujet, $contenu);
		SendMail::send('remy.reverbel@saamp.com', $sujet, $contenu);
		SendMail::send('reyad.bouzeboudja@saamp.com', $sujet, $contenu);


		/*
		// email qualité
		SendMail::send('directeur.qualite@saamp.com', $sujet, $contenu);


		// email agence
		$agence= DB::table('agence')->where('agence_lib',trim($retour->Depot_concerne))->first();
		if(isset($agence))
			SendMail::send($agence->mail, $sujet, $contenu);

		if(isset($agence) && isset($agence->mail2))
			SendMail::send($agence->mail2, $sujet, $contenu);

*/

		return redirect()->route('retours.show', $retour->id)
		->with('success','Retour ajouté');
	}


	public function destroy($id)
	{
		$retour = RetourClient::find($id);
		$retour->delete();

		return back()->with('success', ' Supprimé avec succès');
	}

} // end class

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
#use DB;
use App\Models\Client;
use App\Models\CompteClient;
use App\Models\RetourClient;
use App\Models\Contact;
use App\Models\User;
use App\Services\SendMail;
use App\Models\Consultation;

use Illuminate\Support\Facades\DB;


class ContactsController extends Controller
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



	public function create($id)
	{
		$client = CompteClient::find($id);
		$contact = Contact::where('cl_ident', $client->id)->first();
		Consultation::create(['user' => auth()->id(),'app' => 2,'page' => "Création de contact"]);

		return view('contacts.create', compact('contact', 'client'));
	}

	public function show($id)
	{
		$contact = Contact::find($id);
		//Contact::updateWithSequentialIds();
		if ($contact->cl_ident > 0)
			$client = Client::where('cl_ident', $contact->cl_ident)
				->first();
		else
			$client = Client::where('id', $contact->mycl_ident)
				->first();

		Consultation::create(['user' => auth()->id(),'app' => 2,'page' => "Affichage de contact"]);

		return view('contacts.show', compact('contact', 'client'));
	}


	public function store(Request $request)
	{
		$request->validate([
			'Nom' => 'required',
			'email' => 'unique:contact',
		]);

		$contact = Contact::create($request->except(['etat_id']));

		if($request->get('email')!=''){
			$data['email']=$request->get('email');
			$data['id_client']=$request->get('cl_ident');
			$data['Phone']=$request->get('Phone');
			$data['MobilePhone']=$request->get('MobilePhone');
			$data['tel']=$request->get('MobilePhone') ?? $request->get('Phone') ;
			$data['nom']=$request->get('Nom');
			$data['prenom']=$request->get('Prenom');

			if($request->get('MobilePhone')!='')
				$data['tel']=   $request->get('MobilePhone') ;
			else
				$data['tel']=   $request->get('Phone') ;

			if($request->get('etat_id')==2)
				self::insert_as400($data);

			//$data['password']=self::randomPassword();
			//self::create_user($data);
			DB::select ("  CALL `sp_contact_insert_auto`(); ");

		}

		return redirect()->route('contacts.show', $contact->id)
			->with('success', ' Contact ajouté');
	}


	function randomPassword() {
		$alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
		$pass = array(); //remember to declare $pass as an array
		$alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
		for ($i = 0; $i < 8; $i++) {
			$n = rand(0, $alphaLength);
			$pass[] = $alphabet[$n];
		}
		return implode($pass).date('Y').'*'; //turn the array into a string+ add year & *
	}


	public function update(Request $request, $id)
	{
		$request->validate([
			'Nom' => 'required',
		]);

		$contact = Contact::find($id);
		$contact->update($request->all());
		$client=CompteClient::find($contact->mycl_iden);
		$data=array();

		if($client && $client->etat_id==2){
			if($request->get('email')!=''){
				$data['email']=$request->get('email') ?? '';
				$data['id_client']=$request->get('cl_ident');
				$data['nom']=$request->get('Nom') ?? '';
				$data['prenom']=$request->get('Prenom') ?? '';

				if($request->get('MobilePhone')!='')
					$data['tel']=   $request->get('MobilePhone') ?? '' ;
				else
					$data['tel']=   $request->get('Phone') ?? '' ;

				self::update_as400($data);
				// suprrimer les users avec ancien email
				User::where('email',$request->get('email'))->delete();
			}

			DB::select ("  CALL `sp_contact_insert_auto`(); ");
		}

		return redirect()->route('fiche', $contact->mycl_ident)->with('success', 'Contact modifié');
	}





	public function destroy($id)
	{
		$contact = Contact::find($id);

		if ($contact) {
			$cl_id = $contact->cl_ident;
			$client = Client::where('cl_ident', $cl_id)->first();

			$data=array();
			if($contact->email!=''){
				$data['id_client']=$cl_id;
				$data['email']=$contact->email;
				self::delete_as400($data);

			}

			$contact->delete();

			$count=Contact::where('email',$contact->email)
			->orWhere('email2',$contact->email)
			->count();

			//Supprimer l'utilisateur
			if($count==0)
				User::where('email',$contact->email)->delete();

			$previousUrl = url()->previous();

			if (str_contains($previousUrl, '/show/' . $id)) {
				return redirect()->route('fiche', $client->id)->with('success', 'Supprimé avec succès');
			}
		}

		return back()->with('success', 'Supprimé avec succès');
	}



	//create user

	public function create_user($data){

		$password = bcrypt(trim($data['password']));

		$user = new User([
			'username' => $data['email'],
			'email' => $data['email'],
			'name' => $data['prenom'],
			'lastname' => $data['nom'],
			'mobile' => $data['MobilePhone'],
			'phone' => $data['Phone'],
			'password' => $password,
			'client_id' => $data['id_client'],
		]);

		if ($user->save()) {
			\Log::info(" User created ID: ".$user->id );
			$sujet='Votre compte a été créé chez SAAMP';
			$contenu="Bonjour ".$data['prenom']." ".$data['nom']."<br><br>Votre Compte a été créé chez SAAMP.<br>Voici vos accès:<br><b>Identifiant:</b>". $data['email']." <br><b>Mot de passe:</b> ".$data['password']."<br><br><a target='_blank' href='https://mysaamp.com/login'> Accéder à mon compte </a><br><br><br><i>L'équipe SAAMP</i><br><i>Cordialement</i> ";

			SendMail::send(trim($data['email']), $sujet, $contenu);

		}else{
			\Log::info(" Error adding user with email ". $data['email']);

		}

	}







	// AS400
	public function insert_as400($data)
	{
		try {
			\Log::info(" Contact as400 - data :".json_encode($data));
			$server = config('as400.server');
			$user = config('as400.user');
			$pass = config('as400.pass');
			$dsn = "Driver={IBM i Access ODBC Driver};System=$server;Uid=$user;Pwd=$pass";

			$pdo = new \PDO("odbc:$dsn", $user, $pass);
			$pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);


			// Vérifier si l'email existe déjà pour cet ID client
			$stmt = $pdo->prepare("SELECT CLIMAI FROM S65DD73D.GESCOMF.CLIMAIP1 WHERE CLINUM = ? AND CLIMAI = ?");
			$stmt->execute([$data['id_client'], $data['email']]);

			if ($stmt->rowCount() > 0) {
				// Mettre à jour l'entrée existante
				$stmt = $pdo->prepare("UPDATE S65DD73D.GESCOMF.CLIMAIP1
												   SET CLINOC = ?, CLIPRT = ?
												   WHERE CLINUM = ? AND CLIMAI = ?");
				$nom_complet = $data['nom'] . ' ' . $data['prenom'];
				$tel = $data['tel'] ?? 'Phone';
				$stmt->execute([$nom_complet, $tel, $data['id_client'], $data['email']]);
				\Log::info(" Contact as400 updated  ");
			} else {
				// Ajouter un nouveau contact
				$stmt = $pdo->prepare("INSERT INTO S65DD73D.GESCOMF.CLIMAIP1 (CLINUM, CLIMAI, CLINOC, CLIPRT, CLISTE, CLIRUB)
												   VALUES (?, ?, ?, ?, ?, ?)");
				$nom_complet = $data['nom'] . ' ' . $data['prenom'];
				$stmt->execute([$data['id_client'], $data['email'], $nom_complet, $data['tel'], 'I', 'DEF']);
				\Log::info(" Contact as400 added  ");

			}
		} catch (\Exception $e) {
			\Log::info(' erreur insert contact as400 ' . $e->getMessage());
			return "Erreur : " . $e->getMessage();
		} finally {
			//\Log::info(' add  contact as400 terminé ');
		}
		return  true;
	}

	public function update_as400($data)
	{
		try {
			\Log::info(" Contact as400 - data :".json_encode($data));
			$server = config('as400.server');
			$user = config('as400.user');
			$pass = config('as400.pass');
			$dsn = "Driver={IBM i Access ODBC Driver};System=$server;Uid=$user;Pwd=$pass";

			$pdo = new \PDO("odbc:$dsn", $user, $pass);
			$pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

			try {
				$stmt = $pdo->prepare("UPDATE S65DD73D.GESCOMF.CLIMAIP1
									   SET CLINOC = ?, CLIPRT = ?
									   WHERE CLINUM = ? AND CLIMAI = ?");
				$nom_complet = $data['nom'] . ' ' . $data['prenom'];
				$stmt->execute([$nom_complet, $data['tel'], $data['id_client'], $data['email']]);
				//$message = 'Contact mis à jour avec succès.';
				\Log::info('Contact mis à jour avec succès ');

			} catch (PDOException $e) {
				//$message = 'Erreur lors de la mise à jour : ' . $e->getMessage();
				\Log::info('Erreur lors de la mise à jour du contact' . $e->getMessage());
			}

		} catch (\Exception $e) {
			\Log::info(' erreur insert contact as400 ' . $e->getMessage());
			return "Erreur : " . $e->getMessage();
		} finally {
			//\Log::info('  edit  contact as400 terminé ');
		}
		return  true;
	}

	public function delete_as400($data)
	{
		try {

			$server = config('as400.server');
			$user = config('as400.user');
			$pass = config('as400.pass');
			$dsn = "Driver={IBM i Access ODBC Driver};System=$server;Uid=$user;Pwd=$pass";

			$pdo = new \PDO("odbc:$dsn", $user, $pass);
			$pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

			// Supprimer un contact
			$email = htmlspecialchars(trim($data['email']));
			$stmt = $pdo->prepare("DELETE FROM S65DD73D.GESCOMF.CLIMAIP1 WHERE CLINUM = ? AND CLIMAI = ?");
			if ($stmt->execute([$data['id_client'], $email])) {
				$message = "Contact avec l'email $email supprimé avec succès.";
				\Log::info($message);
			} else {
				$message = "Erreur lors de la suppression du contact.";
				\Log::info($message);
			}

		} catch (\Exception $e) {
			\Log::info(' erreur delete contact as400 ' . $e->getMessage());
			return false;
		} finally {
			\Log::info(' supp contact as400 terminé ');
			return true;
		}
	}


} // end class

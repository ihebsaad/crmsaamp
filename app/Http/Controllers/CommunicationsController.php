<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Communication;
use App\Models\CompteClient;
use App\Models\EmailTemplate;
use App\Models\Agence;
use App\Models\File;
use App\Services\SendMail;

class CommunicationsController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth']);
    }

    // Afficher le formulaire de création
    public function create()
    {
        $agences = Agence::get();
        $templates = EmailTemplate::all();

        return view('communications.create',compact('agences','templates'));
    }

    // Enregistrer une nouvelle communication
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'objet' => 'nullable|string|max:255',
            'corps_message' => 'nullable|string',
            //'fichier' => 'nullable|file|mimes:pdf,docx,jpeg,png|max:20480',
            'par' => 'required|integer',
            'destinataires' => 'required|json',
            'statut' => 'nullable|integer|in:0,1',
            'type' => 'required|integer|in:1,2', // 1: Email, 2: SMS
            'files.*' => 'file|mimes:jpeg,png,pdf,doc,docx|max:10240', // 10 MB max

        ]);

        // Si la validation échoue
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
/*
        // Gestion du fichier uploadé
        $fichierPath = null;
        if ($request->hasFile('fichier')) {
            $fichierPath = $request->file('fichier')->store('fichiers/communications', 'public');
        }
*/
        $template = null;
        if ($request->filled('template_id')) {
            $template = EmailTemplate::find($request->input('template_id'));
        }

        // Création de la communication
        $communication = Communication::create([
            'objet' => $template ? $template->subject : $request->input('objet'),
            'corps_message' => $template ? $template->body : $request->input('corps_message'),
            //'fichier' => $fichierPath,
            'par' => $request->input('par'),
            'destinataires' => $request->input('destinataires'),
            'statut' => $request->input('statut', 1), // Par défaut 1 (actif)
            'type' => $request->input('type'),
            'clients' => $request->input('clients'),
        ]);

       // Récupération des destinataires
        $destinatairesInput = $request->input('destinataires'); // Tableau ou JSON encodé

        // S'assurer que les destinataires sont sous forme de tableau PHP natif
        if (is_string($destinatairesInput)) {
            $destinatairesInput = json_decode($destinatairesInput, true); // Décoder en tableau associatif
        }

        // Vérification de la structure
        if (!is_array($destinatairesInput)) {
            $destinatairesInput = [$destinatairesInput]; // Convertir en tableau si un seul objet
        }

        // Extraire les IDs des clients
        $destinatairesIds = array_column($destinatairesInput, 'id'); // Extraire les IDs

        // Vérification des IDs
        \Log::info('Destinataires IDs : ' . json_encode($destinatairesIds));

        $attachmentPaths = []; // Tableau pour stocker les chemins des fichiers

        if ($request->hasFile('fichiers')) {
            $fichiers = $request->file('fichiers');


            foreach ($fichiers as $fichier) {
                $name = $fichier->getClientOriginalName();
                $path = public_path("fichiers/communications");
                $fichier->move($path, $name);

                // Ajouter le chemin du fichier au tableau des pièces jointes
                $attachmentPaths[] = $path . '/' . $name;

                // Enregistrer chaque fichier dans la table files
                File::create([
                    'name' => $name,
                    'parent_id' => $communication->id,
                    'parent' => 'communication'
                ]);
            }
        }

        // Récupérer les emails des clients
        $emails = CompteClient::whereIn('id', $destinatairesIds)
            ->whereNotNull('email')
            ->pluck('email')
            ->toArray();

        // Objet et contenu de l'email
        $objet = $communication->objet;
        $contenu = $communication->corps_message;

        \Log::info('Emails : ' . json_encode($emails));

        // Ajout de l'envoi d'email via le service SendMail
        try {
            if (!empty($emails)) {
                SendMail::send($emails, $objet, $contenu, $attachmentPaths); // Passer les pièces jointes
            } else {
                logger()->warning('Aucun email trouvé pour les destinataires.');
            }
        } catch (\Exception $e) {
            logger()->error('Erreur lors de l\'envoi de l\'email : ' . $e->getMessage());
            $communication->statut =2;
            $communication->erreurs_envoi = $e->getMessage();
            $communication->save();
            return redirect()->route('communications.index')->withErrors(['msg' => "Erreur lors de l'envoi des emails"]);
        }

        return redirect()->route('communications.index')->with('success', 'Communication créée avec succès.');
    }


    // Méthode pour enregistrer un template
    public function storeTemplate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'body' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        EmailTemplate::create([
            'name' => $request->input('name'),
            'subject' => $request->input('subject'),
            'body' => $request->input('body'),
        ]);

        return redirect()->back()->with('success', 'Template enregistré avec succès.');
    }

		public function index()
	{
		$communications = Communication::orderBy('id', 'desc')->limit(1000)->get();
		return view('communications.index', compact('communications'));
	}

        public function searchAjax(Request $request)
    {
        $query = CompteClient::query();

        if ($request->has('client_id') && $request->client_id) {
            $query->where('cl_ident', 'like', '%' . $request->client_id . '%');
        }

        if ($request->has('type') && $request->type != 0) {
            $query->where('etat_id', $request->type);
        }

        if ($request->has('Nom') && $request->Nom) {
            $query->where('Nom', 'like', '%' . $request->Nom . '%');
        }

        if ($request->has('adresse1') && $request->adresse1) {
            $query->where('adresse1', 'like', '%' . $request->adresse1 . '%');
        }

        if ($request->has('ville') && $request->ville) {
            $query->where('ville', 'like', '%' . $request->ville . '%');
        }

        if ($request->has('zip') && $request->zip) {
            $query->whereRaw("TRIM(zip) LIKE ?", [trim($request->zip) . '%']);
        }

        if ($request->has('agence') && $request->agence) {
            $query->where('agence_ident',  $request->agence);
        }

        $clients = $query->take(100)->get(['id', 'Nom', 'ville', 'cl_ident', 'etat_id','agence_ident']); // Limiter à 100 résultats
        return response()->json($clients);
    }


    public function get_communication(Request $request)
    {
        $comm=Communication::find($request->get('communication'));
        $data['sujet']=$comm->objet ;
        $data['contenu']=$comm->corps_message ;
        return $data;
    }
}

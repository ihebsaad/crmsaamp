<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Communication;
use App\Models\CompteClient;
use App\Models\Agence;

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
        return view('communications.create',compact('agences'));
    }

    // Enregistrer une nouvelle communication
    public function store(Request $request)
    {
        // Validation des données
        $validator = Validator::make($request->all(), [
            'objet' => 'required|string|max:255',
            'corps_message' => 'required|string',
            'fichier' => 'nullable|file|mimes:pdf,docx,jpeg,png|max:20480',
            'par' => 'required|integer',
            'destinataires' => 'required|json',
            'statut' => 'nullable|integer|in:0,1',
            'type' => 'required|integer|in:1,2', // 1: Email, 2: SMS
        ]);

        // Si la validation échoue
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Gestion du fichier uploadé
        $fichierPath = null;
        if ($request->hasFile('fichier')) {
            $fichierPath = $request->file('fichier')->store('fichiers/communications', 'public');
        }

        // Création de la communication
        Communication::create([
            'objet' => $request->input('objet'),
            'corps_message' => $request->input('corps_message'),
            'fichier' => $fichierPath,
            'par' => $request->input('par'),
            'destinataires' => $request->input('destinataires'),
            'statut' => $request->input('statut', 1), // Par défaut 1 (actif)
            'type' => $request->input('type'),
        ]);

        return redirect()->route('communications.index')->with('success', 'Communication créée avec succès.');
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

        $clients = $query->take(100)->get(['id', 'Nom', 'ville', 'cl_ident', 'etat_id','agence_ident']); // Limiter à 100 résultats
        return response()->json($clients);
    }


}

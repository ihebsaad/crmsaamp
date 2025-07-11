<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Services\SendMail;
use App\Models\File;
use App\Models\Consultation;

class TicketController extends Controller
{

    public function __construct()
	{
		$this->middleware(['auth']);
	}

    // Affiche la liste des tickets de l'utilisateur authentifié
    public function index()
    {
        if(auth()->user()->user_type=='admin')
/*            $tickets = Ticket::where(function ($query) {
                    $query->where('status', 'Opened')
                          ->orWhere('created_at', '>=', Carbon::now()->subDays(30));
                })
                ->get();
*/
        $tickets= Ticket::orderBy('id','desc')->get();

        else
            $tickets = Ticket::where('user_id', auth()->user()->id)->get();

        Consultation::create(['user' => auth()->id(),'app' => 2,'page' => "Liste des tickets"]);

        return view('tickets.index', compact('tickets'));
    }

    // Affiche le formulaire de création d'un nouveau ticket
    public function create()
    {
        Consultation::create(['user' => auth()->id(),'app' => 2,'page' => "Création des tickets de support"]);

        return view('tickets.create');
    }

    // Enregistre un nouveau ticket
    public function store(Request $request)
    {
        try{


        $request->validate([
            'subject' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string|max:100',
        ]);

        $ticket=Ticket::create([
            'subject' => $request->subject,
            'description' => $request->description,
            'category' => $request->category,
            'user_id' => auth()->id(),
            'status' => 'Opened',
        ]);

        $user_name= auth()->user()->name.' ' .auth()->user()->lastname ;
        /*
		if ($request->hasFile('files')) {
			$fichiers = $request->file('files');
			$fileNames = [];

			foreach ($fichiers as $fichier) {
				$name = $fichier->getClientOriginalName();
				$path = public_path() . "/tickets";
				$fichier->move($path, $name);
				$fileNames[] = $name;
			}

			// Serialize the filenames array
			$ticket->files = serialize($fileNames);
			$ticket->save();
		}
        */
        if ($request->hasFile('files')) {
			$fichiers = $request->file('files');

			foreach ($fichiers as $fichier) {
				$name = $fichier->getClientOriginalName();
				$path = public_path("fichiers/tickets");
				$fichier->move($path, $name);

				// Store each file in the files table
				File::create([
					'name' => $name,
					'parent_id' => $ticket->id,
					'parent' => 'tickets'
				]);
			}
		}
        $contenu="Bonjour,<br><br>Nouvelle demande d'assistance:<br> <a href='https://crm.mysaamp.com/tickets/$ticket->id' target='_blank'>N° $ticket->id </a><br><br><b>Sujet:</b><br><br> $ticket->subject <br><b>Description:</b><br><br> $ticket->description  .<br><br><b>Par:</b><br> $user_name <br><br><i>CRM SAAMP</i>";
        SendMail::send(env('Admin_Remy'),"Demande d'assistance",$contenu);
        SendMail::send(env('Admin_iheb'),"Demande d'assistance",$contenu);
        SendMail::send(env('Admin_reyad'),"Demande d'assistance",$contenu);

        }catch( \Exception $e){
            dd($e->getMessage());
        }

        return redirect()->route('tickets.index')->with('success', 'Ticket créé avec succès.');
    }

    // Affiche un ticket spécifique avec ses commentaires
    public function show($id)
    {
        $ticket = Ticket::with('comments.user')->findOrFail($id);
		$fichiers=File::where('parent','tickets')->where('parent_id',$ticket->id)->get();

        Consultation::create(['user' => auth()->id(),'app' => 2,'page' => "Affichage de ticket"]);

        return view('tickets.show', compact('ticket','fichiers'));
    }

    // Affiche le formulaire d'édition d'un ticket
    public function edit($id)
    {
        $ticket = Ticket::findOrFail($id);
        Consultation::create(['user' => auth()->id(),'app' => 2,'page' => "Modificaton des ticket"]);

        return view('tickets.edit', compact('ticket'));
    }

    // Met à jour les informations d'un ticket
    public function update(Request $request, $id)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string|max:100',
            'status' => 'required',
        ]);

        $ticket = Ticket::findOrFail($id);
        $status1= $ticket->status;
        $ticket->update([
            'subject' => $request->subject,
            'description' => $request->description,
            'category' => $request->category,
            'status' => $request->status,
        ]);

        $status2= $ticket->status;

        if($status1 != $status2){
            $contenu="La demande d'assistance <a href='https://crm.mysaamp.com/tickets/$ticket->id' target='_blank'>N° $ticket->id</a> est passée à :   $ticket->status   par ".auth()->user()->name." ". auth()->user()->lastname .".<br><br><i>CRM SAAMP</i>" ;

            SendMail::send(env('Admin_Remy'),"Demande d'assistance $ticket->id => $ticket->status ",$contenu);
            SendMail::send(env('Admin_iheb'),"Demande d'assistance $ticket->id => $ticket->status ",$contenu);
            SendMail::send(env('Admin_reyad'),"Demande d'assistance $ticket->id => $ticket->status ",$contenu);

        }

        return redirect()->route('tickets.index')->with('success', 'Ticket mis à jour avec succès.');
    }

    // Supprime un ticket spécifique
    public function destroy($id)
    {
        $ticket = Ticket::findOrFail($id);
        $ticket->delete();

        return redirect()->route('tickets.index')->with('success', 'Ticket supprimé avec succès.');
    }
}

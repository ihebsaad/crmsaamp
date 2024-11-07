<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
#use DB;
use App\Models\Client;
use App\Models\Ticket;
use App\Models\Comment;
use App\Models\User;
use App\Services\SendMail;
use App\Models\File;


class CommentController extends Controller
{

    public function store(Request $request, Ticket $ticket)
    {
        $request->validate(['comment' => 'required|string']);

        $comment=Comment::create([
            'ticket_id' => $ticket->id,
            'user_id' => auth()->id(),
            'comment' => $request->comment,
        ]);

        if ($request->hasFile('files')) {
			$fichiers = $request->file('files');

			foreach ($fichiers as $fichier) {
				$name = $fichier->getClientOriginalName();
				$path = public_path("fichiers/comments");
				$fichier->move($path, $name);

				// Store each file in the files table
				File::create([
					'name' => $name,
					'parent_id' => $comment->id,
					'parent' => 'comments'
				]);
			}
		}

        $user_name = auth()->user()->name . ' ' . auth()->user()->lastname;

        if (auth()->id() != $ticket->user_id) {
            // envoyé email au créateur de ticket
            $user = User::find($ticket->user_id);
            if ($user->email != '')
                SendMail::send($user->email, "Réponse sur le ticket :   " . $ticket->id . "  -  " . $ticket->subject, $request->comment . "  <br>Par: " . $user_name . " <br><br><i>CRM SAAMP</i>");
        } else {
            // envoyé l'email aux users
            SendMail::send(env('Admin_Email'), "Réponse sur le ticket :   " . $ticket->id . "  -  " . $ticket->subject, $request->comment . "  <br>Par: " . $user_name . " <br><br><i>CRM SAAMP</i>");
            SendMail::send(env('Admin_iheb'), "Réponse sur le ticket :   " . $ticket->id . "  -  " . $ticket->subject, $request->comment . "  <br>Par: " . $user_name . " <br><br><i>CRM SAAMP</i>");
            SendMail::send(env('Admin_reyad'), "Réponse sur le ticket :   " . $ticket->id . "  -  " . $ticket->subject, $request->comment . "  <br>Par: " . $user_name . " <br><br><i>CRM SAAMP</i>");
        }


        return back()->with('success', 'Commentaire ajouté.');
    }
} // end class

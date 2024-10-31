<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
#use DB;
use App\Models\Client;
use App\Models\Ticket;
use App\Models\Comment;
use App\Models\User;
use App\Services\SendMail;


class CommentController extends Controller
{

    public function store(Request $request, Ticket $ticket)
    {
        $request->validate(['comment' => 'required|string']);

        Comment::create([
            'ticket_id' => $ticket->id,
            'user_id' => auth()->id(),
            'comment' => $request->comment,
        ]);

        if(auth()->id()!= $ticket->user_id){
            $user=User::find($ticket->user_id);
            if($user->email!='')
                SendMail::send($user->email,"Réponse sur le ticket : ".$ticket->id." - ".$ticket->subject,$request->comment);

        }


        return back()->with('success', 'Commentaire ajouté.');
    }

} // end class

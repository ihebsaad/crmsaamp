<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
#use DB;
use App\Models\Client;
use App\Models\CompteClient;
use App\Models\RetourClient;
use App\Models\Contact;
use App\Services\PhoneService;
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
		$retours=RetourClient::orderBy('id','desc')->get();
		return view('retours.index',compact('retours'));
	}

	public function create($id)
	{
		$client=CompteClient::find($id);
		$retour=RetourClient::where('cl_id',$client->cl_ident)->first();
		$contact=Contact::where('cl_ident',$client->cl_ident)->first();
		return view('retours.create',compact('retour','client','contact'));
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

		$contact=Contact::where('old_id',$retour->ID_Contact_Salesforce)->first();

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
		$retour->name='RC-'.sprintf('%05d',$retour->id);
		$retour->save();
		return redirect()->route('retours.show', $retour->id)
		->with('success','Retour ajouté');
	}


} // end class

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

use App\Models\User;
use App\Models\RendezVous;
use App\Models\Consultation;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\RendezVousExport;
use Maatwebsite\Excel\Facades\Excel;

class AgendaController extends Controller
{
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware('auth');
	}

	/**
	 * Show the application dashboard.
	 *
	 * @return \Illuminate\Http\Response
	 */


	public function agenda(Request $request)
	{
		$user=$request->get('user');
		$users=array();

		$role = auth()->user()->role;
		$user_id = auth()->user()->id;
		$agence_id = auth()->user()->agence_ident;
		$users=DB::table("users")->where('username','like','%@saamp.com')->orderBy('lastname','asc')->get();

/*
		if($role =='admin' || $role =='respAG' || $role =='adv' || $role =='compta' ){
			//$representants=DB::table("representant")->get();
			$users=DB::table("users")->where('username','like','%@saamp.com')->orderBy('lastname','asc')->get();
		}
		if($role =='respAG' || $role =='adv' ){
			$users=DB::table("users")
			->where('username','like','%@saamp.com')
			->where('agence_ident',$agence_id)
			//->where('role','commercial')
			//->whereIn('role', ['commercial', 'user'])
			->get();
		}
*/
		if(auth()->user()->user_role == 4 )
		{
			$users_id= DB::table('representant')->whereRaw("FIND_IN_SET(?, agence)", [auth()->user()->agence_ident])->pluck('users_id');
			$users=DB::table("users")->whereIn('id',$users_id)->orderBy('lastname','asc')->get();
		}

		if($user>0){
			$User=User::find($user);
			$rendezvous=RendezVous::where('Attribue_a',$User->name.' '.$User->lastname)
			->orWhere('user_id',$user)
			//->where('AccountId','>',0)
			->orderBy('id','desc')
			->get();

		}else{
			$rendezvous=RendezVous::where('Attribue_a',auth()->user()->name.' '.auth()->user()->lastname)
			->orWhere('user_id',auth()->user()->id)
			//->where('AccountId','>',0)
			->orderBy('id','desc')
			->get();
		}
		Consultation::create(['user' => auth()->id(),'app' => 2,'page' => "Agenda"]);

		return view('agenda',compact('rendezvous','user','users'));

	}

	public function print_agenda(Request $request)
	{
		$user = $request->get('user');
		$date_debut = $request->get('date_debut');
		$date_fin = $request->get('date_fin');
		$name = "";

		// Validation des dates
		if (!$date_debut || !$date_fin) {
			return back()->with('error', __('msg.Please provide a valid date range.'));
		}
		// Récupération des rendez-vous en fonction de l'utilisateur et de la plage de dates
		if ($user > 0) {
			$User = User::find($user);
			$name = $User->name . ' ' . $User->lastname;
			$rendezvous = RendezVous::where('user_id', $user)
				->whereBetween('Started_at', [$date_debut, $date_fin])
				->orderBy('Started_at', 'asc')
				->orderBy('heure_debut', 'asc')
				->get();
		} else {
			$rendezvous = RendezVous::where('user_id', auth()->user()->id)
				->whereBetween('Started_at', [$date_debut, $date_fin])
				->orderBy('Started_at', 'asc')
				->orderBy('heure_debut', 'asc')
				->get();
		}

		Consultation::create(['user' => auth()->id(),'app' => 2,'page' => "Agenda impression"]);

		return view('rendezvous.print_list', compact('rendezvous', 'user', 'name', 'date_debut', 'date_fin'));
	}

	public function pdf_agenda(Request $request)
	{
		$user = $request->get('user');
		$date_debut = $request->get('date_debut');
		$date_fin = $request->get('date_fin');
		$name = "";

		// Validation des dates
		if (!$date_debut || !$date_fin) {
			return back()->with('error', __('msg.Please provide a valid date range.'));
		}
		// Récupération des rendez-vous en fonction de l'utilisateur et de la plage de dates
		if ($user > 0) {
			$User = User::find($user);
			$name = $User->name . ' ' . $User->lastname;
			$rendezvous = RendezVous::where('user_id', $user)
				->whereBetween('Started_at', [$date_debut, $date_fin])
				->orderBy('Started_at', 'asc')
				->orderBy('heure_debut', 'asc')
				->get();
		} else {
			$rendezvous = RendezVous::where('user_id', auth()->user()->id)
				->whereBetween('Started_at', [$date_debut, $date_fin])
				->orderBy('Started_at', 'asc')
				->orderBy('heure_debut', 'asc')
				->get();
		}

		$date=date('d_m_Y_H_i');
		$pdf = PDF::loadView('rendezvous.pdf_list', compact('rendezvous', 'user', 'name', 'date_debut', 'date_fin'));

		Consultation::create(['user' => auth()->id(),'app' => 2,'page' => "Agenda PDF"]);

		return $pdf->stream('rendezvous-' . $name . '-'.$date.'.pdf');
	}


	public function excel_agenda(Request $request)
	{
		$user = $request->get('user');
		$date_debut = $request->get('date_debut');
		$date_fin = $request->get('date_fin');

		if (!$date_debut || !$date_fin) {
			return back()->with('error', __('msg.Please provide a valid date range.'));
		}

		if ($user > 0) {
			$User = User::find($user);
			$name = $User->name . ' ' . $User->lastname;
			$rendezvous = RendezVous::where('user_id', $user)
				->whereBetween('Started_at', [$date_debut, $date_fin])
				->orderBy('Started_at', 'asc')
				->orderBy('heure_debut', 'asc')
				->get();
		} else {
			$rendezvous = RendezVous::where('user_id', auth()->user()->id)
				->whereBetween('Started_at', [$date_debut, $date_fin])
				->orderBy('Started_at', 'asc')
				->orderBy('heure_debut', 'asc')
				->get();
		}

		Consultation::create(['user' => auth()->id(),'app' => 2,'page' => "Agenda Excel"]);

		return Excel::download(new RendezVousExport($rendezvous), 'agenda_' . date('d_m_Y_H_i') . '.xlsx');
	}

/*
	public function export_agenda(Request $request)
	{
		$type = $request->get('type'); // 'print', 'pdf', 'excel'
		$user = $request->get('user');
		$date_debut = $request->get('date_debut');
		$date_fin = $request->get('date_fin');

		if (!$date_debut || !$date_fin) {
			return back()->with('error', __('msg.Please provide a valid date range.'));
		}

		$query = RendezVous::whereBetween('Started_at', [$date_debut, $date_fin])
			->orderBy('Started_at', 'asc')
			->orderBy('heure_debut', 'asc');

		if ($user > 0) {
			$User = User::find($user);
			$name = $User->name . ' ' . $User->lastname;
			$query->where('user_id', $user);
		} else {
			$query->where('user_id', auth()->user()->id);
		}

		$rendezvous = $query->get();

		if ($type == 'print') {
			return view('rendezvous.print_list', compact('rendezvous', 'user', 'date_debut', 'date_fin'));
		} elseif ($type == 'pdf') {
			$pdf = PDF::loadView('rendezvous.pdf_list', compact('rendezvous', 'user', 'date_debut', 'date_fin'));
			return $pdf->stream('agenda.pdf');
		} elseif ($type == 'excel') {
			return Excel::download(new RendezVousExport($rendezvous), 'agenda.xlsx');
		}

		return back()->with('error', __('msg.Invalid export type.'));
	}
*/




	public function rendesvous_ext(Request $request)
	{
		$representants=DB::table("representant")->get();
		$user=$request->get('user');

		$rendezvous=RendezVous:://where('Attribue_a',auth()->user()->name.' '.auth()->user()->lastname)
		where('user_id',auth()->user()->id)
		->where('AccountId',0)
		->orderBy('id','desc')->get();

		if($user>0){
			$User=User::find($user);
			$rendezvous=RendezVous:://where('Attribue_a',$User->name.' '.$User->lastname)
			where('user_id',$user)
			->where('AccountId',0)
			->orderBy('id','desc')
			->get();
		}else{
			$rendezvous=RendezVous:://where('Attribue_a',auth()->user()->name.' '.auth()->user()->lastname)
			where('user_id',auth()->user()->id)
			->where('AccountId',0)
			->orderBy('id','desc')
			->get();
		}

		Consultation::create(['user' => auth()->id(),'app' => 2,'page' => "Agenda Rdv extérieurs"]);

		return view('agenda',compact('rendezvous','representants','user'));

	}



} // end class

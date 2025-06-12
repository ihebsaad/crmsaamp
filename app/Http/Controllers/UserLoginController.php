<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserLogin;
use App\Models\User;
use App\Models\Consultation;
use Spatie\Activitylog\Models\Activity;
use Carbon\Carbon;

class UserLoginController extends Controller
{
    public function __construct()
	{
		$this->middleware('auth');
	}

    public function index(Request $request)
    {
        $query = UserLogin::with('user')->where('app',2)->orderBy('id', 'desc');

        $debut = $request->get('debut');
        $fin = $request->get('fin');

        if ($debut) {
            $query->whereDate('login_at', '>=', $debut);
        }

        if ($fin) {
            $query->whereDate('login_at', '<=', $fin);
        }

        $logins = $query->limit(1000)->get();

        return view('activities.logins', compact('logins'));
    }


    public function pages($id)
    {
        $currentDate = now();
        $date  = $currentDate->copy()->subDays(14)->format('Y-m-d');
        $user=User::find($id);
        $pages = Activity::where('causer_id', $id)
            ->orderBy('id','desc')
            ->where('created_at','>=',$date)
            ->get();

        return view('activities.pages', compact('pages','user'));
    }

    public function consultations()
    {
        $currentDate = now();
        $date  = $currentDate->copy()->subDays(14)->format('Y-m-d');
 
        $consultations = Consultation::where('app',2)->orderBy('id', 'desc')->where('created_at','>=',$date)->get();
        return view('activities.consultations', compact('consultations'));
    }

    public function deleteOldConsultations()
    {
        // Calculer la date limite pour 3 mois en arrière
        $threeMonthsAgo = Carbon::now()->subMonths(3);

        // Supprimer les consultations créées avant cette date
        Consultation::where('app', 2)
                    ->where('created_at', '<', $threeMonthsAgo)
                    ->delete();

        // Rediriger vers la page des consultations après suppression
        return redirect()->route('consultations')->with('success', 'Consultations supprimées avec succès');
    }
}

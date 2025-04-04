<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserLogin;
use App\Models\User;
use App\Models\Consultation;
use Spatie\Activitylog\Models\Activity;


class UserLoginController extends Controller
{

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
        $user=User::find($id);
        $pages = Activity::where('causer_id', $id)
            ->orderBy('id','desc')
            ->limit(1000)
            ->get();

        return view('activities.pages', compact('pages','user'));
    }

    public function consultations()
    {
        $consultations = Consultation::where('app',2)->orderBy('id', 'desc')->limit(2000)->get();
        return view('activities.consultations', compact('consultations'));
    }


}

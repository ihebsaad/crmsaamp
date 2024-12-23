<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RendezVous;
use App\Models\Client;
use App\Services\RouteService;
use DB;
use Illuminate\Support\Facades\Http;

class MapController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

	public function parcours(Request $request)
    {
        $commercial_id = $request->input('commercial_id', '');
        $date = $request->input('date', '');
        $rdvs = [];
        $total_distance = 0;
        $total_duration = 0;
        $coordinates = [];

		$users=DB::table("users")
		->where('username','like','%@saamp.com')
		//->where('role','commercial')
		//->whereIn('role', ['commercial', 'user'])
		->get();

        if ($request->isMethod('post')) {
            // Récupérer les rendez-vous depuis la base de données
            $rdvs = DB::table('CRM_RendezVous as rv')
                ->join('client as c', 'rv.AccountId', '=', 'c.id')
                ->select(
                    DB::raw('DATE(rv.Started_at) AS date_rdv'),
                    'c.Adresse1 AS adresse',
                    'c.longitude AS lon',
                    'c.latitude AS lat',
                    'rv.Location AS ville',
                    'rv.heure_debut',
                    'rv.heure_fin'
                )
                ->where('rv.user_id', $commercial_id)
                ->whereDate('rv.Started_at', $date)
                ->whereNotNull('c.latitude')
                ->whereNotNull('c.longitude')
                ->orderBy('date_rdv')
                ->orderBy('rv.heure_debut')
                ->get();

            // Construire les coordonnées GPS pour OpenRouteService
            foreach ($rdvs as $rdv) {
                if (!empty($rdv->lat) && !empty($rdv->lon)) {
                    $coordinates[] = [$rdv->lon, $rdv->lat];
                }
            }

            // Appel API OpenRouteService si plus d'une coordonnée
            if (count($coordinates) > 1) {
                $apiKey = '5b3ce3597851110001cf62487ef05d4cd4804a4e807aceb13a557d0d';
                $response = Http::withHeaders(['Content-Type' => 'application/json'])
                    ->post("https://api.openrouteservice.org/v2/directions/driving-car?api_key={$apiKey}", [
                        'coordinates' => $coordinates,
                    ]);

                if ($response->successful() && isset($response->json()['routes'][0])) {
                    $route = $response->json()['routes'][0];
                    $total_distance = $route['summary']['distance'] / 1000; // En km
                    $total_duration = $route['summary']['duration'] / 60; // En minutes
                }
            }
        }

        return view('map.parcours', compact('commercial_id', 'date', 'rdvs', 'total_distance', 'total_duration', 'coordinates','users'));
    }

}

<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Exports\StatsCommercialExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\User;
use App\Models\Consultation;
use App\Services\StatsExportService;

class StatsController extends Controller
{
    protected $exportService;

    public function __construct(StatsExportService $exportService)
    {
        $this->middleware(['auth']);
        $this->exportService = $exportService;
    }


public function stats_client(Request $request)
	{
		$cl_id=$request->get('cl_id');
		$mois=$request->get('mois');

		DB::select("SET @p0=$cl_id;");
		DB::select("SET @p1=$mois;");
		$stats=  DB::select('call `sp_stats_client`(@p0,@p1); ');

		return $stats;
	}
    public static function stats()
    {
        $request = new Request();
        $request['mois'] = 1;
        $request['agence'] = auth()->user()->agence_ident ?? 1;

        try {
            DB::beginTransaction();

            $stats = null;//self::stats_commercial($request);
            $stats2 = null;//self::stats_commercial_client($request);
            $stats3 = null;//self::stats_agence($request);
            $stats4 = null;//self::stats_agence_client($request);
            $stats5 = null;//self::stats_agences($request);

        //    DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

        Consultation::create(['user' => auth()->id(),'app' => 2,'page' => "Statistiques"]);

        return view('home', compact('stats', 'stats2', 'stats3', 'stats4', 'stats5'));
    }

    public static function stats_commercial(Request $request)
    {
        try {
            $user_id =$request->get('user');
            $mois = $request->get('mois') ?? 1;

            DB::select("SET @p0=$user_id;");
            DB::select("SET @p1=$mois;");

            //DB::select("SET @p0 = ?", [$user_id]);
            //DB::select("SET @p1 = ?", [$mois]);
            $stats = DB::select('call `sp_stats_commercial`(@p0, @p1);');
        } catch (\Exception $e) {
            throw $e;
        }

        return $stats;
    }

    public static function stats_commercial_client(Request $request)
    {
        try {
            $user_id = $request->get('user') ;
            $mois = $request->get('mois') ?? 1;

            DB::select("SET @p0=$user_id;");
            DB::select("SET @p1=$mois;");
            $stats = DB::select('call `sp_stats_commercial_client`(@p0, @p1);');
        } catch (\Exception $e) {
            throw $e;
        }

        return $stats;
    }
    public static function stats_commercial_client_12(Request $request)
    {
        try {
            $user_id = $request->get('user') ;
            $mois = $request->get('mois') ?? 1;

            DB::select("SET @p0=$user_id;");
            $stats = DB::select('call `sp_stats_commercial_client_12mois`(@p0);');
        } catch (\Exception $e) {
            throw $e;
        }

        return $stats;
    }

    public static function stats_agence(Request $request)
    {
        try {
            $agence = $request->get('agence');
            $mois = $request->get('mois') ?? 1;

            DB::select("SET @p0=$agence;");
            DB::select("SET @p1=$mois;");
            $stats = DB::select('call `sp_stats_agence`(@p0, @p1);');
        } catch (\Exception $e) {
            throw $e;
        }

        return $stats;
    }

    public static function stats_agence_client(Request $request)
    {
        try {
            $agence = $request->get('agence');
            $mois = $request->get('mois') ?? 1;

            DB::select("SET @p0=$agence;");
            DB::select("SET @p1=$mois;");
            $stats = DB::select('call `sp_stats_agence_clients`(@p0, @p1);');
        } catch (\Exception $e) {
            throw $e;
        }

        return $stats;
    }

    public static function stats_agences(Request $request)
    {
        try {
            $mois = $request->get('mois') ?? 1;

            DB::select("SET @p0=$mois;");
            $stats = DB::select('call `sp_stats_agences`(@p0);');
        } catch (\Exception $e) {
            throw $e;
        }

        return $stats;
    }

    public static function stats_clients_inactifs(Request $request)
    {
        try {
            $user_id = $request->get('user') ;
            $mois = $request->get('mois') ?? 1;

            DB::select("SET @p0=$mois;");
            DB::select("SET @p1=$user_id;");
            $stats = DB::select('call `sp_stats_client_inactifs_mois_com`(@p0, @p1);');
        } catch (\Exception $e) {
            dd($e->getMessage());
            throw $e;
        }

        return $stats;
    }


    public static function stats_actvivites(Request $request)
    {
        try {
            $agence = $request->get('agence') ?? auth()->user()->agence_ident ;

            DB::select("SET @p0=$agence;");
            $stats = DB::select('call `sp_stats_agence_activite_semaine`(@p0);');
        } catch (\Exception $e) {
            dd('stats_actvivites '.$e->getMessage());
            throw $e;
        }

        return $stats;
    }


    public static function stats_actvivites_semaine(Request $request)
    {
        try {
            $debut = $request->get('debut') ;
            $fin = $request->get('fin')  ;
            $stats = DB::select("call `sp_stats_agences_activite_date`('$debut', '$fin');");
        } catch (\Exception $e) {
            dd('stats_actvivites_semaine '.$e->getMessage());
            throw $e;
        }

        return $stats;
    }

    private function getStatsData($userId)
    {
        DB::select("SET @p0=$userId;");
        return DB::select('CALL sp_stats_commercial_client_12mois(@p0);');
    }

 
    // clients sur 12 mois
    public function exportStatsExcel(Request $request)
    {
        $repId = $request->query('user');
        $representant=DB::table('representant')->where('id',$repId)->first();
        $user = User::find($representant->users_id);

        //$stats = $this->getStatsData($userId);
        DB::select("SET @p0=$repId;");
        $stats = DB::select('call `sp_stats_commercial_client_12mois`(@p0);');

        return Excel::download(new StatsCommercialExport($stats, $user), ''.$user->name.'_'.$user->lastname.'_stats_commercial.xlsx');
    }

    /**
     * Export commercial statistics by profession
     */
    public function exportCommercialMetier(Request $request)
    {
        $repId = $request->query('user');
        $mois = $request->query('mois') ?? 1;
        return $this->exportService->exportCommercialMetier($repId, $mois);
    }

    /**
     * Export commercial statistics by client
     */
    public function exportCommercialClient(Request $request)
    {
        $repId = $request->query('user');
        $mois = $request->query('mois') ?? 1;
        return $this->exportService->exportCommercialClient($repId, $mois);
    }

    /**
     * Export agency statistics by profession
     */
    public function exportAgenceMetier(Request $request)
    {
        $agenceId = $request->query('agence');
        $mois = $request->query('mois') ?? 1;
        return $this->exportService->exportAgenceMetier($agenceId, $mois);
    }

    /**
     * Export agency statistics by client
     */
    public function exportAgenceClient(Request $request)
    {
        $agenceId = $request->query('agence');
        $mois = $request->query('mois') ?? 1;
        return $this->exportService->exportAgenceClient($agenceId, $mois);
    }

    /**
     * Export statistics for all agencies
     */
    public function exportAgences(Request $request)
    {
        $mois = $request->query('mois') ?? 1;
        return $this->exportService->exportAgences($mois);
    }

    /**
     * Export statistics for inactive clients
     */
    public function exportClientsInactifs(Request $request)
    {
        $repId = $request->query('user');
        $nbMois = $request->query('mois') ?? 1;
        return $this->exportService->exportClientsInactifs($repId, $nbMois);
    }

}

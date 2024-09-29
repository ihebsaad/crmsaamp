<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;


class StatsController extends Controller
{

	public function __construct()
	{
		$this->middleware(['auth']);
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

}

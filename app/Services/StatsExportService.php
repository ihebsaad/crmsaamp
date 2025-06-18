<?php

namespace App\Services;

use App\Models\User;
use DB;
use App\Exports\StatsCommercialExport;
use App\Exports\StatsMetierExport;
use App\Exports\StatsAgenceClientExport;
use App\Exports\StatsAgencesExport;
use App\Exports\StatsClientsInactifsExport;
use App\Exports\StatsMetalExport;
use App\Exports\TransactionsExport;
use App\Exports\ReceptionExport;
use App\Exports\ReceptionExportMonth;
use Maatwebsite\Excel\Facades\Excel;

class StatsExportService
{
    /**
     * Export commercial client statistics for 12 months
     * 
     * @param int $repId Representative ID
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function exportCommercialClient12($repId)
    {
        $representant = DB::table('representant')->where('id', $repId)->first();
        $user = User::find($representant->users_id);

        DB::select("SET @p0=$repId;");
        $stats = DB::select('call `sp_stats_commercial_client_12mois`(@p0);');

        return Excel::download(
            new StatsCommercialExport($stats, $user), 
            $user->name.'_'.$user->lastname.'_stats_clients_12mois.xlsx'
        );
    }
    
    /**
     * Export commercial statistics by profession
     * 
     * @param int $repId Representative ID
     * @param int $mois Month indicator (0/1)
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function exportCommercialMetier($repId, $mois)
    {
        $representant = DB::table('representant')->where('id', $repId)->first();
        $user = User::find($representant->users_id);

        DB::select("SET @p0=$repId;");
        DB::select("SET @p1=$mois;");
        $stats = DB::select('call `sp_stats_commercial`(@p0, @p1);');

        return Excel::download(
            new StatsMetierExport($stats, $user), 
            $user->name.'_'.$user->lastname.'_stats_metiers.xlsx'
        );
    }
    
    /**
     * Export commercial statistics by client
     * 
     * @param int $repId Representative ID
     * @param int $mois Month indicator (0/1)
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function exportCommercialClient($repId, $mois)
    {
        $representant = DB::table('representant')->where('id', $repId)->first();
        $user = User::find($representant->users_id);

        DB::select("SET @p0=$repId;");
        DB::select("SET @p1=$mois;");
        $stats = DB::select('call `sp_stats_commercial_client`(@p0, @p1);');

        return Excel::download(
            new StatsAgenceClientExport($stats, $user->name.' '.$user->lastname, 'client'), 
            $user->name.'_'.$user->lastname.'_stats_clients.xlsx'
        );
    }
    
    /**
     * Export agency statistics by profession
     * 
     * @param int $agenceId Agency ID
     * @param int $mois Month indicator (0/1)
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function exportAgenceMetier($agenceId, $mois)
    {
        $agence = DB::table('agence')->where('agence_ident', $agenceId)->first();

        DB::select("SET @p0=$agenceId;");
        DB::select("SET @p1=$mois;");
        $stats = DB::select('call `sp_stats_agence`(@p0, @p1);');

        return Excel::download(
            new StatsAgencesExport($stats, $agence->agence_lib), 
            'Agence_'.$agence->agence_lib.'_stats_metiers.xlsx'
        );
    }
    
    /**
     * Export agency statistics by client
     * 
     * @param int $agenceId Agency ID
     * @param int $mois Month indicator (0/1)
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function exportAgenceClient($agenceId, $mois)
    {
        $agence = DB::table('agence')->where('agence_ident', $agenceId)->first();

        DB::select("SET @p0=$agenceId;");
        DB::select("SET @p1=$mois;");
        $stats = DB::select('call `sp_stats_agence_clients`(@p0, @p1);');

        return Excel::download(
            new StatsAgenceClientExport($stats, $agence->agence_lib, 'agence'), 
            'Agence_'.$agence->agence_lib.'_stats_clients.xlsx'
        );
    }
    
    /**
     * Export statistics for all agencies
     * 
     * @param int $mois Month indicator (0/1)
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function exportAgences($mois)
    {
        DB::select("SET @p0=$mois;");
        $stats = DB::select('call `sp_stats_agences`(@p0);');

        return Excel::download(
            new StatsAgencesExport($stats), 
            'Stats_toutes_agences.xlsx'
        );
    }
    
    /**
     * Export statistics for inactive clients
     * 
     * @param int $repId Representative ID
     * @param int $nbMois Number of months of inactivity
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function exportClientsInactifs($repId, $nbMois)
    {
        $representant = DB::table('representant')->where('id', $repId)->first();
        $user = User::find($representant->users_id);

        DB::select("SET @p0=$nbMois;");
        DB::select("SET @p1=$repId;");
        $stats = DB::select('call `sp_stats_client_inactifs_mois_com`(@p0, @p1);');

        return Excel::download(
            new StatsClientsInactifsExport($stats, $user, $nbMois), 
            $user->name.'_'.$user->lastname.'_clients_inactifs_'.$nbMois.'mois.xlsx'
        );
    }



    public function exportMetalStats($type, $metals = [])
    {
        // Convertir le tableau de métaux en chaîne pour la procédure stockée
        $metalsString = is_array($metals) ? implode(',', $metals) : $metals;
        
        // Si aucun métal n'est sélectionné, utiliser tous les métaux par défaut
        if (empty($metalsString)) {
            $metalsString = 'OR,ARGENT,PLATINE,PALLADIUM';
        }
        
        // Exécuter la procédure stockée
        DB::select("SET @p0='$type';");
        DB::select("SET @p1='$metalsString';");
        $result = DB::select("CALL `sp_stats_spot_operations_v3`(@p0, @p1);");

        return Excel::download(
            new StatsMetalExport($result),
            'statistiques_metaux_' . $type . '_' . date('Y-m-d') . '.xlsx'
        );
    }


        public function exportTransactions( )
    {
 
 		$result = DB::table("trading_operation_web")->orderBy('trading_op_id_n', 'desc')->where('cl_ident','<>',15267)->limit(1000)->get()->toArray();

        return Excel::download(
            new TransactionsExport($result),
            'transactions_' . date('Y-m-d') . '.xlsx'
        );
    }


    public function stats_reception( $month=null)
    {
        if($month==0){
            $stats = DB::select('call `sp_stats_agence_reception_poids`();');
            return Excel::download(
                new ReceptionExport($stats),
                'Réception des lots d\'or hautes teneurs par agence (semaine)' . date('Y-m-d') . '.xlsx'
            );
        }
        else{
		    $stats = DB::select('call `sp_stats_agence_reception_mois`();');

            return Excel::download(
            new ReceptionExportMonth($stats),
            'Réception des lots d\'or hautes teneurs par agence (mois)' . date('Y-m-d') . '.xlsx'
            );
        }
    }
/*
    public function exportMetalStats($type, $metals = [])
    {
        // Préparer la requête en fonction du type de statistique
        $query = DB::table('stats_spot')->select(
            'periode', 'metal', 'type_utilisateur', 'sens', 'nb_operations_spot', 'poids_total'
        );
        
        // Filtrer par type (jour ou mois)
        if ($type == 'jour' || $type == 'mois') {
            // Supposons que votre procédure stockée utilise une colonne 'type' ou similaire
            $query->where('type', $type);
        }
        
        // Filtrer par métaux sélectionnés
        if (!empty($metals)) {
            $query->whereIn('metal', $metals);
        }
        
        $stats = $query->get();
        
        return Excel::download(
            new StatsMetalExport($stats),
            'statistiques_metaux_' . $type . '_' . date('Y-m-d') . '.xlsx'
        );
    }

    */
}
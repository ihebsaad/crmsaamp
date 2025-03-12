<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Exports\StatsCommercialExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\User;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Symfony\Component\HttpFoundation\StreamedResponse;

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
            //d('debut '.$debut. '  Fin : '.$fin);

            //DB::select("SET @p0=$debut;");
            //DB::select("SET @p1=$fin;");
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

    /*public function exportStatsExcel(Request $request)
    {
        $user_id = $request->get('user');

        DB::select("SET @p0=$user_id;");
        $stats = DB::select('call `sp_stats_commercial_client_12mois`(@p0);');
        //dd($user_id);
        return Excel::download(new StatsCommercialExport($stats), 'stats_commercial.xlsx');
    }*/
/*

    public function exportStatsExcel(Request $request)
    {
        $userId = $request->query('user'); // Récupérer l'ID du commercial

        $data = $this->getStatsData($userId);

        // Convertir les objets en tableaux
        $statsArray = json_decode(json_encode($data), true);

        // Vérifier si les données existent
        if (!empty($statsArray)) {
            $headers = array_keys($statsArray[0]); // Récupérer les clés des données
        } else {
            $headers = []; // Pas de données
        }

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Définir les styles pour l'en-tête
        $headerStyle = [
            'font' => ['bold' => true, 'size' => 12, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'D4AF37']], // Or
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
        ];

        // Définir le style des cellules
        $cellStyle = [
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
        ];

        // Ajouter les en-têtes
        $headers = array_keys($statsArray[0]); // Récupérer les clés des données
        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($col . '1', $header);
            $sheet->getStyle($col . '1')->applyFromArray($headerStyle);
            $sheet->getColumnDimension($col)->setAutoSize(true); // Ajuster la largeur des colonnes
            $col++;
        }

        // Ajouter les données
        $row = 2;
        foreach ($statsArray as $rowData) {
            $col = 'A';
            foreach ($rowData as $value) {
                $sheet->setCellValue($col . $row, $value);
                $sheet->getStyle($col . $row)->applyFromArray($cellStyle);
                $col++;
            }
            $row++;
        }

        // Centrer les cellules
        $sheet->getStyle('A1:' . $col . ($row - 1))->applyFromArray($cellStyle);

        // Générer le fichier Excel
        $writer = new Xlsx($spreadsheet);
        $fileName = 'Statistiques_Clients.xlsx';

        return new StreamedResponse(function () use ($writer) {
            $writer->save('php://output');
        }, 200, [
            "Content-Type" => "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
            "Content-Disposition" => "attachment; filename=\"$fileName\""
        ]);
    }
    */

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
}

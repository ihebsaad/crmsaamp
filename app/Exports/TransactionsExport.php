<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use \App\Models\User;
use DB;

class TransactionsExport implements FromArray, WithHeadings, WithStyles
{
    protected $stats;

    public function __construct($stats)
    {
        $this->stats = array_map(fn($item) => (array) $item, $stats); // Convertir en tableau
    }

    public function headings(): array
    {
        // En-têtes des colonnes
        return [
            ['DERNIÈRES TRANSACTIONS'], // Titre
            ['Client','Type','Sens','Métal','Date','Poids','Cours','ExID','Par']

        ];
    }
 
    public function array(): array
    {
        $user_list = DB::table("users")
			//->where('username', 'like', '%@saamp.com')
			->pluck(DB::raw("CONCAT(name, ' ', lastname)"), 'id')
			->toArray();
		$metals=array('1'=>'Or','2'=>'Argent','3'=>'Platine','4'=>'Palladium','5'=>'Rhodium','6'=>'Autres');
		$types = DB::table("trading_type_operation")->pluck('lib_court_ope', 'type_ope_id');
        $data = [];

        foreach ($this->stats as $stat) {
            $row = [
                $stat['cl_ident'].' '  ,
                $types[$stat['type_ope']] ?? '',
                $stat['sensclient']  ?? '',
                $metals[$stat['metal_id']] ??  '',
                date('d/m/Y H:i', strtotime($stat['date_ordre']  ?? '')),
                $stat['poids'] ? $stat['poids'] . ' g' : '0 g',
                $stat['cours'] ?? '',
                $stat['EXID'] ?? '',
                $user_list[$stat['user_id']] ?? User::find($stat['user_id'])->email ,
            ];

            $data[] = $row;
        }

        return $data;
    }

    public function styles(Worksheet $sheet)
    {
        // Fusionner les cellules pour le titre
        $sheet->mergeCells('A1:F1');
          
        $sheet->getColumnDimension('B')->setWidth(13);
        $sheet->getColumnDimension('E')->setWidth(20);
        $sheet->getColumnDimension('F')->setWidth(13);
        $sheet->getColumnDimension('G')->setWidth(13);
        $sheet->getColumnDimension('H')->setWidth(20);            
        $sheet->getColumnDimension('I')->setWidth(25);            
        return [
            1 => ['font' => ['bold' => true, 'size' => 14], 'alignment' => ['horizontal' => 'center']], // Titre
            2 => [
                'font' => ['bold' => false, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => 'D4AF37']],
                'alignment' => ['horizontal' => 'center']
            ],
        ];
    }
}

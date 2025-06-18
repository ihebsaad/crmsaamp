<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use \App\Models\User;
use DB;

class ReceptionExport implements FromArray, WithHeadings, WithStyles
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
            ['Réception des lots d\'or hautes teneurs par agence'], // Titre
            ['Agence','S0','S1','S2','S3','S4','S5','S6','S7','S8','S9','S10','S11']

        ];
    }
 
    public function array(): array
    {

        $data = [];

        foreach ($this->stats as $stat) {
            $row = [
                $stat['agences'] ?? '',
                $stat['S0'] ?? '',
                $stat['S1'] ?? '',
                $stat['S2'] ?? '',
                $stat['S3'] ?? '',
                $stat['S4'] ?? '',
                $stat['S5'] ?? '',
                $stat['S6'] ?? '',
                $stat['S7'] ?? '',
                $stat['S8'] ?? '',
                $stat['S9'] ?? '',
                $stat['S10'] ?? '',
                $stat['S11'] ?? '',

            ];

            $data[] = $row;
        }

        return $data;
    }

    public function styles(Worksheet $sheet)
    {
        // Fusionner les cellules pour le titre
        $sheet->mergeCells('A1:F1');
        $sheet->getColumnDimension('A')->setWidth(10);
        $sheet->getColumnDimension('B')->setWidth(10);
        $sheet->getColumnDimension('C')->setWidth(10);
        $sheet->getColumnDimension('D')->setWidth(10);
        $sheet->getColumnDimension('E')->setWidth(10);
        $sheet->getColumnDimension('F')->setWidth(10);
        $sheet->getColumnDimension('G')->setWidth(10);
        $sheet->getColumnDimension('H')->setWidth(10);
        $sheet->getColumnDimension('I')->setWidth(10);
        $sheet->getColumnDimension('J')->setWidth(10);
        $sheet->getColumnDimension('K')->setWidth(10);
        $sheet->getColumnDimension('L')->setWidth(10);
        $sheet->getColumnDimension('M')->setWidth(10);

           
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

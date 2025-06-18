<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use \App\Models\User;
use DB;

class ReceptionExportMonth implements FromArray, WithHeadings, WithStyles
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
            ['Réception des lots d\'or hautes teneurs par agence par mois'], // Titre
            ['Agence','M','M1','M2','M3','M4']

        ];
    }
 
    public function array(): array
    {

        $data = [];

        foreach ($this->stats as $stat) {
            $row = [
                $stat['agences'] ?? '',
                $stat['M'] ?? '',
                $stat['M_1'] ?? '',
                $stat['M_2'] ?? '',
                $stat['M_3'] ?? '',
                $stat['M_4'] ?? '',
            ];

            $data[] = $row;
        }

        return $data;
    }

    public function styles(Worksheet $sheet)
    {
        // Fusionner les cellules pour le titre
        $sheet->mergeCells('A1:F1');
        $sheet->getColumnDimension('A')->setWidth(12);
        $sheet->getColumnDimension('B')->setWidth(12);
        $sheet->getColumnDimension('C')->setWidth(12);
        $sheet->getColumnDimension('D')->setWidth(12);
        $sheet->getColumnDimension('E')->setWidth(12);
        $sheet->getColumnDimension('F')->setWidth(12);
           
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

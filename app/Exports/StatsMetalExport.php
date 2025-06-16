<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class StatsMetalExport implements FromArray, WithHeadings, WithStyles
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
            ['STATISTIQUES DES MÉTAUX'], // Titre
            ['','CLIENT', 'INTERNE'], // Ligne vide
            ['PÉRIODE', 'NB ACHATS', 'POIDS ACHAT', 'NB VENTES', 'POIDS VENTE', 'NB ACHATS', 'POIDS ACHAT', 'NB VENTES', 'POIDS VENTE']

        ];
    }
 
    public function array(): array
    {

        foreach ($this->stats as $stat) {
            $row = [
                $stat['periode'] ?? '',
                $stat['nb_achat_client'] ?? 0,
                $stat['poids_achat_client'] ? $stat['poids_achat_client'] . ' g' : '0 g',
                $stat['nb_vente_client'] ?? 0,
                $stat['poids_vente_client'] ? $stat['poids_vente_client'] . ' g' : '0 g',
                $stat['nb_achat_interne'] ?? 0,
                $stat['poids_achat_interne'] ? $stat['poids_achat_interne'] . ' g' : '0 g',
                $stat['nb_vente_interne'] ?? 0,
                $stat['poids_vente_interne'] ? $stat['poids_vente_interne'] . ' g' : '0 g', // Ajouter l'unité de mesure
            ];

            $data[] = $row;
        }

        return $data;
    }

    public function styles(Worksheet $sheet)
    {
        // Fusionner les cellules pour le titre
        $sheet->mergeCells('A1:F1');
        $sheet->mergeCells('B2:E2'); $sheet->mergeCells('F2:I2');
         

        return [
            1 => ['font' => ['bold' => true, 'size' => 14], 'alignment' => ['horizontal' => 'center']], // Titre
            2 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => 'D4AF37']],
                'alignment' => ['horizontal' => 'center']
            ],
            3 => [
                'font' => ['bold' => false, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => 'D4AF37']],
                'alignment' => ['horizontal' => 'center']
            ],
        ];
    }
}

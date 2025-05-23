<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class StatsAgencesExport implements FromArray, WithHeadings, WithStyles, WithTitle
{
    protected $stats;
    protected $agence;

    public function __construct($stats,$agence=null)
    {
        $this->stats = array_map(fn($item) => (array) $item, $stats);
        $this->agence = $agence;
    }

    public function headings(): array
    {
        $title='Statistiques de toutes les agences';
        if($this->agence!=''){
            $title="Statistiques de l'agence ".$this->agence;
        }
        return [
            [$title],
            [], // Ligne vide
            [
                'Métier', 
                'N', 
                ' ', 
                'N-1', 
                ' ', 
                'N-2', 
                ' ', 
                'N-3'
            ]
        ];
    }

    public function array(): array
    {
        $data = [];

        if($this->agence!=''){
            foreach ($this->stats as $stat) {
                $data[] = [
                    $stat['metier'] ?? '',
                    $stat['N'] ?? 0,
                    $stat['delta_1'] ?? '',
                    $stat['N_1'] ?? 0,
                    $stat['delta_2'] ?? '',
                    $stat['N_2'] ?? 0,
                    $stat['delta_3'] ?? '',
                    $stat['N_3'] ?? 0,
                ];
            }
        }else{
            foreach ($this->stats as $stat) {
                $data[] = [
                    $stat['Agence'] ?? '',
                    $stat['N'] ?? 0,
                    $stat['delta_1'] ?? '',
                    $stat['N_1'] ?? 0,
                    $stat['delta_2'] ?? '',
                    $stat['N_2'] ?? 0,
                    $stat['delta_3'] ?? '',
                    $stat['N_3'] ?? 0,
                ];
            }
        }


        // Ajouter une ligne de total si nécessaire
        if (count($data) > 1) {
            $totalN = array_sum(array_column($data, 1));
            $totalN1 = array_sum(array_column($data, 3));
            $totalN2 = array_sum(array_column($data, 5));
            $totalN3 = array_sum(array_column($data, 7));
            
            // Calculer les variations totales
            $delta1 = $totalN1 > 0 ? round(($totalN - $totalN1) / $totalN1 * 100, 2) . '%' : 'N/A';
            $delta2 = $totalN2 > 0 ? round(($totalN1 - $totalN2) / $totalN2 * 100, 2) . '%' : 'N/A';
            $delta3 = $totalN3 > 0 ? round(($totalN2 - $totalN3) / $totalN3 * 100, 2) . '%' : 'N/A';
            
            $data[] = [
                'TOTAL',
                $totalN,
                $delta1,
                $totalN1,
                $delta2,
                $totalN2,
                $delta3,
                $totalN3
            ];
        }

        return $data;
    }

    public function styles(Worksheet $sheet)
    {
        // Fusionner les cellules pour le titre
        $sheet->mergeCells('A1:H1');
        
        // Appliquer des styles conditionnels pour les variations
        $rows = count($this->stats) + 4; // +4 pour compter le titre, la ligne vide, l'en-tête et le total
        
        // Appliquer couleur rouge/verte pour les variations
        for ($row = 4; $row <= $rows; $row++) {
            $cellC = $sheet->getCell('C' . $row)->getValue();
            $cellE = $sheet->getCell('E' . $row)->getValue();
            $cellG = $sheet->getCell('G' . $row)->getValue();
            
            if ($cellC === 'N/A' || $cellE === 'N/A' || $cellG === 'N/A') {
                continue;
            }
            
            // Convertir les valeurs de pourcentage en nombres pour la comparaison
            $valueC = (float) str_replace(['%', ' '], '', $cellC);
            $valueE = (float) str_replace(['%', ' '], '', $cellE);
            $valueG = (float) str_replace(['%', ' '], '', $cellG);
            
            // Appliquer le style selon la valeur
            if ($valueC < 0) {
                $sheet->getStyle('C' . $row)->getFont()->getColor()->setRGB('FF0000'); // Rouge
            } else {
                $sheet->getStyle('C' . $row)->getFont()->getColor()->setRGB('008000'); // Vert
            }
            
            if ($valueE < 0) {
                $sheet->getStyle('E' . $row)->getFont()->getColor()->setRGB('FF0000');
            } else {
                $sheet->getStyle('E' . $row)->getFont()->getColor()->setRGB('008000');
            }
            
            if ($valueG < 0) {
                $sheet->getStyle('G' . $row)->getFont()->getColor()->setRGB('FF0000');
            } else {
                $sheet->getStyle('G' . $row)->getFont()->getColor()->setRGB('008000');
            }
        }

        // Mettre en gras la ligne de total
        $lastRow = $sheet->getHighestRow();
        $sheet->getStyle('A' . $lastRow . ':H' . $lastRow)->getFont()->setBold(true);
        
        // Appliquer l'autofilter
        $sheet->setAutoFilter('A3:H3');

        // Styles de base
        return [
            1 => [
                'font' => ['bold' => true, 'size' => 14],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
            ],
            3 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => 'D4AF37']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
            ],
        ];
    }

    public function title(): string
    {
        return 'Toutes les agences';
    }
}
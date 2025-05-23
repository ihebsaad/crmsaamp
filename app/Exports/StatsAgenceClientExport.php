<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class StatsAgenceClientExport implements FromArray, WithHeadings, WithStyles, WithTitle
{
    protected $stats;
    protected $title;
    protected $type;

    /**
     * @param array $stats Data to export
     * @param string $title Title (user name or agency name)
     * @param string $type 'client' for commercial stats, 'agence' for agency stats
     */
    public function __construct($stats, $title, $type = 'client')
    {
        $this->stats = array_map(fn($item) => (array) $item, $stats);
        $this->title = $title;
        $this->type = $type;
    }

    public function headings(): array
    {
        $titlePrefix = $this->type === 'agence' ? 'Agence ' : '';
        $titleText = $titlePrefix . $this->title . ' - Statistiques par client';
        
        return [
            [$titleText],
            [], // Ligne vide
            [
                'Client', 
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

        foreach ($this->stats as $stat) {
            $data[] = [
                $stat['nom'] ?? '',
                $stat['N'] ?? 0,
                $stat['delta_1'] ?? '',
                $stat['N_1'] ?? 0,
                $stat['delta_2'] ?? '',
                $stat['N_2'] ?? 0,
                $stat['delta_3'] ?? '',
                $stat['N_3'] ?? 0,
            ];
        }

        return $data;
    }

    public function styles(Worksheet $sheet)
    {
        // Fusionner les cellules pour le titre
        $sheet->mergeCells('A1:H1');
        
        // Appliquer des styles conditionnels pour les variations
        $rows = count($this->stats) + 3; // +3 pour les trois premières lignes (titre, vide, en-têtes)
        
        // Appliquer couleur rouge/verte pour les variations
        for ($row = 4; $row <= $rows; $row++) {
            $cellC = $sheet->getCell('C' . $row)->getValue();
            $cellE = $sheet->getCell('E' . $row)->getValue();
            $cellG = $sheet->getCell('G' . $row)->getValue();
            
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

        // Appliquer l'autofilter
        $sheet->setAutoFilter('A3:H3');
        
        // Ajuster la largeur des colonnes
        $sheet->getColumnDimension('A')->setWidth(30);  // Client
        for ($col = 'B'; $col <= 'H'; $col++) {
            $sheet->getColumnDimension($col)->setWidth(15);
        }

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
        return 'Statistiques par client';
    }
}
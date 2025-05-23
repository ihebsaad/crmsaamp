<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class StatsClientsInactifsExport implements FromArray, WithHeadings, WithStyles, WithTitle
{
    protected $stats;
    protected $user;
    protected $nbMois;

    public function __construct($stats, $user, $nbMois)
    {
        $this->stats = array_map(fn($item) => (array) $item, $stats);
        $this->user = $user;
        $this->nbMois = $nbMois;
    }

    public function headings(): array
    {
        return [
            [$this->user->name . ' ' . $this->user->lastname . ' - Clients inactifs depuis ' . $this->nbMois . ' mois'],
            [], // Ligne vide
            [
                'Client', 
                'Dernière activité', 
                'N', 
                'N-1', 
                'N-2', 
                'N-3'
            ]
        ];
    }

    public function array(): array
    {
        $data = [];

        foreach ($this->stats as $stat) {
            // Formater la date d'activité
            $mois = isset($stat['annee_mois']) ? explode(' - ', $stat['annee_mois'])[1] : '';
            $annee = isset($stat['annee_mois']) ? explode(' - ', $stat['annee_mois'])[0] : '';
            $mois = strlen($mois) === 1 ? '0' . $mois : $mois;
            $annee_mois = $mois . '/' . $annee;

            $data[] = [
                $stat['nom'] ?? '',
                $annee_mois ?? '',
                $stat['N'] ?? 0,
                $stat['N_1'] ?? 0,
                $stat['N_2'] ?? 0,
                $stat['N_3'] ?? 0,
            ];
        }

        return $data;
    }

    public function styles(Worksheet $sheet)
    {
        // Fusionner les cellules pour le titre
        $sheet->mergeCells('A1:F1');
        
        // Appliquer l'autofilter
        $sheet->setAutoFilter('A3:F3');
        
        // Ajuster la largeur des colonnes
        $sheet->getColumnDimension('A')->setWidth(30);  // Client
        $sheet->getColumnDimension('B')->setWidth(15);  // Date
        for ($col = 'C'; $col <= 'F'; $col++) {
            $sheet->getColumnDimension($col)->setWidth(12);
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
        return 'Clients inactifs';
    }
}
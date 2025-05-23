<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class StatsCommercialExportTwo implements FromArray, WithHeadings, WithStyles, WithTitle
{
    protected $stats;
    protected $user;

    public function __construct($stats, $user)
    {
        $this->stats = array_map(fn($item) => (array) $item, $stats);
        $this->user = $user;
    }

    public function headings(): array
    {
        // Associer les mois en français
        $mois_francais = [
            'Jan' => 'Janvier', 'Feb' => 'Février', 'Mar' => 'Mars', 'Apr' => 'Avril',
            'May' => 'Mai', 'Jun' => 'Juin', 'Jul' => 'Juillet', 'Aug' => 'Août',
            'Sep' => 'Septembre', 'Oct' => 'Octobre', 'Nov' => 'Novembre', 'Dec' => 'Décembre'
        ];

        // Générer les en-têtes des mois
        $headers = ['Client']; // Première colonne "Client"

        for ($i = 0; $i <= 11; $i++) {
            $month_date = strtotime("-$i months");
            $month = date('M', $month_date);
            $year = date('Y', $month_date);
            $headers[] = $mois_francais[$month] . ' ' . $year;
        }

        $headers[] = 'TOTAL'; // Dernière colonne "TOTAL"

        // Première ligne avec nom et prénom fusionnés
        return [
            [$this->user->name . ' ' . $this->user->lastname . ' - Statistiques clients sur 12 mois'],
            [], // Ligne vide
            $headers // Ligne 3 : En-têtes des colonnes
        ];
    }

    public function array(): array
    {
        $data = [];

        foreach ($this->stats as $stat) {
            $row = [
                $stat['nom'] ?? '', // Colonne "Client"
            ];

            for ($i = 0; $i <= 11; $i++) {
                $month_key = 'M_' . ($i + 1);
                $row[] = $stat[$month_key] ?? 0; // Valeur pour chaque mois
            }

            $row[] = $stat['TOTAL'] ?? 0; // Valeur pour la colonne "TOTAL"

            $data[] = $row;
        }

        return $data;
    }

    public function styles(Worksheet $sheet)
    {
        // Calculer le nombre total de colonnes (Client + 12 mois + Total)
        $lastColumn = $sheet->getHighestColumn();
        
        // Fusionner les cellules pour le titre
        $sheet->mergeCells('A1:' . $lastColumn . '1');

        // Appliquer l'autofilter
        $lastRow = $sheet->getHighestRow();
        $sheet->setAutoFilter('A3:' . $lastColumn . '3');

        // Ajuster la largeur des colonnes
        $sheet->getColumnDimension('A')->setWidth(30);  // Client
        for ($col = 'B'; $col <= $lastColumn; $col++) {
            $sheet->getColumnDimension($col)->setWidth(15);
        }

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
        return 'Clients sur 12 mois';
    }
}
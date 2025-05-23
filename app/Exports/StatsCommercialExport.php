<?php

namespace App\Exports;

use App\Models\CompteClient;
use App\Models\Agence;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class StatsCommercialExport implements FromArray, WithHeadings, WithStyles, WithColumnFormatting, ShouldAutoSize
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

        // Générer les en-têtes des mois (même ordre que le tableau web)
        $headers = ['CL IDENT', 'Agence', 'Nom du client']; // Trois premières colonnes

        for ($i = 0; $i <= 12; $i++) {
            $month_date = strtotime("-$i months");
            $month = date('M', $month_date);
            $year = date('Y', $month_date);
            $headers[] = $mois_francais[$month] . ' ' . $year;
        }

        $headers[] = 'TOTAL'; // Dernière colonne "TOTAL"

        // Première ligne avec nom et prénom fusionnés
        return [
            [$this->user->name . ' ' . $this->user->lastname], // Ligne 1 : Nom et prénom
            [], // Ligne 2 : Ligne vide
            $headers // Ligne 3 : En-têtes des colonnes
        ];
    }

    public function array(): array
    {
        $data = [];

        foreach ($this->stats as $stat) {
            $id = $stat['id'];
            $client = CompteClient::find($id);
            $agence = Agence::find($client->agence_ident);

            // Construire la ligne avec toutes les colonnes
            $row = [
                $client->cl_ident,           // CL IDENT
                $agence->agence_lib,         // Agence
                $stat['nom'] ?? '',          // Nom du client
            ];

            // Ajouter les valeurs des mois (nettoyer les espaces)
            // L'ordre dans $stats correspond au tableau web : M_1=Mai 2025, M_2=Avril 2025, etc.
            for ($i = 0; $i <= 12; $i++) { // Changé de 0-11 à 1-12
                if($i==0)
                    $month_key = 'M';
                else
                    $month_key = 'M_' . $i;

                $value = $stat[$month_key] ?? 0;
                // Nettoyer les espaces et convertir en nombre
                $cleanValue = is_string($value) ? str_replace(' ', '', $value) : $value;
                $row[] = is_numeric($cleanValue) ? (int)$cleanValue : 0;
            }

            // Ajouter le total (nettoyer les espaces)
            $total = $stat['TOTAL'] ?? 0;
            $cleanTotal = is_string($total) ? str_replace(' ', '', $total) : $total;
            $row[] = is_numeric($cleanTotal) ? (int)$cleanTotal : 0;

            $data[] = $row;
        }

        return $data;
    }

    public function styles(Worksheet $sheet)
    {
        // Fusionner les cellules pour le nom et prénom (ajuster selon le nombre de colonnes)
        $lastColumn = chr(67 + 12); // C + 12 mois = O (CL IDENT + Agence + Nom + 12 mois + TOTAL = 16 colonnes)
        $sheet->mergeCells('A1:' . $lastColumn . '1');

        // Définir la largeur minimale des colonnes
        $sheet->getColumnDimension('A')->setWidth(10);
        $sheet->getColumnDimension('B')->setWidth(15);
        $sheet->getColumnDimension('C')->setWidth(25);

        return [
            1 => ['font' => ['bold' => true, 'size' => 14], 'alignment' => ['horizontal' => 'center']], // Nom + prénom
            3 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => 'D4AF37']],
            ],
        ];
    }

    public function columnFormats(): array
    {
        // Formater les colonnes numériques sans séparateur de milliers
        $formats = [];
        
        // Colonnes des mois (D à O) + colonne TOTAL (P)
        for ($i = 4; $i <= 16; $i++) { // Colonnes D(4) à P(16)
            $column = chr(64 + $i); // Convertir en lettre (D, E, F, etc.)
            $formats[$column] = '0'; // Format nombre entier sans séparateur
        }
        
        return $formats;
    }
}
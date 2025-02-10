<?php

namespace App\Exports;

use App\Models\RendezVous;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class RendezVousExport implements FromCollection, WithHeadings
{
    protected $rendezvous;

    public function __construct($rendezvous)
    {
        $this->rendezvous = $rendezvous;
    }

    public function collection()
    {
        return $this->rendezvous->map(function ($rendezvous) {
            return [
                'ID' => $rendezvous->id,
                'Client' => $rendezvous->client->name ?? 'N/A',
                'Date' => $rendezvous->Started_at,
                'Heure début' => $rendezvous->heure_debut,
                'Heure fin' => $rendezvous->heure_fin,
                'Statut' => $rendezvous->status,
            ];
        });
    }

    public function headings(): array
    {
        return ['ID', 'Client', 'Date', 'Heure début', 'Heure fin', 'Statut'];
    }
}

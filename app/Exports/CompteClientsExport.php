<?php

namespace App\Exports;

use App\Models\CompteClient;
use App\Models\Agence;
use App\Models\Contact;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CompteClientsExport implements FromCollection, WithHeadings
{
    protected $query;

    public function __construct($query)
    {
        $this->query = $query;
    }

    public function collection()
    {

        return $this->query->get()->map(function ($client) {
            $agences = Agence::orderBy('agence_lib', 'asc')->pluck('agence_lib', 'agence_ident')->toArray();
            $agenceLib = $agences[$client->agence_ident] ?? '';
            $type_c='';
            switch ($client->etat_id) {
            case 2 : $type_c='Client' ; break;
            case 1 : $type_c='Prospect' ;break;
            case 3 : $type_c='Fermé' ; break;
            case 4 : $type_c='Inactif' ; break;
            case 5 : $type_c='Particulier' ; break;
            }

            $tel= $client->Phone ??  $client->Tel;
            if(trim($tel)==''){
                $contact=Contact::where('mycl_ident',$client->id)->first();
                if(isset($contact)){
                    $tel= $contact->Phone ?? $contact->MobilePhone   ;
                }
                else{
                    $contact=Contact::where('cl_ident',$client->cl_ident)->first();
                    $tel= $contact->Phone ?? $contact->MobilePhone ?? ''   ;
                }
            }

            return [
                'CL ID' => $client->cl_ident,
                'Nom' => $client->Nom,
                'Ville' => $client->ville,
                'Tel' => $tel,
                'Agence' => $agenceLib,
                'Type' => $type_c,
            ];
        });
    }

    public function headings(): array
    {
        return ['CL ID', 'Nom', 'Ville','Tel', 'Agence', 'Type'];
    }
}


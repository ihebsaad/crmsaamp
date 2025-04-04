<?php

namespace App\Exports;

use App\Models\UserLogin;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class UserLoginsExport implements FromCollection, WithHeadings
{
    protected $debut;
    protected $fin;

    public function __construct($debut = null, $fin = null)
    {
        $this->debut = $debut;
        $this->fin = $fin;
    }

    public function headings(): array
    {
        return [
            'ID',
            'Client ID',
            'Nom complet',
            'Email',
            'Type',
            'Date de connexion',
            'Date de déconnexion',
        ];
    }

    public function collection()
    {
        $query = UserLogin::with('user')->orderBy('id', 'desc');

        if ($this->debut) {
            $query->whereDate('login_at', '>=', $this->debut);
        }

        if ($this->fin) {
            $query->whereDate('login_at', '<=', $this->fin);
        }

        return $query->get()->map(function ($login) {
            $login_date = date('d/m/Y H:i', strtotime($login->login_at));
            $logout_date = $login->logout_at != '' ? date('d/m/Y H:i', strtotime($login->logout_at)) : '';
            $name= $login->user->name ?? '' ;
            $lastname= $login->user->lastname ?? '' ;
            $complete_name= $name.' '.$lastname;
            return [
                'ID' => $login->user->id ?? '',
                'Client ID' => $login->user->client_id ?? '',
                'Nom complet' =>  $complete_name,
                'Email' => $login->user->email ?? '',
                'Type' => $login->user->user_type ?? '',
                'Date de connexion' => $login_date,
                'Date de déconnexion' => $logout_date,
            ];
        });
    }
}
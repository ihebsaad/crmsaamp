<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class Offre extends Model
{
    use HasFactory;

    protected $table = "CRM_OffrePrix";

    protected $guarded = [];

    public $timestamps = false;

    public static function updateWithSequentialIds()
    {
        $count = Offre::count();
        for ($i = 1; $i <= $count; $i++) {
            Offre::where('id', null)->limit(1)->update(['id' => $i]);
        }
    }

    protected $casts = [
        'Offre_validee' => 'boolean',
        'Date_creation' => 'datetime',
        'Date_cloture' => 'datetime',
        //'date_valide' => 'datetime'
    ];

    // Relation avec l'utilisateur créateur
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relation avec l'utilisateur validateur
    public function validator()
    {
        return $this->belongsTo(User::class, 'valide_par');
    }

    // Relation avec le client
    public function client()
    {
        return $this->belongsTo(CompteClient::class, 'mycl_id');
    }

}


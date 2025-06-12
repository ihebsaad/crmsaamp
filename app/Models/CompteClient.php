<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompteClient extends Model
{
    use HasFactory;

    //protected $table = "CRM_CompteCLient";
    protected $table = "client";
    //protected $primaryKey = 'cl_ident';

    protected $guarded = [];

    public $timestamps = false;

    public static function updateWithSequentialIds()
    {
        $count = CompteClient::count();
        for ($i = 1; $i <= $count; $i++) {
            CompteClient::where('id', null)->limit(1)->update(['id' => $i]);
        }
    }
    
    public function agence()
    {
        return $this->belongsTo(Agence::class, 'agence_ident');
    }

    public function offres()
    {
        return $this->hasMany(Offre::class, 'mycl_id');
    }

    public function contacts()
    {
        return $this->hasMany(Contact::class, 'mycl_ident');
    }
}
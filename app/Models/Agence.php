<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agence extends Model
{
    use HasFactory;

    protected $table = "agence";
    protected $primaryKey = 'agence_ident';

    protected $guarded = [];

    public function clients()
    {
        return $this->hasMany(Client::class, 'agence_ident', 'agence_ident');
    }



}
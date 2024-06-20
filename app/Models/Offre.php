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


}


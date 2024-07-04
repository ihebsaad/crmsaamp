<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RendezVous extends Model
{
    use HasFactory;

    protected $table = "CRM_RendezVous";

    protected $guarded = [];

    public $timestamps = false;

    public static function updateWithSequentialIds()
    {
        $count = RendezVous::count();
        for ($i = 1; $i <= $count; $i++) {
            RendezVous::where('id', null)->limit(1)->update(['id' => $i]);
        }
    }

}
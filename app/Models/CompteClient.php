<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompteClient extends Model
{
    use HasFactory;

    protected $table = "CRM_CompteCLient";

    protected $guarded = [];

    public $timestamps = false;

    public static function updateWithSequentialIds()
    {
        $count = CompteClient::count();
        for ($i = 1; $i <= $count; $i++) {
            CompteClient::where('id', null)->limit(1)->update(['id' => $i]);
        }
    }
}
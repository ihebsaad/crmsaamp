<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RetourClient extends Model
{
    use HasFactory;

    protected $table = "CRM_RetourClient";

    protected $guarded = [];

    public $timestamps = false;

    public static function updateWithSequentialIds()
    {
        $count = RetourClient::count();
        for ($i = 1; $i <= $count; $i++) {
            RetourClient::where('id', null)->limit(1)->update(['id' => $i]);
        }
    }

}
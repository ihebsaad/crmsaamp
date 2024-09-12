<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;

    protected $table = "contact";

    protected $guarded = [];

    public $timestamps = false;


    public static function updateWithSequentialIds()
    {
        $count = Contact::count();
        for ($i = 1; $i <= $count; $i++) {
            Contact::where('id', null)->limit(1)->update(['id' => $i]);
        }
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class Tache extends Model
{
    use HasFactory;

    protected $table = "CRM_Tache";

    protected $guarded = [];

    public $timestamps = false;

    public static function updateWithSequentialIds()
    {
        $count = Tache::count();
        $start = Tache::where('id','<>',null)->count();
        $j=$start+1;
        for ($i = $j; $i <= $count; $i++) {
            Tache::where('id', null)->limit(1)->update(['id' => $i]);
        }
    }

    public static function init()
    {
        $taches = Tache::where('id','>',118755)->update(['id' =>null]);
    }


    public static function correctDuplications()
{

    $tasks = DB::table('CRM_Tache')
    ->where('id','>',118755)
    ->select('id')->get();

    // Utiliser un tableau pour suivre les IDs existants.
    $existingIds = [];

    foreach ($tasks as $task) {
        if (in_array($task->id, $existingIds)) {
            // Générer un nouvel ID unique.
            $newId = DB::table('CRM_Tache')->max('id') + 1;
            DB::table('CRM_Tache')
                ->where('id', $task->id)
                ->update(['id' => $newId]);
            $existingIds[] = $newId;
        } else {
            $existingIds[] = $task->id;
        }
    }

}

}
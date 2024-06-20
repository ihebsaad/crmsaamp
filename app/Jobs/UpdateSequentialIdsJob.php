<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Tache;

class UpdateSequentialIdsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        /*
        $count = Tache::count();
        for ($i = 1; $i <= $count; $i++) {
            Tache::whereNull('id')->limit(1)->update(['id' => $i]);
        }
*/
        $count = Tache::count();
        $start = Tache::where('id','<>',null)->count();
        $j=$start+1;
        for ($i = $j; $i <= $count; $i++) {
            Tache::where('id', null)->limit(1)->update(['id' => $i]);
        }
    }
}
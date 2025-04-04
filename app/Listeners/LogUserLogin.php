<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\DB;

class LogUserLogin
{
    public function handle(Login $event)
    {
        DB::table('user_logins')->insert([
            'user_id' => $event->user->id,
            'app'=> 2, //mysaamp
            'login_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}

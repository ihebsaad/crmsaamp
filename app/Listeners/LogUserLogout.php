<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Auth\Events\Logout;
use Illuminate\Support\Facades\DB;

class LogUserLogout
{

    public function handle(Logout $event)
    {
        DB::table('user_logins')
            ->where('user_id', $event->user->id)
            ->whereNull('logout_at')
            ->where('app',2)
            ->latest('login_at')
            ->limit(1)
            ->update([
                'logout_at' => now(),
                'updated_at' => now(),
            ]);
    }

}

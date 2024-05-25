<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\TradingController;

class trading extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:trading';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'update trading every minute';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        TradingController::listetrading();
        $this->info('Successfully updated.');

    }
}

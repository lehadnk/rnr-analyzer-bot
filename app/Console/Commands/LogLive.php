<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class LogLive extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'log:live {logId} {raidDate} {sleepTime=15}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
     * @return mixed
     */
    public function handle()
    {
        $logId = $this->argument('logId');
        $raidDate = $this->argument('raidDate');
        $sleepTime = $this->argument('sleepTime');

        while(1 == 1) {
            $this->info('Trying to update the log...');

            $this->call('log:cache', [
                'id' => $logId,
                'raidDate' => $raidDate,
            ]);

            sleep($sleepTime);
        }
    }
}

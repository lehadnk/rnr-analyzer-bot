<?php

namespace App\Console\Commands;

use App\Fight;
use App\Fights;
use Illuminate\Console\Command;

class LogCache extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'log:cache {id} {raidDate}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    private $logId, $raidDate;

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
        $this->logId = $this->argument('id');
        $this->raidDate = $this->argument('raidDate');

        //$baseUrl = "https://www.warcraftlogs.com/reports/$logId#type=deaths&boss=-2&difficulty=0";

        $this->getFights();
    }

    private function getFights() {
        $url = "https://www.warcraftlogs.com/reports/fights_and_participants/{$this->logId}/0";

        $json = file_get_contents($url);
        $data = json_decode($json);
        if ($data === null) {
            $this->error("Can't access this log! Is it open to public access?");
        }

        foreach ($data->fights as $fight) {
            if ($fight->boss == 0) {
                continue;
            }

            $model = new Fight();
            $model->log_id = $this->logId;
            $model->fight_id = $fight->id;
            $model->raid_date = $this->raidDate;
            $model->start_time = $fight->start_time;
            $model->fight_length = $fight->end_time - $fight->start_time;
            $model->boss_id = $fight->boss;
            $model->difficulty_id = $fight->difficulty;
            $model->is_kill = $fight->kill;
            $model->percentage = $fight->bossPercentage / 100;
            $model->boss_name = $fight->name;

            try {
                $model->save();
            } catch (\Exception $e) {
                $this->warn("Warning: can't save fight with log_id {$this->logId} and id {$fight->id}: {$e->getMessage()} (Probably already existing?)");
            }

        }
    }
}

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
    protected $signature = 'log:cache {id} {raidDate} {--reupload=0}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    private $logId, $raidDate, $reUpload;

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
        $this->reUpload = (boolean) $this->option('reupload');

        if ($this->reUpload) {
            $this->info("Re-upload mode: removing old log data first...");
            $this->deleteFights();
        }

        $this->storeFights();
    }

    private function deleteFights() {
        $fights = $this->getStoredFights();

        if ($fights->count() > 0) {
            $this->info("{$fights->count()} old attempts found, removing...");
            foreach ($fights as $fight) {
                $fight->remove();
            }
        }
    }

    private function getStoredFights() {
        return Fight
            ::where('log_id', '=', $this->logId)
            ->where('raid_date', '=', $this->raidDate)
            ->get();
    }

    private function storeFights() {
        $fights = $this->getStoredFights();
        $lastFightId = $fights->max('fight_id');

        $url = "https://www.warcraftlogs.com/reports/fights_and_participants/{$this->logId}/0";

        $json = file_get_contents($url);
        $data = json_decode($json);
        if ($data === null) {
            $this->error("Can't access this log! Is it open to public access?");
        }

        foreach ($data->fights as $fight) {
            if ($fight->id <= $lastFightId) {
                continue;
            }

            if ($fight->boss == 0) {
                continue;
            }

            $this->info("New attempt discovered! Parsing...");

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

            $this->call('fight:cache', [
                'fight_id' => $model->id
            ]);
        }


    }
}

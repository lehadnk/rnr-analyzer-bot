<?php

namespace App\Console\Commands;

use App\Boss;
use App\BossMetric;
use App\Fight;
use App\FightUnits;
use App\Helpers\DB;
use Illuminate\Console\Command;

class FightCache extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fight:cache {fight_id}';

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
        $fightId = $this->argument('fight_id');

        $fight = Fight::find($fightId);
        if (!$fight) {
            $this->error('Error: no such fight exists in our DB. Might consider importing it first?');
            return;
        }

        $boss = Boss::where('in_game_id', '=', $fight->boss_id)->get()->first();
        if (!$boss) {
            $this->error('Error: no such boss exists in our DB. Might consider adding it first?');
        }

        $metrics = BossMetric::getByBossId($boss->id);

        foreach ($metrics as $metric) {
            $metric->setFight($fight);
            $metric->run();
        }

        $url = "https://www.warcraftlogs.com/reports/graph/damage-taken/{$fight->log_id}/{$fight->fight_id}/{$fight->start_time}/{$fight->getEndTime()}/source/0/0/0/0/0/0/-1.0.-1/4/Any/Any/2/0";
        $json = file_get_contents($url);
        $data = json_decode($json);
        $this->saveUnits($data->series, $fight);
    }

    protected function saveUnits($series, Fight $fight) {
        foreach ($series as $unit) {
            if (!is_numeric($unit->id)) {
                continue;
            }

            $model = new FightUnits();
            $model->unit_id = $unit->id;
            $model->fight_id = $fight->id;
            $model->name = $unit->name;
            $model->save();
        }
    }
}

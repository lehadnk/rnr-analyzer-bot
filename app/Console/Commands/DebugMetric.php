<?php

namespace App\Console\Commands;

use App\Boss;
use App\BossMetric;
use App\Fight;
use Illuminate\Console\Command;

class DebugMetric extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'debug:metric {fight_id} {metric_id}';

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
        $metricId = $this->argument('metric_id');
        //$strategyName = 'App\\'.$this->argument('metric_name');

        $metric = BossMetric::find($metricId);
        if ($metric === null) {
            $this->error("Error: no metric with id: $metricId found!");
            return;
        }

        $fight = Fight::find($fightId);

        if ($fight === null) {
            $this->error("Error: no fight with id: $fightId found!");
            return;
        }

        $strategy = $metric->getStrategy();
        $strategy->setFight($fight);
        $strategy->run();
    }
}

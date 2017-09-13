<?php

namespace App\Console\Commands;

use App\Ability;
use Illuminate\Console\Command;

class AbilityAdd extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ability:add {in_game_ability_id} {boss_id} {name}';

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
        $inGameAbilityId = $this->argument('in_game_ability_id');
        $bossId = $this->argument('boss_id');
        $name = $this->argument('name');

        $model = new Ability();
        $model->in_game_ability_id = $inGameAbilityId;
        $model->boss_id = $bossId;
        $model->name = $name;
        $model->save();
    }
}

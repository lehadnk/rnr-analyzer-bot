<?php

namespace App\Console\Commands;

use App\Boss;
use Illuminate\Console\Command;
use Mockery\Exception;

class BossAdd extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'boss:add {name} {id}';

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
        $name = $this->argument('name');
        $id = $this->argument('id');

        $boss = new Boss();
        $boss->name = $name;
        $boss->in_game_id = $id;

        try {
            $boss->save();
            $this->info("Boss $name added to DB!");
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
    }
}

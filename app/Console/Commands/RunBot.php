<?php

namespace App\Console\Commands;

use BotMan\BotMan\BotMan;
use BotMan\BotMan\BotManFactory;
use BotMan\BotMan\Cache\ArrayCache;
use BotMan\BotMan\Drivers\DriverManager;
use BotMan\Drivers\Discord\DiscordDriver;
use Illuminate\Console\Command;
use React\EventLoop\Factory;
use BotMan\BotMan\Middleware\ApiAi;

class RunBot extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'run:bot';

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
        $apiAi = ApiAi::create('22b84338101e4215bf593bb1353af81c')->listenForAction();

        $loop = Factory::create();
        DriverManager::loadDriver(DiscordDriver::class);
        $botman = BotManFactory::createForDiscord(config('botman', [
            'discord' => [
                'token' => 'MzU2ODU4MjM1MjA4MjA0Mjg4.DJhdsQ.1xjg_5UQYLL4oOp9LOuvS4-gtZE',
            ]
        ]), $loop, new ArrayCache());

        $botman->middleware->received($apiAi);

        $botman->hears('', function(BotMan $bot) {
            if ($bot->getMessage()->getSender() == '356858235208204288') {
                return;
            }

            $extras = $bot->getMessage()->getExtras();
            $apiReply = $extras['apiReply'] ?? null;
            $apiAction = $extras['apiAction'] ?? null;
            $apiIntent = $extras['apiIntent'] ?? null;
            $apiParameters = $extras['apiParameters'] ?? [];

            $this->info($apiIntent);
            $this->info(print_r($apiParameters, true));

            $bot->reply('Hello yourself, idiot.');
        });

        $botman->fallback(function(BotMan $bot) {
            if ($bot->getMessage()->getSender() == '356858235208204288') {
                return;
            }

            $bot->reply("test");
        });

        $loop->run();
    }
}

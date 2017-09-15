<?php

namespace App\Console\Commands;

use App\Conversations\BestAttempt;
use App\Conversations\FailsOnAbility;
use App\Conversations\HowWasYourRaid;
use App\Conversations\LastLog;
use App\Conversations\Math;
use App\Conversations\PlayerFailDetail;
use App\Conversations\UpdateLog;
use BotMan\BotMan\BotMan;
use BotMan\BotMan\BotManFactory;
use BotMan\BotMan\Cache\ArrayCache;
use BotMan\BotMan\Drivers\DriverManager;
use BotMan\Drivers\Discord\DiscordDriver;
use Illuminate\Console\Command;
use Mockery\Exception;
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
        try {
            $apiAi = \App\Helpers\ApiAi::create('22b84338101e4215bf593bb1353af81c')->listenForAction();

            $loop = Factory::create();
            DriverManager::loadDriver(DiscordDriver::class);
            $botman = BotManFactory::createForDiscord(config('botman', [
                'discord' => [
                    'token' => env('DISCORD_BOT_TOKEN'),
                ]
            ]), $loop, new ArrayCache());

            $botman->middleware->received($apiAi);

            $botman->hears('', function (BotMan $bot) {
                $botUserId = env('DISCORD_BOT_USER_ID');
                if ($bot->getMessage()->getSender() == $botUserId) {
                    return;
                }
                if (!stristr($bot->getMessage()->getText(), "<@$botUserId>")) {
                    return;
                }

                $extras = $bot->getMessage()->getExtras();
                $apiReply = $extras['apiReply'] ?? null;
                $apiAction = $extras['apiAction'] ?? null;
                $apiIntent = $extras['apiIntent'] ?? null;
                $apiParameters = $extras['apiParameters'] ?? [];

                if ($apiReply) {
                    $bot->reply($apiReply);
                } else {
                    $this->info($apiIntent);
                    $this->info(print_r($apiParameters, true));

                    if ($apiIntent == 'best-attempt') {
                        $conversation = new BestAttempt();
                    }
                    if ($apiIntent == 'math') {
                        $conversation = new Math();
                    }
                    if ($apiIntent == 'last-log') {
                        $conversation = new LastLog();
                    }
                    if ($apiIntent == 'how-was-your-raid') {
                        $conversation = new HowWasYourRaid();
                    }
                    if ($apiIntent == 'fails-on-ability') {
                        $conversation = new FailsOnAbility();
                    }
                    if ($apiIntent == 'player-fail-detail') {
                        $conversation = new PlayerFailDetail();
                    }
                    if ($apiIntent == 'update-log') {
                        $conversation = new UpdateLog();
                    }

                    $conversation->setApiParameters($apiParameters);
                    try {
                        $bot->startConversation($conversation);
                    } catch (\Exception $e) {
                        $bot->reply("Хуйню какую-то понаписали: {$e->getMessage()}");
                    }

                }
            });

            $loop->run();
        } catch (Exception $e) {
            var_dump($e->getMessage());
        }
    }
}

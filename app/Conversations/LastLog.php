<?php
/**
 * BestAttempt.php
 * Creator: lehadnk
 * Date: 12/09/2017
 */

namespace App\Conversations;



use App\Boss;
use App\Helpers\DB;
use BotMan\BotMan\Messages\Incoming\Answer;

class LastLog extends ConversationBase
{
    public function giveAnswer()
    {
        try {
            $result = \DB::select("
                SELECT log_id
                FROM fights f
                ORDER BY raid_date DESC
                LIMIT 1
            ");

            $id = DB::getValue($result);
        } catch (\Exception $e) {
            return 'Чота вы там наговнокодили: '.$e->getMessage();
        }


        return "https://www.warcraftlogs.com/reports/$id#type=deaths&boss=-2&difficulty=0";
    }
}
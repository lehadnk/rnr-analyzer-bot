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

class HowWasYourRaid extends ConversationBase
{
    public function giveAnswer()
    {
        try {
            $result = \DB::select("
                SELECT 
                    count(CASE WHEN is_kill = TRUE THEN id END) as cnt,
                    min(CASE WHEN q1.boss_id IS NULL THEN percentage END) as pcnt,
                    min(CASE WHEN q1.boss_id IS NULL THEN boss_name END) as boss
                FROM fights f
                LEFT JOIN (
                    SELECT DISTINCT boss_id
                    FROM fights
                    WHERE is_kill = TRUE AND difficulty_id = 5 AND raid_date = :raidDate
                ) q1 ON q1.boss_id = f.boss_id
                WHERE difficulty_id = 5 AND raid_date = :raidDate
            ", [
                ':raidDate' => $this->getDataFieldValue('date'),
            ]);

            $data = DB::getRow($result);

            return ($data->pcnt === null) ? "Отфармили $data->cnt" : "Отфармили $data->cnt, $data->boss оставили $data->pcnt%";
        } catch (\Exception $e) {
            return 'Чота вы там наговнокодили: '.$e->getMessage();
        }
    }
}
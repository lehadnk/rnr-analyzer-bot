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

class BestAttempt extends ConversationBase
{
    public function giveAnswer()
    {
        $bossName = $this->getDataFieldValue('BossName');
        $boss = Boss::getByName($bossName);
        if ($boss === null) {
            return "Мне не давали логов с $bossName";
        }

        try {
            $result = \DB::select("
                SELECT min(percentage)
                FROM fights f
                WHERE raid_date = :raidDate AND boss_id = :bossId
            ", [
                ':raidDate' => $this->getDataFieldValue('date'),
                ':bossId' => $boss->in_game_id,
            ]);
        } catch (\Exception $e) {
            return 'Чота вы там наговнокодили: '.$e->getMessage();
        }

        $value = DB::getValue($result);
        if ($value === null) {
            return 'Не пулили';
        }

        return ($value == 0) ? 'Убили': $value.'%';
    }
}
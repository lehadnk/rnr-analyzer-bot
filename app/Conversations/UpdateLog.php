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
use Illuminate\Support\Facades\Artisan;

class UpdateLog extends ConversationBase
{
    public function giveAnswer()
    {
        $raidDate = $this->getDataFieldValue('date');

        try {
            $result = \DB::select("
                SELECT log_id
                FROM fights f
                WHERE raid_date = :date
                LIMIT 1
            ", [
                ':date' => $raidDate,
            ]);

            $logId = DB::getValue($result);

            if (!$logId) {
                return 'Нет лога за этот день';
            }

            Artisan::call('log:cache', ['id' => $logId, 'raidDate' => $raidDate]);
        } catch (\Exception $e) {
            return 'Чота вы там наговнокодили: '.$e->getMessage();
        }

        return "Обновлено, можно смотреть";
    }
}
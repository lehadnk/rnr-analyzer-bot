<?php
/**
 * BestAttempt.php
 * Creator: lehadnk
 * Date: 12/09/2017
 */

namespace App\Conversations;



use App\Ability;
use App\Boss;
use App\BossMetric;

class FailsOnAbility extends ConversationBase
{
    public function giveAnswer()
    {
        $bossName = $this->getDataFieldValue('BossName');
        $boss = Boss::getByName($bossName);
        if ($boss === null) {
            return "Мне не давали логов с $bossName";
        }

        $abilityName = $this->getDataFieldValue('AbilityName');
        $ability = Ability::getByName($abilityName);
        if ($ability === null) {
            return "Не знаю что такое $abilityName";
        }

        $metrics = BossMetric::getByAbilityId($ability->id);
        foreach ($metrics as $metric) {
            $this->say($metric->getReport($this->getDataFieldValue('date')));
        }
    }
}
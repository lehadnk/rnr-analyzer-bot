<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BossMetric extends Model
{
    /**
     * @param $bossId
     * @return AbstractStrategy[]
     */
    public static function factory($metrics) {
        $strategies = [];
        foreach ($metrics as $metric) {
            $strategies[] = $metric->getStrategy();
        }

        return $strategies;
    }

    public static function getByAbilityId($abilityId) {
        $metrics = self::where('ability_id', '=', $abilityId)->get();
        return self::factory($metrics);
    }

    public static function getByBossId($bossId) {
        $metrics = self::where('boss_id', '=', $bossId)->get();
        return self::factory($metrics);
    }

    public function getStrategy() {
        $strategyClass = "App\\{$this->strategy}";
        $strategy = new $strategyClass($this);

        return $strategy;
    }
}

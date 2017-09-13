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
            $strategyClass = "App\\{$metric->strategy}";
            $strategy = new $strategyClass($metric);
            $strategies[] = $strategy;
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
}

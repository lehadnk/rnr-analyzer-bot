<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BossMetric extends Model
{
    public static function factory($bossId) {
        $metrics = self::where('boss_id', '=', $bossId)->get();

        $strategies = [];
        foreach ($metrics as $metric) {
            $strategyClass = "App\\{$metric->strategy}";
            $strategy = new $strategyClass($metric->payload, $metric->id);
            $strategies[] = $strategy;
        }

        return $strategies;
    }
}

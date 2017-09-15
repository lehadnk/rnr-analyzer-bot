<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Fight extends Model
{
    public function getEndTime() {
        return $this->start_time + $this->fight_length;
    }

    public function remove() {
        FightUnits::where('fight_id', '=', $this->id)->delete();
        BossMetricData::where('fight_id', '=', $this->id)->delete();
        $this->delete();
    }
}

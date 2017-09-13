<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Fight extends Model
{
    public function getEndTime() {
        return $this->start_time + $this->fight_length;
    }
}

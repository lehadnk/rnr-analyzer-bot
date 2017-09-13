<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ability extends Model
{
    public static function getByName($name) {
        return self::where('name', '=', $name)->get()->first();
    }
}

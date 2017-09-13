<?php
/**
 * DB.php
 * Creator: lehadnk
 * Date: 12/09/2017
 */

namespace App\Helpers;


class DB
{
    public static function getValue($dbResult) {
        if (count($dbResult) < 1) {
            return null;
        }

        $row = reset($dbResult);
        if (count($row) < 1) {
            return null;
        }

        $value = reset($row);

        return $value;
    }

    public static function getRow($dbResult) {
        if (count($dbResult) < 1) {
            return null;
        }

        $row = reset($dbResult);
        return $row;
    }
}
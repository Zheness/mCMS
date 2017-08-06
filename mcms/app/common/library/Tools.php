<?php

namespace Mcms\Library;

use Phalcon\Mvc\User\Plugin;

class Tools extends Plugin
{
    /**
     * Converts an MySQL formatted date to French date
     * @param string $mysqlDate
     * @param bool $includeHours
     * @return string
     */
    public static function mysqlDateToFr($mysqlDate, $includeHours = true)
    {
        if ($mysqlDate == null) {
            return "-";
        }
        $date = date("d/m/Y", strtotime($mysqlDate));
        if ($date == false) {
            return "-";
        }
        if ($includeHours) {
            $date .= " à " . date("H\\hi", strtotime($mysqlDate));
        }
        return $date;
    }

    /**
     * Returns the current date in MySQL format DateTime
     * @return string
     */
    public static function now()
    {
        return date("Y-m-d H:i:s");
    }
}

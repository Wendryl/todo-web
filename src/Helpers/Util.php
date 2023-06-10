<?php

namespace ProgWeb\TodoWeb\Helpers;

use DateTime;

class Util {
    public static function validateDate(string $date, $format = 'Y-m-d'): bool {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) === $date;
    }
}

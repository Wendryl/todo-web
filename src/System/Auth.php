<?php

namespace ProgWeb\TodoWeb\System;

class Auth {

    public static function getAuthKey(): string {
        return $_ENV['AUTH_KEY'];
    }
}

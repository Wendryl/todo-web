<?php

require 'vendor/autoload.php';

use Dotenv\Dotenv;
use ProgWeb\TodoWeb\System\DbConnector;

$env = Dotenv::createImmutable(__DIR__);
$env->load();

$dbConnection = (new DbConnector())->getConnection();

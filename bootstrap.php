<?php

require 'vendor/autoload.php';

use ProgWeb\TodoWeb\System\DbConnector;

$dbConnection = (new DbConnector())->getConnection();

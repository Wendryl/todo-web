<?php

use ProgWeb\TodoWeb\Controllers\UserController;

require '../bootstrap.php';

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = explode('/', $uri);

$requestMethod = $_SERVER["REQUEST_METHOD"];

if($uri[1] == 'users') {
    $userController = new UserController($dbConnection, $requestMethod, $userId);
    $userController->processRequest();
    return;
}

if($uri[1] == '') {
    header("HTTP/1.1 200 Not Found");
    echo json_encode(['message' => 'A API Gestor Web está funcionando corretamente!'], JSON_UNESCAPED_UNICODE);
    exit();
}

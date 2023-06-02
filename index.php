<?php

use ProgWeb\TodoWeb\Controllers\AuthController;
use ProgWeb\TodoWeb\Controllers\UserController;

require './bootstrap.php';

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = explode('/api', $uri);

$requestMethod = $_SERVER["REQUEST_METHOD"];

if($uri[1] == '/users') {
    $userController = new UserController($dbConnection, $requestMethod);
    $userController->processRequest();
    return;
}

if(strpos($uri[1], 'auth') != false) {
    $routes = explode('/', $uri[1]);
    $authController = new AuthController($dbConnection, $requestMethod);
    $authController->processRequest($routes[2]);
    return;
}

if($uri[1] == '' ) {
    http_response_code(200);
    echo json_encode(['message' => 'A API Gestor Web está funcionando corretamente!'], JSON_UNESCAPED_UNICODE);
    return;
}

http_response_code(404);
exit();

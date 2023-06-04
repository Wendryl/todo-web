<?php
namespace ProgWeb\TodoWeb\Controllers;

use ProgWeb\TodoWeb\Gateways\UserGateway;

abstract class BaseController {
    public function __construct(private $db, private $requestMethod, private $userGateway) {
        $this->db = $db;
        $this->requestMethod = $requestMethod;

        $this->userGateway = new UserGateway($db);
    }

    protected function notFoundResponse()
    {
        http_response_code(404);
        $response['body'] = null;
        return $response;
    }

    protected function badRequestResponse()
    {
        http_response_code(400);
        $response['body'] = null;
        return $response;
    }

    protected function verifyUserAuth()  {
        if (!isset($_COOKIE['auth_token'])) {
            http_response_code(401);
            die();
        }

        $auth_cookie = $_COOKIE['auth_token'];

        if(is_null($auth_cookie) || !AuthController::isTokenValid($auth_cookie)) {
            http_response_code(401);
            die();
        }
    }
}


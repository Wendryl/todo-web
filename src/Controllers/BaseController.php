<?php
namespace ProgWeb\TodoWeb\Controllers;

use ProgWeb\TodoWeb\Gateways\UserGateway;

abstract class BaseController {
    public function __construct($db, $requestMethod) {
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
}


<?php
namespace ProgWeb\TodoWeb\Controllers;

use ProgWeb\TodoWeb\Gateways\UserGateway;

class UserController extends BaseController {

    private $db;
    private $requestMethod;
    private $userId;

    private $userGateway;

    public function __construct($db, $requestMethod, $userId) {
        $this->db = $db;
        $this->requestMethod = $requestMethod;
        $this->userId = $userId;

        $this->userGateway = new UserGateway($db);
    }

    public function processRequest() {
        switch ($this->requestMethod) {
            case 'POST':
                $response = $this->createUserFromRequest();
                break;
            case 'GET':
                $response = $this->listUsers();
                break;
            default:
                $response = $this->notFoundResponse();
            break;
        }
        header($response['status_code_header']);
        if ($response['body']) {
            echo $response['body'];
        }
    }

    private function createUserFromRequest() {
        $input = (array) json_decode(file_get_contents('php://input'), TRUE);

        if (! $this->validateUser($input)) {
            return $this->badRequestResponse();
        }

        $this->userGateway->insert($input);
        $response['status_code_header'] = 'HTTP/1.1 201 Created';
        $response['body'] = null;
        return $response;
    }

    private function listUsers() {
        $params = $_GET;
        $data = $this->userGateway->get($params);
        $response['status_code_header'] = 'HTTP/1.1 200 Ok';
        $response['body'] = $data;
        return $response;
    }

    private function validateUser($input)
    {
        if (!isset($input['firstname'])) {
            return false;
        }
        if (!isset($input['lastname'])) {
            return false;
        }
        if (!isset($input['login'])) {
            return false;
        }
        if (!isset($input['password'])) {
            return false;
        }
        return true;
    }
}

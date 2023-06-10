<?php
namespace ProgWeb\TodoWeb\Controllers;

use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use ProgWeb\TodoWeb\Gateways\ActivityGateway;
use ProgWeb\TodoWeb\Helpers\Util;
use ProgWeb\TodoWeb\System\Auth;

class ActivityController extends BaseController {

    private $activityGateway;
    private $userId;

    public function __construct(private $db, private $requestMethod) {
        $this->activityGateway = new ActivityGateway($db);
    }

    public function processRequest($id = null) {

        try {
            $this->userId = JWT::decode($_COOKIE['auth_token'], new Key(Auth::getAuthKey(), 'HS256'))->user_id;
        } catch (Exception $e) {
            return $this->unauthorizedResponse();
        }

        switch ($this->requestMethod) {
            case 'GET':
                $this->verifyUserAuth();
                $response = $this->listActivities();
                break;
            case 'POST':
                $this->verifyUserAuth();
                $response = $this->createActivity();
                break;
            case 'PUT':
                $this->verifyUserAuth();
                $response = $this->updateActivity($id);
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

    private function listActivities() {
        $data = $this->activityGateway->list($this->userId);
        $response['status_code_header'] = 'HTTP/1.1 200 Ok';
        $response['body'] = $data;
        return $response;
    }

    private function activityExists($activityId): bool {
        $data = $this->activityGateway->get($activityId);
        return count($data) > 0;
    }

    private function createActivity() {
        $input = (array) json_decode(file_get_contents('php://input'), TRUE);

        if (! $this->validateActivity($input)) {
            return $this->badRequestResponse();
        }

        $this->activityGateway->insert($this->userId, $input);
        $response['status_code_header'] = 'HTTP/1.1 201 Created';
        $response['body'] = null;
        return $response;
    }

    private function updateActivity($id) {
        $input = (array) json_decode(file_get_contents('php://input'), TRUE);

        if (!$this->validateActivity($input)) {
            return $this->badRequestResponse();
        }

        if (!$this->activityExists($id)) {
            return $this->notFoundResponse();
        }

        $result = $this->activityGateway->update($id, $input);

        $response['status_code_header'] = 'HTTP/1.1 204 No Content';
        $response['body'] = null;
        return $response;
    }

    private function validateActivity($input)
    {
        if (!isset($input['title'])) {
            return false;
        }
        if (!isset($input['description'])) {
            return false;
        }
        if (!isset($input['due_date'])) {
            return false;
        }
        if (!Util::validateDate($input['due_date'])) {
            return false;
        }
        return true;
    }
}

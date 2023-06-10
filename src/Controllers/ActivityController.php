<?php
namespace ProgWeb\TodoWeb\Controllers;

use ProgWeb\TodoWeb\Gateways\ActivityGateway;
use ProgWeb\TodoWeb\Helpers\Util;

class ActivityController extends BaseController {

    private $activityGateway;

    public function __construct(private $db, private $requestMethod) {
        $this->activityGateway = new ActivityGateway($db);
    }

    public function processRequest() {
        switch ($this->requestMethod) {
            case 'GET':
                $this->verifyUserAuth();
                $response = $this->listActivities();
                break;
            case 'POST':
                $this->verifyUserAuth();
                $response = $this->createActivity();
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
        $data = $this->activityGateway->get();
        $response['status_code_header'] = 'HTTP/1.1 200 Ok';
        $response['body'] = $data;
        return $response;
    }

    private function createActivity() {
        $input = (array) json_decode(file_get_contents('php://input'), TRUE);

        if (! $this->validateActivity($input)) {
            return $this->badRequestResponse();
        }

        $this->activityGateway->insert($input);
        $response['status_code_header'] = 'HTTP/1.1 201 Created';
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

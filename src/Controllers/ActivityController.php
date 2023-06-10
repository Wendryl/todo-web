<?php
namespace ProgWeb\TodoWeb\Controllers;

use ProgWeb\TodoWeb\Gateways\ActivityGateway;

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
        $params = $_GET;
        $data = $this->activityGateway->get($params);
        $response['status_code_header'] = 'HTTP/1.1 200 Ok';
        $response['body'] = $data;
        return $response;
    }
}

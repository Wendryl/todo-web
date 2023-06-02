<?php
namespace ProgWeb\TodoWeb\Controllers;

use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use ProgWeb\TodoWeb\Gateways\UserGateway;
use ProgWeb\TodoWeb\System\Auth;

class AuthController extends BaseController {

    private $userGateway;

    public function __construct(private $db, private $requestMethod) {
        $this->userGateway = new UserGateway($db);
    }

    public function processRequest($route) {
        switch ($route) {
            case 'login':
                $response = $this->authenticateUser();
            break;
            case 'logout':
                $response = $this->unsetToken();
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

    public function authenticateUser() {
        $input = (array) json_decode(file_get_contents('php://input'), TRUE);

        if (!$this->userGateway->isCredentialsValid($input)) {
            $response['status_code_header'] = 'HTTP/1.1 401 Unauthorized';
            $response['body'] = null;
            return $response;
        }

        $response['status_code_header'] = 'HTTP/1.1 200 Ok';
        $response['body'] = null;
        setcookie('auth_token', $this->generateToken($input), httponly:true, path:"/");
        return $response;
    }

    public function generateToken($credentials): string {
        $key = Auth::getAuthKey();
        $hours = 3600;
        $payload = [
            'sub' => 'todo-web-app',
            'name' => $credentials['login'],
            'iat' => time(),
            'exp' => time() + 1 * $hours
        ];

        $jwt = JWT::encode($payload, $key, 'HS256');

        return $jwt;
    }

    public function unsetToken() {

        setcookie('auth_token', "", httponly:true, path:"/");

        $response['status_code_header'] = 'HTTP/1.1 200 Ok';
        $response['body'] = null;

        return $response;
    }

    public static function isTokenValid($token): bool {
        try {
            JWT::decode($token, new Key(Auth::getAuthKey(), 'HS256'));
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
}

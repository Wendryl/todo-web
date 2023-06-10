<?php
namespace ProgWeb\TodoWeb\Gateways;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use ProgWeb\TodoWeb\Controllers\AuthController;
use ProgWeb\TodoWeb\System\Auth;

class ActivityGateway {

    private $db = null;
    private $authController;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function get() {

        $statement = "
        SELECT *
        FROM activities WHERE user_id = :user_id
        ";

        $user_id = JWT::decode($_COOKIE['auth_token'], new Key(Auth::getAuthKey(), 'HS256'))->user_id;

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute([
                'user_id' => $user_id // id do mateus
            ]);
            return json_encode($statement->fetchAll(\PDO::FETCH_ASSOC));
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }
}

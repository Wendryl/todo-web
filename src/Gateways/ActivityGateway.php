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
            $parsed_data = array_map(array($this, 'mapActivities'), $statement->fetchAll(\PDO::FETCH_ASSOC));
            return json_encode($parsed_data);
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }

    private function mapActivities($activity) {
        $activity['is_complete'] = $activity['is_complete'] ? true : false;
        return $activity;
    }
}

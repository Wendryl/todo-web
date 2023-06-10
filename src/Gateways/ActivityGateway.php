<?php
namespace ProgWeb\TodoWeb\Gateways;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use ProgWeb\TodoWeb\System\Auth;

class ActivityGateway {

    private $db = null;
    private $user_id;
    private $authController;

    public function __construct($db)
    {
        $this->db = $db;
        $this->user_id = $user_id = JWT::decode($_COOKIE['auth_token'], new Key(Auth::getAuthKey(), 'HS256'))->user_id;
    }

    public function get() {

        $statement = "
        SELECT *
        FROM activities WHERE user_id = :user_id
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute([
                'user_id' => $this->user_id
            ]);
            $parsed_data = array_map(array($this, 'mapActivities'), $statement->fetchAll(\PDO::FETCH_ASSOC));
            return json_encode($parsed_data);
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function insert(Array $input)
    {
        $statement = "
        INSERT INTO activities
        (user_id, title, description, due_date)
        VALUES
        (:user_id, :title, :description, :due_date);
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array(
                'user_id' => $this->user_id,
                'title' => $input['title'],
                'description'  => $input['description'],
                'due_date' => $input['due_date'],
            ));
            return $statement->rowCount();
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }

    private function mapActivities($activity) {
        $activity['is_complete'] = $activity['is_complete'] ? true : false;
        return $activity;
    }
}

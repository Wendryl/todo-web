<?php
namespace ProgWeb\TodoWeb\Gateways;

class UserGateway {

    private $db = null;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function insert(Array $input)
    {
        $statement = "
        INSERT INTO users
        (firstname, lastname, birthdate, login, password)
        VALUES
        (:firstname, :lastname, :birthdate, :login, :password);
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array(
                'firstname' => $input['firstname'],
                'lastname'  => $input['lastname'],
                'birthdate' => $input['birthdate'],
                'login' => $input['login'],
                'password' => $input['password'],
            ));
            return $statement->rowCount();
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function get(Array $params) {

        $statement = "
        SELECT *
        FROM users
        ";

        if (isset($params['name'])) {
            $statement = $statement . "WHERE firstname like '%${params['name']}%'";
        }

        if (isset($params['pageSize'])) {
            $offset = $params['pageSize'] * ($params['page'] - 1);
            $statement = $statement . " LIMIT ${params['pageSize']}";
        } else {
            $params['pageSize'] = 10;
            $statement = $statement . " LIMIT 10";
        }

        if (isset($params['page'])) {
            $offset = $params['pageSize'] * ($params['page'] - 1);
            $statement = $statement . " OFFSET ${offset}";
        }

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute();
            return json_encode($statement->fetchAll(\PDO::FETCH_ASSOC));
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }
}

<?php
namespace ProgWeb\TodoWeb\Gateways;

class UserGateway {

    private $db = null;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function isValidCredentials(Array $credentials) {
        $statement = "
        SELECT login, password FROM users WHERE :login = login";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array(
                'login' => $credentials['login'])
            );

            if ($statement->rowCount() < 1) return false;

            $user = $statement->fetch(\PDO::FETCH_ASSOC);
            $dbPassword = $user['password'];

            return (password_verify($credentials['password'], $dbPassword));

        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
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
                'birthdate' => $input['birthdate'] ?? null,
                'login' => $input['login'],
                'password' => password_hash($input['password'], PASSWORD_DEFAULT),
            ));
            return $statement->rowCount();
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function get(Array $params) {

        $statement = "
        SELECT id, firstname, lastname, birthdate, login
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

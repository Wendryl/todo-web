<?php
namespace ProgWeb\TodoWeb\Gateways;

class UserGateway {

    private $db = null;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function isCredentialsValid(Array $credentials) {
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

    public function getUserByLogin($login) {

        $statement = "
        SELECT id, firstname, lastname, birthdate, login
        FROM users
        WHERE login = :login;
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute([
                'login' => $login,
            ]);
            return $statement->fetch(\PDO::FETCH_OBJ);
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }
}

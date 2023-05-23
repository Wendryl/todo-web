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
}

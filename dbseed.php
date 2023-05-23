<?php
require 'bootstrap.php';

$statement = <<<EOS
    CREATE TABLE IF NOT EXISTS users (
        id INT NOT NULL AUTO_INCREMENT,
        firstname VARCHAR(100) NOT NULL,
        lastname VARCHAR(100) NOT NULL,
        birthdate date DEFAULT NULL,
        login VARCHAR(100) NOT NULL,
        password VARCHAR(100) NOT NULL,
        PRIMARY KEY (id)
    );

    INSERT INTO users
        (id, firstname, lastname, login, password)
    VALUES
        (1, 'John', 'Doe', 'john.doe', '123456')
EOS;

try {
    $createTable = $dbConnection->exec($statement);
    echo "Successo!\n";
} catch (\PDOException $e) {
    echo "Muito provavelmente seu banco de dados já está configurado!";
    echo "Para mais detalhes verifique o arquivo de logs. (./logs.txt)";
    file_put_contents('logs.txt', "Warning DB Seed Setup Command: " . $e->getMessage());
    exit();
}
?>

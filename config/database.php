<?php

class Database {

        private $host = "localhost:3306";
        private $db = "blog_react_php";
        private $user = "root";
        private $password = "1234";
        private $charset = "utf8";

    public function __construct(){}

    public function connect() {
        try {
            // dato de conexion
            $connection = "mysql:host=" . $this->host . ";dbname=" . $this->db . ";charset=" . $this->charset;
            
            //opciones de conexion
            $options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ERRMODE_EXCEPTION];

            $pdo = new PDO($connection, $this->user, $this->password, $options);

            return $pdo;

        } catch (PDOException $e) {
            $e->getMessage();
        }
    }

}
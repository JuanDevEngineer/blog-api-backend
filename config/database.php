<?php

class Database {

        private $host = "us-cdbr-east-03.cleardb.com:3306";
        private $db = "heroku_63dae8056924b5f";
        private $user = "ba4e338b01b472";
        private $password = "36bb9295";
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
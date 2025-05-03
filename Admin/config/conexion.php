<?php
class Database {
    private $connection;

    public function __construct() {
        try {
            $this->connection = new PDO('mysql:host=localhost;dbname=macablue', 'root', '');
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Error de conexión: " . $e->getMessage());
        }
    }

    public function getConnection() {
        return $this->connection;
    }
}
?>

<?php
class Conexion {
    private $servername = "localhost";
    private $username = "root";
    private $password = "";
    private $dbname = "macablue";
    private $conn;
    private static $conexion;

    public function conectar() {
        $this->conn = new mysqli($this->servername, $this->username, $this->password, $this->dbname);
        if ($this->conn->connect_error) {
            die("Error en la conexión: " . $this->conn->connect_error);
        }
        return $this->conn;
    }

    public static function obtenerConexion() {
        if (!isset(self::$conexion)) {
            $conexion = new self();
            self::$conexion = $conexion->conectar();
        }
        return self::$conexion;
    }

    public static function cerrarConexion() {
        if (isset(self::$conexion)) {
            self::$conexion->close();
            self::$conexion = null; // Reinicia la conexión para evitar conflictos
        }
    }
}
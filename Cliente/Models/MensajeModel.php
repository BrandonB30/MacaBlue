<?php
require_once dirname(__DIR__) . '/config/conexion.php';
class MensajeModel {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function insertarMensaje($nombre, $email, $asunto, $mensaje, $fecha) {
        $sql = "INSERT INTO mensajes_contacto (nombre, email, asunto, mensaje, fecha) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql); // Asegúrate de que $this->conn sea una instancia válida de mysqli
        if ($stmt === false) {
            die("Error en la preparación de la consulta: " . $this->conn->error);
        }

        $stmt->bind_param("sssss", $nombre, $email, $asunto, $mensaje, $fecha);
        if (!$stmt->execute()) {
            die("Error al ejecutar la consulta: " . $stmt->error);
        }

        $stmt->close();
    }

    public function validarEmail($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }
}

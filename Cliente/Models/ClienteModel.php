<?php
require_once '../config/conexion.php';

class ClienteModel {
    public static function correoExiste($email) {
        $conn = Conexion::obtenerConexion();
        $sql = "SELECT * FROM clientes WHERE emailCliente = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $existe = $resultado->num_rows > 0;
        $stmt->close();
        return $existe;
    }

    public static function registrar($nombre, $apellido, $email, $contrasena) {
        $conn = Conexion::obtenerConexion();
        $sql = "INSERT INTO clientes (nombreCliente, apellidoCliente, emailCliente, passwordCliente) 
                VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $hashedPassword = password_hash($contrasena, PASSWORD_BCRYPT);
        $stmt->bind_param("ssss", $nombre, $apellido, $email, $hashedPassword);
        $resultado = $stmt->execute();
        $stmt->close();
        return $resultado;
    }
}

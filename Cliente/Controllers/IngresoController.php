<?php
session_start();
require_once dirname(__DIR__) . '/config/conexion.php'; // Ruta corregida para incluir la clase de conexión

class IngresoController {
    private $conn;

    public function __construct() {
        $conexion = new Conexion();
        $this->conn = $conexion->conectar();
    }

    public function autenticar($email, $contrasena) {
        $mensaje = "";
        $tipoMensaje = "";

        $sql = "SELECT * FROM clientes WHERE emailCliente = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($resultado->num_rows === 1) {
            $cliente = $resultado->fetch_assoc();
            if (password_verify($contrasena, $cliente['passwordCliente'])) {
                $_SESSION['cliente_id'] = $cliente['cliente_id'];
                $_SESSION['nombreUsuario'] = $cliente['nombreCliente'];
                $_SESSION['apellidoUsuario'] = $cliente['apellidoCliente'];

                // Agregar el mensaje de éxito
                $_SESSION['login_success'] = "Has iniciado sesión correctamente. ¡Bienvenido!";

                header("Location: ../view/productos.php");
                exit;
            } else {
                $mensaje = "Contraseña incorrecta";
                $tipoMensaje = "danger";
            }
        } else {
            $mensaje = "Cliente no encontrado";
            $tipoMensaje = "danger";
        }

        $_SESSION['mensaje'] = $mensaje;
        $_SESSION['tipoMensaje'] = $tipoMensaje;
        header("Location: ../view/ingreso.php");
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $contrasena = $_POST['contrasena'];

    $controller = new IngresoController();
    $controller->autenticar($email, $contrasena);
}

<?php
require_once '../models/ClienteModel.php';

class ClienteController {
    public static function registrarCliente($nombre, $apellido, $email, $contrasena) {
        // Verificar si el correo ya está registrado
        if (ClienteModel::correoExiste($email)) {
            return [
                'mensaje' => "El correo electrónico ya está registrado. Por favor intenta con otro.",
                'tipo' => "danger",
                'redireccionar' => false
            ];
        }

        // Registrar al cliente
        $registroExitoso = ClienteModel::registrar($nombre, $apellido, $email, $contrasena);

        if ($registroExitoso) {
            return [
                'mensaje' => "Registro exitoso. Redirigiendo a inicio de sesión...",
                'tipo' => "success",
                'redireccionar' => true
            ];
        } else {
            return [
                'mensaje' => "Error en el registro. Inténtalo nuevamente.",
                'tipo' => "danger",
                'redireccionar' => false
            ];
        }
    }
}

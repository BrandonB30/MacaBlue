<?php
require_once dirname(__DIR__) . '/models/MensajeModel.php';

class ContactoController {
    private $mensajeModel;

    public function __construct($mensajeModel) {
        $this->mensajeModel = $mensajeModel;
    }

    public function procesarFormulario() {
        $mensaje_enviado = false;
        $mensaje_error = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['enviar_mensaje'])) {
            $nombre = $_POST['nombre'] ?? '';
            $email = $_POST['email'] ?? '';
            $asunto = $_POST['asunto'] ?? '';
            $mensaje = $_POST['mensaje'] ?? '';
            $fecha = date('Y-m-d H:i:s');

            if (empty($nombre) || empty($email) || empty($asunto) || empty($mensaje)) {
                $mensaje_error = 'Todos los campos son obligatorios.';
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $mensaje_error = 'El correo electrónico no es válido.';
            } else {
                try {
                    $this->mensajeModel->insertarMensaje($nombre, $email, $asunto, $mensaje, $fecha);
                    $mensaje_enviado = true;
                } catch (Exception $e) {
                    $mensaje_error = 'Error al enviar el mensaje: ' . $e->getMessage();
                }
            }
        }

        return [
            'mensaje_enviado' => $mensaje_enviado,
            'mensaje_error' => $mensaje_error,
        ];
    }
}
?>

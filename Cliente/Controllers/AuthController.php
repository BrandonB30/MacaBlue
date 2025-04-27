<?php
class AuthController {
    public static function verificarSesion() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Deshabilitar la caché del navegador
        header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");

        if (!isset($_SESSION['cliente_id'])) {
            header("Location: /MacaBlue/cliente/view/ingreso.php");
            exit();
        }
    }
}

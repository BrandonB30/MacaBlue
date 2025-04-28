<?php
class AuthController {
    public static function verificarSesion() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");

        if (!isset($_SESSION['cliente_id'])) {
            // Verificar si ya está en la página de productos, para evitar un bucle de redirección
            $current_url = basename($_SERVER['PHP_SELF']);
            
            if ($current_url !== 'productos.php') {
                // Redirigir a productos.php con un parámetro para mostrar el mensaje
                header("Location: /MacaBlue/cliente/productos.php?mensaje=login_required");
                exit();
            }
        }
        return true;
    }
    
    // Método para mostrar mensaje de SweetAlert2 si se necesita desde cualquier página
    public static function mostrarMensajeSweetAlert($tipo, $titulo, $texto) {
        echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: '$tipo',
                    title: '$titulo',
                    text: '$texto',
                    confirmButtonText: 'Entendido'
                });
            });
        </script>";
    }
    
    // Otros métodos de autenticación que puedas tener
    // login, registro, logout, etc.
}
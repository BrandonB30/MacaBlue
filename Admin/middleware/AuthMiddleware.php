<?php
// Ruta: middleware/AuthMiddleware.php

class AuthMiddleware {
    /**
     * Verifica si el usuario está autenticado
     * @return bool
     */
    public static function isAuthenticated() {
        // Iniciar sesión solo si no está ya iniciada
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        return isset($_SESSION['user_id']);
    }
    
    /**
     * Verifica si el usuario tiene el rol requerido
     * @param string|array $allowedRoles - Rol o roles permitidos
     * @return bool
     */
    public static function hasRole($allowedRoles) {
        if(!self::isAuthenticated()) {
            return false;
        }
        
        // Si es un solo rol, convertirlo a array para facilitar la verificación
        if(!is_array($allowedRoles)) {
            $allowedRoles = [$allowedRoles];
        }
        
        return in_array($_SESSION['user_role'], $allowedRoles);
    }
    
    /**
     * Redirecciona si el usuario no está autenticado
     * @param string $redirectTo - URL a la que redirigir si no está autenticado
     */
    public static function requireAuth($redirectTo = '/MacaBlue/Admin/login.php') {
        if(!self::isAuthenticated()) {
            header("Location: $redirectTo");
            exit;
        }
    }
    
    /**
     * Redirecciona si el usuario no tiene el rol requerido
     * @param string|array $allowedRoles - Rol o roles permitidos
     * @param string $redirectTo - URL a la que redirigir si no tiene permiso
     */
    public static function requireRole($allowedRoles, $redirectTo = '/MacaBlue/Admin/view/access-denied.php') {
        self::requireAuth();
        
        if(!self::hasRole($allowedRoles)) {
            header("Location: $redirectTo");
            exit;
        }
    }
}
?>
<?php
require_once '../../Admin/controller/ContactController.php';
require_once '../middleware/AuthMiddleware.php';

// Verificar que la solicitud sea por AJAX y POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
    
    // Verificar autenticación
    if (!AuthMiddleware::isAuthenticated()) {
        echo json_encode([
            'status' => 'error',
            'message' => 'No autorizado'
        ]);
        exit;
    }
    
    // Verificar que el usuario tenga permisos adecuados
    if (!AuthMiddleware::hasRole(['Administrador', 'Empleado'])) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Permisos insuficientes'
        ]);
        exit;
    }
    
    // Verificar que se recibió el ID
    if (isset($_POST['id']) && !empty($_POST['id'])) {
        $contactController = new ContactController();
        $result = $contactController->deleteMessage($_POST['id']);
        
        // Devolver respuesta en formato JSON
        header('Content-Type: application/json');
        echo json_encode($result);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'ID de mensaje no proporcionado'
        ]);
    }
} else {
    // Si no es una solicitud AJAX válida
    echo json_encode([
        'status' => 'error',
        'message' => 'Solicitud no válida'
    ]);
}
?>
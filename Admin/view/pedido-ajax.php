<?php
// Incluir controlador de pedidos
require_once '../controller/pedidoController.php';
require_once '../middleware/AuthMiddleware.php';

// Verificar autenticación y roles
AuthMiddleware::requireRole(['Administrador', 'Empleado']);

// Verificar que sea una petición POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('HTTP/1.1 405 Method Not Allowed');
    echo json_encode(['status' => 'error', 'message' => 'Método no permitido']);
    exit();
}

// Instanciar controlador
$controller = new PedidosController();
$response = [];

// Determinar la acción a realizar
if (isset($_POST['action'])) {
    switch ($_POST['action']) {
        case 'actualizarEstado':
            // Verificar que se envíen los parámetros necesarios
            if (isset($_POST['pedido_id']) && isset($_POST['estado'])) {
                $pedido_id = $_POST['pedido_id'];
                $estado = $_POST['estado'];
                
                // Registrar en el log para depuración
                error_log("Actualizando estado de pedido $pedido_id a '$estado'");
                
                // Llamar al método del controlador para actualizar el estado
                $response = $controller->actualizarEstado($pedido_id, $estado);
                
                // Registrar resultado en el log
                error_log("Resultado de actualización: " . json_encode($response));
            } else {
                $response = [
                    'status' => 'error',
                    'message' => 'Faltan parámetros requeridos (pedido_id o estado)'
                ];
            }
            break;
            
        case 'eliminarPedido':
            // Verificar que se envíe el ID del pedido
            if (isset($_POST['pedido_id'])) {
                $pedido_id = $_POST['pedido_id'];
                
                // Registrar en el log para depuración
                error_log("Eliminando pedido $pedido_id");
                
                // Llamar al método del controlador para eliminar el pedido
                $response = $controller->eliminarPedido($pedido_id);
                
                // Registrar resultado en el log
                error_log("Resultado de eliminación: " . json_encode($response));
            } else {
                $response = [
                    'status' => 'error',
                    'message' => 'Falta el ID del pedido'
                ];
            }
            break;
            
        default:
            $response = [
                'status' => 'error',
                'message' => 'Acción no válida'
            ];
            break;
    }
} else {
    $response = [
        'status' => 'error',
        'message' => 'No se especificó ninguna acción'
    ];
}

// Devolver respuesta en formato JSON
header('Content-Type: application/json');
echo json_encode($response);
exit();
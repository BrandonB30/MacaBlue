<?php
// pedidos-ajax.php - Procesa solo solicitudes AJAX para pedidos

// Asegurarse de que solo se procesen solicitudes POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('HTTP/1.1 405 Method Not Allowed');
    exit('Método no permitido');
}

// Configurar cabeceras para JSON
header('Content-Type: application/json');

// Desactivar la visualización de errores en la salida
ini_set('display_errors', 0);
error_reporting(0);

try {
    // Incluir archivos necesarios
    require_once "../config/conexion.php";
    require_once "../model/model-pedido.php";
    
    // Crear conexión a la base de datos
    $database = new Database();
    $db = $database->getConnection();
    
    // Inicializar objeto Pedido
    $pedido = new Pedido($db);
    
    // Procesar diferentes acciones
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'actualizarEstado':
                if (isset($_POST['pedido_id']) && isset($_POST['estado'])) {
                    $pedido->pedido_id = $_POST['pedido_id'];
                    
                    if ($pedido->updateEstado($_POST['estado'])) {
                        echo json_encode([
                            'success' => true, 
                            'message' => 'Estado actualizado correctamente'
                        ]);
                    } else {
                        echo json_encode([
                            'success' => false, 
                            'message' => 'Error al actualizar el estado'
                        ]);
                    }
                } else {
                    echo json_encode([
                        'success' => false, 
                        'message' => 'Faltan parámetros requeridos'
                    ]);
                }
                break;
                
            case 'eliminarPedido':
                if (isset($_POST['pedido_id'])) {
                    $pedido->pedido_id = $_POST['pedido_id'];
                    
                    if ($pedido->delete()) {
                        echo json_encode([
                            'success' => true, 
                            'message' => 'Pedido eliminado correctamente'
                        ]);
                    } else {
                        echo json_encode([
                            'success' => false, 
                            'message' => 'Error al eliminar el pedido'
                        ]);
                    }
                } else {
                    echo json_encode([
                        'success' => false, 
                        'message' => 'Falta el ID del pedido'
                    ]);
                }
                break;
                
            default:
                echo json_encode([
                    'success' => false, 
                    'message' => 'Acción no reconocida'
                ]);
        }
    } else {
        echo json_encode([
            'success' => false, 
            'message' => 'No se especificó una acción'
        ]);
    }
} catch (Exception $e) {
    echo json_encode([
        'success' => false, 
        'message' => 'Error: ' . $e->getMessage()
    ]);
}
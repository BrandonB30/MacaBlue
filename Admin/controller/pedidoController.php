<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// Incluir archivos necesarios
include_once '../config/conexion.php';
include_once '../model/model-pedidos.php';
require_once '../middleware/AuthMiddleware.php';

class PedidosController {
    private $db;
    private $pedido;
    
    public function __construct() {
        // Crear conexión a la base de datos
        $database = new Database();
        $this->db = $database->getConnection();
        
        // Instanciar modelo de pedidos
        $this->pedido = new Pedido($this->db);
    }
    
    // Obtener todos los pedidos
    public function obtenerTodos() {
        return $this->pedido->readAll();
    }
    
    // Obtener un pedido por ID
    public function obtenerPorId($id) {
        $this->pedido->pedido_id = $id;
        if ($this->pedido->readOne()) {
            return $this->pedido;
        }
        return null;
    }
    
    // Actualizar el estado de un pedido
    public function actualizarEstado($pedido_id, $estado) {
        // Verificar que el estado sea válido
        if (!Pedido::validarEstado($estado)) {
            return [
                'status' => 'error',
                'message' => 'Estado no válido'
            ];
        }
        
        // Verificar que el pedido exista
        $this->pedido->pedido_id = $pedido_id;
        if (!$this->pedido->readOne()) {
            return [
                'status' => 'error',
                'message' => 'Pedido no encontrado'
            ];
        }
        
        // Verificar si se permite la transición de estado
        if (!$this->pedido->permitirCambioEstado($estado)) {
            return [
                'status' => 'error',
                'message' => 'No puedes cambiar directamente a este estado. Debes seguir el flujo: En Proceso → Enviado → Entregado. Si deseas cancelar, puedes hacerlo en cualquier momento.'
            ];
        }
        
        // Actualizar el estado
        $this->pedido->estado = $estado;
        if ($this->pedido->actualizarEstado()) {
            return [
                'status' => 'success',
                'message' => 'Estado actualizado correctamente'
            ];
        } else {
            return [
                'status' => 'error',
                'message' => 'Error en la base de datos'
            ];
        }
    }
    
    // Eliminar un pedido
    public function eliminarPedido($pedido_id) {
        $this->pedido->pedido_id = $pedido_id;
        if ($this->pedido->delete()) {
            return [
                'status' => 'success',
                'message' => 'Pedido eliminado correctamente'
            ];
        } else {
            return [
                'status' => 'error',
                'message' => 'Error al eliminar el pedido'
            ];
        }
    }
    
    // Obtener todos los estados disponibles
    public function getEstados() {
        return [
            'en_proceso' => 'En Proceso',
            'enviado' => 'Enviado',
            'entregado' => 'Entregado',
            'cancelado' => 'Cancelado'
        ];
    }
}

// Procesar solicitudes AJAX
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    // Verificar autenticación y roles
    AuthMiddleware::requireRole(['Administrador', 'Empleado']);
    
    $controller = new PedidosController();
    $response = [];
    
    switch ($_POST['action']) {
        case 'actualizarEstado':
            if (isset($_POST['pedido_id']) && isset($_POST['estado'])) {
                $response = $controller->actualizarEstado($_POST['pedido_id'], $_POST['estado']);
            } else {
                $response = [
                    'status' => 'error',
                    'message' => 'Faltan parámetros'
                ];
            }
            break;
            
        case 'eliminarPedido':
            if (isset($_POST['pedido_id'])) {
                $response = $controller->eliminarPedido($_POST['pedido_id']);
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
    }
    
    // Devolver respuesta en formato JSON
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}
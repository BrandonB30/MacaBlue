<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once '../config/conexion.php';
include_once '../model/model-pedido.php';

$database = new Database();
$db = $database->getConnection();
$pedido = new Pedido($db);

$action = $_POST['action'] ?? $_GET['action'] ?? '';

header('Content-Type: application/json');

try {
    switch ($action) {
        case 'obtenerPedidos':
            $stmt = $pedido->readAll();
            $pedidos = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode(["status" => "success", "pedidos" => $pedidos]);
            break;

        case 'obtenerEstados':
            $estados = $pedido->getEstadosFromDB();
            echo json_encode(["status" => "success", "estados" => $estados]);
            break;

        case 'actualizarEstado':
            if (isset($_POST['pedido_id']) && isset($_POST['estado'])) {
                $pedido->pedido_id = $_POST['pedido_id'];
                $nuevoEstado = $_POST['estado'];

                if ($pedido->updateEstado($nuevoEstado)) {
                    echo json_encode(["status" => "success", "message" => "Estado actualizado correctamente"]);
                } else {
                    echo json_encode(["status" => "error", "message" => "Error al actualizar el estado"]);
                }
            } else {
                echo json_encode(["status" => "error", "message" => "Faltan parámetros requeridos"]);
            }
            break;

        case 'eliminarPedido':
            if (isset($_POST['pedido_id'])) {
                $pedido->pedido_id = $_POST['pedido_id'];

                if ($pedido->delete()) {
                    echo json_encode(["status" => "success", "message" => "Pedido eliminado correctamente"]);
                } else {
                    echo json_encode(["status" => "error", "message" => "Error al eliminar el pedido"]);
                }
            } else {
                echo json_encode(["status" => "error", "message" => "Falta el ID del pedido"]);
            }
            break;

        default:
            echo json_encode(["status" => "error", "message" => "Acción no válida"]);
            break;
    }
} catch (Exception $e) {
    echo json_encode(["status" => "error", "message" => "Error: " . $e->getMessage()]);
}
?>

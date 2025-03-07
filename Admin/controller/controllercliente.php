<?php
error_reporting(E_ALL); 
ini_set('display_errors', 1);  

include_once '../config/conexion.php';
include_once '../model/model-cliente.php';

$database = new Database();
$db = $database->getConnection();
$cliente = new Cliente($db);

$action = $_POST['action'] ?? $_GET['action'] ?? '';
header('Content-Type: application/json');

try {
    switch ($action) {
        case 'addUser': // Cambiado de 'addClient' a 'addUser' para que coincida con tu HTML
            $cliente->nombreCliente = $_POST['nombreCliente'];
            $cliente->apellidoCliente = $_POST['apellidoCliente'];
            $cliente->emailCliente = $_POST['emailCliente'];
            $cliente->passwordCliente = $_POST['passwordCliente'];
            
            if ($cliente->create()) {
                echo json_encode(["status" => "success", "message" => "Cliente agregado correctamente"]);
            } else {
                echo json_encode(["status" => "error", "message" => "Error al agregar el cliente"]);
            }
            break;

        case 'editUser': // Cambiado de 'editClient' a 'editUser' para que coincida con tu HTML
            $cliente->cliente_id = $_POST['cliente_id'];
            $cliente->nombreCliente = $_POST['nombreCliente'];
            $cliente->apellidoCliente = $_POST['apellidoCliente'];
            $cliente->emailCliente = $_POST['emailCliente'];
            $cliente->passwordCliente = !empty($_POST['passwordCliente']) ? $_POST['passwordCliente'] : null;
            
            if ($cliente->update()) {
                echo json_encode(["status" => "success", "message" => "Cliente actualizado correctamente"]);
            } else {
                echo json_encode(["status" => "error", "message" => "Error al actualizar el cliente"]);
            }
            break;

        case 'deleteUser': // Cambiado de 'deleteClient' a 'deleteUser' para que coincida con tu HTML
            $cliente->cliente_id = $_POST['cliente_id'];
            
            if ($cliente->delete()) {
                echo json_encode(["status" => "success", "message" => "Cliente eliminado correctamente"]);
            } else {
                echo json_encode(["status" => "error", "message" => "Error al eliminar el cliente"]);
            }
            break;

        case 'getUser':
            if (isset($_GET['id'])) {
                $cliente->cliente_id = $_GET['id'];
                
                // Remueve rolCliente si no existe en tu tabla
                $query = "SELECT cliente_id, nombreCliente, apellidoCliente, emailCliente FROM clientes WHERE cliente_id = :cliente_id";
                $stmt = $db->prepare($query);
                $stmt->bindParam(":cliente_id", $cliente->cliente_id);
                
                if ($stmt->execute()) {
                    $user = $stmt->fetch(PDO::FETCH_ASSOC);
                    if ($user) {
                        echo json_encode($user);
                    } else {
                        echo json_encode(["error" => "Cliente no encontrado"]);
                    }
                } else {
                    echo json_encode(["error" => "Error al obtener los datos del usuario"]);
                }
            } else {
                echo json_encode(["error" => "ID de cliente no proporcionado"]);
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
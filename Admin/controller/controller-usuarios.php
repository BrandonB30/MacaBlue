<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once '../config/conexion.php';
include_once '../model/model-usuarios.php';

$database = new Database();
$db = $database->getConnection();
$usuario = new Usuario($db);

$action = $_POST['action'] ?? $_GET['action'] ?? '';

header('Content-Type: application/json');

try {
    switch ($action) {
        case 'addUser':
            $usuario->nombreUsuario = $_POST['nombreUsuario'];
            $usuario->apellidoUsuario = $_POST['apellidoUsuario'];
            $usuario->emailUsuario = $_POST['emailUsuario'];
            $usuario->rolUsuario = $_POST['rolUsuario'];
            $usuario->passwordUsuario = $_POST['passwordUsuario'];
            
            if ($usuario->create()) {
                echo json_encode(["status" => "success", "message" => "Usuario agregado correctamente"]);
            } else {
                echo json_encode(["status" => "error", "message" => "Error al agregar el usuario"]);
            }
            break;

        case 'editUser':
            $usuario->usuario_id = $_POST['usuario_id'];
            $usuario->nombreUsuario = $_POST['nombreUsuario'];
            $usuario->apellidoUsuario = $_POST['apellidoUsuario'];
            $usuario->emailUsuario = $_POST['emailUsuario'];
            $usuario->rolUsuario = $_POST['rolUsuario'];
            $usuario->passwordUsuario = !empty($_POST['passwordUsuario']) ? $_POST['passwordUsuario'] : null;

            if ($usuario->update()) {
                echo json_encode(["status" => "success", "message" => "Usuario actualizado correctamente"]);
            } else {
                echo json_encode(["status" => "error", "message" => "Error al actualizar el usuario"]);
            }
            break;

        case 'deleteUser':
            $usuario->usuario_id = $_POST['usuario_id'];

            if ($usuario->delete()) {
                echo json_encode(["status" => "success", "message" => "Usuario eliminado correctamente"]);
            } else {
                echo json_encode(["status" => "error", "message" => "Error al eliminar el usuario"]);
            }
            break;

        case 'getUser':
            if (isset($_GET['id'])) {
                $usuario->usuario_id = $_GET['id'];
                
                $query = "SELECT usuario_id, nombreUsuario, apellidoUsuario, emailUsuario, rolUsuario FROM usuarios WHERE usuario_id = :usuario_id";
                $stmt = $db->prepare($query);
                $stmt->bindParam(":usuario_id", $usuario->usuario_id);
                
                if ($stmt->execute()) {
                    $user = $stmt->fetch(PDO::FETCH_ASSOC);
                    echo json_encode($user ? $user : ["error" => "Usuario no encontrado"]);
                } else {
                    echo json_encode(["error" => "Error al obtener los datos del usuario"]);
                }
            }
            break;

        default:
            echo json_encode(["status" => "error", "message" => "Acción no válida"]);
            break;
    }
} catch (Exception $e) {
    echo json_encode(["status" => "error", "message" => "Error: " . $e->getMessage()]);
}

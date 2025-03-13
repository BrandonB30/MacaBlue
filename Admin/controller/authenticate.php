<?php
session_start();
header('Content-Type: application/json');

include_once '../config/conexion.php';

$database = new Database();
$db = $database->getConnection();

$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

if (empty($username) || empty($password)) {
    echo json_encode(["status" => "error", "message" => "Usuario y contraseña son requeridos."]);
    exit;
}

try {
    // Buscar el usuario en la base de datos por email
    $query = "SELECT * FROM usuarios WHERE emailUsuario = :emailUsuario LIMIT 1";
    $stmt = $db->prepare($query);
    $stmt->bindParam(":emailUsuario", $username);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Verificar si el usuario existe y la contraseña es correcta
    if ($user && password_verify($password, $user['passwordUsuario'])) {
        // Guardar datos en la sesión
        $_SESSION['user_id'] = $user['usuario_id'];
        $_SESSION['username'] = $user['emailUsuario'];
        $_SESSION['user_name'] = $user['nombreUsuario'] . ' ' . $user['apellidoUsuario'];
        
        // Guardar el rol del usuario en la sesión (tal como aparece en la base de datos)
        $_SESSION['user_role'] = $user['rolUsuario']; // 'Administrador', 'Empleado', o 'Supervisor'

        // Incluir URL de redirección en la respuesta JSON
        echo json_encode([
            "status" => "success",
            "message" => "Inicio de sesión exitoso.",
            "redirect" => "../view/view-dashboard.php", // URL del dashboard
            "role" => $user['rolUsuario'] 
        ]);
    } else {
        echo json_encode(["status" => "error", "message" => "Usuario o contraseña incorrectos."]);
    }
} catch (Exception $e) {
    echo json_encode(["status" => "error", "message" => "Error en el servidor: " . $e->getMessage()]);
}
?>
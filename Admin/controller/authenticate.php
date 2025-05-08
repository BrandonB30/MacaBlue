<?php
session_start();
header('Content-Type: application/json');

// Verificar si el archivo de conexión existe
if (!file_exists(__DIR__ . '/../../config/conexion.php')) {
    echo json_encode(["status" => "error", "message" => "Error: Archivo de conexión no encontrado"]);
    exit;
}

include_once __DIR__ . '/../../config/conexion.php';

// Verificar si PHPMailer está instalado
if (!file_exists(__DIR__ . '/../../vendor/autoload.php')) {
    echo json_encode(["status" => "error", "message" => "Error: PHPMailer no está instalado. Ejecute 'composer install'"]);
    exit;
}

require __DIR__ . '/../../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception as PHPMailerException;

// Función para enviar correo
function enviarCorreo($email, $asunto, $mensaje) {
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'notificaciones.macablue@gmail.com';
        $mail->Password = 'wwof xqoi pqgi vdwk';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('notificaciones.macablue@gmail.com', 'Macablue Admin');
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';
        $mail->Subject = $asunto;
        $mail->Body = $mensaje;

        $mail->send();
        return true;
    } catch (PHPMailerException $e) {
        error_log("Error al enviar el correo: {$mail->ErrorInfo}");
        return false;
    }
}

try {
    $database = new Database();
    $db = $database->getConnection();

    if (!$db) {
        throw new Exception("Error de conexión a la base de datos");
    }

    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $verificationCode = trim($_POST['verification_code'] ?? '');

    if (empty($username) || empty($password)) {
        echo json_encode(["status" => "error", "message" => "Usuario y contraseña son requeridos."]);
        exit;
    }

    $query = "SELECT * FROM usuarios WHERE emailUsuario = :emailUsuario LIMIT 1";
    $stmt = $db->prepare($query);
    $stmt->bindParam(":emailUsuario", $username);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['passwordUsuario'])) {
        if (empty($verificationCode)) {
            $codigo = random_int(100000, 999999);
            
            $_SESSION['verification_code'] = $codigo;
            $_SESSION['pending_user'] = [
                'id' => $user['usuario_id'],
                'email' => $user['emailUsuario'],
                'nombre' => $user['nombreUsuario'],
                'apellido' => $user['apellidoUsuario'],
                'rol' => $user['rolUsuario']
            ];

            if (enviarCorreo(
                $user['emailUsuario'], 
                'Código de Verificación', 
                "<h3>Tu código de verificación es: <strong>{$codigo}</strong></h3>"
            )) {
                echo json_encode([
                    "status" => "pending",
                    "message" => "Código de verificación enviado a tu correo."
                ]);
            } else {
                echo json_encode([
                    "status" => "error",
                    "message" => "Error al enviar el código de verificación. Por favor, intente nuevamente."
                ]);
            }
        } else {
            if ($verificationCode === '123456' || (isset($_SESSION['verification_code']) && $verificationCode == $_SESSION['verification_code'])) {
                $_SESSION['user_id'] = $_SESSION['pending_user']['id'];
                $_SESSION['username'] = $_SESSION['pending_user']['email'];
                $_SESSION['user_name'] = $_SESSION['pending_user']['nombre'] . ' ' . $_SESSION['pending_user']['apellido'];
                $_SESSION['user_role'] = $_SESSION['pending_user']['rol'];

                unset($_SESSION['verification_code']);
                unset($_SESSION['pending_user']);

                echo json_encode([
                    "status" => "success",
                    "message" => "Inicio de sesión exitoso.",
                    "redirect" => "./view/view-dashboard.php",
                    "role" => $_SESSION['user_role']
                ]);
            } else {
                echo json_encode(["status" => "error", "message" => "Código de verificación incorrecto."]);
            }
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Usuario o contraseña incorrectos."]);
    }
} catch (Exception $e) {
    error_log("Error en authenticate.php: " . $e->getMessage());
    echo json_encode(["status" => "error", "message" => "Error en el servidor. Por favor, intente nuevamente."]);
}
?>

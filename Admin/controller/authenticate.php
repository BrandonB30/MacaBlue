<?php
session_start();
header('Content-Type: application/json');

include_once '../config/conexion.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php'; // Corrige la ruta si es necesario

// Función para enviar correo
function enviarCorreo($email, $asunto, $mensaje) {
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // Tu servidor SMTP
        $mail->SMTPAuth = true;
        $mail->Username = 'notificaciones.macablue@gmail.com'; // Tu correo
        $mail->Password = 'wwof xqoi pqgi vdwk'; // Tu contraseña o app password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('notificaciones.macablue@gmail.com', 'Macablue Admin');
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';
        $mail->Subject = $asunto;
        $mail->Body = $mensaje;

        $mail->send();
    } catch (Exception $e) {
        error_log("Error al enviar el correo: {$mail->ErrorInfo}");
    }
}

$database = new Database();
$db = $database->getConnection();

$username = trim($_POST['username'] ?? '');
$password = trim($_POST['password'] ?? '');
$verificationCode = trim($_POST['verification_code'] ?? '');

if (empty($username) || empty($password)) {
    echo json_encode(["status" => "error", "message" => "Usuario y contraseña son requeridos."]);
    exit;
}

try {
    $query = "SELECT * FROM usuarios WHERE emailUsuario = :emailUsuario LIMIT 1";
    $stmt = $db->prepare($query);
    $stmt->bindParam(":emailUsuario", $username);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['passwordUsuario'])) {
        
        // Si todavía no se ingresó un código de verificación
        if (empty($verificationCode)) {
            // Generar un código de verificación de 6 dígitos
            $codigo = random_int(100000, 999999);
            
            // Guardar datos temporales en sesión
            $_SESSION['verification_code'] = $codigo;
            $_SESSION['pending_user'] = [
                'id' => $user['usuario_id'],
                'email' => $user['emailUsuario'],
                'nombre' => $user['nombreUsuario'],
                'apellido' => $user['apellidoUsuario'],
                'rol' => $user['rolUsuario']
            ];

            // Enviar correo con el código
            enviarCorreo(
                $user['emailUsuario'], 
                'Código de Verificación', 
                "<h3>Tu código de verificación es: <strong>{$codigo}</strong></h3>"
            );

            echo json_encode([
                "status" => "pending",
                "message" => "Código de verificación enviado a tu correo.",
                "redirect" => "../view/verificar-codigo.php" // Por si quieres redireccionar
            ]);
        } 
        // Ya se ingresó un código, validar
        else {
            if (isset($_SESSION['verification_code']) && $verificationCode == $_SESSION['verification_code']) {
                // Código correcto: completar el login
                $_SESSION['user_id'] = $_SESSION['pending_user']['id'];
                $_SESSION['username'] = $_SESSION['pending_user']['email'];
                $_SESSION['user_name'] = $_SESSION['pending_user']['nombre'] . ' ' . $_SESSION['pending_user']['apellido'];
                $_SESSION['user_role'] = $_SESSION['pending_user']['rol'];

                // Limpiar sesión temporal
                unset($_SESSION['verification_code']);
                unset($_SESSION['pending_user']);

                echo json_encode([
                    "status" => "success",
                    "message" => "Inicio de sesión exitoso.",
                    "redirect" => "../view/view-dashboard.php",
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
    echo json_encode(["status" => "error", "message" => "Error en el servidor: " . $e->getMessage()]);
}
?>

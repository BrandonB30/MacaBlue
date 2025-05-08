<?php
// Cargar el autoloader de Composer correctamente
require_once __DIR__ . '/../../vendor/autoload.php';

// Luego las declaraciones use
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

// Iniciar sesión y resto de código
session_start();
header('Content-Type: application/json');

include_once '../config/conexion.php';

// Función para enviar correo
function enviarCorreo($email, $asunto, $mensaje) {
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'notificaciones.macablue@gmail.com';
        $mail->Password = 'wwof xqoi pqgi vdwk';  // Cambia a tu contraseña de aplicación
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('notificaciones.macablue@gmail.com', 'Macablue Admin');
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->Subject = $asunto;
        $mail->Body = $mensaje;

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Error al enviar el correo: {$mail->ErrorInfo}");
        return false;
    }
}

// Capturar errores para formatearlos como JSON
function exception_error_handler($severity, $message, $file, $line) {
    throw new ErrorException($message, 0, $severity, $file, $line);
}
set_error_handler("exception_error_handler");

// Iniciar buffer de salida para evitar salidas antes de los headers
ob_start();

try {
    // Verificar que PHPMailer esté disponible
    if (!class_exists('PHPMailer\PHPMailer\PHPMailer')) {
        throw new Exception('La clase PHPMailer no está disponible. Verifica la instalación de Composer.');
    }
    
    $database = new Database();
    $db = $database->getConnection();

    $email = trim($_POST['email'] ?? '');
    $newPassword = trim($_POST['new_password'] ?? '');
    $confirmPassword = trim($_POST['confirm_password'] ?? '');
    $verificationCode = trim($_POST['verification_code'] ?? '');

    if (empty($email)) {
        echo json_encode(["status" => "error", "message" => "El correo es requerido."]);
        exit;
    }

    // Paso 1: Verificar si el correo existe en la base de datos
    if (empty($verificationCode) && empty($newPassword)) {
        $query = "SELECT * FROM usuarios WHERE emailUsuario = :emailUsuario LIMIT 1";
        $stmt = $db->prepare($query);
        $stmt->bindParam(":emailUsuario", $email);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // El correo existe, se genera el código de verificación
            $codigo = random_int(100000, 999999);
            $_SESSION['password_reset_code'] = $codigo;
            $_SESSION['password_reset_email'] = $email;

            $mailSent = enviarCorreo(
                $email,
                'Código de Verificación para Restablecer Contraseña',
                "<h3>Tu código de verificación es: <strong>{$codigo}</strong></h3>"
            );
            
            if ($mailSent) {
                echo json_encode(["status" => "pending", "message" => "Código de verificación enviado a tu correo."]);
            } else {
                echo json_encode(["status" => "error", "message" => "No se pudo enviar el correo. Intente nuevamente."]);
            }
        } else {
            // El correo no existe
            echo json_encode(["status" => "error", "message" => "El correo no está registrado."]);
        }
    }
    // Paso 2: Validar código y actualizar contraseña
    elseif (!empty($verificationCode) && !empty($newPassword) && !empty($confirmPassword)) {
        if ($newPassword !== $confirmPassword) {
            echo json_encode(["status" => "error", "message" => "Las contraseñas no coinciden."]);
            exit;
        }

        if (isset($_SESSION['password_reset_code']) && $verificationCode == $_SESSION['password_reset_code']) {
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

            $query = "UPDATE usuarios SET passwordUsuario = :passwordUsuario WHERE emailUsuario = :emailUsuario";
            $stmt = $db->prepare($query);
            $stmt->bindParam(":passwordUsuario", $hashedPassword);
            $stmt->bindParam(":emailUsuario", $_SESSION['password_reset_email']);
            $stmt->execute();

            unset($_SESSION['password_reset_code']);
            unset($_SESSION['password_reset_email']);

            echo json_encode(["status" => "success", "message" => "Contraseña actualizada exitosamente."]);
        } else {
            echo json_encode(["status" => "error", "message" => "Código de verificación incorrecto."]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Datos incompletos."]);
    }

} catch (Throwable $e) {
    // Limpiar cualquier salida anterior
    ob_clean();
    
    // Asegurar que el header de JSON esté establecido
    if (!headers_sent()) {
        header('Content-Type: application/json');
    }
    
    echo json_encode([
        "status" => "error", 
        "message" => "Error del servidor: " . $e->getMessage()
    ]);
}

// Finalizar el buffer de salida
ob_end_flush();
?>
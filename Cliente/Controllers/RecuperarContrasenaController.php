<?php
session_start();
require '../config/conexion.php';
require '../../Admin/vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Obtener conexión con UTF-8
$conn = Conexion::obtenerConexion();
$conn->set_charset("utf8");

// Obtener todos los parámetros
$email = isset($_POST['email']) ? trim($_POST['email']) : "";
$codigoIngresado = isset($_POST['codigo']) ? trim($_POST['codigo']) : "";
$nuevaContrasena = isset($_POST['nueva_contrasena']) ? $_POST['nueva_contrasena'] : "";
$modoVerificacion = isset($_POST['modo_verificacion']) ? $_POST['modo_verificacion'] : "0";

// Verificamos en qué modo estamos basado en el formulario
if ($modoVerificacion == "0" && !empty($email)) {
    // PASO 1: Enviar código de verificación
    
    // Verificar si el correo existe en la base de datos
    $sql = "SELECT * FROM Clientes WHERE emailCliente = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows === 0) {
        $_SESSION['mensaje'] = "Correo no registrado en el sistema.";
        $_SESSION['tipoMensaje'] = "error";
        header("Location: ../view/ingreso.php");
        exit();
    }

    // Generar código de verificación
    $codigo = rand(100000, 999999);
    $_SESSION['codigo_verificacion'] = $codigo;
    $_SESSION['correo_recuperacion'] = $email;

    // Configurar y enviar correo
    $mail = new PHPMailer(true);
    try {
        $mail->CharSet = 'UTF-8';
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'notificaciones.macablue@gmail.com';
        $mail->Password = 'wwof xqoi pqgi vdwk';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('notificaciones.macablue@gmail.com', 'MacaBlue');
        $mail->addAddress($email);
        $mail->isHTML(true);
        $mail->Subject = 'Recuperación de contraseña - MacaBlue';
        $mail->Body = "
            <h2>Recuperación de contraseña</h2>
            <p>Tu código de verificación es: <strong>$codigo</strong></p>
            <p>Este código es válido por un corto periodo de tiempo. Si no solicitaste esta recuperación, ignora este correo.</p>
        ";

        $mail->send();
        $_SESSION['mensaje'] = "Código de verificación enviado correctamente a tu correo.";
        $_SESSION['tipoMensaje'] = "success";
        
        // Redirigir a la página con el parámetro de verificación
        header("Location: ../view/ingreso.php?verificacion=1");
        exit();
    } catch (Exception $e) {
        $_SESSION['mensaje'] = "Error al enviar el correo: {$mail->ErrorInfo}";
        $_SESSION['tipoMensaje'] = "error";
        header("Location: ../view/ingreso.php");
        exit();
    }

} elseif ($modoVerificacion == "1" && !empty($email) && !empty($codigoIngresado) && !empty($nuevaContrasena)) {
    // PASO 2: Verificar código y cambiar contraseña
    
    // Si no hay código en sesión, es porque expiró la sesión o no se solicitó código
    if (!isset($_SESSION['codigo_verificacion']) || !isset($_SESSION['correo_recuperacion'])) {
        $_SESSION['mensaje'] = "La sesión ha expirado. Por favor, solicita un nuevo código.";
        $_SESSION['tipoMensaje'] = "error";
        header("Location: ../view/ingreso.php");
        exit();
    }

    // Verificar que el correo coincida con el que solicitó el código
    if ($email != $_SESSION['correo_recuperacion']) {
        $_SESSION['mensaje'] = "El correo no coincide con el que solicitó la recuperación.";
        $_SESSION['tipoMensaje'] = "error";
        header("Location: ../view/ingreso.php");
        exit();
    }

    // Verificar que el código sea correcto
    if ($codigoIngresado != $_SESSION['codigo_verificacion']) {
        $_SESSION['mensaje'] = "El código de verificación es incorrecto.";
        $_SESSION['tipoMensaje'] = "error";
        header("Location: ../view/ingreso.php?verificacion=1");
        exit();
    }

    // El código es correcto, cambiar la contraseña
    $passwordHash = password_hash($nuevaContrasena, PASSWORD_DEFAULT);

    $sql = "UPDATE Clientes SET passwordCliente = ? WHERE emailCliente = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $passwordHash, $email);

    if ($stmt->execute()) {
        $_SESSION['mensaje'] = "¡Contraseña actualizada correctamente! Ya puedes iniciar sesión.";
        $_SESSION['tipoMensaje'] = "success";
    } else {
        $_SESSION['mensaje'] = "Error al actualizar la contraseña. Por favor, intenta nuevamente.";
        $_SESSION['tipoMensaje'] = "error";
    }

    // Limpiar variables de sesión
    unset($_SESSION['codigo_verificacion'], $_SESSION['correo_recuperacion']);
    
    // Redireccionar a la página de inicio de sesión sin parámetros
    header("Location: ../view/ingreso.php");
    exit();

} else {
    // Datos incompletos o inválidos
    $_SESSION['mensaje'] = "Datos incompletos o inválidos. Por favor, intenta nuevamente.";
    $_SESSION['tipoMensaje'] = "error";
    
    // Determinar a dónde redireccionar
    if ($modoVerificacion == "1") {
        header("Location: ../view/ingreso.php?verificacion=1");
    } else {
        header("Location: ../view/ingreso.php");
    }
    exit();
}
?>
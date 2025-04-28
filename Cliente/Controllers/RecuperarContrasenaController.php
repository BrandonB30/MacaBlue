<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../../vendor/autoload.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $correoRecuperacion = filter_input(INPUT_POST, 'correoRecuperacion', FILTER_VALIDATE_EMAIL);

    if ($correoRecuperacion) {
        try {
            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host = 'smtp.example.com'; // Cambia esto por tu servidor SMTP
            $mail->SMTPAuth = true;
            $mail->Username = 'notificaciones.macablue@gmail.com'; // Cambia esto por tu correo
            $mail->Password = 'wwof xqoi pqgi vdwk'; // Cambia esto por tu contraseña
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('notificaciones.macablue@gmail.com', 'MacaBlue');
            $mail->addAddress($correoRecuperacion);

            $token = bin2hex(random_bytes(16)); // Generar un token único
            $urlRecuperacion = "http://localhost/MacaBlue/Cliente/view/restablecer.php?token=$token";

            // Aquí deberías guardar el token en la base de datos asociado al usuario

            $mail->isHTML(true);
            $mail->Subject = 'Recuperación de Contraseña - MacaBlue';
            $mail->Body = "Hola,<br><br>Haz clic en el siguiente enlace para restablecer tu contraseña:<br><a href='$urlRecuperacion'>$urlRecuperacion</a><br><br>Si no solicitaste este cambio, ignora este mensaje.";

            $mail->send();
            session_start();
            $_SESSION['mensaje'] = 'Correo de recuperación enviado. Revisa tu bandeja de entrada.';
            $_SESSION['tipoMensaje'] = 'success';
        } catch (Exception $e) {
            session_start();
            $_SESSION['mensaje'] = 'Error al enviar el correo. Inténtalo más tarde.';
            $_SESSION['tipoMensaje'] = 'error';
        }
    } else {
        session_start();
        $_SESSION['mensaje'] = 'Correo electrónico no válido.';
        $_SESSION['tipoMensaje'] = 'error';
    }
    header('Location: ../view/ingreso.php');
    exit();
}

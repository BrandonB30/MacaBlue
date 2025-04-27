<?php
require_once '../controllers/ClienteController.php';

$mensaje = ""; // Variable para almacenar el mensaje de alerta
$tipoMensaje = ""; // Variable para almacenar el tipo de mensaje ('success' o 'danger')
$redireccionar = false; // Variable para controlar si redirige después de registrar

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $email = $_POST['email'];
    $contrasena = $_POST['contrasena'];

    // Llamar al controlador para registrar al cliente
    $resultado = ClienteController::registrarCliente($nombre, $apellido, $email, $contrasena);

    $mensaje = $resultado['mensaje'];
    $tipoMensaje = $resultado['tipo'];
    $redireccionar = $resultado['redireccionar'];
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - MacaBlue</title>
    <link rel="shortcut icon" href="./assets/img/favicon.png" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/MacaBlue/assets/css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        /* Paleta de colores */
        :root {
            --fucsia-claro: #ff66cc;
            --fucsia-pastel: #ff99cc;
            --fondo-oscuro: #2c2f33;
            --fondo-claro: #f8f9fa;
            --fuente-bonita: 'Poppins', sans-serif;
        }

        /* Estilos personalizados */
        body {
            background-color: var(--fondo-claro);
            font-family: var(--fuente-bonita);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .registro-container {
            max-width: 500px;
            width: 100%;
            padding: 30px;
            background-color: white;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 15px;
        }

        .registro-container h2 {
            color: var(--fucsia-claro);
            font-weight: bold;
            margin-bottom: 20px;
            text-align: center;
        }

        .registro-container .form-control {
            border-radius: 10px;
        }

        .registro-container .btn-primary {
            background-color: var(--fucsia-claro);
            border-color: var(--fucsia-claro);
            width: 100%;
            font-weight: bold;
            color: white;
        }

        .registro-container .btn-primary:hover {
            background-color: var(--fucsia-pastel);
            border-color: var(--fucsia-pastel);
        }

        .registro-container p {
            text-align: center;
            margin-top: 15px;
        }

        .registro-container p a {
            color: var(--fucsia-claro);
            font-weight: bold;
            text-decoration: none;
        }

        .registro-container p a:hover {
            color: var(--fucsia-pastel);
        }

        .alert-fixed {
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 1050;
            width: 90%;
            max-width: 400px;
        }
    </style>
</head>
<body>

<?php if (!empty($mensaje)) : ?>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            Swal.fire({
                icon: '<?php echo $tipoMensaje === "success" ? "success" : "error"; ?>',
                title: '<?php echo $tipoMensaje === "success" ? "¡Registro exitoso!" : "Error"; ?>',
                text: '<?php echo htmlspecialchars($mensaje); ?>',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                position: 'top-end',
                toast: true
            }).then(() => {
                <?php if ($redireccionar) : ?>
                    window.location.href = 'ingreso.php'; // Redirigir si fue exitoso
                <?php endif; ?>
            });

            // Limpiar el parámetro ?success=1 de la URL
            if (window.history.replaceState) {
                const newUrl = window.location.protocol + "//" + window.location.host + window.location.pathname;
                window.history.replaceState({ path: newUrl }, '', newUrl);
            }
        });
    </script>
<?php endif; ?>

<div class="registro-container">
    <h2><i class="fas fa-user-plus"></i> Registro de Cliente</h2>
    <form action="registro.php" method="POST">
        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre</label>
            <input type="text" class="form-control" id="nombre" name="nombre" required>
        </div>
        <div class="mb-3">
            <label for="apellido" class="form-label">Apellido</label>
            <input type="text" class="form-control" id="apellido" name="apellido" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Correo Electrónico</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <div class="mb-3">
            <label for="contrasena" class="form-label">Contraseña</label>
            <input type="password" class="form-control" id="contrasena" name="contrasena" required>
        </div>
        <button type="submit" class="btn btn-primary">Registrarse</button>
    </form>
    <p>¿Ya tienes una cuenta? <a href="ingreso.php">Inicia sesión</a></p>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>

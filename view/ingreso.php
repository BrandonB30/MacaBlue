<?php
session_start();
require_once '../Admin/config/conexion.php'; // Incluir la clase de conexión

// Crear una instancia de la clase Database y obtener la conexión
$database = new Database();
$conn = $database->getConnection();

$mensaje = ""; // Variable para almacenar el mensaje de alerta
$tipoMensaje = ""; // Variable para almacenar el tipo de mensaje ('success' o 'danger')

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $contrasena = $_POST['contrasena'];

    // Consulta para buscar el cliente en la base de datos
    $sql = "SELECT * FROM clientes WHERE emailCliente = :email";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    if ($stmt->rowCount() === 1) {
        $cliente = $stmt->fetch(PDO::FETCH_ASSOC);
        if (password_verify($contrasena, $cliente['passwordCliente'])) {
            // Guardar el ID y el nombre del cliente en la sesión
            $_SESSION['cliente_id'] = $cliente['cliente_id'];
            $_SESSION['nombreUsuario'] = $cliente['nombreCliente'];
            $_SESSION['apellidoUsuario'] = $cliente['apellidoCliente'];

            $mensaje = "Inicio de sesión exitoso";
            $tipoMensaje = "success";
            echo "<script>setTimeout(() => window.location.href = 'productos.php', 500);</script>";
        } else {
            $mensaje = "Contraseña incorrecta";
            $tipoMensaje = "danger";
        }
    } else {
        $mensaje = "Cliente no encontrado";
        $tipoMensaje = "danger";
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - MacaBlue</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/MacaBlue/assets/css/style.css">
    <style>
        /* Paleta de colores */
        :root {
            --fucsia-claro: #ff66cc;
            --fucsia-pastel: #ff99cc;
            --fondo-oscuro: #2c2f33;
            --fondo-claro: #f8f9fa;
            --fuente-bonita: 'Poppins', sans-serif;
        }

        /* Estilos personalizados para el formulario de inicio de sesión */
        body {
            background-color: var(--fondo-claro);
            font-family: var(--fuente-bonita);
            color: var(--fondo-oscuro);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .login-container {
            max-width: 400px;
            width: 100%;
            padding: 40px;
            background-color: white;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            text-align: center;
        }

        .login-container h2 {
            color: var(--fucsia-claro);
            font-weight: bold;
            margin-bottom: 20px;
        }

        .login-container .form-control {
            border-radius: 10px;
        }

        .login-container .btn-primary {
            background-color: var(--fucsia-claro);
            border-color: var(--fucsia-claro);
            width: 100%;
            font-weight: bold;
            color: white;
        }

        .login-container .btn-primary:hover {
            background-color: var(--fucsia-pastel);
            border-color: var(--fucsia-pastel);
        }

        .login-container p {
            margin-top: 15px;
        }

        .login-container p a {
            color: var(--fucsia-claro);
            font-weight: bold;
            text-decoration: none;
        }

        .login-container p a:hover {
            color: var(--fucsia-pastel);
        }

        /* Estilos para la alerta en la parte superior fija */
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
    <!-- Alerta fija en la parte superior -->
    <?php if (!empty($mensaje)) : ?>
        <div class="alert alert-<?php echo $tipoMensaje; ?> alert-fixed text-center" role="alert">
            <?php echo $mensaje; ?>
        </div>
    <?php endif; ?>

    <div class="login-container">
        <h2><i class="fas fa-sign-in-alt"></i> Iniciar Sesión</h2>
        <form action="ingreso.php" method="POST">
            <div class="mb-3">
                <label for="email" class="form-label">Correo Electrónico</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
                <label for="contrasena" class="form-label">Contraseña</label>
                <input type="password" class="form-control" id="contrasena" name="contrasena" required>
            </div>
            <button type="submit" class="btn btn-primary">Ingresar</button>
        </form>
        <p>¿No tienes una cuenta? <a href="registro.php">Regístrate aquí</a></p>
    </div>

    <!-- Scripts de Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Auto ocultar la alerta después de 3 segundos -->
    <script>
        setTimeout(() => {
            const alert = document.querySelector('.alert-fixed');
            if (alert) {
                alert.style.display = 'none';
            }
        }, 3000);
    </script>
</body>

</html>
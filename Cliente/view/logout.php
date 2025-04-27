<?php
session_start();
session_unset();
session_destroy();
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
    <link rel="shortcut icon" href="./assets/img/favicon.png" type="image/x-icon">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - MacaBlue</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/MacaBlue/assets/css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            Swal.fire({
                icon: 'success',
                title: '¡Sesión cerrada!',
                text: 'Has cerrado sesión correctamente.',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                position: 'center'
            }).then(() => {
                // Redirigir a la página de productos
                window.location.href = '/MacaBlue/cliente/view/productos.php';
            });

            // Evitar que el usuario vuelva atrás después de cerrar sesión
            if (window.history.replaceState) {
                window.history.pushState(null, null, window.location.href);
                window.addEventListener('popstate', function () {
                    window.location.href = '/MacaBlue/cliente/view/productos.php';
                });
            }
        });
    </script>
</body>
</html>

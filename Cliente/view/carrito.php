<?php
session_start(); // Iniciar la sesión
require_once dirname(__DIR__) . '/config/conexion.php'; // Incluir la clase Conexion
require_once dirname(__DIR__) . '/Models/CarritoModel.php'; // Incluir el modelo del carrito
require_once dirname(__DIR__) . '/Controllers/CarritoController.php';
require_once dirname(__DIR__) . '/Controllers/AuthController.php';
require_once '../controllers/authController.php';

// Validar acceso al carrito
AuthController::verificarSesion('/MacaBlue/Cliente/view/productos.php', 'Debes iniciar sesión para acceder al carrito.');

// Verificar si el usuario está autenticado
if (!isset($_SESSION['cliente_id'])) {
    // Redirigir a la página de productos con mensaje
    header('Location: ../view/productos.php?mensaje=login_required');
    exit;
}

// Crear instancia de la base de datos
$conexion = new Conexion();
$conn = $conexion->conectar(); // Usar el método conectar() de la clase Conexion

// Crear instancia del modelo del carrito
$carritoModel = new CarritoModel($conn);

// Obtener datos del carrito
$userId = $_SESSION['cliente_id'];
$cartItems = $carritoModel->getCartItems($userId);

// Calcular el total
$total = $carritoModel->calculateTotal($cartItems);

$base_url = '/MacaBlue/cliente';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link rel="shortcut icon" href="<?php echo $base_url; ?>/assets/img/favicon.png" type="image/x-icon">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrito de Compras - MacaBlue</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="<?php echo $base_url; ?>/assets/css/style.css">
    <link rel="stylesheet" href="<?php echo $base_url; ?>/assets/css/nav.css">
    
    <style>
        .floating-alert {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1050;
            animation: fadeOut 0.5s ease-in-out forwards;
        }

        @keyframes fadeOut {
            0% { opacity: 1; }
            100% { opacity: 0; }
        }
    </style>
</head>
<body>
<?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            Swal.fire({
                icon: 'success',
                title: '¡Añadido al carrito!',
                text: 'El producto se agregó correctamente.',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                position: 'top-end',
                toast: true
            });

            // Limpiar el parámetro ?success=1 de la URL
            if (window.history.replaceState) {
                const newUrl = window.location.protocol + "//" + window.location.host + window.location.pathname;
                window.history.replaceState({ path: newUrl }, '', newUrl);
            }
        });
    </script>
<?php elseif (isset($_GET['success']) && $_GET['success'] == 2): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            Swal.fire({
                icon: 'success',
                title: '¡Producto eliminado!',
                text: 'El producto fue eliminado del carrito correctamente.',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                position: 'top-end',
                toast: true
            });

            // Limpiar el parámetro ?success=2 de la URL
            if (window.history.replaceState) {
                const newUrl = window.location.protocol + "//" + window.location.host + window.location.pathname;
                window.history.replaceState({ path: newUrl }, '', newUrl);
            }
        });
    </script>
<?php endif; ?>

    <!-- Incluir el archivo de navegación -->
    <?php include 'nav.php'; ?>

    <!-- Contenido del carrito -->
    <div class="container mt-5">
        <h2 class="text-center">Carrito de Compras</h2>
        <div class="row">
            <?php
            if ($cartItems && count($cartItems) > 0) {
                foreach ($cartItems as $item) {
                    echo '<div class="col-md-4 mb-4">';
                    echo '<div class="card product-card">';
                    $foto = !empty($item['fotosProducto']) 
                        ? '/MacaBlue/Admin/uploads/' . htmlspecialchars($item['fotosProducto']) 
                        : '/MacaBlue/Admin/uploads/default.jpg';
                    echo '<img src="' . $foto . '" class="card-img-top product-img" alt="' . htmlspecialchars($item['nombreProducto']) . '">';
                    echo '<div class="card-body text-center">';
                    echo '<h5 class="card-title">' . htmlspecialchars($item['nombreProducto']) . '</h5>';
                    echo '<p class="price">$' . number_format($item['precioProducto'], 2) . '</p>';
                    echo '<p>Cantidad: ' . htmlspecialchars($item['cantidad']) . '</p>';
                    echo '<p>Subtotal: $' . number_format($item['precioProducto'] * $item['cantidad'], 2) . '</p>';
                    echo '<form action="../Controllers/CarritoController.php?action=eliminarItem" method="post" class="mt-2">';
                    echo '<input type="hidden" name="carrito_id" value="' . $item['carrito_id'] . '">';
                    echo '<button type="submit" class="btn btn-danger"><i class="fas fa-trash-alt"></i> Eliminar</button>';
                    echo '</form>';
                    echo '</div>';
                    echo '</div>';
                    echo '</div>';
                }
                // Mostrar el total
                echo '<div class="col-12 text-end mt-4">';
                echo '<h4>Total: $' . number_format($total, 2) . '</h4>';
                echo '<a href="pago.php" class="btn btn-primary mt-3 mb-3">Proceder al Pago</a>';
                echo '</div>';
            } else {
                echo '<p class="text-center">Tu carrito está vacío.</p>';
            }
            ?>
        </div>
    </div>

    <!-- Pie de página -->
    <?php include 'footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Ocultar el mensaje automáticamente después de 3 segundos
        setTimeout(() => {
            const alert = document.getElementById('success-alert');
            if (alert) {
                alert.classList.remove('show');
                alert.addEventListener('transitionend', () => alert.remove());
            }
        }, 3000);
    </script>
</body>
</html>
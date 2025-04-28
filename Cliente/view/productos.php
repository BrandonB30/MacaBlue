<?php
session_start();
require_once '../controllers/ProductoController.php';

// Verificar si hay un mensaje de inicio de sesión exitoso
$mensajeLogin = isset($_SESSION['login_success']) ? $_SESSION['login_success'] : '';

// Limpiar el mensaje de la sesión después de recuperarlo
if (isset($_SESSION['login_success'])) {
    unset($_SESSION['login_success']);
}

$subcategoria = isset($_GET['subcategoria']) && !empty(trim($_GET['subcategoria'])) ? trim($_GET['subcategoria']) : null;

if ($subcategoria !== null) {
    $productos = ProductoController::obtenerProductos($subcategoria);
} else {
    $productos = ProductoController::obtenerProductos(); // Mostrar todo
}

$base_url = '/MacaBlue/cliente';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link rel="shortcut icon" href="<?php echo $base_url; ?>/assets/img/favicon.png" type="image/x-icon">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Pedidos</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo $base_url; ?>/assets/css/style.css">
    <link rel="stylesheet" href="<?php echo $base_url; ?>/assets/css/nav.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
<!-- Mensaje de inicio de sesión exitoso -->
<?php if (!empty($mensajeLogin)) : ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'success',
                title: '¡Bienvenido!',
                text: '<?php echo htmlspecialchars($mensajeLogin); ?>',
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
            });
        });
    </script>
<?php endif; ?>
<?php 
if (isset($_GET['mensaje']) && $_GET['mensaje'] == 'login_required') {
    echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'info',
                title: 'Acceso Restringido',
                text: 'Debes iniciar sesión para acceder al carrito',
                confirmButtonText: 'Entendido'
            });
            // Limpiar el parámetro de la URL
            if (window.history.replaceState) {
                const newUrl = window.location.protocol + '//' + window.location.host + window.location.pathname;
                window.history.replaceState({ path: newUrl }, '', newUrl);
            }
        });
    </script>";
}
?>
<?php 
if (isset($_GET['mensaje']) && $_GET['mensaje'] == 'login_required') {
    echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'info',
                title: 'Acceso Restringido',
                text: 'Debes iniciar sesión para acceder al carrito.',
                confirmButtonText: 'Entendido'
            }).then(() => {
                window.location.href = 'productos.php';
            });
        });
    </script>";
}
?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Seleccionar todos los formularios de añadir al carrito
    const addToCartForms = document.querySelectorAll('form[action="../controllers/CarritoController.php?action=agregarProducto"]');
    
    addToCartForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            // Verificar si el usuario está logueado
            const isLoggedIn = <?php echo isset($_SESSION['cliente_id']) ? 'true' : 'false'; ?>;
            
            if (!isLoggedIn) {
                e.preventDefault(); // Detener el envío del formulario
                
                // Mostrar SweetAlert2
                Swal.fire({
                    icon: 'warning',
                    title: 'Acceso denegado',
                    text: 'Debes iniciar sesión para añadir productos al carrito.',
                    confirmButtonText: 'Iniciar sesión',
                    allowOutsideClick: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '../view/ingreso.php';
                    }
                });
            }
            // Si está logueado, el formulario se envía normalmente
        });
    });
});
</script>
<!-- Incluir el archivo de navegación -->
<?php include 'nav.php'; ?>

<div class="container mt-5">
    <div class="row">
        <?php if (!empty($productos)): ?>
            <?php foreach ($productos as $producto): ?>
                <div class="col-md-4 mb-4">
                    <div class="card product-card" data-bs-toggle="modal" data-bs-target="#productModal<?php echo $producto['producto_id']; ?>">
                        <?php
                        $foto = !empty($producto['fotosProducto']) ? '/MacaBlue/Admin/uploads/' . $producto['fotosProducto'] : '/MacaBlue/Admin/uploads/default.jpg';
                        ?>
                        <img src="<?php echo htmlspecialchars($foto); ?>" class="card-img-top product-img" alt="<?php echo htmlspecialchars($producto['nombreProducto']); ?>">
                        <div class="card-body text-center">
                            <h5 class="card-title"><?php echo htmlspecialchars($producto['nombreProducto']); ?></h5>
                            <p class="price">$<?php echo number_format($producto['precioProducto'], 2); ?></p>
                            <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#productModal<?php echo $producto['producto_id']; ?>">Ver Detalles</a>
                        </div>
                    </div>
                </div>

                <!-- Modal -->
                <div class="modal fade" id="productModal<?php echo $producto['producto_id']; ?>" tabindex="-1" aria-labelledby="productModalLabel<?php echo $producto['producto_id']; ?>" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="productModalLabel<?php echo $producto['producto_id']; ?>"><?php echo htmlspecialchars($producto['nombreProducto']); ?></h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <img src="<?php echo htmlspecialchars($foto); ?>" class="img-fluid" alt="<?php echo htmlspecialchars($producto['nombreProducto']); ?>">
                                    </div>
                                    <div class="col-md-6">
                                        <p><strong>Descripción:</strong> <?php echo htmlspecialchars($producto['descripcionProducto']); ?></p>
                                        <p><strong>Color:</strong> <?php echo htmlspecialchars($producto['colorProducto']); ?></p>
                                        <p><strong>Material:</strong> <?php echo htmlspecialchars($producto['materialProducto']); ?></p>
                                        <p><strong>Tallas:</strong> <?php echo htmlspecialchars($producto['tallas']); ?></p>
                                        <p class="price">Precio: $<?php echo number_format($producto['precioProducto'], 2); ?></p>
                                        <form action="../controllers/CarritoController.php?action=agregarProducto" method="POST">
                                            <input type="hidden" name="producto_id" value="<?php echo $producto['producto_id']; ?>">
                                            <div class="mb-3">
                                                <label for="cantidad<?php echo $producto['producto_id']; ?>" class="form-label">Cantidad:</label>
                                                <input type="number" id="cantidad<?php echo $producto['producto_id']; ?>" name="cantidad" class="form-control" min="1" value="1" required>
                                            </div>
                                            <button type="submit" class="btn btn-primary">Añadir al carrito</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-center">No hay productos disponibles en esta subcategoría.</p>
        <?php endif; ?>
    </div>
</div>

<?php include 'footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

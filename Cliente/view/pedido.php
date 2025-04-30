<?php
session_start();
require_once '../controllers/PedidoController.php';

if (!isset($_SESSION['cliente_id'])) {
    echo "<script>
        document.addEventListener('DOMContentLoaded', function () {
            Swal.fire({
                icon: 'warning',
                title: 'Acceso denegado',
                text: 'Debes iniciar sesión para ver tus pedidos.',
                confirmButtonText: 'Iniciar sesión',
                allowOutsideClick: false
            }).then(() => {
                window.location.href = 'ingreso.php';
            });
        });
    </script>";
    exit();
}

$usuario_id = $_SESSION['cliente_id'];
$controller = new PedidoController();

if (isset($_POST['realizar_pedido'])) {
    if ($controller->realizarPedido($usuario_id)) {
        echo "<script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    icon: 'success',
                    title: '¡Pedido realizado!',
                    text: 'Tu pedido se ha realizado exitosamente.',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    position: 'top-end',
                    toast: true
                }).then(() => {
                    window.location.href = 'pedido.php';
                });
            });
        </script>";
    } else {
        echo "<script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'No hay productos en el carrito.',
                    confirmButtonText: 'Aceptar',
                    position: 'center'
                }).then(() => {
                    window.location.href = 'carrito.php';
                });
            });
        </script>";
    }
}

$categorias = $controller->obtenerCategorias();
$pedidos = $controller->obtenerPedidos($usuario_id);
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
    <!-- Incluir el archivo de navegación -->
    <?php include 'nav.php'; ?>

    <div class="container mt-5">
        <h2 class="text-center mb-5" style="color: var(--fucsia-claro);">Mis Pedidos</h2>

        <?php if ($pedidos->num_rows > 0): ?>
            <div class="row">
                <?php while ($pedido = $pedidos->fetch_assoc()): ?>
                    <div class="col-md-6 mb-4">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title">Pedido #<?php echo $pedido['pedido_id']; ?></h5>
                                <p class="card-text"><strong>Fecha:</strong> <?php echo $pedido['fecha_pedido']; ?></p>
                                <p class="card-text"><strong>Total:</strong> $<?php echo number_format($pedido['total'], 2); ?></p>
                                <p class="card-text">
                                    <strong>Estado:</strong> 
                                    <span class="badge <?php echo ($pedido['estado'] == 'Pendiente') ? 'bg-warning' : (($pedido['estado'] == 'Completado') ? 'bg-success' : 'bg-info'); ?>">
                                        <?php echo $pedido['estado']; ?>
                                    </span>
                                </p>
                                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modal<?php echo $pedido['pedido_id']; ?>">
                                    <i class="fas fa-eye"></i> Ver Detalles
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Modal Detalle del Pedido -->
                    <div class="modal fade" id="modal<?php echo $pedido['pedido_id']; ?>" tabindex="-1" aria-labelledby="modalLabel<?php echo $pedido['pedido_id']; ?>" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="modalLabel<?php echo $pedido['pedido_id']; ?>">Detalle del Pedido #<?php echo $pedido['pedido_id']; ?></h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <p><strong>Fecha:</strong> <?php echo $pedido['fecha_pedido']; ?></p>
                                            <p><strong>Estado:</strong> 
                                                <span class="badge <?php echo ($pedido['estado'] == 'Pendiente') ? 'bg-warning' : (($pedido['estado'] == 'Completado') ? 'bg-success' : 'bg-info'); ?>">
                                                    <?php echo $pedido['estado']; ?>
                                                </span>
                                            </p>
                                        </div>
                                        <div class="col-md-6">
                                            <p><strong>Total:</strong> $<?php echo number_format($pedido['total'], 2); ?></p>
                                        </div>
                                    </div>
                                    <h6 class="border-bottom pb-2 mb-3">Productos</h6>
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Producto</th>
                                                <th>Cantidad</th>
                                                <th>Precio</th>
                                                <th>Subtotal</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $detalles = $controller->obtenerDetallesPedido($pedido['pedido_id']);
                                            while ($detalle = $detalles->fetch_assoc()):
                                                $foto = !empty($detalle['fotosProducto']) ? '../Admin/uploads/' . $detalle['fotosProducto'] : '../Admin/uploads/default.jpg';
                                            ?>
                                                <tr>
                                                    <td class="d-flex align-items-center">
                                                        <img src="<?php echo htmlspecialchars($foto); ?>" alt="<?php echo htmlspecialchars($detalle['nombreProducto']); ?>" class="img-thumbnail me-2" style="width: 50px; height: 50px; object-fit: cover;">
                                                        <?php echo htmlspecialchars($detalle['nombreProducto']); ?>
                                                    </td>
                                                    <td><?php echo $detalle['cantidad']; ?></td>
                                                    <td>$<?php echo number_format($detalle['precio'], 2); ?></td>
                                                    <td>$<?php echo number_format($detalle['subtotal'], 2); ?></td>
                                                </tr>
                                            <?php endwhile; ?>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <div class="card shadow-sm">
                <div class="card-body text-center py-5">
                    <i class="fas fa-shopping-bag fa-4x mb-3" style="color: var(--fucsia-claro);"></i>
                    <h4>No has realizado ningún pedido todavía</h4>
                    <p class="text-muted">¡Explora nuestra tienda y encuentra productos increíbles!</p>
                    <a href="/MacaBlue/Cliente/view/productos.php" class="btn btn-primary mt-3">Ir a comprar</a>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <?php include 'footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Limpiar el parámetro ?success=1 de la URL
        if (window.history.replaceState) {
            const newUrl = window.location.protocol + "//" + window.location.host + window.location.pathname;
            window.history.replaceState({ path: newUrl }, '', newUrl);
        }
    </script>
</body>
</html>
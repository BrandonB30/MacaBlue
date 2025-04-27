<?php
session_start();
require_once '../controllers/PagoController.php';

// Verificar si el usuario está autenticado
if (!isset($_SESSION['cliente_id'])) {
    echo "<script>
        document.addEventListener('DOMContentLoaded', function () {
            Swal.fire({
                icon: 'warning',
                title: 'Acceso denegado',
                text: 'Debes iniciar sesión para realizar el pago.',
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
$pagoController = new PagoController();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirmar_pago'])) {
    $direccion = $_POST['direccion'];
    $metodo_pago = $_POST['metodo_pago'];
    $pagoController->procesarPago($usuario_id, $direccion, $metodo_pago);
}

// Obtener productos del carrito para mostrar en la vista
$productos = $pagoController->carritoModel->getCartItems($usuario_id);
$total = $pagoController->calcularTotal($productos);
$base_url = '/MacaBlue/cliente';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link rel="shortcut icon" href="<?php echo $base_url; ?>/assets/img/favicon.png" type="image/x-icon">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultados de Búsqueda - <?php echo htmlspecialchars($query); ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo $base_url; ?>/assets/css/style.css">
    <link rel="stylesheet" href="<?php echo $base_url; ?>/assets/css/nav.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-custom" style="background-color: var(--fondo-oscuro);">
        <div class="container-fluid">
            <a class="navbar-brand" href="/MacaBlue/view/productos.php" style="color: var(--fucsia-claro); font-weight: bold;">MacaBlue</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="/MacaBlue/view/productos.php">Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/MacaBlue/view/sobre_nosotros.php">Sobre Nosotros</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/MacaBlue/view/contacto.php">Contacto</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="row">
            <div class="col-12">
                <h2 class="text-center mb-4" style="color: var(--fucsia-claro);">Finalizar Compra</h2>
                <div class="progress mb-4">
                    <div class="progress-bar" role="progressbar" style="width: 80%; background-color: var(--fucsia-claro);" 
                         aria-valuenow="80" aria-valuemin="0" aria-valuemax="100">Paso 2 de 2: Pago</div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Formulario de pago -->
            <div class="col-md-8">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0"><i class="fas fa-map-marker-alt me-2"></i>Datos de envío</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="" id="payment-form">
                            <!-- Información de envío -->
                            <div class="mb-4">
                                <div class="mb-3">
                                    <label for="direccion" class="form-label">Dirección de envío</label>
                                    <textarea class="form-control" id="direccion" name="direccion" rows="3" required></textarea>
                                </div>
                            </div>

                            <!-- Métodos de pago -->
                            <div class="mb-4">
                                <h5 class="mb-3"><i class="fas fa-credit-card me-2"></i>Método de pago</h5>

                                <div class="payment-method" onclick="selectPaymentMethod('tarjeta')">
                                    <input type="radio" name="metodo_pago" value="tarjeta" id="tarjeta" required>
                                    <label for="tarjeta" class="ms-2">
                                        <span class="d-flex align-items-center">
                                            <i class="fab fa-cc-visa me-2 fa-lg text-primary"></i>
                                            <i class="fab fa-cc-mastercard me-2 fa-lg text-danger"></i>
                                            <span>Tarjeta de crédito/débito</span>
                                        </span>
                                    </label>
                                </div>

                                <div class="payment-method" onclick="selectPaymentMethod('paypal')">
                                    <input type="radio" name="metodo_pago" value="paypal" id="paypal">
                                    <label for="paypal" class="ms-2">
                                        <span class="d-flex align-items-center">
                                            <i class="fab fa-paypal me-2 fa-lg text-primary"></i>
                                            <span>PayPal</span>
                                        </span>
                                    </label>
                                </div>

                                <div class="payment-method" onclick="selectPaymentMethod('transferencia')">
                                    <input type="radio" name="metodo_pago" value="transferencia" id="transferencia">
                                    <label for="transferencia" class="ms-2">
                                        <span class="d-flex align-items-center">
                                            <i class="fas fa-university me-2 fa-lg"></i>
                                            <span>Transferencia bancaria</span>
                                        </span>
                                    </label>
                                </div>
                            </div>

                            <!-- Botones de acción -->
                            <div class="d-flex justify-content-between mt-4">
                                <a href="carrito.php" class="btn btn-outline-secondary">
                                    <i class="fas fa-arrow-left me-2"></i>Volver al carrito
                                </a>
                                <button type="submit" name="confirmar_pago" class="btn btn-primary">
                                    <i class="fas fa-check-circle me-2"></i>Confirmar pago
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Resumen del pedido -->
            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-light">
                        <h5 class="mb-0"><i class="fas fa-shopping-cart me-2"></i>Resumen del pedido</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <?php if (count($productos) > 0): ?>
                                <div class="mb-3">
                                    <?php foreach ($productos as $producto): 
                                        $subtotal = $producto['cantidad'] * $producto['precioProducto'];
                                        $foto = !empty($producto['fotosProducto']) ? '../Admin/uploads/' . $producto['fotosProducto'] : '../Admin/uploads/default.jpg';
                                    ?>
                                        <div class="d-flex mb-2 pb-2 border-bottom">
                                            <img src="<?php echo htmlspecialchars($foto); ?>" alt="<?php echo htmlspecialchars($producto['nombreProducto']); ?>" 
                                                 class="img-thumbnail me-2" style="width: 50px; height: 50px; object-fit: cover;">
                                            <div class="flex-grow-1">
                                                <div class="d-flex justify-content-between">
                                                    <span><?php echo htmlspecialchars($producto['nombreProducto']); ?></span>
                                                    <span class="text-end">$<?php echo number_format($subtotal, 2); ?></span>
                                                </div>
                                                <small class="text-muted">Cantidad: <?php echo $producto['cantidad']; ?> 
                                                x $<?php echo number_format($producto['precioProducto'], 2); ?></small>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <p class="text-center">No hay productos en el carrito</p>
                            <?php endif; ?>
                        </div>

                        <div class="border-top pt-3">
                            <div class="d-flex justify-content-between mb-2">
                                <span>Subtotal:</span>
                                <span>$<?php echo number_format($total, 2); ?></span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Envío:</span>
                                <span>Gratis</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2 fw-bold">
                                <span>Total:</span>
                                <span>$<?php echo number_format($total, 2); ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include 'footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function selectPaymentMethod(method) {
            // Eliminar clase selected de todos los métodos de pago
            document.querySelectorAll('.payment-method').forEach(el => {
                el.classList.remove('selected');
            });
            
            // Marcar el método seleccionado
            document.getElementById(method).checked = true;
            document.getElementById(method).closest('.payment-method').classList.add('selected');
        }

        // Validación del formulario con SweetAlert2
        document.getElementById('payment-form').addEventListener('submit', function(event) {
            let direccion = document.getElementById('direccion').value;
            let metodoPago = document.querySelector('input[name="metodo_pago"]:checked');

            if (!direccion.trim()) {
                event.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title: 'Dirección requerida',
                    text: 'Por favor, ingresa una dirección de envío.',
                    confirmButtonText: 'Aceptar',
                    position: 'center'
                });
                return false;
            }

            if (!metodoPago) {
                event.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title: 'Método de pago requerido',
                    text: 'Por favor, selecciona un método de pago.',
                    confirmButtonText: 'Aceptar',
                    position: 'center'
                });
                return false;
            }

            return true;
        });

        // Limpiar el parámetro ?success=1 de la URL
        if (window.history.replaceState) {
            const newUrl = window.location.protocol + "//" + window.location.host + window.location.pathname;
            window.history.replaceState({ path: newUrl }, '', newUrl);
        }
    </script>
</body>
</html>
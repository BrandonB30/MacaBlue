<?php
// Incluir archivos de configuración
require_once '../Admin/config/conexion.php';
require_once '../Admin/model/CartModel.php';

// Iniciar sesión si no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['cliente_id'])) {
    echo "<script>alert('Debes iniciar sesión para ver tu carrito'); window.location.href = 'ingreso.php';</script>";
    exit();
}

// Crear instancia de la base de datos
$database = new Database();
$conn = $database->getConnection();

// Crear instancia del modelo del carrito
$cartModel = new CartModel($conn);

// Obtener datos del carrito
$userId = $_SESSION['cliente_id'];
$cartItems = $cartModel->getCartItems($userId);

// Calcular el total
$total = $cartModel->calculateTotal($cartItems);

// Continuar con el resto del código de la vista
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrito de Compras - MacaBlue</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="/MacaBlue/assets/css/style.css">
</head>
<body>
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
                    $foto = !empty($item['fotosProducto']) ? '../Admin/uploads/' . htmlspecialchars($item['fotosProducto']) : '../Admin/uploads/default.jpg';
                    echo '<img src="' . $foto . '" class="card-img-top product-img" alt="' . htmlspecialchars($item['nombreProducto']) . '">';
                    echo '<div class="card-body text-center">';
                    echo '<h5 class="card-title">' . htmlspecialchars($item['nombreProducto']) . '</h5>';
                    echo '<p class="price">$' . number_format($item['precioProducto'], 2) . '</p>';
                    echo '<p>Cantidad: ' . htmlspecialchars($item['cantidad']) . '</p>';
                    echo '<p>Subtotal: $' . number_format($item['precioProducto'] * $item['cantidad'], 2) . '</p>';
                    echo '<form action="eliminar_del_carrito.php" method="post" class="mt-2">';
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
</body>
</html>
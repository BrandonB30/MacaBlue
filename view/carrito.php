<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once '../Admin/config/conexion.php'; // Incluir la clase de conexión

// Crear una instancia de la conexión a la base de datos
$database = new Database();
$conn = $database->getConnection();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['cliente_id'])) {
    echo "<script>alert('Debes iniciar sesión para ver tu carrito'); window.location.href = 'ingreso.php';</script>";
    exit();
}

// Obtener el ID del cliente desde la sesión
$cliente_id = $_SESSION['cliente_id'];

// Consultar los productos en el carrito del usuario
$sql = "SELECT productos.*, carrito.cantidad FROM carrito 
        JOIN productos ON carrito.producto_id = productos.producto_id 
        WHERE carrito.usuario_id = :cliente_id";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':cliente_id', $cliente_id, PDO::PARAM_INT);
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Calcular el total
$total = 0;
foreach ($result as $row) {
    $total += $row['precioProducto'] * $row['cantidad'];
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrito de Compras - MacaBlue</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
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
            if (count($result) > 0) {
                foreach ($result as $row) {
                    echo '<div class="col-md-4 mb-4">';
                    echo '<div class="card product-card">';
                    $foto = !empty($row['fotosProducto']) ? '../Admin/uploads/' . htmlspecialchars($row['fotosProducto']) : '../Admin/uploads/default.jpg';
                    echo '<img src="' . $foto . '" class="card-img-top product-img" alt="' . htmlspecialchars($row['nombreProducto']) . '">';
                    echo '<div class="card-body text-center">';
                    echo '<h5 class="card-title">' . htmlspecialchars($row['nombreProducto']) . '</h5>';
                    echo '<p class="price">$' . number_format($row['precioProducto'], 2) . '</p>';
                    echo '<p>Cantidad: ' . htmlspecialchars($row['cantidad']) . '</p>';
                    echo '<p>Subtotal: $' . number_format($row['precioProducto'] * $row['cantidad'], 2) . '</p>';
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

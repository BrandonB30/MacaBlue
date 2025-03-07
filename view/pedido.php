<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "macablue";

// Conectar a la base de datos
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Error en la conexión: " . $conn->connect_error);
}

// Consultar las categorías y subcategorías para el menú
$sqlCategorias = "SELECT nombreCategoria, subcategorias FROM categorias";
$resultCategorias = $conn->query($sqlCategorias);

// Verificar si el usuario está autenticado
if (!isset($_SESSION['cliente_id'])) {
    echo "<script>alert('Debes iniciar sesión para ver tus pedidos'); window.location.href = 'ingreso.php';</script>";
    exit();
}

$usuario_id = $_SESSION['cliente_id'];

// **1. Crear un pedido a partir del carrito**
if (isset($_POST['realizar_pedido'])) {
    // Obtener productos en el carrito
    $sqlCarrito = "SELECT producto_id, cantidad, p.precioProducto FROM carrito 
                   JOIN productos p ON carrito.producto_id = p.producto_id
                   WHERE usuario_id = ?";
    $stmt = $conn->prepare($sqlCarrito);
    $stmt->bind_param("i", $usuario_id);
    $stmt->execute();
    $resultCarrito = $stmt->get_result();

    if ($resultCarrito->num_rows > 0) {
        // Calcular total
        $total = 0;
        $productos = [];

        while ($row = $resultCarrito->fetch_assoc()) {
            $subtotal = $row['cantidad'] * $row['precioProducto'];
            $total += $subtotal;
            $productos[] = $row;
        }

        // Insertar pedido
        $sqlPedido = "INSERT INTO pedidos (usuario_id, total) VALUES (?, ?)";
        $stmt = $conn->prepare($sqlPedido);
        $stmt->bind_param("id", $usuario_id, $total);
        $stmt->execute();
        $pedido_id = $stmt->insert_id;

        // Insertar detalles del pedido
        $sqlDetalle = "INSERT INTO detalle_pedido (pedido_id, producto_id, cantidad, precio) VALUES (?, ?, ?, ?)";
        $stmtDetalle = $conn->prepare($sqlDetalle);

        foreach ($productos as $producto) {
            $stmtDetalle->bind_param("iiid", $pedido_id, $producto['producto_id'], $producto['cantidad'], $producto['precioProducto']);
            $stmtDetalle->execute();
        }

        // Vaciar el carrito
        $sqlVaciarCarrito = "DELETE FROM carrito WHERE usuario_id = ?";
        $stmt = $conn->prepare($sqlVaciarCarrito);
        $stmt->bind_param("i", $usuario_id);
        $stmt->execute();

        echo "<script>alert('Pedido realizado exitosamente'); window.location.href = 'pedido.php';</script>";
    } else {
        echo "<script>alert('No hay productos en el carrito'); window.location.href = 'carrito.php';</script>";
    }
}

// **2. Consultar pedidos del usuario**
$sqlPedidos = "SELECT * FROM pedidos WHERE usuario_id = ? ORDER BY fecha_pedido DESC";
$stmt = $conn->prepare($sqlPedidos);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$resultPedidos = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Pedidos - MacaBlue</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/MacaBlue/assets/css/style.css">
    <link rel="stylesheet" href="/MacaBlue/assets/css/nav.css">
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
                    <?php
                    if ($resultCategorias->num_rows > 0) {
                        while ($row = $resultCategorias->fetch_assoc()) {
                            $categoria = $row['nombreCategoria'];
                            $subcategorias = explode(',', $row['subcategorias']);

                            echo '<li class="nav-item dropdown">';
                            echo '<a class="nav-link dropdown-toggle" href="#" id="dropdown' . htmlspecialchars($categoria) . '" role="button" data-bs-toggle="dropdown" aria-expanded="false">';
                            echo htmlspecialchars($categoria);
                            echo '</a>';
                            echo '<ul class="dropdown-menu" aria-labelledby="dropdown' . htmlspecialchars($categoria) . '">';
                            foreach ($subcategorias as $subcategoria) {
                                $subcategoria = trim($subcategoria);
                                echo '<li><a class="dropdown-item" href="/MacaBlue/view/productos.php?subcategoria=' . urlencode($subcategoria) . '">' . htmlspecialchars($subcategoria) . '</a></li>';
                            }
                            echo '</ul></li>';
                        }
                    } else {
                        echo '<li><a class="nav-link" href="#">No hay categorías</a></li>';
                    }
                    ?>
                    <li class="nav-item">
                        <a class="nav-link" href="/MacaBlue/view/sobre_nosotros.php">Sobre Nosotros</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/MacaBlue/view/contacto.php">Contacto</a>
                    </li>
                </ul>

                <!-- Barra de búsqueda centrada -->
                <form class="d-flex mx-5" action="/MacaBlue/view/buscar.php" method="get">
                    <input class="form-control me-2" type="search" placeholder="Buscar" aria-label="Buscar" name="query">
                    <button class="btn btn-outline-success" type="submit">Buscar</button>
                </form>

                <!-- Icono de carrito -->
                <a class="nav-link me-3" href="/MacaBlue/view/carrito.php" style="color: var(--fondo-claro);">
                    <i class="fas fa-shopping-cart"></i>
                </a>
                
                <!-- Icono de pedidos -->
                <a class="nav-link me-3" href="/MacaBlue/view/pedido.php" style="color: var(--fondo-claro);">
                    <i class="fas fa-box"></i> <!-- Ícono de caja de pedidos -->
                </a>

                <!-- Menú desplegable para usuario logueado -->
                <?php if (isset($_SESSION['cliente_id'])) : ?>
                    <div class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false" style="color: var(--fucsia-claro); font-weight: bold;">
                            <i class="fas fa-user"></i> <?php echo htmlspecialchars($_SESSION['nombreUsuario']) . ' ' . htmlspecialchars($_SESSION['apellidoUsuario']); ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <li><a class="dropdown-item" href="/MacaBlue/view/perfil.php">Perfil</a></li>
                            <li><a class="dropdown-item" href="/MacaBlue/view/logout.php">Cerrar sesión</a></li>
                        </ul>
                    </div>
                <?php else : ?>
                    <a class="nav-link" href="/MacaBlue/view/ingreso.php" style="color: var(--fucsia-claro); font-weight: bold;">
                        <i class="fas fa-user"></i> Iniciar sesión
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <h2 class="text-center mb-5" style="color: var(--fucsia-claro);">Mis Pedidos</h2>

        <?php if ($resultPedidos->num_rows > 0): ?>
            <div class="card shadow-sm">
                <div class="card-body">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID Pedido</th>
                                <th>Fecha</th>
                                <th>Total</th>
                                <th>Estado</th>
                                <th>Detalles</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($pedido = $resultPedidos->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $pedido['pedido_id']; ?></td>
                                    <td><?php echo $pedido['fecha_pedido']; ?></td>
                                    <td>$<?php echo number_format($pedido['total'], 2); ?></td>
                                    <td>
                                        <span class="badge <?php echo ($pedido['estado'] == 'Pendiente') ? 'bg-warning' : (($pedido['estado'] == 'Completado') ? 'bg-success' : 'bg-info'); ?>">
                                            <?php echo $pedido['estado']; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modal<?php echo $pedido['pedido_id']; ?>">
                                            <i class="fas fa-eye"></i> Ver
                                        </button>
                                    </td>
                                </tr>

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
                                                        <p><strong>Número de Pedido:</strong> #<?php echo $pedido['pedido_id']; ?></p>
                                                        <p><strong>Fecha:</strong> <?php echo $pedido['fecha_pedido']; ?></p>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <p><strong>Estado:</strong> 
                                                            <span class="badge <?php echo ($pedido['estado'] == 'Pendiente') ? 'bg-warning' : (($pedido['estado'] == 'Completado') ? 'bg-success' : 'bg-info'); ?>">
                                                                <?php echo $pedido['estado']; ?>
                                                            </span>
                                                        </p>
                                                        <p><strong>Total:</strong> $<?php echo number_format($pedido['total'], 2); ?></p>
                                                    </div>
                                                </div>
                                                <h6 class="border-bottom pb-2 mb-3">Productos</h6>
                                                <table class="table">
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
                                                        $sqlDetalles = "SELECT p.nombreProducto, p.fotosProducto, d.cantidad, d.precio, (d.cantidad * d.precio) AS subtotal 
                                                                        FROM detalle_pedido d 
                                                                        JOIN productos p ON d.producto_id = p.producto_id 
                                                                        WHERE d.pedido_id = ?";
                                                        $stmtDetalles = $conn->prepare($sqlDetalles);
                                                        $stmtDetalles->bind_param("i", $pedido['pedido_id']);
                                                        $stmtDetalles->execute();
                                                        $resultDetalles = $stmtDetalles->get_result();

                                                        while ($detalle = $resultDetalles->fetch_assoc()):
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
                                                    <tfoot>
                                                        <tr>
                                                            <td colspan="3" class="text-end"><strong>Total:</strong></td>
                                                            <td><strong>$<?php echo number_format($pedido['total'], 2); ?></strong></td>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php else: ?>
            <div class="card shadow-sm">
                <div class="card-body text-center py-5">
                    <i class="fas fa-shopping-bag fa-4x mb-3" style="color: var(--fucsia-claro);"></i>
                    <h4>No has realizado ningún pedido todavía</h4>
                    <p class="text-muted">¡Explora nuestra tienda y encuentra productos increíbles!</p>
                    <a href="/MacaBlue/view/productos.php" class="btn btn-primary mt-3">Ir a comprar</a>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <?php include 'footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php $conn->close(); ?>
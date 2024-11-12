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

// Consultar las categorías y subcategorías
$sql = "SELECT nombreCategoria, subcategorias FROM categorias";
$resultCategorias = $conn->query($sql);

// Función para agregar productos al carrito
if (isset($_POST['agregar_al_carrito'])) {
    $producto_id = $_POST['producto_id'];
    $cantidad = $_POST['cantidad'];
    $usuario_id = $_SESSION['cliente_id'] ?? null;

    if (!$usuario_id) {
        echo "<script>alert('Debes iniciar sesión para agregar productos al carrito'); window.location.href = 'ingreso.php';</script>";
        exit();
    }

    $sql = "INSERT INTO carrito (usuario_id, producto_id, cantidad) VALUES (?, ?, ?)
            ON DUPLICATE KEY UPDATE cantidad = cantidad + VALUES(cantidad)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iii", $usuario_id, $producto_id, $cantidad);
    $stmt->execute();
    $stmt->close();

    echo "<script>alert('Producto agregado al carrito');</script>";
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tienda de Ropa Online</title>
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

    <?php
    // Obtener la subcategoría desde la URL y limpiar espacios
    $subcategoria = isset($_GET['subcategoria']) ? trim($conn->real_escape_string($_GET['subcategoria'])) : null;

    if ($subcategoria) {
        $sqlProductos = "SELECT * FROM productos WHERE estadoProducto = 'Disponible' AND subcategoriaProducto = '$subcategoria'";
    } else {
        $sqlProductos = "SELECT * FROM productos WHERE estadoProducto = 'Disponible'";
    }

    // Ejecutar la consulta
    $resultProductos = $conn->query($sqlProductos);
    ?>

    <div class="container mt-5">
        <h1 class="text-center mb-5" style="color: var(--fucsia-claro);">
            <?php echo htmlspecialchars($subcategoria ?? 'Productos Destacados'); ?>
        </h1>
        <div class="row">
            <?php
            if ($resultProductos === false) {
                echo "<p>Error en la consulta: " . $conn->error . "</p>";
            } elseif ($resultProductos->num_rows > 0) {
                while ($row = $resultProductos->fetch_assoc()) {
                    echo '<div class="col-md-4 mb-4">';
                    echo '<div class="card product-card" data-bs-toggle="modal" data-bs-target="#productModal' . $row['producto_id'] . '">';

                    $foto = !empty($row['fotosProducto']) ? '../Admin/uploads/' . $row['fotosProducto'] : '../Admin/uploads/default.jpg';
                    echo '<img src="' . htmlspecialchars($foto) . '" class="card-img-top product-img" alt="' . htmlspecialchars($row['nombreProducto']) . '">';

                    echo '<div class="card-body text-center">';
                    echo '<h5 class="card-title">' . htmlspecialchars($row['nombreProducto']) . '</h5>';
                    echo '<p class="price">$' . number_format($row['precioProducto'], 2) . '</p>';
                    echo '<a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#productModal' . $row['producto_id'] . '">Ver Detalles</a>';
                    echo '</div>';
                    echo '</div>';
                    echo '</div>';

                    // Modal para mostrar detalles del producto y agregar al carrito
                    echo '<div class="modal fade" id="productModal' . $row['producto_id'] . '" tabindex="-1" aria-labelledby="productModalLabel' . $row['producto_id'] . '" aria-hidden="true">';
                    echo '<div class="modal-dialog modal-lg">';
                    echo '<div class="modal-content">';
                    echo '<div class="modal-header">';
                    echo '<h5 class="modal-title" id="productModalLabel' . $row['producto_id'] . '">' . htmlspecialchars($row['nombreProducto']) . '</h5>';
                    echo '<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>';
                    echo '</div>';

                    echo '<div class="modal-body">';
                    echo '<div class="row">';
                    echo '<div class="col-md-6">';
                    echo '<img src="' . htmlspecialchars($foto) . '" class="img-fluid" alt="' . htmlspecialchars($row['nombreProducto']) . '">';
                    echo '</div>';
                    echo '<div class="col-md-6">';
                    echo '<p><strong>Descripción:</strong> ' . htmlspecialchars($row['descripcionProducto']) . '</p>';
                    echo '<p><strong>Color:</strong> ' . htmlspecialchars($row['colorProducto']) . '</p>';
                    echo '<p><strong>Material:</strong> ' . htmlspecialchars($row['materialProducto']) . '</p>';
                    echo '<p><strong>Tallas:</strong> ' . htmlspecialchars($row['tallas']) . '</p>';
                    echo '<p class="price">Precio: $' . number_format($row['precioProducto'], 2) . '</p>';

                    // Formulario para añadir al carrito con selección de cantidad
                    echo '<form method="POST" action="">';
                    echo '<input type="hidden" name="producto_id" value="' . $row['producto_id'] . '">';
                    echo '<div class="mb-3">';
                    echo '<label for="cantidad' . $row['producto_id'] . '" class="form-label">Cantidad:</label>';
                    echo '<input type="number" id="cantidad' . $row['producto_id'] . '" name="cantidad" class="form-control" min="1" value="1" required>';
                    echo '</div>';
                    echo '<button type="submit" name="agregar_al_carrito" class="btn btn-primary">Añadir al carrito</button>';
                    echo '</form>';
                    echo '</div>';
                    echo '</div>';
                    echo '</div>';

                    echo '<div class="modal-footer">';
                    echo '<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>';
                    echo '</div>';
                    echo '</div>';
                    echo '</div>';
                    echo '</div>';
                }
            } else {
                echo '<p class="text-center">No hay productos disponibles en esta subcategoría.</p>';
            }
            ?>
        </div>
    </div>

    <?php include 'footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

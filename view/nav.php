<?php


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
    <style>
        /* Ajustes de la barra de búsqueda y colores del navbar */
        .navbar .form-control {
            width: 250px;
            transition: none;
        }
        .navbar .nav-link {
            color: var(--fondo-claro);
            transition: color 0s;
        }
        .navbar .nav-link:hover {
            color: var(--fucsia-claro);
        }
    </style>
</head>

<body>
    <!-- Navegación -->
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
                <form class="d-flex mx-4" action="/MacaBlue/view/buscar.php" method="get">
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
</body>
</html>

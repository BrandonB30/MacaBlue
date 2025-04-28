<?php
require_once dirname(__DIR__) . '/Controllers/CategoriaController.php'; // Incluir el controlador de categorías

$categoriaController = new CategoriaController();
$categorias = $categoriaController->obtenerCategorias(); // Obtener las categorías y subcategorías
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
    <!-- Navegación -->
    <nav class="navbar navbar-expand-lg navbar-custom" style="background-color: var(--fondo-oscuro);">
        <div class="container-fluid">
            <a class="navbar-brand" href="/MacaBlue/Cliente/view/productos.php" style="color: var(--fucsia-claro); font-weight: bold;">MacaBlue</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="/MacaBlue/Cliente/view/productos.php">Inicio</a>
                    </li>
                    <?php if (!empty($categorias)) : ?>
                        <?php foreach ($categorias as $categoria) : ?>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="dropdown<?php echo htmlspecialchars($categoria['nombreCategoria']); ?>" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <?php echo htmlspecialchars($categoria['nombreCategoria']); ?>
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="dropdown<?php echo htmlspecialchars($categoria['nombreCategoria']); ?>">
                                    <?php foreach ($categoria['subcategorias'] as $subcategoria) : ?>
                                        <li><a class="dropdown-item" href="/MacaBlue/Cliente/view/productos.php?subcategoria=<?php echo urlencode($subcategoria); ?>"><?php echo htmlspecialchars($subcategoria); ?></a></li>
                                    <?php endforeach; ?>
                                </ul>
                            </li>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <li><a class="nav-link" href="#">No hay categorías</a></li>
                    <?php endif; ?>
                    <li class="nav-item">
                        <a class="nav-link" href="/MacaBlue/Cliente/view/sobre_nosotros.php">Sobre Nosotros</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/MacaBlue/Cliente/view/contacto.php">Contacto</a>
                    </li>
                </ul>

                <!-- Barra de búsqueda centrada -->
                <form class="d-flex mx-4" action="/MacaBlue/Cliente/view/buscar.php" method="get">
                    <input class="form-control me-2" type="search" placeholder="Buscar" aria-label="Buscar" name="query">
                    <button class="btn btn-outline-success" type="submit">Buscar</button>
                </form>

                <!-- Icono de pedidos -->
                <?php if (isset($_SESSION['cliente_id'])): ?>
                    <a class="nav-link me-3" href="/MacaBlue/Cliente/view/pedido.php" style="color: var(--fondo-claro);">
                        <i class="fas fa-shopping-cart"></i>
                    </a>
                <?php else: ?>
                    <a class="nav-link me-3" href="#" onclick="mostrarAlertaNoLogueado1()" style="color: var(--fondo-claro);">
                        <i class="fas fa-shopping-cart"></i>
                    </a>
                <?php endif; ?>

                <!-- Icono de carrito -->
                <?php if (isset($_SESSION['cliente_id'])): ?>
                    <a class="nav-link me-3" href="/MacaBlue/Cliente/view/carrito.php" style="color: var(--fondo-claro);">
                        <i class="fas fa-shopping-cart"></i>
                    </a>
                <?php else: ?>
                    <a class="nav-link me-3" href="#" onclick="mostrarAlertaNoLogueado()" style="color: var(--fondo-claro);">
                        <i class="fas fa-shopping-cart"></i>
                    </a>
                <?php endif; ?>

                <!-- Menú desplegable para usuario logueado -->
                <?php if (isset($_SESSION['cliente_id'])) : ?>
                    <div class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false" style="color: var(--fucsia-claro); font-weight: bold;">
                            <i class="fas fa-user"></i> <?php echo htmlspecialchars($_SESSION['nombreUsuario']) . ' ' . htmlspecialchars($_SESSION['apellidoUsuario']); ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <li><a class="dropdown-item" href="/MacaBlue/Cliente/view/perfil.php">Perfil</a></li>
                            <li><a class="dropdown-item" href="/MacaBlue/Cliente/view/logout.php">Cerrar sesión</a></li>
                        </ul>
                    </div>
                <?php else : ?>
                    <a class="nav-link" href="/MacaBlue/cliente/view/ingreso.php" style="color: var(--fucsia-claro); font-weight: bold;">
                        <i class="fas fa-user"></i> Iniciar sesión
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </nav>
            <script>
            function mostrarAlertaNoLogueado() {
                Swal.fire({
                icon: 'warning',
                title: '¡Debes iniciar sesión!',
                text: 'Por favor, ingresa al sistema para ver tu carrito.',
                confirmButtonText: 'Ir a Iniciar Sesión',
                confirmButtonColor: '#3085d6',
                showCancelButton: true,
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '/MacaBlue/cliente/view/ingreso.php'; // Redirige al login
                }
            });
        }
        </script>
        <script>
            function mostrarAlertaNoLogueado1() {
                Swal.fire({
                icon: 'warning',
                title: '¡Debes iniciar sesión!',
                text: 'Por favor, ingresa al sistema para ver tus pedidos.',
                confirmButtonText: 'Ir a Iniciar Sesión',
                confirmButtonColor: '#3085d6',
                showCancelButton: true,
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '/MacaBlue/cliente/view/ingreso.php'; // Redirige al login
                }
            });
        }
</script>
</body>
</html>

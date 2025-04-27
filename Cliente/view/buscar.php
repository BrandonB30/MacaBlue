<?php
// Incluir el controlador - usando ruta relativa desde /MacaBlue/cliente/view/
require_once dirname(__DIR__) . '/Controllers/ProductoController.php';

// Obtener el término de búsqueda y limpiar espacios
$query = isset($_GET['query']) ? trim($_GET['query']) : '';

// Inicializar el controlador y obtener los resultados
$productoController = new ProductoController();
$productos = $productoController->buscar($query);

// Definir base URL para enlaces
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
</head>

<body>
    <?php include dirname(__DIR__) . '/view/nav.php'; ?>

    <div class="container mt-5">
        <h1 class="text-center mb-5" style="color: var(--fucsia-claro);">
            Resultados para: <?php echo htmlspecialchars($query); ?>
        </h1>
        <div class="row">
            <?php
            // Solo mostrar resultados si hay productos
            if (empty($productos) && !empty($query)) {
                echo '<p class="text-center">No se encontraron productos para "' . htmlspecialchars($query) . '".</p>';
            } elseif (empty($query)) {
                echo '<p class="text-center">Por favor, ingresa un término de búsqueda.</p>';
            } else {
                foreach ($productos as $row) {
                    echo '<div class="col-md-4 mb-4">';
                    echo '<div class="card product-card" data-bs-toggle="modal" data-bs-target="#productModal' . $row['producto_id'] . '">';

                    $foto = !empty($row['fotosProducto']) ? $base_url . '/../Admin/uploads/' . $row['fotosProducto'] : $base_url . '/../Admin/uploads/default.jpg';
                    echo '<img src="' . htmlspecialchars($foto) . '" class="card-img-top product-img" alt="' . htmlspecialchars($row['nombreProducto']) . '">';

                    echo '<div class="card-body text-center">';
                    echo '<h5 class="card-title">' . htmlspecialchars($row['nombreProducto']) . '</h5>';
                    echo '<p class="price">$' . number_format($row['precioProducto'], 2) . '</p>';
                    echo '<a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#productModal' . $row['producto_id'] . '">Ver Detalles</a>';
                    echo '</div>';
                    echo '</div>';
                    echo '</div>';

                    // Modal para mostrar detalles del producto
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
                    echo '</div>';
                    echo '</div>';
                    echo '</div>';

                    echo '<div class="modal-footer">';
                    echo '<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>';
                    echo '<button type="button" class="btn btn-primary">Añadir al carrito</button>';
                    echo '</div>';
                    echo '</div>';
                    echo '</div>';
                    echo '</div>';
                }
            }
            ?>
        </div>
    </div>

    <?php include dirname(__DIR__) . '/view/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
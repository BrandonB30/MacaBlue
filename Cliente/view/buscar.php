<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link rel="shortcut icon" href="./assets/img/favicon.png" type="image/x-icon">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultados de Búsqueda - <?php echo htmlspecialchars($_GET['query']); ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/MacaBlue/assets/css/style.css">
    <link rel="stylesheet" href="/MacaBlue/assets/css/nav.css">
</head>

<body>
    <?php include 'nav.php'; ?>

    <div class="container mt-5">
        <h1 class="text-center mb-5" style="color: var(--fucsia-claro);">
            Resultados para: <?php echo htmlspecialchars($_GET['query']); ?>
        </h1>
        <div class="row">
            <?php if (empty($productos)) : ?>
                <p class="text-center">No se encontraron productos para "<?php echo htmlspecialchars($_GET['query']); ?>".</p>
            <?php else : ?>
                <?php foreach ($productos as $producto) : ?>
                    <div class="col-md-4 mb-4">
                        <div class="card product-card" data-bs-toggle="modal" data-bs-target="#productModal<?php echo $producto['producto_id']; ?>">

                            <?php
                            $foto = !empty($producto['fotosProducto']) ? '../Admin/uploads/' . $producto['fotosProducto'] : '../Admin/uploads/default.jpg';
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
                                        </div>
                                    </div>
                                </div>

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                    <button type="button" class="btn btn-primary">Añadir al carrito</button>
                                </div>

                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <?php include 'footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

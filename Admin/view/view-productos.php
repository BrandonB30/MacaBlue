<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Macablue | Admin - Gestión de Productos</title>
    <link rel="shortcut icon" href="./assets/img/favicon.png" type="image/x-icon">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.3.0/styles/overlayscrollbars.min.css" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.min.css" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" crossorigin="anonymous">
    <link rel="stylesheet" href="../Adminlte/dist/css/adminlte.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
</head>

<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
    <div class="app-wrapper">
        <?php include 'nav.php'; ?>
        <?php include 'menu-lateral.php'; ?>

        <div class="container my-4">
            <h2 class="text-center mb-4">Gestión de Productos - Tienda de Ropa Online</h2>

            <!-- Formulario para agregar productos -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5>Agregar Nuevo Producto</h5>
                </div>
                <div class="card-body">
                    <form id="formProducto" enctype="multipart/form-data" method="POST">
                        <input type="hidden" name="producto_id" id="producto_id">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="nombreProducto" class="form-label">Nombre del Producto</label>
                                <input type="text" name="nombreProducto" class="form-control" id="nombreProducto" placeholder="Ej. Camiseta, Jeans" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="categoriaProducto" class="form-label">Categoría</label>
                                <select name="categoriaProducto" id="categoriaProducto" class="form-select" required onchange="loadSubcategorias()">
                                    <option value="" selected disabled>Seleccione una categoría</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="subcategoriaProducto" class="form-label">Subcategoría</label>
                                <select name="subcategoriaProducto" id="subcategoriaProducto" class="form-select" required>
                                    <option value="" selected disabled>Seleccione una subcategoría</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="precioProducto" class="form-label">Precio (COP)</label>
                                <input type="number" name="precioProducto" class="form-control" id="precioProducto" placeholder="Ej. 29999" step="0.01" min="0" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="stockProducto" class="form-label">Cantidad en Inventario</label>
                                <input type="number" name="stockProducto" class="form-control" id="stockProducto" placeholder="Ej. 50" min="0" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="estadoProducto" class="form-label">Estado</label>
                                <select name="estadoProducto" id="estadoProducto" class="form-select" required>
                                    <option selected disabled>Seleccione un estado</option>
                                    <option value="Disponible">Disponible</option>
                                    <option value="No disponible">No disponible</option>
                                </select>
                            </div>

                            <!-- Tallas con checkboxes -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tallas Disponibles</label>
                                <div class="d-flex flex-wrap">
                                    <div class="form-check me-3">
                                        <input class="form-check-input" type="checkbox" id="tallaXS" name="tallas[]" value="XS">
                                        <label class="form-check-label" for="tallaXS">XS</label>
                                    </div>
                                    <div class="form-check me-3">
                                        <input class="form-check-input" type="checkbox" id="tallaS" name="tallas[]" value="S">
                                        <label class="form-check-label" for="tallaS">S</label>
                                    </div>
                                    <div class="form-check me-3">
                                        <input class="form-check-input" type="checkbox" id="tallaM" name="tallas[]" value="M">
                                        <label class="form-check-label" for="tallaM">M</label>
                                    </div>
                                    <div class="form-check me-3">
                                        <input class="form-check-input" type="checkbox" id="tallaL" name="tallas[]" value="L">
                                        <label class="form-check-label" for="tallaL">L</label>
                                    </div>
                                    <div class="form-check me-3">
                                        <input class="form-check-input" type="checkbox" id="tallaXL" name="tallas[]" value="XL">
                                        <label class="form-check-label" for="tallaXL">XL</label>
                                    </div>
                                    <div class="form-check me-3">
                                        <input class="form-check-input" type="checkbox" id="tallaXXL" name="tallas[]" value="XXL">
                                        <label class="form-check-label" for="tallaXXL">XXL</label>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="colorProducto" class="form-label">Color</label>
                                <input type="text" name="colorProducto" class="form-control" id="colorProducto" placeholder="Ej. Azul, Rojo" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="materialProducto" class="form-label">Material</label>
                                <input type="text" name="materialProducto" class="form-control" id="materialProducto" placeholder="Ej. Algodón, Poliester" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="fotosProducto" class="form-label">Fotos del Producto</label>
                                <input type="file" name="fotosProducto[]" class="form-control" id="fotosProducto" accept="image/*" multiple>
                                <input type="hidden" name="fotosProductoActual" id="fotosProductoActual">

                                <small class="form-text text-muted">Puede subir varias imágenes.</small>
                            </div>
                            <div class="col-12 mb-3">
                                <label for="descripcionProducto" class="form-label">Descripción del Producto</label>
                                <textarea name="descripcionProducto" class="form-control" id="descripcionProducto" rows="3" placeholder="Descripción breve del producto" required></textarea>
                            </div>
                        </div>
                        <div class="text-end">
                            <button type="submit" name="action" value="addProduct" id="submitButton" class="btn btn-primary">Agregar Producto</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Tabla de productos -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5>Lista de Productos</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered" id="productTable">
                            <thead class="table-dark">
                                <tr>
                                    <th>Nombre</th>
                                    <th>Categoría</th>
                                    <th>Subcategoría</th>
                                    <th>Precio</th>
                                    <th>Cantidad</th>
                                    <th>Estado</th>
                                    <th>Tallas</th>
                                    <th>Color</th>
                                    <th>Material</th>
                                    <th>Descripción</th>
                                    <th>Fotos</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="productTableBody">
                                <!-- Productos cargados dinámicamente -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <?php include_once 'footer.php'; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Función para editar producto
        function editProduct(productId) {
            fetch('../controller/productos.php?action=listProducts')
                .then(response => response.json())
                .then(data => {
                    const product = data.data.find(p => p.producto_id === productId);
                    if (product) {
                        document.getElementById('producto_id').value = product.producto_id;
                        document.getElementById('nombreProducto').value = product.nombreProducto;
                        document.getElementById('categoriaProducto').value = product.categoriaProducto;
                        loadSubcategorias(); // Cargar subcategorías después de seleccionar la categoría
                        document.getElementById('subcategoriaProducto').value = product.subcategoriaProducto;
                        document.getElementById('precioProducto').value = product.precioProducto;
                        document.getElementById('stockProducto').value = product.stockProducto;
                        document.getElementById('estadoProducto').value = product.estadoProducto;
                        document.getElementById('colorProducto').value = product.colorProducto;
                        document.getElementById('materialProducto').value = product.materialProducto;
                        document.getElementById('descripcionProducto').value = product.descripcionProducto;

                        // Cargar las tallas
                        const tallas = product.tallas.split(',');
                        tallas.forEach(talla => {
                            document.querySelector(`input[name="tallas[]"][value="${talla}"]`).checked = true;
                        });

                        // Cargar fotosProductoActual con el valor de fotosProducto
                        document.getElementById('fotosProductoActual').value = product.fotosProducto;

                        Swal.fire("Editando Producto", `Función para editar el producto con ID: ${productId}`, "info");
                    }
                })
                .catch(error => {
                    console.error('Error cargando productos:', error);
                    Swal.fire("Error", "Error cargando productos", "error");
                });
        }


        // Cargar categorías
        function loadCategories() {
            fetch('../controller/productos.php?action=listCategories')
                .then(response => response.json())
                .then(data => {
                    const select = document.getElementById("categoriaProducto");
                    select.innerHTML = '<option value="" selected disabled>Seleccione una categoría</option>';
                    data.data.forEach(cat => {
                        const option = document.createElement("option");
                        option.value = cat.categoria_id;
                        option.textContent = cat.nombreCategoria;
                        option.dataset.subcategorias = JSON.stringify(cat.subcategorias); // Guardar subcategorías como atributo de datos
                        select.appendChild(option);
                    });
                })
                .catch(error => {
                    console.error('Error cargando categorías:', error);
                    Swal.fire("Error", "Error cargando categorías", "error");
                });
        }

        // Cargar subcategorías al seleccionar una categoría
        function loadSubcategorias() {
            const selectCategoria = document.getElementById("categoriaProducto");
            const selectedOption = selectCategoria.options[selectCategoria.selectedIndex];
            const subcategorias = JSON.parse(selectedOption.dataset.subcategorias);

            const subcategoriaSelect = document.getElementById("subcategoriaProducto");
            subcategoriaSelect.innerHTML = '<option value="" selected disabled>Seleccione una subcategoría</option>';

            subcategorias.forEach(subcat => {
                const option = document.createElement("option");
                option.value = subcat.trim();
                option.textContent = subcat.trim();
                subcategoriaSelect.appendChild(option);
            });
        }
        // Cargar y mostrar productos
        // Cargar y mostrar productos
        function loadProducts() {
            fetch('../controller/productos.php?action=listProducts')
                .then(response => response.json())
                .then(data => {
                    const tableBody = document.getElementById("productTableBody");
                    tableBody.innerHTML = "";

                    if (data && data.status === "success" && Array.isArray(data.data)) {
                        data.data.forEach(product => {
                            const row = document.createElement("tr");
                            row.innerHTML = `
                        <td>${product.nombreProducto}</td>
                        <td>${product.nombreCategoria}</td>
                        <td>${product.subcategoriaProducto || 'N/A'}</td>
                        <td>$${Number(product.precioProducto).toFixed(2)}</td>
                        <td>${product.stockProducto}</td>
                        <td>${product.estadoProducto}</td>
                        <td>${product.tallas}</td>
                        <td>${product.colorProducto}</td>
                        <td>${product.materialProducto}</td>
                        <td>${product.descripcionProducto}</td>
                        <td>
                            <img src="${product.fotosProducto ? '../uploads/' + product.fotosProducto.split(',')[0] : ''}" class="img-thumbnail" style="width: 50px; height: auto;">
                        </td>
                        <td>
                            <button class="btn btn-warning btn-sm me-2" onclick="editProduct(${product.producto_id})">
                                <i class="bi bi-pencil"></i> Editar
                            </button>
                            <button class="btn btn-danger btn-sm" onclick="deleteProduct(${product.producto_id})">
                                <i class="bi bi-trash"></i> Eliminar
                            </button>
                        </td>
                    `;
                            tableBody.appendChild(row);
                        });
                    } else {
                        console.error('No se encontraron productos en la respuesta del servidor:', data.message || 'Respuesta inesperada');
                        Swal.fire("Error", "No se encontraron productos o la respuesta del servidor fue incorrecta.", "error");
                    }
                })
                .catch(error => {
                    console.error('Error cargando productos:', error);
                    Swal.fire("Error", "Error cargando productos", "error");
                });
        }



        // Procesar formulario para agregar producto
        document.getElementById("formProducto").addEventListener("submit", function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const action = this.producto_id.value ? "editProduct" : "addProduct"; // Determinar si es edición o adición
            formData.append("action", action);

            fetch('../controller/productos.php', {
                    method: "POST",
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === "success") {
                        Swal.fire("Éxito", data.message, "success").then(() => {
                            loadProducts();
                            this.reset();
                        });
                    } else {
                        Swal.fire("Error", data.message, "error");
                    }
                })
                .catch(error => Swal.fire("Error", "No se pudo procesar la solicitud", "error"));
        });

        // Función para eliminar producto
        function deleteProduct(productId) {
            Swal.fire({
                title: '¿Estás seguro?',
                text: "No podrás recuperar este producto después de eliminarlo.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch('../controller/productos.php', {
                            method: "POST",
                            body: new URLSearchParams({
                                'action': 'deleteProduct',
                                'producto_id': productId
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.status === "success") {
                                Swal.fire("Eliminado", data.message, "success").then(() => {
                                    loadProducts();
                                });
                            } else {
                                Swal.fire("Error", data.message, "error");
                            }
                        })
                        .catch(error => Swal.fire("Error", "No se pudo procesar la solicitud", "error"));
                }
            });
        }

        document.addEventListener("DOMContentLoaded", () => {
            loadCategories();
            loadProducts();
        });
    </script>
</body>

</html>
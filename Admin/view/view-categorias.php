<?php
// Incluir el middleware
require_once '../middleware/AuthMiddleware.php';

// Verificar permisos (solo admin puede acceder)
AuthMiddleware::requireRole('Administrador');

// Continuar con el resto del código de la vista...
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Macablue | Admin - Gestión de Categorías</title>
    <link rel="shortcut icon" href="./assets/img/favicon.png" type="image/x-icon">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.3.0/styles/overlayscrollbars.min.css" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.min.css" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" crossorigin="anonymous">
    <link rel="stylesheet" href="../Adminlte/dist/css/adminlte.css">
</head>

<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
    <div class="app-wrapper">
        <?php include 'nav.php'; ?>
        <?php include 'menu-lateral.php'; ?>

        <div class="container my-4">
            <h2 class="text-center mb-4">Gestión de Categorías - Tienda de Ropa Online</h2>

            <div class="card mb-4">
                <div class="card-header">
                    <h5 id="formTitle">Agregar Nueva Categoría</h5>
                </div>
                <div class="card-body">
                    <form id="formCategoria" method="POST">
                        <input type="hidden" name="categoria_id" id="categoria_id">
                        <div class="mb-3">
                            <label for="nombreCategoria" class="form-label">Nombre de la Categoría</label>
                            <input type="text" name="nombreCategoria" class="form-control" id="nombreCategoria" placeholder="Ej. Ropa Casual, Accesorios" required>
                        </div>
                        <div class="mb-3">
                            <label for="subcategorias" class="form-label">Subcategorías</label>
                            <input type="text" name="subcategorias" class="form-control" id="subcategorias" placeholder="Ej. Camisas, Pantalones" required>
                        </div>
                        <div class="text-end">
                            <button type="submit" name="action" value="addCategory" id="submitButton" class="btn btn-primary">Agregar Categoría</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">
                    <h5>Lista de Categorías</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered" id="categoryTable">
                            <thead class="table-dark">
                                <tr>
                                    <th>Nombre</th>
                                    <th>Subcategorías</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="categoryTableBody">
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
        function loadCategories() {
            fetch('../controller/categorias.php?action=listCategories')
            .then(response => response.json())
            .then(data => {
                if (data.status === "success") {
                    const tableBody = document.getElementById('categoryTableBody');
                    tableBody.innerHTML = '';

                    data.data.forEach(category => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${category.nombreCategoria}</td>
                            <td>${category.subcategorias}</td>
                            <td>
                                <button class="btn btn-warning btn-sm me-2" onclick="editCategory(${category.categoria_id})">
                                    <i class="bi bi-pencil"></i> Editar
                                </button>
                                <button class="btn btn-danger btn-sm" onclick="deleteCategory(${category.categoria_id})">
                                    <i class="bi bi-trash"></i> Eliminar
                                </button>
                            </td>
                        `;
                        tableBody.appendChild(row);
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message,
                    });
                }
            })
            .catch(error => {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Ocurrió un error al cargar las categorías.',
                });
            });
        }

        document.addEventListener('DOMContentLoaded', loadCategories);

        document.getElementById('formCategoria').addEventListener('submit', function (event) {
            event.preventDefault();
            const formData = new FormData(this);
            const action = document.getElementById('categoria_id').value ? 'editCategory' : 'addCategory';
            formData.append('action', action);

            fetch('../controller/categorias.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === "success") {
                    Swal.fire({
                        icon: 'success',
                        title: 'Éxito',
                        text: data.message,
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => {
                        loadCategories();
                        document.getElementById('formCategoria').reset();
                        document.getElementById('formTitle').innerText = 'Agregar Nueva Categoría';
                        document.getElementById('submitButton').innerText = 'Agregar Categoría';
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message,
                    });
                }
            })
            .catch(error => {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Ocurrió un error al procesar la solicitud.',
                });
            });
        });

        function deleteCategory(categoria_id) {
            Swal.fire({
                title: '¿Está seguro?',
                text: "Esta acción no se puede deshacer",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch('../controller/categorias.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: new URLSearchParams({
                            action: 'deleteCategory',
                            categoria_id: categoria_id
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === "success") {
                            Swal.fire('Eliminado', data.message, 'success').then(() => loadCategories());
                        } else {
                            Swal.fire('Error', data.message, 'error');
                        }
                    })
                    .catch(error => {
                        Swal.fire('Error', 'No se pudo procesar la solicitud.', 'error');
                    });
                }
            });
        }

        function editCategory(categoria_id) {
            fetch(`../controller/categorias.php?action=listCategories`)
                .then(response => response.json())
                .then(data => {
                    const category = data.data.find(cat => cat.categoria_id == categoria_id);
                    if (category) {
                        document.getElementById('categoria_id').value = category.categoria_id;
                        document.getElementById('nombreCategoria').value = category.nombreCategoria;
                        document.getElementById('subcategorias').value = category.subcategorias;

                        document.getElementById('formTitle').innerText = 'Editar Categoría';
                        document.getElementById('submitButton').innerText = 'Guardar Cambios';
                    }
                })
                .catch(error => {
                    Swal.fire('Error', 'No se pudo cargar la categoría para edición.', 'error');
                });
        }
    </script>
</body>
</html>

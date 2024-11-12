<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Macablue | Clientes Registrados</title>
    <link rel="shortcut icon" href="./assets/img/favicon.png" type="image/x-icon">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="title" content="AdminLTE v4 | Dashboard">
    <meta name="author" content="ColorlibHQ">
    <meta name="description" content="AdminLTE is a Free Bootstrap 5 Admin Dashboard, 30 example pages using Vanilla JS.">
    <meta name="keywords" content="bootstrap 5, bootstrap, bootstrap 5 admin dashboard, bootstrap 5 dashboard, bootstrap 5 charts, bootstrap 5 calendar, bootstrap 5 datepicker, bootstrap 5 tables, bootstrap 5 datatable, vanilla js datatable, colorlibhq, colorlibhq dashboard, colorlibhq admin dashboard">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.3.0/styles/overlayscrollbars.min.css" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.min.css" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" crossorigin="anonymous">
    <link rel="stylesheet" href="../Adminlte/dist/css/adminlte.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
</head>

<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
    <div class="app-wrapper">
        <?php
        // Barra de navegación
        include 'nav.php';
        // Menu lateral
        include 'menu-lateral.php';
        ?>

        <div class="app-content mt-5">
            <div class="container-fluid">
                <!-- Header de la tabla de clientes -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="h4">Clientes Registrados</h2>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addClientModal">
                        <i class="bi bi-person-plus"></i> Añadir Cliente
                    </button>
                </div>

                <!-- Tabla de clientes -->
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover align-middle" id="clientTable">
                                <thead class="table-light">
                                    <tr>
                                        <th>Nombre</th>
                                        <th>Email</th>
                                        <th>Teléfono</th>
                                        <th>Fecha de Registro</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Ejemplo de fila de cliente -->
                                    <tr>
                                        <td>Juan Pérez</td>
                                        <td>juan.perez@example.com</td>
                                        <td>(123) 456-7890</td>
                                        <td>01/01/2023</td>
                                        <td><span class="badge bg-success">Activo</span></td>
                                        <td>
                                            <button class="btn btn-info btn-sm me-2"><i class="bi bi-eye"></i> Ver</button>
                                            <button class="btn btn-warning btn-sm me-2"><i class="bi bi-pencil"></i> Editar</button>
                                            <button class="btn btn-danger btn-sm"><i class="bi bi-trash"></i> Eliminar</button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Ana García</td>
                                        <td>ana.garcia@example.com</td>
                                        <td>(987) 654-3210</td>
                                        <td>15/02/2023</td>
                                        <td><span class="badge bg-warning">Inactivo</span></td>
                                        <td>
                                            <button class="btn btn-info btn-sm me-2"><i class="bi bi-eye"></i> Ver</button>
                                            <button class="btn btn-warning btn-sm me-2"><i class="bi bi-pencil"></i> Editar</button>
                                            <button class="btn btn-danger btn-sm"><i class="bi bi-trash"></i> Eliminar</button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Modal para añadir cliente -->
                <div class="modal fade" id="addClientModal" tabindex="-1" aria-labelledby="addClientModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="addClientModalLabel">Añadir Nuevo Cliente</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form>
                                    <div class="mb-3">
                                        <label for="clientName" class="form-label">Nombre</label>
                                        <input type="text" class="form-control" id="clientName" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="clientEmail" class="form-label">Email</label>
                                        <input type="email" class="form-control" id="clientEmail" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="clientPhone" class="form-label">Teléfono</label>
                                        <input type="tel" class="form-control" id="clientPhone">
                                    </div>
                                    <div class="mb-3">
                                        <label for="clientStatus" class="form-label">Estado</label>
                                        <select class="form-select" id="clientStatus">
                                            <option selected>Activo</option>
                                            <option>Inactivo</option>
                                        </select>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                        <button type="submit" class="btn btn-primary">Guardar Cliente</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Fin de Modal para añadir cliente -->
            </div>
        </div>

        <!-- Pie de página -->
        <?php include_once 'footer.php'; ?>
    </div>

    <!-- JavaScript Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.3.0/browser/overlayscrollbars.browser.es6.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Custom Script -->
    <script>
        // Script para SweetAlert en la acción de eliminar
        document.querySelectorAll('.btn-danger').forEach(button => {
            button.addEventListener('click', function() {
                Swal.fire({
                    title: '¿Estás seguro?',
                    text: "Esta acción no se puede deshacer.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire(
                            'Eliminado',
                            'El cliente ha sido eliminado.',
                            'success'
                        )
                        // Aquí puedes agregar la lógica para eliminar el cliente
                    }
                });
            });
        });
    </script>
</body>

</html>

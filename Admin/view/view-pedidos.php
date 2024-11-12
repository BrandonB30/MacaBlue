<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Macablue | Admin - Pedidos</title>
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
        <?php
        // Barra de navegación
        include 'nav.php';
        // Menu lateral
        include 'menu-lateral.php';
        ?>

        <div class="content-wrapper">
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>Gestión de Pedidos</h1>
                        </div>
                    </div>
                </div>
            </section>

            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Pedidos Recibidos</h3>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table id="ordersTable" class="table table-bordered table-hover">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>ID Pedido</th>
                                                    <th>Cliente</th>
                                                    <th>Fecha</th>
                                                    <th>Total</th>
                                                    <th>Estado</th>
                                                    <th>Dirección de Entrega</th>
                                                    <th>Acciones</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>1</td>
                                                    <td>Juan Pérez</td>
                                                    <td>2023-10-01</td>
                                                    <td>$100.00</td>
                                                    <td>
                                                        <select class="form-select" onchange="confirmStatusChange(this)">
                                                            <option value="Pendiente">Pendiente</option>
                                                            <option value="Enviado">Enviado</option>
                                                            <option value="Entregado">Entregado</option>
                                                        </select>
                                                    </td>
                                                    <td>123 Calle Falsa, Ciudad, País</td>
                                                    <td>
                                                        <button class="btn btn-primary btn-sm" onclick="viewOrderDetails(1)">
                                                            <i class="bi bi-eye"></i> Ver
                                                        </button>
                                                        <button class="btn btn-danger btn-sm" onclick="confirmDeleteOrder(1)">
                                                            <i class="bi bi-trash"></i> Eliminar
                                                        </button>
                                                    </td>
                                                </tr>
                                                <!-- Puedes añadir más filas de pedidos aquí -->
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Modal para Ver Detalles de Pedido -->
            <div class="modal fade" id="orderDetailsModal" tabindex="-1" aria-labelledby="orderDetailsModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="orderDetailsModalLabel">Detalles del Pedido</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p><strong>ID Pedido:</strong> <span id="modalOrderId">1</span></p>
                            <p><strong>Cliente:</strong> Juan Pérez</p>
                            <p><strong>Fecha:</strong> 2023-10-01</p>
                            <p><strong>Total:</strong> $100.00</p>
                            <p><strong>Estado:</strong> Pendiente</p>
                            <p><strong>Dirección de Entrega:</strong> 123 Calle Falsa, Ciudad, País</p>
                            <hr>
                            <h6>Productos:</h6>
                            <ul>
                                <li>Producto A - Cantidad: 1 - Precio: $50.00</li>
                                <li>Producto B - Cantidad: 2 - Precio: $25.00</li>
                            </ul>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Fin de Modal -->

        </div>
    </div>

    <!-- JavaScript Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.3.0/browser/overlayscrollbars.browser.es6.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Custom Scripts -->
    <script>
        // Función para ver detalles del pedido
        function viewOrderDetails(orderId) {
            // Aquí puedes cargar dinámicamente los detalles del pedido usando el orderId
            // Para efectos de esta demo, el modal muestra datos estáticos
            document.getElementById("modalOrderId").textContent = orderId;
            var orderDetailsModal = new bootstrap.Modal(document.getElementById("orderDetailsModal"));
            orderDetailsModal.show();
        }

        // Función para confirmar eliminación del pedido
        function confirmDeleteOrder(orderId) {
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
                        'El pedido ha sido eliminado.',
                        'success'
                    )
                    // Aquí puedes agregar la lógica para eliminar el pedido
                }
            });
        }

        // Función para confirmar cambio de estado
        function confirmStatusChange(selectElement) {
            const newStatus = selectElement.value;
            Swal.fire({
                title: '¿Confirmar cambio de estado?',
                text: `El estado cambiará a "${newStatus}".`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, cambiar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire(
                        'Estado Actualizado',
                        `El pedido ha sido marcado como "${newStatus}".`,
                        'success'
                    )
                    // Aquí puedes agregar la lógica para actualizar el estado del pedido
                } else {
                    selectElement.value = selectElement.getAttribute("data-prev-value"); // Restablecer el valor original si se cancela
                }
            });
        }
    </script>
</body>

</html>

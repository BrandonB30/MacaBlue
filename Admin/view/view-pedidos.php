<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// Incluir controlador
require_once '../controller/pedidoController.php';
require_once '../middleware/AuthMiddleware.php';

// Verificar autenticación y roles
AuthMiddleware::requireRole(['Administrador', 'Empleado']);

// Instanciar controlador
$controller = new PedidosController();

// Obtener todos los pedidos
$pedidos = $controller->obtenerTodos();

// Obtener estados disponibles
$estados = $controller->getEstados();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Macablue | Admin - Gestión de Pedidos</title>
    <link rel="shortcut icon" href="../assets/img/favicon.png" type="image/x-icon">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.3.0/styles/overlayscrollbars.min.css" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.min.css" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" crossorigin="anonymous">
    <link rel="stylesheet" href="../Adminlte/dist/css/adminlte.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <style>
        /* Estilos para las etiquetas de estado */
        .badge-estado {
            min-width: 110px;
            display: inline-block;
            text-align: center;
            font-size: 1rem;
            padding: 5px 10px;
            border-radius: 4px;
        }
        .badge-en_proceso {
            background-color: #ffc107;
            color: black;
        }
        .badge-enviado {
            background-color: #17a2b8;
            color: white;
        }
        .badge-entregado {
            background-color: #28a745;
            color: white;
        }
        .badge-cancelado {
            background-color: #dc3545;
            color: white;
        }
    </style>
</head>

<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
    <div class="app-wrapper">
        <?php include 'nav.php'; ?>
        <?php include 'menu-lateral.php'; ?>

        <div class="container my-4">
            <h2 class="text-center mb-4">Gestión de Pedidos</h2>
            
            <!-- Card para la tabla de pedidos -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5>Lista de Pedidos</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead class="table-dark">
                                <tr>
                                    <th>ID Pedido</th>
                                    <th>Fecha</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($pedidos)): ?>
                                    <tr>
                                        <td colspan="4" class="text-center">No hay pedidos disponibles</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($pedidos as $pedido): ?>
                                        <tr>
                                            <td><?= $pedido['pedido_id'] ?></td>
                                            <td><?= date('d/m/Y H:i', strtotime($pedido['fecha_pedido'])) ?></td>
                                            <td>
                                                <span class="badge-estado badge-<?= $pedido['estado'] ?>">
                                                    <?= Pedido::getEstadoTexto($pedido['estado']) ?>
                                                </span>
                                            </td>    
                                            <td>
                                                <button type="button" class="btn btn-info btn-sm me-2" 
                                                        onclick="verDetallesPedido(<?= htmlspecialchars(json_encode($pedido), ENT_QUOTES, 'UTF-8') ?>)">
                                                    <i class="bi bi-eye"></i> Ver Detalles
                                                </button>
                                                <button type="button" class="btn btn-danger btn-sm" 
                                                        onclick="confirmarEliminarPedido(<?= $pedido['pedido_id'] ?>)">
                                                    <i class="bi bi-trash"></i> Eliminar
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal para ver detalles del pedido -->
        <div class="modal fade" id="detallesPedidoModal" tabindex="-1" aria-labelledby="detallesPedidoModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="detallesPedidoModalLabel">Detalles del Pedido</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>ID Pedido:</strong> <span id="modalPedidoId"></span></p>
                                <p><strong>Cliente:</strong> <span id="modalCliente"></span></p>
                                <p><strong>Fecha:</strong> <span id="modalFecha"></span></p>
                                <p><strong>Estado:</strong> <span id="modalEstadoTexto"></span></p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Total:</strong> $<span id="modalTotal"></span></p>
                                <p><strong>Dirección de Envío:</strong> <span id="modalDireccion"></span></p>
                                <p><strong>Método de Pago:</strong> <span id="modalMetodoPago"></span></p>
                            </div>
                        </div>
                        
                        <hr>
                        
                        <h6>Cambiar Estado del Pedido</h6>
                        <div class="row align-items-end">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label for="modalCambiarEstado" class="form-label">Estado</label>
                                    <select id="modalCambiarEstado" class="form-select">
                                        <?php foreach ($estados as $key => $value): ?>
                                            <option value="<?= $key ?>"><?= $value ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <button type="button" class="btn btn-primary" id="btnGuardarEstado">
                                        Guardar Cambio
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>

        <?php include_once 'footer.php'; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.3.0/browser/overlayscrollbars.browser.es6.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../js/pedido.js"></script>
</body>
</html>
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once '../config/conexion.php'; // Incluir conexión a la base de datos
include_once '../model/model-pedidos.php'; // Incluir el modelo de pedidos

// Crear conexión a la base de datos
$database = new Database();
$db = $database->getConnection();

// Crear instancia de Pedido
$pedido = new Pedido($db);

// Obtener todos los pedidos
$pedidos = $pedido->readAll();
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
                            <!-- Modificación en la tabla principal para mostrar el estado como texto en lugar de select -->
                            <tbody>
                                <?php foreach ($pedidos as $pedido): ?>
                                    <tr>
                                        <td><?= $pedido['pedido_id'] ?></td>
                                        <td><?= date('d/m/Y H:i', strtotime($pedido['fecha_pedido'])) ?></td>
                                        <td>
                                            <span class="badge-<?= $pedido['estado'] ?>">
                                                <?php 
                                                switch($pedido['estado']){
                                                    case 'pendiente': echo 'Pendiente'; break;
                                                    case 'en_proceso': echo 'En Proceso'; break;
                                                    case 'enviado': echo 'Enviado'; break;
                                                    case 'entregado': echo 'Entregado'; break;
                                                    case 'cancelado': echo 'Cancelado'; break;
                                                    default: echo ucfirst($pedido['estado']);
                                                }
                                                ?>
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
                                <p><strong>Estado:</strong> <span id="modalEstado"></span></p>
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
                                    <select class="form-select" id="modalCambiarEstado">
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
    <script>
   // Función para actualizar el estado del pedido
function actualizarEstadoPedido(pedidoId, estado) {
    const formData = new FormData();
    formData.append('action', 'actualizarEstado');
    formData.append('pedido_id', pedidoId);
    formData.append('estado', estado);

    fetch('../controller/controller-pedidos.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === "success") {
            Swal.fire({
                icon: 'success',
                title: 'Estado actualizado',
                text: 'El estado del pedido ha sido actualizado',
                timer: 2000,
                showConfirmButton: false
            }).then(() => {
                location.reload(); // Recargar la página
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: data.message || 'No se pudo actualizar el estado'
            });
        }
    })
    .catch(error => {
        console.error('Error en la petición:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Ocurrió un error al actualizar el estado: ' + error.message
        });
    });
}
// Función para ver los detalles del pedido en el modal
function verDetallesPedido(pedido) {
    console.log("Pedido recibido:", pedido); // Para verificar en consola si los datos llegan

    // Llenar los campos del modal con los datos del pedido
    document.getElementById('modalPedidoId').textContent = pedido.pedido_id;
    document.getElementById('modalCliente').textContent = pedido.usuario_id; // Ajustar según tu base de datos
    document.getElementById('modalFecha').textContent = pedido.fecha_pedido;
    document.getElementById('modalEstado').textContent = pedido.estado;
    document.getElementById('modalTotal').textContent = pedido.total;
    document.getElementById('modalDireccion').textContent = pedido.direccion_envio;
    document.getElementById('modalMetodoPago').textContent = pedido.metodo_pago;

    // Mostrar el modal
    var modal = new bootstrap.Modal(document.getElementById('detallesPedidoModal'));
    modal.show();
}


// Función para eliminar un pedido
function eliminarPedido(pedidoId) {
    const formData = new FormData();
    formData.append('action', 'eliminarPedido');
    formData.append('pedido_id', pedidoId);

    fetch('../controller/controller-pedidos.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === "success") {
            Swal.fire({
                icon: 'success',
                title: 'Pedido eliminado',
                text: 'El pedido ha sido eliminado correctamente',
                timer: 2000,
                showConfirmButton: false
            }).then(() => {
                location.reload(); // Recargar la página
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: data.message || 'No se pudo eliminar el pedido'
            });
        }
    })
    .catch(error => {
        console.error('Error en la petición:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Ocurrió un error al procesar la solicitud: ' + error.message
        });
    });
}
// Función para confirmar la eliminación del pedido
function confirmarEliminarPedido(pedidoId) {
    Swal.fire({
        title: '¿Estás seguro?',
        text: "Esta acción no se puede deshacer",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            eliminarPedido(pedidoId);
        }
    });
}


// Evento para detectar cambios en el estado del pedido
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.estado-pedido').forEach(select => {
        select.addEventListener('change', function() {
            const pedidoId = this.getAttribute('data-pedido-id');
            const nuevoEstado = this.value;
            actualizarEstadoPedido(pedidoId, nuevoEstado);
        });
    });
});

</script>
</body> 
</html>
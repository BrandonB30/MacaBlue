// Variables globales
let pedidoActual = null;

// Función para ver los detalles del pedido en el modal
function verDetallesPedido(pedido) {
    console.log("Pedido recibido:", pedido);
    
    // Guardar el pedido actual
    pedidoActual = pedido;
    
    // Llenar los campos del modal con los datos del pedido
    document.getElementById('modalPedidoId').textContent = pedido.pedido_id;
    
    // Mostrar nombre completo del cliente si está disponible
    if (pedido.nombre && pedido.apellido) {
        document.getElementById('modalCliente').textContent = pedido.nombre + ' ' + pedido.apellido;
    } else {
        document.getElementById('modalCliente').textContent = pedido.usuario_id;
    }
    
    document.getElementById('modalFecha').textContent = formatearFecha(pedido.fecha_pedido);
    document.getElementById('modalEstadoTexto').textContent = getEstadoTexto(pedido.estado);
    document.getElementById('modalTotal').textContent = pedido.total;
    document.getElementById('modalDireccion').textContent = pedido.direccion_envio;
    document.getElementById('modalMetodoPago').textContent = pedido.metodo_pago;
    
    // Actualizar el select con el estado actual
    const selectEstado = document.getElementById('modalCambiarEstado');
    if (selectEstado) {
        selectEstado.value = pedido.estado;
        
        // Configurar el botón para guardar cambios
        const btnGuardar = document.getElementById('btnGuardarEstado');
        btnGuardar.setAttribute('data-pedido-id', pedido.pedido_id);
    }
    
    // Mostrar el modal
    const modal = new bootstrap.Modal(document.getElementById('detallesPedidoModal'));
    modal.show();
}

// Función para actualizar el estado del pedido
function actualizarEstadoPedido(pedidoId, estado) {
    const formData = new FormData();
    formData.append('action', 'actualizarEstado');
    formData.append('pedido_id', pedidoId);
    formData.append('estado', estado);

    fetch('../controller/pedidoController.php', {
        method: 'POST',
        body: formData
    })
    .then(async response => {
        const text = await response.text();
        try {
            const data = JSON.parse(text);
            if (data.status === "success") {
                Swal.fire({
                    icon: 'success',
                    title: 'Estado actualizado',
                    text: 'El estado del pedido ha sido actualizado correctamente',
                    timer: 2000,
                    showConfirmButton: false
                }).then(() => {
                    location.reload();
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: data.message || 'No se pudo actualizar el estado'
                });
            }
        } catch (e) {
            // Mostrar el error HTML recibido
            console.error('Respuesta inesperada:', text);
            Swal.fire({
                icon: 'error',
                title: 'Error inesperado',
                html: '<pre style="text-align:left">' + text + '</pre>'
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

// Función para eliminar un pedido
function eliminarPedido(pedidoId) {
    const formData = new FormData();
    formData.append('action', 'eliminarPedido');
    formData.append('pedido_id', pedidoId);

    fetch('../controller/pedidoController.php', {
        method: 'POST',
        body: formData
    })
    .then(async response => {
        const text = await response.text();
        try {
            const data = JSON.parse(text);
            if (data.status === "success") {
                Swal.fire({
                    icon: 'success',
                    title: 'Pedido eliminado',
                    text: 'El pedido ha sido eliminado correctamente',
                    timer: 2000,
                    showConfirmButton: false
                }).then(() => {
                    location.reload();
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: data.message || 'No se pudo eliminar el pedido'
                });
            }
        } catch (e) {
            // Mostrar el error HTML recibido
            console.error('Respuesta inesperada:', text);
            Swal.fire({
                icon: 'error',
                title: 'Error inesperado',
                html: '<pre style="text-align:left">' + text + '</pre>'
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

// Utilidades
function formatearFecha(fechaStr) {
    const fecha = new Date(fechaStr);
    return fecha.toLocaleString('es-ES', { 
        day: '2-digit', 
        month: '2-digit', 
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
}

function getEstadoTexto(estado) {
    const estados = {
        'pendiente': 'Pendiente',
        'en_proceso': 'En Proceso',
        'enviado': 'Enviado',
        'entregado': 'Entregado',
        'cancelado': 'Cancelado'
    };
    
    return estados[estado] || 'Desconocido';
}

// Event Listeners cuando el DOM esté cargado
document.addEventListener('DOMContentLoaded', function () {
    // Escuchar clic en el botón "Guardar Cambio" del modal
    const btnGuardar = document.getElementById('btnGuardarEstado');
    if (btnGuardar) {
        btnGuardar.addEventListener('click', function () {
            const pedidoId = this.getAttribute('data-pedido-id');
            const nuevoEstado = document.getElementById('modalCambiarEstado').value;

            if (pedidoId && nuevoEstado) {
                actualizarEstadoPedido(pedidoId, nuevoEstado);
            } else {
                Swal.fire({
                    icon: 'warning',
                    title: 'Faltan datos',
                    text: 'No se pudo determinar el pedido o el nuevo estado.'
                });
            }
        });
    }
});
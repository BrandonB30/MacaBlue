<?php
require_once '../../Admin/controller/ContactController.php';
require_once '../middleware/AuthMiddleware.php';
AuthMiddleware::requireRole(['Administrador', 'Empleado']);

$contactController = new ContactController();
$messages = $contactController->getAllMessages();
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Macablue | Admin - Gestión de Mensajes</title>
    <link rel="shortcut icon" href="./assets/img/favicon.png" type="image/x-icon">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.3.0/styles/overlayscrollbars.min.css" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.min.css" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" crossorigin="anonymous">
    <link rel="stylesheet" href="../Adminlte/dist/css/adminlte.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.css" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/jsvectormap@1.5.3/dist/css/jsvectormap.min.css" crossorigin="anonymous">
</head>

<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
    <div class="app-wrapper">
        <?php
        include 'nav.php';
        include 'menu-lateral.php';
        ?>

        <div class="container my-4">
            <h2 class="text-center mb-4">Gestión de Mensajes de Contacto</h2>

            <!-- Widget de Resumen -->
            <div class="row mb-4">
                <div class="col-lg-3 col-6">
                    <div class="small-box text-bg-primary">
                        <div class="inner">
                            <h3><?= count($messages) ?></h3>
                            <p>Mensajes Totales</p>
                        </div>
                        <svg class="small-box-icon" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                            <path d="M1.5 8.67v8.58a3 3 0 003 3h15a3 3 0 003-3V8.67l-8.928 5.493a3 3 0 01-3.144 0L1.5 8.67z"></path>
                            <path d="M22.5 6.908V6.75a3 3 0 00-3-3h-15a3 3 0 00-3 3v.158l9.714 5.978a1.5 1.5 0 001.572 0L22.5 6.908z"></path>
                        </svg>
                        <a href="#" class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-50-hover">
                            Ver detalles <i class="bi bi-link-45deg"></i>
                        </a>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box text-bg-success">
                        <div class="inner">
                            <h3>Consultas</h3>
                            <p>Gestión de Contacto</p>
                        </div>
                        <svg class="small-box-icon" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                            <path d="M4.913 2.658c2.075-.27 4.19-.408 6.337-.408 2.147 0 4.262.139 6.337.408 1.922.25 3.291 1.861 3.405 3.727a4.403 4.403 0 00-1.032-.211 50.89 50.89 0 00-8.42 0c-2.358.196-4.04 2.19-4.04 4.434v4.286a4.47 4.47 0 002.433 3.984L7.28 21.53A.75.75 0 016 21v-4.03a48.527 48.527 0 01-1.087-.128C2.905 16.58 1.5 14.833 1.5 12.862V6.638c0-1.97 1.405-3.718 3.413-3.979z"></path>
                            <path d="M15.75 7.5c-1.376 0-2.739.057-4.086.169C10.124 7.797 9 9.103 9 10.609v4.285c0 1.507 1.128 2.814 2.67 2.94 1.243.102 2.5.157 3.768.165l2.782 2.781a.75.75 0 001.28-.53v-2.39l.33-.026c1.542-.125 2.67-1.433 2.67-2.94v-4.286c0-1.505-1.125-2.811-2.664-2.94A49.392 49.392 0 0015.75 7.5z"></path>
                        </svg>
                        <a href="#" class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-50-hover">
                            Ver detalles <i class="bi bi-link-45deg"></i>
                        </a>
                    </div>
                </div>
                <div class="col-lg-6 col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5>Filtrar Mensajes</h5>
                        </div>
                        <div class="card-body">
                            <form class="row g-3">
                                <div class="col-md-5">
                                    <input type="text" class="form-control" placeholder="Buscar por nombre o email">
                                </div>
                                <div class="col-md-5">
                                    <select class="form-select">
                                        <option selected disabled>Filtrar por asunto</option>
                                        <option value="consulta">Consulta</option>
                                        <option value="problema">Problema</option>
                                        <option value="sugerencia">Sugerencia</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-primary w-100">Filtrar</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabla de Mensajes -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5>Mensajes de Contacto</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nombre</th>
                                    <th>Email</th>
                                    <th>Asunto</th>
                                    <th>Mensaje</th>
                                    <th>Fecha</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($messages as $message): ?>
                                <tr id="row-<?= $message['id'] ?>">
                                    <td><?= htmlspecialchars($message['id']) ?></td>
                                    <td><?= htmlspecialchars($message['nombre']) ?></td>
                                    <td><?= htmlspecialchars($message['email']) ?></td>
                                    <td><?= htmlspecialchars($message['asunto']) ?></td>
                                    <td>
                                        <?php 
                                        $shortMessage = strlen($message['mensaje']) > 50 ? 
                                            substr(htmlspecialchars($message['mensaje']), 0, 50) . '...' : 
                                            htmlspecialchars($message['mensaje']);
                                        echo $shortMessage;
                                        ?>
                                        <button class="btn btn-sm btn-link" data-bs-toggle="modal" data-bs-target="#messageModal<?= $message['id'] ?>">
                                            Ver completo
                                        </button>
                                    </td>
                                    <td><?= htmlspecialchars($message['fecha']) ?></td>
                                    <td>
                                        <button class="btn btn-sm btn-primary" title="Responder">
                                            <i class="bi bi-reply"></i>
                                        </button>
                                        <button class="btn btn-sm btn-danger delete-btn" data-id="<?= $message['id'] ?>" title="Eliminar">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                
                                <!-- Modal para ver mensaje completo -->
                                <div class="modal fade" id="messageModal<?= $message['id'] ?>" tabindex="-1" aria-labelledby="messageModalLabel<?= $message['id'] ?>" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="messageModalLabel<?= $message['id'] ?>">Mensaje de <?= htmlspecialchars($message['nombre']) ?></h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p><strong>Asunto:</strong> <?= htmlspecialchars($message['asunto']) ?></p>
                                                <p><strong>Fecha:</strong> <?= htmlspecialchars($message['fecha']) ?></p>
                                                <p><strong>Email:</strong> <?= htmlspecialchars($message['email']) ?></p>
                                                <hr>
                                                <p><?= nl2br(htmlspecialchars($message['mensaje'])) ?></p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                                <button type="button" class="btn btn-primary">Responder</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <!-- Paginación -->
            <nav aria-label="Page navigation example">
                <ul class="pagination justify-content-center">
                    <li class="page-item disabled">
                        <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Anterior</a>
                    </li>
                    <li class="page-item active"><a class="page-link" href="#">1</a></li>
                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                    <li class="page-item">
                        <a class="page-link" href="#">Siguiente</a>
                    </li>
                </ul>
            </nav>
        </div>

        <?php include_once 'footer.php'; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.3.0/browser/overlayscrollbars.browser.es6.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // Script para manejar la eliminación de mensajes
        document.addEventListener('DOMContentLoaded', function() {
            // Seleccionar todos los botones de eliminar
            document.querySelectorAll('.delete-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const messageId = this.getAttribute('data-id');
                    
                    Swal.fire({
                        title: '¿Estás seguro?',
                        text: "¡No podrás revertir esta acción!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Sí, eliminar',
                        cancelButtonText: 'Cancelar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Realizar la solicitud AJAX para eliminar
                            $.ajax({
                                url: 'delete_message.php',
                                type: 'POST',
                                data: { id: messageId },
                                dataType: 'json',
                                headers: {
                                    'X-Requested-With': 'XMLHttpRequest'
                                },
                                success: function(response) {
                                    if (response.status === 'success') {
                                        // Eliminar la fila de la tabla
                                        document.getElementById('row-' + messageId).remove();
                                        
                                        Swal.fire(
                                            '¡Eliminado!',
                                            response.message,
                                            'success'
                                        );
                                        
                                        // Actualizar contador de mensajes totales
                                        const totalMessages = document.querySelector('.small-box h3');
                                        if (totalMessages) {
                                            const currentCount = parseInt(totalMessages.textContent);
                                            totalMessages.textContent = currentCount - 1;
                                        }
                                    } else {
                                        Swal.fire(
                                            'Error',
                                            response.message,
                                            'error'
                                        );
                                    }
                                },
                                error: function() {
                                    Swal.fire(
                                        'Error',
                                        'Ha ocurrido un error al intentar eliminar el mensaje',
                                        'error'
                                    );
                                }
                            });
                        }
                    });
                });
            });
        });
    </script>
</body>

</html>
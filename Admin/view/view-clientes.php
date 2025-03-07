<?php
include_once '../config/conexion.php';
include_once '../model/model-cliente.php';

$database = new Database();
$db = $database->getConnection();
$cliente = new Cliente($db);

// Cambia esta línea
$resultados = $cliente->readAll();

// Si la línea 88 es el bucle while, asegúrate de que quede así:
// <?php while ($row = $resultados->fetch(PDO::FETCH_ASSOC)) : ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Macablue | Admin - Gestión de clientes</title>
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
        <?php
        include 'nav.php';
        include 'menu-lateral.php';
        ?>

        <div class="container my-4">
            <h2 class="text-center mb-4">Gestión de clientes</h2>

            <!-- Formulario para agregar o editar Clientes -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 id="formHeader">Agregar Nuevo cliente</h5>
                </div>
                <div class="card-body">
                    <form id="formCliente" method="POST" action="../controller/controllercliente.php">
                        <input type="hidden" name="cliente_id" id="cliente_id">
                        <input type="hidden" name="action" id="formAction" value="addUser">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="nombreCliente" class="form-label">Nombre</label>
                                <input type="text" name="nombreCliente" class="form-control" id="nombreCliente" placeholder="Ej. John" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="apellidoCliente" class="form-label">Apellido</label>
                                <input type="text" name="apellidoCliente" class="form-control" id="apellidoCliente" placeholder="Ej. Doe" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="emailCliente" class="form-label">Correo Electrónico</label>
                            <input type="email" name="emailCliente" class="form-control" id="emailCliente" placeholder="Cliente@ejemplo.com" required>
                        </div>
                        <div class="mb-3">
                            <label for="passwordCliente" class="form-label">Contraseña</label>
                            <input type="password" name="passwordCliente" class="form-control" id="passwordCliente" placeholder="Contraseña" required>
                        </div>
                        <div class="text-end">
                            <button type="submit" id="submitButton" class="btn btn-primary">Agregar Cliente</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Tabla de Clientes -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5>Lista de Clientes</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead class="table-dark">
                                <tr>
                                    <th>Nombre</th>
                                    <th>Apellido</th>
                                    <th>Email</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = $resultados->fetch(PDO::FETCH_ASSOC)) : ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($row['nombreCliente']); ?></td>
                                        <td><?php echo htmlspecialchars($row['apellidoCliente']); ?></td>
                                        <td><?php echo htmlspecialchars($row['emailCliente']); ?></td>
                                        <td>
                                            <button class="btn btn-warning btn-sm me-2" onclick="editUser(<?php echo $row['cliente_id']; ?>)">
                                                <i class="bi bi-pencil"></i> Editar
                                            </button>
                                            <button class="btn btn-danger btn-sm" onclick="confirmDeleteUser(<?php echo $row['cliente_id']; ?>)">
                                                <i class="bi bi-trash"></i> Eliminar
                                            </button>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <?php include 'footer.php'; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.3.0/browser/overlayscrollbars.browser.es6.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
                function validarFormulario() {
                let nombre = document.getElementById('nombreCliente').value.trim();
                let apellido = document.getElementById('apellidoCliente').value.trim();
                let email = document.getElementById('emailCliente').value.trim();
                let password = document.getElementById('passwordCliente').value.trim();

                if (nombre === '' || apellido === '' || email === '' || password === '') {
                    Swal.fire('Error', 'Todos los campos son obligatorios.', 'error');
                    return false;
                }

                // Validar formato de correo electrónico
                let emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(email)) {
                    Swal.fire('Error', 'Ingrese un correo electrónico válido.', 'error');
                    return false;
                }

                return true;
            }

        document.getElementById('formCliente').addEventListener('submit', function (e) {
            e.preventDefault();
            if (validarFormulario()) {
                const formData = new FormData(this);
                fetch('../controller/controllercliente.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    Swal.fire(data.status === "success" ? 'Éxito' : 'Error', data.message, data.status)
                        .then(() => data.status === "success" && location.reload());
                })
                .catch(error => {
                    console.error("Error:", error);
                    Swal.fire('Error', 'Ocurrió un error al procesar la solicitud.', 'error');
                });
            }
        });

        function confirmDeleteUser(userId) {
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
                    const formData = new FormData();
                    formData.append("action", "deleteUser");
                    formData.append("cliente_id", userId);

                    fetch('../controller/controllercliente.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        Swal.fire(data.status === "success" ? 'Éxito' : 'Error', data.message, data.status)
                            .then(() => data.status === "success" && location.reload());
                    })
                    .catch(error => {
                        console.error("Error:", error);
                        Swal.fire('Error', 'Ocurrió un error al intentar eliminar el cliente.', 'error');
                    });
                }
            });
        }

        function editUser(userId) {
            fetch(`../controller/controllercliente.php?action=getUser&id=${userId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        Swal.fire('Error', data.error, 'error');
                    } else {
                        document.getElementById('cliente_id').value = data.cliente_id;
                        document.getElementById('nombreCliente').value = data.nombreCliente;
                        document.getElementById('apellidoCliente').value = data.apellidoCliente;
                        document.getElementById('emailCliente').value = data.emailCliente;
                        document.getElementById('passwordCliente').required = false;
                        document.getElementById('formAction').value = 'editUser';
                        document.getElementById('submitButton').innerText = 'Actualizar Cliente';
                        document.getElementById('formHeader').innerText = 'Editar Cliente';
                    }
                })
                .catch(error => {
                    console.error("Error:", error);
                    Swal.fire('Error', 'Error al obtener los datos del cliente.', 'error');
                });
        }
    </script>
</body>
</html>

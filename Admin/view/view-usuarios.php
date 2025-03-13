<?php
include_once '../config/conexion.php';
include_once '../model/model-usuarios.php';
require_once '../middleware/AuthMiddleware.php';

// Verificar permisos (solo Administrador puede acceder)
AuthMiddleware::requireRole('Administrador');
$database = new Database();
$db = $database->getConnection();
$usuario = new Usuario($db);

$usuarios = $usuario->readAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Macablue | Admin - Gestión de Usuarios</title>
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
            <h2 class="text-center mb-4">Gestión de Usuarios Administrativos</h2>

            <!-- Formulario para agregar o editar usuarios -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 id="formHeader">Agregar Nuevo Usuario</h5>
                </div>
                <div class="card-body">
                    <form id="formUsuario" method="POST" action="../controller/controller-usuarios.php">
                        <input type="hidden" name="usuario_id" id="usuario_id">
                        <input type="hidden" name="action" id="formAction" value="addUser">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="nombreUsuario" class="form-label">Nombre</label>
                                <input type="text" name="nombreUsuario" class="form-control" id="nombreUsuario" placeholder="Ej. John" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="apellidoUsuario" class="form-label">Apellido</label>
                                <input type="text" name="apellidoUsuario" class="form-control" id="apellidoUsuario" placeholder="Ej. Doe" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="emailUsuario" class="form-label">Correo Electrónico</label>
                            <input type="email" name="emailUsuario" class="form-control" id="emailUsuario" placeholder="usuario@ejemplo.com" required>
                        </div>
                        <div class="mb-3">
                            <label for="rolUsuario" class="form-label">Rol</label>
                            <select name="rolUsuario" id="rolUsuario" class="form-select" required>
                                <option selected disabled value="">Seleccione un rol</option>
                                <option value="Administrador">Administrador</option>
                                <option value="Empleado">Empleado</option>
                                <option value="Supervisor">Supervisor</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="passwordUsuario" class="form-label">Contraseña</label>
                            <input type="password" name="passwordUsuario" class="form-control" id="passwordUsuario" placeholder="Contraseña" required>
                        </div>
                        <div class="text-end">
                            <button type="submit" id="submitButton" class="btn btn-primary">Agregar Usuario</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Tabla de usuarios -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5>Lista de Usuarios</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead class="table-dark">
                                <tr>
                                    <th>Nombre</th>
                                    <th>Apellido</th>
                                    <th>Email</th>
                                    <th>Rol</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = $usuarios->fetch(PDO::FETCH_ASSOC)) : ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($row['nombreUsuario']); ?></td>
                                        <td><?php echo htmlspecialchars($row['apellidoUsuario']); ?></td>
                                        <td><?php echo htmlspecialchars($row['emailUsuario']); ?></td>
                                        <td><?php echo htmlspecialchars($row['rolUsuario']); ?></td>
                                        <td>
                                            <button class="btn btn-warning btn-sm me-2" onclick="editUser(<?php echo $row['usuario_id']; ?>)">
                                                <i class="bi bi-pencil"></i> Editar
                                            </button>
                                            <button class="btn btn-danger btn-sm" onclick="confirmDeleteUser(<?php echo $row['usuario_id']; ?>)">
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
            const rolUsuario = document.getElementById("rolUsuario").value;
            if (rolUsuario === "") {
                Swal.fire('Error', 'Por favor, seleccione un rol', 'error');
                return false;
            }
            return true;
        }

        document.getElementById('formUsuario').addEventListener('submit', function (e) {
            e.preventDefault();
            if (validarFormulario()) {
                const formData = new FormData(this);
                fetch('../controller/controller-usuarios.php', {
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
                    formData.append("usuario_id", userId);

                    fetch('../controller/controller-usuarios.php', {
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
                        Swal.fire('Error', 'Ocurrió un error al intentar eliminar el usuario.', 'error');
                    });
                }
            });
        }

        function editUser(userId) {
            fetch(`../controller/controller-usuarios.php?action=getUser&id=${userId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        Swal.fire('Error', data.error, 'error');
                    } else {
                        document.getElementById('usuario_id').value = data.usuario_id;
                        document.getElementById('nombreUsuario').value = data.nombreUsuario;
                        document.getElementById('apellidoUsuario').value = data.apellidoUsuario;
                        document.getElementById('emailUsuario').value = data.emailUsuario;
                        document.getElementById('rolUsuario').value = data.rolUsuario;
                        document.getElementById('passwordUsuario').required = false;
                        document.getElementById('formAction').value = 'editUser';
                        document.getElementById('submitButton').innerText = 'Actualizar Usuario';
                        document.getElementById('formHeader').innerText = 'Editar Usuario';
                    }
                })
                .catch(error => {
                    console.error("Error:", error);
                    Swal.fire('Error', 'Error al obtener los datos del usuario.', 'error');
                });
        }
    </script>
</body>
</html>

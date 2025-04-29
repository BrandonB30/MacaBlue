<?php
session_start();
$mensaje = isset($_SESSION['mensaje']) ? $_SESSION['mensaje'] : "";
$tipoMensaje = isset($_SESSION['tipoMensaje']) ? $_SESSION['tipoMensaje'] : "";

// Verificar si hay un parámetro de verificación y guardarlo en una variable
$verificacion = isset($_GET['verificacion']) ? $_GET['verificacion'] : 0;

// Limpiar variables de sesión una vez que se han utilizado
unset($_SESSION['mensaje'], $_SESSION['tipoMensaje']);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <link rel="shortcut icon" href="/MacaBlue/Cliente/assets/img/favicon.png" type="image/x-icon">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - MacaBlue</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/MacaBlue/Cliente/assets/css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        /* Paleta de colores */
        :root {
            --fucsia-claro: #ff66cc;
            --fucsia-pastel: #ff99cc;
            --fondo-oscuro: #2c2f33;
            --fondo-claro: #f8f9fa;
            --fuente-bonita: 'Poppins', sans-serif;
        }

        /* Estilos personalizados para el formulario de inicio de sesión */
        body {
            background-color: var(--fondo-claro);
            font-family: var(--fuente-bonita);
            color: var(--fondo-oscuro);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .login-container {
            max-width: 400px;
            width: 100%;
            padding: 40px;
            background-color: white;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            text-align: center;
        }

        .login-container h2 {
            color: var(--fucsia-claro);
            font-weight: bold;
            margin-bottom: 20px;
        }

        .login-container .form-control {
            border-radius: 10px;
        }

        .login-container .btn-primary {
            background-color: var(--fucsia-claro);
            border-color: var(--fucsia-claro);
            width: 100%;
            font-weight: bold;
            color: white;
        }

        .login-container .btn-primary:hover {
            background-color: var(--fucsia-pastel);
            border-color: var(--fucsia-pastel);
        }

        .login-container p {
            margin-top: 15px;
        }

        .login-container p a {
            color: var(--fucsia-claro);
            font-weight: bold;
            text-decoration: none;
        }

        .login-container p a:hover {
            color: var(--fucsia-pastel);
        }
    </style>
</head>

<body>
    <?php if (!empty($mensaje)) : ?>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    icon: '<?php echo $tipoMensaje === "success" ? "success" : "error"; ?>',
                    title: '<?php echo $tipoMensaje === "success" ? "¡Éxito!" : "Error"; ?>',
                    text: '<?php echo htmlspecialchars($mensaje); ?>',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    position: 'top-end',
                    toast: true
                });
                
                // No eliminamos el parámetro verificacion=1 si está presente
                // Solo eliminamos otros parámetros si es necesario
                if (window.history.replaceState && window.location.search && !window.location.search.includes('verificacion=1')) {
                    const newUrl = window.location.protocol + "//" + window.location.host + window.location.pathname;
                    window.history.replaceState({ path: newUrl }, '', newUrl);
                }
            });
        </script>
    <?php endif; ?>

    <div class="login-container">
        <h2><i class="fas fa-sign-in-alt"></i> Iniciar Sesión</h2>
        <form action="../controllers/IngresoController.php" method="POST">
            <div class="mb-3">
                <label for="email" class="form-label">Correo Electrónico</label>
                <input type="email" class="form-control" id="email" name="email" autocomplete="username" required>
            </div>
            <div class="mb-3">
                <label for="contrasena" class="form-label">Contraseña</label>
                <input type="password" class="form-control" id="contrasena" name="contrasena" autocomplete="current-password" required>
            </div>
            <button type="submit" class="btn btn-primary">Ingresar</button>
        </form>
        <p>¿No tienes una cuenta? <a href="registro.php">Regístrate aquí</a></p>
        <p>¿Eres Administrador? <a href="/MacaBlue/Admin/login.php">Inicia sesión</a></p>
        <p>¿Olvidaste tu contraseña? <a href="#" data-bs-toggle="modal" data-bs-target="#recuperarContrasenaModal">Recupérala aquí</a></p>
    </div>

    <div class="modal fade" id="recuperarContrasenaModal" tabindex="-1" aria-labelledby="recuperarContrasenaLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="formRecuperar" action="../controllers/RecuperarContrasenaController.php" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title" id="recuperarContrasenaLabel">Recuperar Contraseña</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <!-- Paso 1: ingresar correo -->
                    <div class="mb-3" id="paso1Email">
                        <label for="emailRecuperacion" class="form-label">Correo electrónico</label>
                        <input type="email" class="form-control" id="emailRecuperacion" name="email" autocomplete="email" required>
                    </div>

                    <!-- Paso 2: ingresar código y nueva contraseña (se ocultan inicialmente) -->
                    <div id="codigoNuevaContrasena" style="display: none;">
                        <div class="mb-3">
                            <label for="codigo" class="form-label">Código de verificación</label>
                            <input type="text" class="form-control" id="codigo" name="codigo" placeholder="123456" autocomplete="one-time-code">
                        </div>
                        <div class="mb-3 position-relative">
                            <label for="nueva_contrasena" class="form-label">Nueva contraseña</label>
                            <input type="password" class="form-control" id="nueva_contrasena" name="nueva_contrasena" minlength="6" autocomplete="new-password">
                            <i class="fa fa-eye position-absolute top-50 end-0 translate-middle-y me-3" style="cursor:pointer;" onclick="togglePassword('nueva_contrasena', this)"></i>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary" id="btnEnviar">Enviar código</button>
                </div>
                <!-- Campo oculto para indicar si estamos en modo verificación -->
                <input type="hidden" name="modo_verificacion" id="modo_verificacion" value="<?php echo $verificacion; ?>">
            </form>
        </div>
    </div>
</div>

<!-- Scripts de Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- Script para mostrar/ocultar contraseña y manejar el modal de recuperación -->
<script>
    function togglePassword(inputId, icon) {
        const input = document.getElementById(inputId);
        if (input.type === "password") {
            input.type = "text";
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            input.type = "password";
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }
    
    // Función para manejar el modal de recuperación de contraseña
    document.addEventListener('DOMContentLoaded', function() {
        // Verificar si hay un parámetro verificacion en la URL
        const urlParams = new URLSearchParams(window.location.search);
        
        // Si hay mensaje de SweetAlert, esperar a que termine antes de mostrar el modal
        let showModalAfterAlert = urlParams.has('verificacion');
        
        // Si hay mensaje de alerta y estamos en modo verificación
        if (showModalAfterAlert) {
            // Si hay un elemento con mensaje SweetAlert
            const alertElement = document.querySelector('script:not([src])');
            if (alertElement && alertElement.textContent.includes('Swal.fire')) {
                // Esperamos a que termine la alerta antes de mostrar el modal
                setTimeout(function() {
                    showVerificationModal();
                }, 1000); // Esperar un segundo después del SweetAlert
            } else {
                // No hay SweetAlert, mostrar modal inmediatamente
                showVerificationModal();
            }
        }
        
        function showVerificationModal() {
            // Configurar el formulario para el segundo paso
            document.getElementById('codigoNuevaContrasena').style.display = 'block';
            document.getElementById('paso1Email').style.display = 'none';
            document.getElementById('btnEnviar').textContent = 'Cambiar contraseña';
            document.getElementById('modo_verificacion').value = '1';
            
            // Eliminar el atributo required de los campos ocultos
            document.getElementById('emailRecuperacion').removeAttribute('required');
            
            // Añadir el atributo required a los campos visibles
            document.getElementById('codigo').setAttribute('required', '');
            document.getElementById('nueva_contrasena').setAttribute('required', '');
            
            // Recuperar el email del localStorage y añadirlo como campo oculto
            if (localStorage.getItem('emailRecuperacion')) {
                // Verificar si ya existe un campo oculto para el email
                let emailInput = document.querySelector('input[name="email"][type="hidden"]');
                if (!emailInput) {
                    // Si no existe, crear uno nuevo
                    emailInput = document.createElement('input');
                    emailInput.type = 'hidden';
                    emailInput.name = 'email';
                    document.getElementById('formRecuperar').appendChild(emailInput);
                }
                // Asignar el valor
                emailInput.value = localStorage.getItem('emailRecuperacion');
            }
            
            // Mostrar el modal automáticamente
            const recuperarContrasenaModal = document.getElementById('recuperarContrasenaModal');
            const modal = new bootstrap.Modal(recuperarContrasenaModal);
            modal.show();
            
            // Hacer que el modal no se cierre al hacer clic fuera
            recuperarContrasenaModal.setAttribute('data-bs-backdrop', 'static');
            recuperarContrasenaModal.setAttribute('data-bs-keyboard', 'false');
            
            // Si se cierra el modal, redireccionar a la página sin parámetros
            recuperarContrasenaModal.addEventListener('hidden.bs.modal', function() {
                window.location.href = 'ingreso.php';
            });
        }
        
        // Manejar el envío del formulario de recuperación para preservar el email
        document.getElementById('formRecuperar').addEventListener('submit', function(e) {
            // Si estamos en el primer paso, guardar el email en localStorage
            if (document.getElementById('modo_verificacion').value === '0') {
                localStorage.setItem('emailRecuperacion', document.getElementById('emailRecuperacion').value);
            }
        });
    });
</script>
</body>
</html>
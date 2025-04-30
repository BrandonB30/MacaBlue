<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Macablue | Admin - Inicio de Sesión</title>
    <link rel="shortcut icon" href="./assets/img/favicon.png" type="image/x-icon">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" crossorigin="anonymous">
    <link rel="stylesheet" href="../Admin/Adminlte/dist/css/adminlte.css">
    <style>
        .login-container {
            max-width: 400px;
            width: 100%;
            position: relative;
        }
        html, body {
            height: 100%;
            overflow: hidden;
        }
        .bg-fixed {
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .custom-alert {
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            max-width: 350px;
            width: 100%;
            padding: 15px;
            border-radius: 8px;
            color: #fff;
            font-size: 1rem;
            text-align: center;
            z-index: 1050;
            display: none;
            animation: fadeIn 0.5s ease-out;
        }
        .alert-success { background-color: #28a745; }
        .alert-error { background-color: #dc3545; }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        #verificationCodeContainer {
            display: none;
        }
    </style>
</head>
<body class="bg-fixed bg-body-tertiary">

    <div class="custom-alert" id="customAlert"></div>

    <div class="card shadow-lg login-container">
        <div class="card-body p-5">
            <h3 class="text-center mb-4">Inicio de Sesión</h3>
            <form id="loginForm" method="POST">
                <div class="mb-3">
                    <label for="username" class="form-label">Usuario</label>
                    <input type="text" id="username" name="username" class="form-control" required autofocus>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Contraseña</label>
                    <input type="password" id="password" name="password" class="form-control" required>
                </div>
                <div id="verificationCodeContainer" class="mb-3">
                    <label for="verification_code" class="form-label">Código de Verificación</label>
                    <input type="text" id="verification_code" name="verification_code" class="form-control">
                </div>
                <div class="mb-3 form-check">
                    <input type="checkbox" id="rememberMe" name="rememberMe" class="form-check-input">
                    <label for="rememberMe" class="form-check-label">Recordarme</label>
                </div>
                <button type="submit" class="btn btn-primary w-100" id="submitButton">Iniciar Sesión</button>
            </form>
            <div class="text-center mt-3">
                <a href="http://localhost/MacaBlue/Admin/view/password-reset.php" class="text-decoration-none">¿Olvidaste tu contraseña?</a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>

    <script>
        function showCustomAlert(message, type) {
            const alertBox = document.getElementById('customAlert');
            alertBox.className = `custom-alert alert-${type}`;
            alertBox.textContent = message;
            alertBox.style.display = 'block';

            setTimeout(() => {
                alertBox.style.display = 'none';
            }, 3000);
        }

        document.getElementById('loginForm').addEventListener('submit', function (event) {
            event.preventDefault();

            const formData = new FormData(this);
            const verificationCodeContainer = document.getElementById('verificationCodeContainer');
            const submitButton = document.getElementById('submitButton');

            fetch('./controller/authenticate.php', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.text(); // Leer como texto para validar si es JSON
            })
            .then(text => {
                try {
                    const data = JSON.parse(text); // Intentar parsear como JSON
                    if (data.status === "pending") {
                        showCustomAlert(data.message, 'success');
                        verificationCodeContainer.style.display = 'block'; // Mostrar campo de código
                        submitButton.textContent = 'Verificar Código'; // Cambiar texto del botón
                    } else if (data.status === "success") {
                        showCustomAlert(data.message, 'success');
                        setTimeout(() => {
                            window.location.href = './view/view-dashboard.php';
                        }, 1500);
                    } else {
                        showCustomAlert(data.message, 'error');
                    }
                } catch (error) {
                    console.error('Respuesta no válida:', text); // Registrar respuesta no JSON
                    showCustomAlert('Error en la respuesta del servidor.', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error); // Registrar el error en la consola
                showCustomAlert('Error en la comunicación con el servidor.', 'error');
            });
        });
    </script>
</body>
</html>

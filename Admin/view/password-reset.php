<?php session_start(); ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Macablue | Restablecer Contraseña</title>
    <link rel="shortcut icon" href="./assets/img/favicon.png" type="image/x-icon">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" crossorigin="anonymous">
    <link rel="stylesheet" href="../Adminlte/dist/css/adminlte.css">
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
    </style>
</head>
<body class="bg-fixed bg-body-tertiary">
    <div class="custom-alert" id="customAlert"></div>

    <div class="card shadow-lg login-container">
        <div class="card-body p-5">
            <h3 class="text-center mb-4">Restablecer Contraseña</h3>
            <form id="password-reset-form" method="POST" action="../controller/password_reset.php">
                <div class="mb-3">
                    <label for="email" class="form-label">Correo Electrónico:</label>
                    <input type="email" id="email" name="email" class="form-control" required autocomplete="email">
                </div>

                <!-- Este bloque se mostrará solo después de la verificación del correo -->
                <div id="verification-section" class="mb-3" style="display: none;">
                    <label for="verification_code" class="form-label">Código de Verificación:</label>
                    <input type="text" id="verification_code" name="verification_code" class="form-control" autocomplete="one-time-code">
                    
                    <label for="new_password" class="form-label">Nueva Contraseña:</label>
                    <input type="password" id="new_password" name="new_password" class="form-control" autocomplete="new-password">
                    
                    <label for="confirm_password" class="form-label">Confirmar Contraseña:</label>
                    <input type="password" id="confirm_password" name="confirm_password" class="form-control" autocomplete="new-password">
                </div>

                <button type="submit" class="btn btn-primary w-100" id="submitButton">Enviar</button>
            </form>
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

        document.getElementById('password-reset-form').addEventListener('submit', async function (event) {
            event.preventDefault();
            const formData = new FormData(this);
            const verificationSection = document.getElementById('verification-section');
            const newPasswordField = document.getElementById('new_password');
            const confirmPasswordField = document.getElementById('confirm_password');

            const response = await fetch(this.action, {
                method: 'POST',
                body: formData
            });
            const result = await response.json();
            showCustomAlert(result.message, result.status === 'success' ? 'success' : 'error');

            if (result.status === 'pending') {
                verificationSection.style.display = 'block';
                // Add required attribute only when these fields become visible
                newPasswordField.setAttribute('required', 'true');
                confirmPasswordField.setAttribute('required', 'true');
                document.getElementById('submitButton').textContent = 'Verificar Código';
            } else if (result.status === 'success') {
                setTimeout(() => {
                    window.location.href = 'admin/login.php';
                }, 1500);
            }
        });
    </script>
</body>
</html>
<?php
session_start();
// Al inicio del archivo, después de session_start()
require_once dirname(__DIR__) . '/config/conexion.php';
require_once dirname(__DIR__) . '/Models/MensajeModel.php';
require_once dirname(__DIR__) . '/Controllers/ContactoController.php';

// Crear la conexión
$conexion = new Conexion();
$conn = $conexion->conectar(); 

// Crear el modelo con la conexión correcta
$mensajeModel = new MensajeModel($conn);
$controller = new ContactoController($mensajeModel);
// Procesar el formulario
$result = $controller->procesarFormulario();
$mensaje_enviado = isset($result['mensaje_enviado']) ? $result['mensaje_enviado'] : false;
$mensaje_error = isset($result['mensaje_error']) ? $result['mensaje_error'] : null;

$base_url = '/MacaBlue/cliente';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link rel="shortcut icon" href="<?php echo $base_url; ?>/assets/img/favicon.png" type="image/x-icon">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contacto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo $base_url; ?>/assets/css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

<body>
    <!-- Incluir el archivo de navegación -->
    <?php include 'nav.php'; ?>

    <div class="container mt-5">
        <div class="row">
            <div class="col-md-12 text-center mb-4">
                <h1 style="color: var(--fucsia-claro);">Contacto</h1>
                <p class="lead">Estamos aquí para ayudarte. No dudes en ponerte en contacto con nosotros.</p>
            </div>
        </div>

        <?php if ($mensaje_enviado): ?>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    icon: 'success',
                    title: '¡Mensaje enviado!',
                    text: 'Tu mensaje ha sido enviado con éxito. Te responderemos lo antes posible.',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    position: 'top-end',
                    toast: true
                });

                // Limpiar el parámetro ?success=1 de la URL
                if (window.history.replaceState) {
                    const newUrl = window.location.protocol + "//" + window.location.host + window.location.pathname;
                    window.history.replaceState({ path: newUrl }, '', newUrl);
                }
            });
        </script>
        <?php elseif ($mensaje_error): ?>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    icon: 'error',
                    title: 'Error al enviar',
                    text: '<?php echo htmlspecialchars($mensaje_error); ?>',
                    confirmButtonText: 'Aceptar',
                    position: 'center'
                });

                // Limpiar el parámetro ?success=1 de la URL
                if (window.history.replaceState) {
                    const newUrl = window.location.protocol + "//" + window.location.host + window.location.pathname;
                    window.history.replaceState({ path: newUrl }, '', newUrl);
                }
            });
        </script>
        <?php endif; ?>

        <div class="row mb-5">
            <div class="col-md-6">
                <div class="card border-0 shadow">
                    <div class="card-body">
                        <h2 class="mb-4" style="color: var(--fucsia-claro);">Envíanos un mensaje</h2>
                        <form method="POST" action="">
                            <div class="mb-3">
                                <label for="nombre" class="form-label">Nombre completo</label>
                                <input type="text" class="form-control" id="nombre" name="nombre" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Correo electrónico</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            <div class="mb-3">
                                <label for="asunto" class="form-label">Asunto</label>
                                <input type="text" class="form-control" id="asunto" name="asunto" required>
                            </div>
                            <div class="mb-3">
                                <label for="mensaje" class="form-label">Mensaje</label>
                                <textarea class="form-control" id="mensaje" name="mensaje" rows="5" required></textarea>
                            </div>
                            <button type="submit" name="enviar_mensaje" class="btn btn-primary">Enviar mensaje</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card border-0 shadow h-100">
                    <div class="card-body">
                        <h2 class="mb-4" style="color: var(--fucsia-claro);">Información de contacto</h2>
                        <div class="d-flex mb-4">
                            <div class="me-3">
                                <i class="fas fa-map-marker-alt fa-2x" style="color: var(--fucsia-claro);"></i>
                            </div>
                            <div>
                                <h4>Dirección</h4>
                                <p>Av. Principal 123, Bogotá, Colombia</p>
                            </div>
                        </div>
                        <div class="d-flex mb-4">
                            <div class="me-3">
                                <i class="fas fa-phone fa-2x" style="color: var(--fucsia-claro);"></i>
                            </div>
                            <div>
                                <h4>Teléfono</h4>
                                <p>+123 456 7890</p>
                            </div>
                        </div>
                        <div class="d-flex mb-4">
                            <div class="me-3">
                                <i class="fas fa-envelope fa-2x" style="color: var(--fucsia-claro);"></i>
                            </div>
                            <div>
                                <h4>Email</h4>
                                <p>info@macablue.com</p>
                            </div>
                        </div>
                        <div class="d-flex mb-4">
                            <div class="me-3">
                                <i class="fas fa-clock fa-2x" style="color: var(--fucsia-claro);"></i>
                            </div>
                            <div>
                                <h4>Horario de atención</h4>
                                <p>Lunes - Viernes: 9:00 - 18:00<br>Sábados: 10:00 - 14:00</p>
                            </div>
                        </div>
                        <div class="mt-4">
                            <h4>Síguenos en redes sociales</h4>
                            <div class="social-icons mt-3">
                                <a href="https://www.facebook.com/share/1AVspFX6cp/" class="me-3"><i class="fab fa-facebook fa-2x" style="color: var(--fucsia-claro);"></i></a>
                                <a href="https://www.instagram.com/macablue2025?igsh=enZ3ZDBiZzBnbHMw" class="me-3"><i class="fab fa-instagram fa-2x" style="color: var(--fucsia-claro);"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-5">
            <div class="col-md-12">
                <h2 class="mb-4 text-center" style="color: var(--fucsia-claro);">Encuéntranos</h2>
                <div class="map-container">
                    <iframe 
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3976.5818884703535!2d-74.10566992526135!3d4.668385095306515!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x8e3f9b9e2b2a82a1%3A0x761d0701a1e01f06!2sUniversidad%20Libre%20Sede%20El%20Bosque!5e0!3m2!1ses-419!2sco!4v1745728196697!5m2!1ses-419!2sco"  
                        width="100%" 
                        height="450" 
                        style="border:0;" 
                        allowfullscreen="" 
                        loading="lazy" 
                        referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                </div>
            </div>
        </div>

        <div class="row mb-5">
            <div class="col-md-12">
                <div class="card border-0 shadow">
                    <div class="card-body">
                        <h2 class="mb-4 text-center" style="color: var(--fucsia-claro);">Preguntas Frecuentes</h2>
                        <div class="accordion" id="accordionFAQ">
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingOne">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                        ¿Cómo puedo realizar un pedido?
                                    </button>
                                </h2>
                                <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionFAQ">
                                    <div class="accordion-body">
                                        Para realizar un pedido, simplemente navega por nuestra tienda, selecciona los productos que deseas, añádelos al carrito y sigue el proceso de pago. Es necesario registrarse o iniciar sesión para completar la compra.
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingTwo">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                        ¿Cuáles son los métodos de pago aceptados?
                                    </button>
                                </h2>
                                <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionFAQ">
                                    <div class="accordion-body">
                                        Aceptamos pagos con tarjetas de crédito y débito (Visa, MasterCard, American Express), PayPal, transferencia bancaria y pago contra entrega en algunas zonas.
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingThree">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                        ¿Cuál es la política de devoluciones?
                                    </button>
                                </h2>
                                <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#accordionFAQ">
                                    <div class="accordion-body">
                                        Aceptamos devoluciones dentro de los 14 días posteriores a la recepción del pedido. Los productos deben estar en su estado original, sin usar y con todas las etiquetas. Contacta con nuestro servicio de atención al cliente para iniciar el proceso.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include 'footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
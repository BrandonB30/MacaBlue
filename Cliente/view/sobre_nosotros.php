<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "macablue";

// Conectar a la base de datos
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Error en la conexión: " . $conn->connect_error);
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sobre Nosotros - MacaBlue</title>
    <link rel="shortcut icon" href="./assets/img/favicon.png" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/MacaBlue/assets/css/style.css">
    <link rel="stylesheet" href="/MacaBlue/assets/css/nav.css">
</head>

<body>
    <!-- Incluir el archivo de navegación -->
    <?php include 'nav.php'; ?>

    <div class="container mt-5">
        <div class="row">
            <div class="col-md-12 text-center mb-4">
                <h1 style="color: var(--fucsia-claro);">Sobre Nosotros</h1>
            </div>
        </div>

        <div class="row mb-5">
            <div class="col-md-6">
                <img src="/MacaBlue/assets/img/andrei-stan-d56QrDc_Sk0-unsplash.jpg" alt="Nuestra Tienda" class="img-fluid rounded shadow">
            </div>
            <div class="col-md-6">
                <h2 class="mb-4" style="color: var(--fucsia-claro);">Nuestra Historia</h2>
                <p>MacaBlue nació en 2018 con la visión de ofrecer ropa de alta calidad y diseño exclusivo a precios accesibles. Desde nuestros humildes inicios en un pequeño local, hemos crecido hasta convertirnos en una de las tiendas de moda más reconocidas de la región.</p>
                <p>Nuestro compromiso con la calidad y la atención personalizada nos ha permitido ganarnos la confianza de miles de clientes que vuelven a elegirnos temporada tras temporada.</p>
                <p>En MacaBlue creemos que la moda debe ser para todos, por eso trabajamos constantemente para ofrecer diseños innovadores que se adapten a todos los gustos y estilos.</p>
            </div>
        </div>

        <div class="row mb-5">
            <div class="col-md-6 order-md-2">
                <img src="/MacaBlue/assets/img/austin-distel-rxpThOwuVgE-unsplash.jpg" alt="Nuestro Equipo" class="img-fluid rounded shadow">
            </div>
            <div class="col-md-6 order-md-1">
                <h2 class="mb-4" style="color: var(--fucsia-claro);">Nuestro Equipo</h2>
                <p>Detrás de MacaBlue hay un equipo de profesionales apasionados por la moda y el servicio al cliente. Nuestros diseñadores están constantemente buscando las últimas tendencias para ofrecerte lo mejor en cada colección.</p>
                <p>Nuestro personal de ventas está altamente capacitado para brindarte asesoramiento personalizado y ayudarte a encontrar las prendas que mejor se adapten a tu estilo y necesidades.</p>
                <p>En MacaBlue somos una gran familia que trabaja unida para ofrecerte la mejor experiencia de compra posible.</p>
            </div>
        </div>

        <div class="row mb-5">
            <div class="col-md-12">
                <h2 class="mb-4 text-center" style="color: var(--fucsia-claro);">Nuestros Valores</h2>
                <div class="row">
                    <div class="col-md-4 text-center mb-4">
                        <div class="card h-100 border-0 shadow">
                            <div class="card-body">
                                <i class="fas fa-heart fa-3x mb-3" style="color: var(--fucsia-claro);"></i>
                                <h3>Pasión</h3>
                                <p>Amamos lo que hacemos y nos esforzamos cada día para ofrecerte lo mejor.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 text-center mb-4">
                        <div class="card h-100 border-0 shadow">
                            <div class="card-body">
                                <i class="fas fa-handshake fa-3x mb-3" style="color: var(--fucsia-claro);"></i>
                                <h3>Compromiso</h3>
                                <p>Nos comprometemos a ofrecerte productos de calidad y un servicio excepcional.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 text-center mb-4">
                        <div class="card h-100 border-0 shadow">
                            <div class="card-body">
                                <i class="fas fa-leaf fa-3x mb-3" style="color: var(--fucsia-claro);"></i>
                                <h3>Sostenibilidad</h3>
                                <p>Trabajamos para minimizar nuestro impacto ambiental y promover prácticas sostenibles.</p>
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
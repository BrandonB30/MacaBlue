<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$userRole = isset($_SESSION['user_role']) ? $_SESSION['user_role'] : '';
?>

<nav class="app-header navbar navbar-expand bg-body">
    <div class="container-fluid">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button">
                    <i class="bi bi-list"></i>
                </a>
            </li>
            <li class="nav-item d-none d-md-block">
                <a href="#" class="nav-link">Inicio</a>
            </li>
        </ul>
        <ul class="navbar-nav ms-auto">
            <li class="nav-item dropdown">
                <a class="nav-link" data-bs-toggle="dropdown" href="#">
                    <i class="bi bi-bell-fill"></i>
                    <span class="navbar-badge badge text-bg-warning">15</span>
                </a>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
                        <i class="bi bi-envelope me-2"></i> 4 new messages
                        <span class="float-end text-secondary fs-7">3 mins</span>
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="#" class="dropdown-item">
                        <i class="bi bi-people-fill me-2"></i> 8 friend requests
                        <span class="float-end text-secondary fs-7">12 hours</span>
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="#" class="dropdown-item">
                        <i class="bi bi-file-earmark-fill me-2"></i> 3 new reports
                        <span class="float-end text-secondary fs-7">2 days</span>
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="#" class="dropdown-item dropdown-footer">See All Notifications</a>
                </div>
            </li>
            <li class="nav-item dropdown user-menu">
                <a href="#" class="nav-link dropdown-toggle d-flex align-items-center" data-bs-toggle="dropdown">
                    <span class="d-none d-md-inline me-1">
                    <?php
                        // Mostrar el nombre del usuario si está autenticado
                        if (isset($_SESSION['user_name'])) {
                            echo htmlspecialchars($_SESSION['user_name']);
                        } else {
                            echo "Usuario";
                        }

                        // Agregar un espacio solo si el rol también está definido
                        if (isset($_SESSION['user_role'])) {
                            echo " " . htmlspecialchars($_SESSION['user_role']);
                        } else {
                            echo " Usuario";
                        }
                        ?>
                    </span>
                    <i class="bi bi-chevron-down"></i> <!-- Icono de flecha hacia abajo -->
                </a>
                <ul class="dropdown-menu dropdown-menu-sm dropdown-menu-end">
                    <li class="user-body">
                        <a href="profile.php" class="dropdown-item">Perfil</a>
                        <a href="../controller/logout.php" class="dropdown-item">Cerrar sesión</a>
                    </li>
                </ul>
            </li>

        </ul>
    </div>
</nav>
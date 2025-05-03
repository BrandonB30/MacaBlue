<?php 
// Iniciar sesión solo si no está ya iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
// Verificar si el rol está establecido en la sesión
$userRole = isset($_SESSION['user_role']) ? $_SESSION['user_role'] : '';
?>

<style>
    html, body {
    height: 100%;
    margin: 0;
}

.app-sidebar {
    height: 100%;
    display: flex;
    flex-direction: column;
}

.sidebar-wrapper {
    flex: 1;
    overflow-y: auto;
}
</style>
<aside class="app-sidebar bg-body-secondary shadow-lg p-3" data-bs-theme="dark">
    <div class="sidebar-brand mb-4">
        <a href="\MacaBlue\Admin\view\view-dashboard.php" class="brand-link d-flex align-items-center">
            <img src="../assets/img/MacaBlue.jpg" alt="Store Logo" class="brand-image opacity-75 shadow rounded-circle me-2">
            <span class="brand-text fw-bold">MacaBlue</span>
        </a>
    </div>
    <div class="sidebar-wrapper">
        <nav class="mt-2">
            <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="menu" data-accordion="false">
                <li class="nav-item">
                    <a href="\MacaBlue\Admin\view\view-dashboard.php" class="nav-link active d-flex align-items-center">
                    <i class="nav-icon bi bi-speedometer me-2"></i>
                        <p class="mb-0">Dashboard</p>
                    </a>
                </li>
                
                <!-- Visible para todos los roles -->
                <li class="nav-item">
                    <a href="\MacaBlue\Admin\view\view-productos.php" class="nav-link d-flex align-items-center">
                        <i class="nav-icon bi bi-box-seam me-2"></i>
                        <p class="mb-0">Productos</p>
                    </a>
                </li>
                
                <!-- Visible para todos los roles -->
                <li class="nav-item">
                    <a href="\MacaBlue\Admin\view\view-categorias.php" class="nav-link d-flex align-items-center">
                        <i class="nav-icon bi bi-tags me-2"></i>
                        <p class="mb-0">Categorías</p>
                    </a>
                </li>
                
                <!-- Visible para todos los roles -->
                <li class="nav-item">
                    <a href="\MacaBlue\Admin\view\view-pedidos.php" class="nav-link d-flex align-items-center">
                        <i class="nav-icon bi bi-cart me-2"></i>
                        <p class="mb-0">Pedidos</p>
                    </a>
                </li>
                
                <!-- Visible para 'Administrador' y 'Empleado' -->
                <?php if($userRole == 'Administrador' || $userRole == 'Empleado'): ?>
                <li class="nav-item">
                    <a href="\MacaBlue\Admin\view\view-clientes.php" class="nav-link d-flex align-items-center">
                        <i class="nav-icon bi bi-people me-2"></i>
                        <p class="mb-0">Clientes</p>
                    </a>
                </li>
                <?php endif; ?>

                <!-- Visible para 'Administrador' y 'Empleado' -->
                <?php if($userRole == 'Administrador' || $userRole == 'Empleado'): ?>
                <li class="nav-item">
                    <a href="\MacaBlue\Admin\view\contact_messages.php" class="nav-link d-flex align-items-center">
                        <i class="nav-icon bi bi-people me-2"></i>
                        <p class="mb-0">Mensajes</p>
                    </a>
                </li>
                <?php endif; ?>
                
                <!-- Sólo visible para 'Administrador' -->
                <?php if($userRole == 'Administrador'): ?>
                <li class="nav-item">
                    <a href="\MacaBlue\Admin\view\view-usuarios.php" class="nav-link d-flex align-items-center" id="usuarios-link">
                        <i class="nav-icon bi bi-person-circle me-2"></i>
                        <p class="mb-0">Usuarios</p>
                    </a>
                </li>
                <?php endif; ?>
            </ul>
        </nav>
    </div>
</aside>
<?php
session_start();
session_unset();  // Eliminar todas las variables de sesión
session_destroy();  // Destruir la sesión

// Redirigir al usuario a la página de inicio de sesión con un mensaje opcional
header("Location: ../login.php?logout=success");
exit;

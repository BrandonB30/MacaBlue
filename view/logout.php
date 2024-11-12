<?php
session_start();
session_unset();
session_destroy();
header("Location: /MacaBlue/view/ingreso.php"); // Redirige a la página de inicio de sesión
exit();

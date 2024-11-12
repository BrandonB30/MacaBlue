<?php
session_start();
require_once '../model/ecommerce.sql'; // Conexión a la base de datos

if (!isset($_SESSION['cliente_id'])) {
    echo "<script>alert('Debes iniciar sesión para realizar el pago'); window.location.href = 'ingreso.php';</script>";
    exit();
}

echo "<script>alert('Pago exitoso. Gracias por tu compra'); window.location.href = 'index.php';</script>";

$cliente_id = $_SESSION['cliente_id'];
$conn->query("DELETE FROM carrito WHERE usuario_id = $cliente_id");
?>

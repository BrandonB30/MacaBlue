<?php
// Incluir archivos necesarios
require_once '../Admin/config/conexion.php';
require_once '../Admin/Model/CartModel.php';

// Iniciar sesión si no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['cliente_id'])) {
    echo "<script>alert('Debes iniciar sesión para realizar esta acción'); window.location.href = 'ingreso.php';</script>";
    exit();
}

// Verificar si se recibió el ID del carrito
if (!isset($_POST['carrito_id']) || empty($_POST['carrito_id'])) {
    echo "<script>alert('Error: No se especificó el producto a eliminar'); window.location.href = 'carrito.php';</script>";
    exit();
}

$carrito_id = $_POST['carrito_id'];
$cliente_id = $_SESSION['cliente_id'];

// Crear instancia de la base de datos
$database = new Database();
$conn = $database->getConnection();

// Crear instancia del modelo del carrito
$cartModel = new CartModel($conn);

// Eliminar el producto del carrito
$result = $cartModel->deleteCartItem($carrito_id, $cliente_id);

if ($result) {
    echo "<script>alert('Producto eliminado del carrito'); window.location.href = 'carrito.php';</script>";
} else {
    echo "<script>alert('Error al eliminar el producto'); window.location.href = 'carrito.php';</script>";
}
?>
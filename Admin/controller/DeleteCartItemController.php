<?php
require_once 'Models/CartModel.php';

class DeleteCartItemController {
    private $cartModel;
    private $db;
    
    public function __construct($db) {
        $this->db = $db;
        $this->cartModel = new CartModel($db);
    }
    
    public function delete() {
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
        
        $cartId = $_POST['carrito_id'];
        $userId = $_SESSION['cliente_id'];
        
        // Eliminar el producto del carrito
        $result = $this->cartModel->deleteCartItem($cartId, $userId);
        
        if ($result) {
            echo "<script>alert('Producto eliminado del carrito'); window.location.href = 'carrito.php';</script>";
        } else {
            echo "<script>alert('Error al eliminar el producto'); window.location.href = 'carrito.php';</script>";
        }
    }
}
?>
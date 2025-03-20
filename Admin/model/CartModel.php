<?php
class CartModel {
    private $conn;
    
    public function __construct($db) {
        $this->conn = $db;
    }
    
    // Obtener todos los productos en el carrito del usuario
    public function getCartItems($userId) {
        try {
            $sql = "SELECT productos.*, carrito.cantidad, carrito.carrito_id 
                    FROM carrito
                    JOIN productos ON carrito.producto_id = productos.producto_id
                    WHERE carrito.usuario_id = :usuario_id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':usuario_id', $userId, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // En un entorno de producción, registrar el error en lugar de mostrarlo
            error_log("Error en getCartItems: " . $e->getMessage());
            return [];
        }
    }
    
    // Eliminar un producto del carrito
    public function deleteCartItem($cartId, $userId) {
        try {
            // Primero verificar que el item pertenezca al usuario
            $verificar_sql = "SELECT * FROM carrito WHERE carrito_id = :carrito_id AND usuario_id = :usuario_id";
            $verificar_stmt = $this->conn->prepare($verificar_sql);
            $verificar_stmt->bindParam(':carrito_id', $cartId, PDO::PARAM_INT);
            $verificar_stmt->bindParam(':usuario_id', $userId, PDO::PARAM_INT);
            $verificar_stmt->execute();
            
            if ($verificar_stmt->rowCount() > 0) {
                // Eliminar el producto
                $eliminar_sql = "DELETE FROM carrito WHERE carrito_id = :carrito_id";
                $eliminar_stmt = $this->conn->prepare($eliminar_sql);
                $eliminar_stmt->bindParam(':carrito_id', $cartId, PDO::PARAM_INT);
                
                return $eliminar_stmt->execute();
            }
            
            return false;
        } catch (PDOException $e) {
            error_log("Error en deleteCartItem: " . $e->getMessage());
            return false;
        }
    }
    
    // Calcular el total del carrito
    public function calculateTotal($cartItems) {
        if (!is_array($cartItems)) {
            return 0;
        }
        
        $total = 0;
        foreach ($cartItems as $item) {
            $total += $item['precioProducto'] * $item['cantidad'];
        }
        return $total;
    }
}
?>
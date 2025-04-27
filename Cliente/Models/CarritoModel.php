<?php
require_once dirname(__DIR__) . '/config/conexion.php'; // Verifica que esta ruta sea correcta

class CarritoModel {
    private $db;

    public function __construct() {
        $this->db = Conexion::obtenerConexion();
    }

    // Añadir producto al carrito
    public function addProductToCart($productoId, $cantidad, $userId) {
        try {
            $query = "INSERT INTO carrito (producto_id, cantidad, usuario_id, fecha_agregado) 
                      VALUES (?, ?, ?, NOW()) 
                      ON DUPLICATE KEY UPDATE cantidad = cantidad + ?";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param("iiii", $productoId, $cantidad, $userId, $cantidad);
            return $stmt->execute();
        } catch (mysqli_sql_exception $e) {
            error_log("Error en addProductToCart: " . $e->getMessage());
            return false;
        }
    }

    // Actualizar cantidad de un producto en el carrito
    public function updateCartItem($cartId, $quantity, $userId) {
        try {
            $query = "UPDATE carrito SET cantidad = ? WHERE carrito_id = ? AND usuario_id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param("iii", $quantity, $cartId, $userId);
            return $stmt->execute();
        } catch (mysqli_sql_exception $e) {
            error_log("Error en updateCartItem: " . $e->getMessage());
            return false;
        }
    }

    // Obtener todos los productos en el carrito del usuario
    public function getCartItems($userId) {
        try {
            $sql = "SELECT p.producto_id, p.nombreProducto, p.precioProducto, p.fotosProducto, 
                           c.cantidad, c.carrito_id 
                    FROM carrito c
                    JOIN productos p ON c.producto_id = p.producto_id
                    WHERE c.usuario_id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param("i", $userId);
            $stmt->execute();
            $result = $stmt->get_result();
            return $result->fetch_all(MYSQLI_ASSOC);
        } catch (mysqli_sql_exception $e) {
            error_log("Error en getCartItems: " . $e->getMessage());
            return [];
        }
    }

    // Eliminar un producto del carrito
    public function deleteCartItem($cartId, $userId) {
        try {
            $query = "DELETE FROM carrito WHERE carrito_id = ? AND usuario_id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param("ii", $cartId, $userId);
            return $stmt->execute();
        } catch (mysqli_sql_exception $e) {
            error_log("Error en deleteCartItem: " . $e->getMessage());
            return false;
        }
    }

    // Vaciar el carrito del usuario
    public function emptyCart($userId) {
        try {
            $sql = "DELETE FROM carrito WHERE usuario_id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param("i", $userId);
            return $stmt->execute();
        } catch (mysqli_sql_exception $e) {
            error_log("Error en emptyCart: " . $e->getMessage());
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
<?php
require_once '../config/conexion.php';

class PedidoModel {
    private $conn;

    public function __construct() {
        $this->conn = Conexion::obtenerConexion();
    }

    public function obtenerCategorias() {
        $sql = "SELECT nombreCategoria, subcategorias FROM categorias";
        return $this->conn->query($sql);
    }

    public function obtenerCarrito($usuario_id) {
        $sql = "SELECT producto_id, cantidad, p.precioProducto FROM carrito 
                JOIN productos p ON carrito.producto_id = p.producto_id
                WHERE usuario_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $usuario_id);
        $stmt->execute();
        return $stmt->get_result();
    }

    public function crearPedido($usuario_id, $total) {
        $sql = "INSERT INTO pedidos (usuario_id, total) VALUES (?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("id", $usuario_id, $total);
        $stmt->execute();
        return $stmt->insert_id;
    }

    public function agregarDetallePedido($pedido_id, $producto_id, $cantidad, $precio) {
        $sql = "INSERT INTO detalle_pedido (pedido_id, producto_id, cantidad, precio) VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("iiid", $pedido_id, $producto_id, $cantidad, $precio);
        $stmt->execute();
    }

    public function crearDetallesPedido($pedido_id, $productos) {
        $sql = "INSERT INTO detalle_pedido (pedido_id, producto_id, cantidad, precio) VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);

        foreach ($productos as $producto) {
            $stmt->bind_param("iiid", $pedido_id, $producto['producto_id'], $producto['cantidad'], $producto['precioProducto']);
            $stmt->execute();
        }
    }

    public function actualizarStock($producto_id, $cantidad) {
        $sql = "UPDATE productos SET stock = stock - ? WHERE producto_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ii", $cantidad, $producto_id);
        $stmt->execute();
    }

    public function vaciarCarrito($usuario_id) {
        $sql = "DELETE FROM carrito WHERE usuario_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $usuario_id);
        $stmt->execute();
    }

    public function obtenerPedidos($usuario_id) {
        $sql = "SELECT * FROM pedidos WHERE usuario_id = ? ORDER BY fecha_pedido DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $usuario_id);
        $stmt->execute();
        return $stmt->get_result();
    }

    public function obtenerDetallesPedido($pedido_id) {
        $sql = "SELECT p.nombreProducto, p.fotosProducto, d.cantidad, d.precio, (d.cantidad * d.precio) AS subtotal 
                FROM detalle_pedido d 
                JOIN productos p ON d.producto_id = p.producto_id 
                WHERE d.pedido_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $pedido_id);
        $stmt->execute();
        return $stmt->get_result();
    }
}
?>

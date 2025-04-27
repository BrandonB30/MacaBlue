<?php
require_once '../models/PedidoModel.php';

class PedidoController {
    private $model;

    public function __construct() {
        $this->model = new PedidoModel();
    }

    public function realizarPedido($usuario_id) {
        $carrito = $this->model->obtenerCarrito($usuario_id);
        if ($carrito->num_rows > 0) {
            $total = 0;
            $productos = [];

            while ($row = $carrito->fetch_assoc()) {
                $subtotal = $row['cantidad'] * $row['precioProducto'];
                $total += $subtotal;
                $productos[] = $row;
            }

            $pedido_id = $this->model->crearPedido($usuario_id, $total);

            foreach ($productos as $producto) {
                $this->model->agregarDetallePedido($pedido_id, $producto['producto_id'], $producto['cantidad'], $producto['precioProducto']);
                $this->model->actualizarStock($producto['producto_id'], $producto['cantidad']);
            }

            $this->model->vaciarCarrito($usuario_id);
            return true;
        }
        return false;
    }

    public function obtenerPedidos($usuario_id) {
        return $this->model->obtenerPedidos($usuario_id);
    }

    public function obtenerDetallesPedido($pedido_id) {
        return $this->model->obtenerDetallesPedido($pedido_id);
    }

    public function obtenerCategorias() {
        return $this->model->obtenerCategorias();
    }
}

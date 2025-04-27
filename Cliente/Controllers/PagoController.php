<?php
require_once '../models/CarritoModel.php';
require_once '../models/PedidoModel.php';

class PagoController {
    public $carritoModel;
    private $pedidoModel;

    public function __construct() {
        $this->carritoModel = new CarritoModel();
        $this->pedidoModel = new PedidoModel();
    }

    public function procesarPago($usuario_id, $direccion, $metodo_pago) {
        $productos = $this->carritoModel->getCartItems($usuario_id);
        $total = $this->calcularTotal($productos);

        if (count($productos) > 0) {
            try {
                $pedido_id = $this->pedidoModel->crearPedido($usuario_id, $total, $direccion, $metodo_pago);
                $this->pedidoModel->crearDetallesPedido($pedido_id, $productos);
                $this->carritoModel->emptyCart($usuario_id);

                echo "<script>alert('¡Pago procesado con éxito! Tu pedido #" . $pedido_id . " ha sido registrado.'); window.location.href = 'pedido.php';</script>";
                exit();
            } catch (Exception $e) {
                echo "<script>alert('Error al procesar el pago: " . $e->getMessage() . "'); window.location.href = 'carrito.php';</script>";
                exit();
            }
        } else {
            echo "<script>alert('No hay productos en el carrito'); window.location.href = 'carrito.php';</script>";
            exit();
        }
    }

    public function calcularTotal($productos) {
        $total = 0;
        foreach ($productos as $producto) {
            $total += $producto['cantidad'] * $producto['precioProducto'];
        }
        return $total;
    }
}
?>

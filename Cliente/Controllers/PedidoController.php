<?php
$base_url = '/MacaBlue/cliente';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link rel="shortcut icon" href="<?php echo $base_url; ?>/assets/img/favicon.png" type="image/x-icon">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultados de Búsqueda - <?php echo htmlspecialchars($query); ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo $base_url; ?>/assets/css/style.css">
    <link rel="stylesheet" href="<?php echo $base_url; ?>/assets/css/nav.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
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

            // Calcular el total y recopilar los productos
            while ($row = $carrito->fetch_assoc()) {
                $subtotal = $row['cantidad'] * $row['precioProducto'];
                $total += $subtotal;
                $productos[] = $row;
            }

            // Crear el pedido
            $pedido_id = $this->model->crearPedido($usuario_id, $total);

            // Agregar detalles del pedido y actualizar el stock
            foreach ($productos as $producto) {
                $this->model->agregarDetallePedido($pedido_id, $producto['producto_id'], $producto['cantidad'], $producto['precioProducto']);
                $this->model->actualizarStock($producto['producto_id'], $producto['cantidad']);
            }

            // Vaciar el carrito
            $this->model->vaciarCarrito($usuario_id);

            // Actualizar el estado del pedido a "En proceso"
            $this->model->actualizarEstadoPedido($pedido_id, "En proceso");

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

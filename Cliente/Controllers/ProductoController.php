<?php
require_once 'models/ProductoModel.php';

class ProductoController {
    private $productoModel;

    public function __construct($conexion) {
        $this->productoModel = new ProductoModel($conexion);
    }

    public function buscar() {
        $query = isset($_GET['query']) ? $_GET['query'] : '';

        if (empty($query)) {
            $productos = [];
        } else {
            $productos = $this->productoModel->buscarProductos($query);
        }

        // Mostrar la vista
        require_once 'views/resultadosBusqueda.php';
    }
}
?>

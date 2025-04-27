<?php
require_once '../models/ProductoModel.php';

class ProductoController {
    public static function obtenerProductos($subcategoria = null) {
        return ProductoModel::obtenerProductos($subcategoria);
    }

    public function buscar($query) {
        return ProductoModel::buscarProductos($query);
    }
}

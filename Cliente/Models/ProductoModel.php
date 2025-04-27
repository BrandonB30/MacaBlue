<?php
require_once '../config/conexion.php';

class ProductoModel {
    public static function obtenerProductos($subcategoria = null) {
        $conn = Conexion::obtenerConexion(); // Cambiado a obtenerConexion()
        $sql = "SELECT * FROM productos WHERE estadoProducto = 'Disponible'";
        if ($subcategoria) {
            $sql .= " AND subcategoriaProducto = ?";
        }
        $stmt = $conn->prepare($sql);
        if ($subcategoria) {
            $stmt->bind_param("s", $subcategoria);
        }
        $stmt->execute();
        $result = $stmt->get_result();
        $productos = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $productos;
    }

    public static function buscarProductos($query) {
        $conn = Conexion::obtenerConexion(); // Cambiado a obtenerConexion()
        $sql = "SELECT * FROM productos WHERE estadoProducto = 'Disponible' AND (nombreProducto LIKE ? OR descripcionProducto LIKE ?)";
        $stmt = $conn->prepare($sql);
        $likeQuery = '%' . $query . '%';
        $stmt->bind_param("ss", $likeQuery, $likeQuery);
        $stmt->execute();
        $result = $stmt->get_result();
        $productos = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $productos;
    }
}

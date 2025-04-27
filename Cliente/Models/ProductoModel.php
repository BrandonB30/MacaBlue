<?php
class ProductoModel {
    private $conn;

    public function __construct($conexion) {
        $this->conn = $conexion;
    }

    public function buscarProductos($query) {
        $query = $this->conn->real_escape_string(trim($query));

        $sql = "SELECT * FROM productos WHERE estadoProducto = 'Disponible' 
                AND (nombreProducto LIKE '%$query%' OR descripcionProducto LIKE '%$query%')";

        $result = $this->conn->query($sql);

        if ($result === false) {
            return false;
        }

        $productos = [];
        while ($row = $result->fetch_assoc()) {
            $productos[] = $row;
        }
        return $productos;
    }
}
?>

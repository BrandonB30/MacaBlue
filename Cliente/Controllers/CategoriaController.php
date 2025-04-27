<?php
require_once dirname(__DIR__) . '/config/conexion.php'; // Incluir la clase de conexión

class CategoriaController {
    private $conn;

    public function __construct() {
        $conexion = new Conexion();
        $this->conn = $conexion->conectar();
    }

    public function obtenerCategorias() {
        $categorias = [];
        $sql = "SELECT nombreCategoria, subcategorias FROM categorias";
        $result = $this->conn->query($sql);

        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $categorias[] = [
                    'nombreCategoria' => $row['nombreCategoria'],
                    'subcategorias' => array_map('trim', explode(',', $row['subcategorias']))
                ];
            }
        }

        return $categorias;
    }
}

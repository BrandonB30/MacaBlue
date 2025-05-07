<?php
class Pedido {
    // Conexión a la base de datos
    private $conn;
    private $table_name = "pedidos";
    
    // Propiedades del pedido
    public $pedido_id;
    public $usuario_id;
    public $fecha_pedido;
    public $estado;
    public $total;
    public $direccion_envio;
    public $metodo_pago;
    
    // Estados válidos para los pedidos (enum)
    const ESTADOS = [
        'En Proceso' => 'En Proceso',
        'Enviado' => 'Enviado',
        'Entregado' => 'Entregado',
        'Cancelado' => 'Cancelado'
    ];
    
    // Constructor
    public function __construct($db) {
        $this->conn = $db;
    }
    
    // Obtener todos los pedidos
    public function readAll() {
        $query = "SELECT p.* 
                 FROM " . $this->table_name . " p
                 ORDER BY p.fecha_pedido DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Obtener un solo pedido por ID
    public function readOne() {
        $query = "SELECT p.*, u.nombre, u.apellido 
                 FROM " . $this->table_name . " p
                 LEFT JOIN usuarios u ON p.usuario_id = u.id
                 WHERE p.pedido_id = :pedido_id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':pedido_id', $this->pedido_id);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($row) {
            $this->usuario_id = $row['usuario_id'];
            $this->fecha_pedido = $row['fecha_pedido'];
            $this->estado = $row['estado'];
            $this->total = $row['total'];
            $this->direccion_envio = $row['direccion_envio'];
            $this->metodo_pago = $row['metodo_pago'];
            // Agregamos información del cliente
            return true;
        }
        
        return false;
    }
    
    // Actualizar el estado de un pedido
    public function actualizarEstado() {
        // Verificar que el estado sea válido
        if (!array_key_exists($this->estado, self::ESTADOS)) {
            return false;
        }
        
        $query = "UPDATE " . $this->table_name . "
                 SET estado = :estado
                 WHERE pedido_id = :pedido_id";
        
        $stmt = $this->conn->prepare($query);
        
        // Sanear los datos
        $this->estado = htmlspecialchars(strip_tags($this->estado));
        $this->pedido_id = htmlspecialchars(strip_tags($this->pedido_id));
        
        // Vincular parámetros
        $stmt->bindParam(':estado', $this->estado);
        $stmt->bindParam(':pedido_id', $this->pedido_id);
        
        // Ejecutar la consulta
        if ($stmt->execute()) {
            return true;
        }
        
        return false;
    }
    
    // Eliminar un pedido
    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE pedido_id = :pedido_id";
        
        $stmt = $this->conn->prepare($query);
        
        // Sanear los datos
        $this->pedido_id = htmlspecialchars(strip_tags($this->pedido_id));
        
        // Vincular parámetro
        $stmt->bindParam(':pedido_id', $this->pedido_id);
        
        // Ejecutar la consulta
        if ($stmt->execute()) {
            return true;
        }
        
        return false;
    }
    
    // Validar si un estado es válido según nuestro enum
    public static function validarEstado($estado) {
        return array_key_exists($estado, self::ESTADOS);
    }
    
    // Obtener texto descriptivo del estado
    public static function getEstadoTexto($estado) {
        return self::ESTADOS[$estado] ?? 'Desconocido';
    }
    
    // Verificar si se permite la transición de estado
    public function permitirCambioEstado($nuevoEstado) {
        // Reglas de transición entre estados
        $transiciones_permitidas = [
            'En proceso' => ['Enviado', 'Cancelado'],
            'Enviado' => ['Entregado', 'Cancelado'],
            'Entregado' => [], // Estado final, no se permite cambio
            'Cancelado' => []  // Estado final, no se permite cambio
        ];
        
        // Obtener el estado actual del pedido
        $this->readOne();
        
        // Verificar si la transición está permitida
        if (isset($transiciones_permitidas[$this->estado]) && in_array($nuevoEstado, $transiciones_permitidas[$this->estado])) {
            return true;
        }
        
        return false;
    }
}
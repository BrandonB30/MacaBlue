<?php
class Pedido {
    private $conn;
    private $table_name = "pedidos";

    public $pedido_id;
    public $usuario_id;
    public $fecha_pedido;
    public $total;
    public $estado;
    public $direccion_envio;
    public $metodo_pago;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function readAll() {
        $query = "SELECT pedido_id, usuario_id, fecha_pedido, total, estado, direccion_envio, metodo_pago 
                  FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
    
    public function read() {
        $query = "SELECT pedido_id, usuario_id, fecha_pedido, total, estado, direccion_envio, metodo_pago 
                  FROM " . $this->table_name . " 
                  WHERE pedido_id = :pedido_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":pedido_id", $this->pedido_id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if($row) {
            $this->usuario_id = $row['usuario_id'];
            $this->fecha_pedido = $row['fecha_pedido'];
            $this->total = $row['total'];
            $this->estado = $row['estado'];
            $this->direccion_envio = $row['direccion_envio'];
            $this->metodo_pago = $row['metodo_pago'];
            return true;
        }
        return false;
    }
    
    public function create() {
        $query = "INSERT INTO " . $this->table_name . " 
                  (usuario_id, fecha_pedido, total, estado, direccion_envio, metodo_pago) 
                  VALUES 
                  (:usuario_id, :fecha_pedido, :total, :estado, :direccion_envio, :metodo_pago)";
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(":usuario_id", $this->usuario_id);
        $stmt->bindParam(":fecha_pedido", $this->fecha_pedido);
        $stmt->bindParam(":total", $this->total);
        $stmt->bindParam(":estado", $this->estado);
        $stmt->bindParam(":direccion_envio", $this->direccion_envio);
        $stmt->bindParam(":metodo_pago", $this->metodo_pago);
        
        return $stmt->execute();
    }
    
    public function update() {
        $query = "UPDATE " . $this->table_name . " 
                  SET usuario_id = :usuario_id, 
                      total = :total, 
                      estado = :estado, 
                      direccion_envio = :direccion_envio, 
                      metodo_pago = :metodo_pago 
                  WHERE pedido_id = :pedido_id";
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(":usuario_id", $this->usuario_id);
        $stmt->bindParam(":total", $this->total);
        $stmt->bindParam(":estado", $this->estado);
        $stmt->bindParam(":direccion_envio", $this->direccion_envio);
        $stmt->bindParam(":metodo_pago", $this->metodo_pago);
        $stmt->bindParam(":pedido_id", $this->pedido_id);
        
        return $stmt->execute();
    }
    
    public function updateEstado($nuevoEstado) {
        $query = "UPDATE " . $this->table_name . " 
                  SET estado = :estado
                  WHERE pedido_id = :pedido_id";
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(":estado", $nuevoEstado);
        $stmt->bindParam(":pedido_id", $this->pedido_id);
        
        if ($stmt->execute()) {
            $this->estado = $nuevoEstado;
            return true;
        }
        return false;
    }
    
    public function delete() {
        try {
            // Primero eliminar registros relacionados (si hay)
            $query = "DELETE FROM detalles_pedido WHERE pedido_id = :pedido_id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":pedido_id", $this->pedido_id);
            $stmt->execute();
            
            // Luego eliminar el pedido
            $query = "DELETE FROM " . $this->table_name . " WHERE pedido_id = :pedido_id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":pedido_id", $this->pedido_id);
            return $stmt->execute();
        } catch(PDOException $e) {
            throw new Exception("Error al eliminar: " . $e->getMessage());
        }
    }
    
    // Método para obtener los estados disponibles desde la base de datos
    public function getEstadosFromDB() {
        // Consulta para obtener los valores del ENUM
        $query = "SHOW COLUMNS FROM " . $this->table_name . " WHERE Field = 'estado'";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Parsear los valores del ENUM
        if ($row && preg_match("/^enum\(\'(.*)\'\)$/", $row['Type'], $matches)) {
            $estados = array();
            $enum_values = explode("','", $matches[1]);
            
            foreach ($enum_values as $value) {
                // Convertir valor_enum a Valor Enum (primera letra mayúscula y espacios en lugar de guiones bajos)
                $display_value = ucfirst(str_replace('_', ' ', $value));
                $estados[$value] = $display_value;
            }
            
            return $estados;
        }
        
        return array();
    }
}
?>
<?php
class Cliente {
    private $conn;
    private $table_name = "clientes";

    public $cliente_id;
    public $nombreCliente;
    public $apellidoCliente;
    public $emailCliente;
    public $passwordCliente;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function emailExistsForOtherClient() {
        $query = "SELECT COUNT(*) FROM " . $this->table_name . " WHERE emailCliente = :emailCliente AND cliente_id != :cliente_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":emailCliente", $this->emailCliente);
        $stmt->bindParam(":cliente_id", $this->cliente_id);
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }

    public function create() {
        if ($this->emailExistsForOtherClient()) {
            throw new Exception("El correo electrónico ya está registrado.");
        }

        $query = "INSERT INTO " . $this->table_name . " (nombreCliente, apellidoCliente, emailCliente, passwordCliente) VALUES (:nombreCliente, :apellidoCliente, :emailCliente, :passwordCliente)";
        $stmt = $this->conn->prepare($query);

        $hashedPassword = password_hash($this->passwordCliente, PASSWORD_BCRYPT);

        $stmt->bindParam(":nombreCliente", $this->nombreCliente);
        $stmt->bindParam(":apellidoCliente", $this->apellidoCliente);
        $stmt->bindParam(":emailCliente", $this->emailCliente);
        $stmt->bindParam(":passwordCliente", $hashedPassword);

        return $stmt->execute();
    }

    public function update() {
        if ($this->emailExistsForOtherClient()) {
            throw new Exception("El correo electrónico ya está registrado por otro cliente.");
        }

        $query = "UPDATE " . $this->table_name . " SET nombreCliente = :nombreCliente, apellidoCliente = :apellidoCliente, emailCliente = :emailCliente";

        if (!empty($this->passwordCliente)) {
            $query .= ", passwordCliente = :passwordCliente";
        }

        $query .= " WHERE cliente_id = :cliente_id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":nombreCliente", $this->nombreCliente);
        $stmt->bindParam(":apellidoCliente", $this->apellidoCliente);
        $stmt->bindParam(":emailCliente", $this->emailCliente);
        $stmt->bindParam(":cliente_id", $this->cliente_id);

        if (!empty($this->passwordCliente)) {
            $hashedPassword = password_hash($this->passwordCliente, PASSWORD_BCRYPT);
            $stmt->bindParam(":passwordCliente", $hashedPassword);
        }

        return $stmt->execute();
    }

    public function readAll() {
        $query = "SELECT cliente_id, nombreCliente, apellidoCliente, emailCliente FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function delete() {
        try {
            // Primero eliminar registros relacionados en la tabla carrito
            $query = "DELETE FROM carrito WHERE usuario_id = :cliente_id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":cliente_id", $this->cliente_id);
            $stmt->execute();
            
            // Luego eliminar el cliente
            $query = "DELETE FROM " . $this->table_name . " WHERE cliente_id = :cliente_id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":cliente_id", $this->cliente_id);
            return $stmt->execute();
        } catch(PDOException $e) {
            throw new Exception("Error al eliminar: " . $e->getMessage());
        }
    }
}
?>
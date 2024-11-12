<?php
class Usuario {
    private $conn;
    private $table_name = "usuarios";

    public $usuario_id;
    public $nombreUsuario;
    public $apellidoUsuario;
    public $emailUsuario;
    public $rolUsuario;
    public $passwordUsuario;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function emailExistsForOtherUser() {
        $query = "SELECT COUNT(*) FROM " . $this->table_name . " WHERE emailUsuario = :emailUsuario AND usuario_id != :usuario_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":emailUsuario", $this->emailUsuario);
        $stmt->bindParam(":usuario_id", $this->usuario_id);
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }

    public function create() {
        if ($this->emailExistsForOtherUser()) {
            throw new Exception("El correo electrónico ya está registrado.");
        }

        $query = "INSERT INTO " . $this->table_name . " (nombreUsuario, apellidoUsuario, emailUsuario, rolUsuario, passwordUsuario) VALUES (:nombreUsuario, :apellidoUsuario, :emailUsuario, :rolUsuario, :passwordUsuario)";
        $stmt = $this->conn->prepare($query);

        $hashedPassword = password_hash($this->passwordUsuario, PASSWORD_BCRYPT);

        $stmt->bindParam(":nombreUsuario", $this->nombreUsuario);
        $stmt->bindParam(":apellidoUsuario", $this->apellidoUsuario);
        $stmt->bindParam(":emailUsuario", $this->emailUsuario);
        $stmt->bindParam(":rolUsuario", $this->rolUsuario);
        $stmt->bindParam(":passwordUsuario", $hashedPassword);

        return $stmt->execute();
    }

    public function update() {
        if ($this->emailExistsForOtherUser()) {
            throw new Exception("El correo electrónico ya está registrado por otro usuario.");
        }

        $query = "UPDATE " . $this->table_name . " SET nombreUsuario = :nombreUsuario, apellidoUsuario = :apellidoUsuario, emailUsuario = :emailUsuario, rolUsuario = :rolUsuario";

        if (!empty($this->passwordUsuario)) {
            $query .= ", passwordUsuario = :passwordUsuario";
        }

        $query .= " WHERE usuario_id = :usuario_id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":nombreUsuario", $this->nombreUsuario);
        $stmt->bindParam(":apellidoUsuario", $this->apellidoUsuario);
        $stmt->bindParam(":emailUsuario", $this->emailUsuario);
        $stmt->bindParam(":rolUsuario", $this->rolUsuario);
        $stmt->bindParam(":usuario_id", $this->usuario_id);

        if (!empty($this->passwordUsuario)) {
            $hashedPassword = password_hash($this->passwordUsuario, PASSWORD_BCRYPT);
            $stmt->bindParam(":passwordUsuario", $hashedPassword);
        }

        return $stmt->execute();
    }

    public function readAll() {
        $query = "SELECT usuario_id, nombreUsuario, apellidoUsuario, emailUsuario, rolUsuario FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE usuario_id = :usuario_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":usuario_id", $this->usuario_id);
        return $stmt->execute();
    }
}
?>

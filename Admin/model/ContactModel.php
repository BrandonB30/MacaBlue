<?php
require_once '../../Admin/config/Conexion.php';

class ContactModel {
    private $db;

    public function __construct() {
        $this->db = (new Database())->getConnection();
    }

    public function fetchAllMessages() {
        $query = "SELECT id, nombre, email, asunto, mensaje, fecha FROM mensajes_contacto ORDER BY fecha DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Elimina un mensaje de contacto por su ID
     * 
     * @param int $id ID del mensaje a eliminar
     * @return bool Retorna true si se eliminó correctamente, false en caso contrario
     */
    public function deleteMessage($id) {
        try {
            $query = "DELETE FROM mensajes_contacto WHERE id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            // Registrar el error en un log
            error_log("Error al eliminar mensaje: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Obtiene un mensaje específico por su ID
     * 
     * @param int $id ID del mensaje a recuperar
     * @return array|false Retorna el mensaje o false si no existe
     */
    public function getMessageById($id) {
        $query = "SELECT id, nombre, email, asunto, mensaje, fecha FROM mensajes_contacto WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    /**
     * Marca un mensaje como leído
     * 
     * @param int $id ID del mensaje a marcar
     * @return bool Retorna true si se actualizó correctamente, false en caso contrario
     */
    public function markAsRead($id) {
        try {
            $query = "UPDATE mensajes_contacto SET leido = 1 WHERE id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error al marcar mensaje como leído: " . $e->getMessage());
            return false;
        }
    }
}
?>
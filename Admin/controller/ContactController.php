<?php
require_once '../../Admin/model/ContactModel.php';

class ContactController {
    private $model;
    
    public function __construct() {
        $this->model = new ContactModel();
    }
    
    /**
     * Obtiene todos los mensajes de contacto
     * 
     * @return array Arreglo con todos los mensajes
     */
    public function getAllMessages() {
        return $this->model->fetchAllMessages();
    }
    
    /**
     * Elimina un mensaje de contacto
     * 
     * @param int $id ID del mensaje a eliminar
     * @return array Retorna un array con el estado y mensaje de la operación
     */
    public function deleteMessage($id) {
        if (!is_numeric($id)) {
            return [
                'status' => 'error',
                'message' => 'ID de mensaje no válido'
            ];
        }
        
        $result = $this->model->deleteMessage($id);
        
        if ($result) {
            return [
                'status' => 'success',
                'message' => 'Mensaje eliminado correctamente'
            ];
        } else {
            return [
                'status' => 'error',
                'message' => 'Error al eliminar el mensaje'
            ];
        }
    }
    
    /**
     * Obtiene un mensaje específico por su ID
     * 
     * @param int $id ID del mensaje
     * @return array|null El mensaje o null si no existe
     */
    public function getMessageById($id) {
        if (!is_numeric($id)) {
            return null;
        }
        
        return $this->model->getMessageById($id);
    }
    
    /**
     * Marca un mensaje como leído
     * 
     * @param int $id ID del mensaje
     * @return array Estado de la operación
     */
    public function markMessageAsRead($id) {
        if (!is_numeric($id)) {
            return [
                'status' => 'error',
                'message' => 'ID de mensaje no válido'
            ];
        }
        
        $result = $this->model->markAsRead($id);
        
        if ($result) {
            return [
                'status' => 'success',
                'message' => 'Mensaje marcado como leído'
            ];
        } else {
            return [
                'status' => 'error',
                'message' => 'Error al actualizar el estado del mensaje'
            ];
        }
    }
}
?>
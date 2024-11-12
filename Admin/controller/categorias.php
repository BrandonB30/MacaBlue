<?php
session_start();
header('Content-Type: application/json');

include_once '../config/conexion.php';

$database = new Database();
$db = $database->getConnection();

$action = $_POST['action'] ?? $_GET['action'] ?? '';

try {
    if ($action === 'addCategory') {
        $nombreCategoria = $_POST['nombreCategoria'] ?? '';
        $subcategorias = $_POST['subcategorias'] ?? '';

        if (empty($nombreCategoria) || empty($subcategorias)) {
            echo json_encode(["status" => "error", "message" => "Todos los campos son obligatorios."]);
            exit;
        }

        $checkQuery = "SELECT categoria_id, subcategorias FROM categorias WHERE nombreCategoria = :nombreCategoria";
        $checkStmt = $db->prepare($checkQuery);
        $checkStmt->bindParam(':nombreCategoria', $nombreCategoria);
        $checkStmt->execute();
        $existingCategory = $checkStmt->fetch(PDO::FETCH_ASSOC);

        if ($existingCategory) {
            $categoria_id = $existingCategory['categoria_id'];
            $existingSubcategorias = explode(', ', $existingCategory['subcategorias']);
            $newSubcategorias = explode(',', $subcategorias);
            $allSubcategorias = array_unique(array_merge($existingSubcategorias, array_map('trim', $newSubcategorias)));
            $subcategorias = implode(', ', $allSubcategorias);

            $updateQuery = "UPDATE categorias SET subcategorias = :subcategorias WHERE categoria_id = :categoria_id";
            $updateStmt = $db->prepare($updateQuery);
            $updateStmt->bindParam(':subcategorias', $subcategorias);
            $updateStmt->bindParam(':categoria_id', $categoria_id);

            if ($updateStmt->execute()) {
                echo json_encode(["status" => "success", "message" => "Subcategorías actualizadas correctamente."]);
            } else {
                echo json_encode(["status" => "error", "message" => "No se pudieron actualizar las subcategorías."]);
            }
        } else {
            $query = "INSERT INTO categorias (nombreCategoria, subcategorias) VALUES (:nombreCategoria, :subcategorias)";
            $stmt = $db->prepare($query);
            $stmt->bindParam(':nombreCategoria', $nombreCategoria);
            $stmt->bindParam(':subcategorias', $subcategorias);

            if ($stmt->execute()) {
                echo json_encode(["status" => "success", "message" => "Categoría y subcategorías agregadas correctamente."]);
            } else {
                echo json_encode(["status" => "error", "message" => "No se pudo agregar la categoría."]);
            }
        }
    } elseif ($action === 'deleteCategory') {
        $categoria_id = $_POST['categoria_id'] ?? null;

        if (!$categoria_id) {
            echo json_encode(["status" => "error", "message" => "ID de categoría no proporcionado."]);
            exit;
        }

        $query = "DELETE FROM categorias WHERE categoria_id = :categoria_id";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':categoria_id', $categoria_id);

        if ($stmt->execute()) {
            echo json_encode(["status" => "success", "message" => "Categoría eliminada correctamente."]);
        } else {
            echo json_encode(["status" => "error", "message" => "No se pudo eliminar la categoría."]);
        }
    } elseif ($action === 'editCategory') {
        $categoria_id = $_POST['categoria_id'] ?? null;
        $nombreCategoria = $_POST['nombreCategoria'] ?? '';
        $subcategorias = $_POST['subcategorias'] ?? '';

        if (!$categoria_id || empty($nombreCategoria) || empty($subcategorias)) {
            echo json_encode(["status" => "error", "message" => "Todos los campos son obligatorios."]);
            exit;
        }

        $subcategorias = implode(', ', array_map('trim', explode(',', $subcategorias)));

        $query = "UPDATE categorias SET nombreCategoria = :nombreCategoria, subcategorias = :subcategorias WHERE categoria_id = :categoria_id";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':nombreCategoria', $nombreCategoria);
        $stmt->bindParam(':subcategorias', $subcategorias);
        $stmt->bindParam(':categoria_id', $categoria_id);

        if ($stmt->execute()) {
            echo json_encode(["status" => "success", "message" => "Categoría actualizada correctamente."]);
        } else {
            echo json_encode(["status" => "error", "message" => "No se pudo actualizar la categoría."]);
        }
    } elseif ($action === 'listCategories') {
        $query = "SELECT * FROM categorias";
        $stmt = $db->prepare($query);
        $stmt->execute();
        $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($categories as &$category) {
            $category['subcategorias'] = explode(', ', $category['subcategorias']);
        }

        echo json_encode(["status" => "success", "data" => $categories]);
    } else {
        echo json_encode(["status" => "error", "message" => "Acción no válida."]);
    }
} catch (PDOException $e) {
    echo json_encode(["status" => "error", "message" => "Error en la base de datos: " . $e->getMessage()]);
} catch (Exception $e) {
    echo json_encode(["status" => "error", "message" => "Error inesperado: " . $e->getMessage()]);
}
?>

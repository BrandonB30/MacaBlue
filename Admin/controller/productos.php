<?php
session_start();
header('Content-Type: application/json');

include_once '../config/conexion.php';

$database = new Database();
$db = $database->getConnection();

$action = $_POST['action'] ?? $_GET['action'] ?? '';

try {
    if ($action === 'addProduct') {
        $nombreProducto = $_POST['nombreProducto'] ?? '';
        $categoriaProducto = $_POST['categoriaProducto'] ?? '';
        $subcategoriaProducto = $_POST['subcategoriaProducto'] ?? '';
        $precioProducto = floatval($_POST['precioProducto'] ?? 0);
        $stockProducto = intval($_POST['stockProducto'] ?? 0);
        $estadoProducto = $_POST['estadoProducto'] ?? '';
        $tallas = isset($_POST['tallas']) ? implode(',', $_POST['tallas']) : '';
        $colorProducto = $_POST['colorProducto'] ?? '';
        $materialProducto = $_POST['materialProducto'] ?? '';
        $descripcionProducto = $_POST['descripcionProducto'] ?? '';

        // Validaciones
        if (empty($nombreProducto) || empty($categoriaProducto) || empty($subcategoriaProducto) || $precioProducto <= 0 || $stockProducto < 0 || empty($estadoProducto)) {
            echo json_encode(["status" => "error", "message" => "Todos los campos son obligatorios y deben ser válidos."]);
            exit;
        }

        // Manejar imágenes
        $fotosProducto = '';
        if (!empty($_FILES['fotosProducto']['name'][0])) {
            foreach ($_FILES['fotosProducto']['tmp_name'] as $key => $tmp_name) {
                $file_name = $_FILES['fotosProducto']['name'][$key];
                $file_tmp = $_FILES['fotosProducto']['tmp_name'][$key];
                $upload_dir = realpath('../uploads') . DIRECTORY_SEPARATOR;

                // Solo guarda el nombre del archivo, sin incluir "uploads/"
                $fotosProducto .= $file_name . ',';

                move_uploaded_file($file_tmp, $upload_dir . $file_name);
            }
            $fotosProducto = rtrim($fotosProducto, ',');
        }

        $query = "INSERT INTO productos (nombreProducto, categoriaProducto, subcategoriaProducto, precioProducto, stockProducto, estadoProducto, tallas, colorProducto, materialProducto, descripcionProducto, fotosProducto) VALUES (:nombreProducto, :categoriaProducto, :subcategoriaProducto, :precioProducto, :stockProducto, :estadoProducto, :tallas, :colorProducto, :materialProducto, :descripcionProducto, :fotosProducto)";

        $stmt = $db->prepare($query);
        $stmt->bindParam(':nombreProducto', $nombreProducto);
        $stmt->bindParam(':categoriaProducto', $categoriaProducto);
        $stmt->bindParam(':subcategoriaProducto', $subcategoriaProducto);
        $stmt->bindParam(':precioProducto', $precioProducto);
        $stmt->bindParam(':stockProducto', $stockProducto);
        $stmt->bindParam(':estadoProducto', $estadoProducto);
        $stmt->bindParam(':tallas', $tallas);
        $stmt->bindParam(':colorProducto', $colorProducto);
        $stmt->bindParam(':materialProducto', $materialProducto);
        $stmt->bindParam(':descripcionProducto', $descripcionProducto);
        $stmt->bindParam(':fotosProducto', $fotosProducto);

        if ($stmt->execute()) {
            echo json_encode(["status" => "success", "message" => "Producto agregado correctamente."]);
        } else {
            echo json_encode(["status" => "error", "message" => "No se pudo agregar el producto."]);
        }
    } elseif ($action === 'listProducts') {
        $query = "SELECT p.*, c.nombreCategoria AS nombreCategoria 
                  FROM productos p 
                  LEFT JOIN categorias c ON p.categoriaProducto = c.categoria_id";

        $stmt = $db->prepare($query);
        $stmt->execute();
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($products) {
            echo json_encode(["status" => "success", "data" => $products]);
        } else {
            echo json_encode(["status" => "error", "message" => "No se encontraron productos."]);
        }
    } elseif ($action === 'editProduct') {
        $producto_id = $_POST['producto_id'] ?? '';
        $nombreProducto = $_POST['nombreProducto'] ?? '';
        $categoriaProducto = $_POST['categoriaProducto'] ?? '';
        $subcategoriaProducto = $_POST['subcategoriaProducto'] ?? '';
        $precioProducto = floatval($_POST['precioProducto'] ?? 0);
        $stockProducto = intval($_POST['stockProducto'] ?? 0);
        $estadoProducto = $_POST['estadoProducto'] ?? '';
        $tallas = isset($_POST['tallas']) ? implode(',', $_POST['tallas']) : '';
        $colorProducto = $_POST['colorProducto'] ?? '';
        $materialProducto = $_POST['materialProducto'] ?? '';
        $descripcionProducto = $_POST['descripcionProducto'] ?? '';

        // Validaciones
        if (empty($nombreProducto) || empty($categoriaProducto) || empty($subcategoriaProducto) || $precioProducto <= 0 || $stockProducto < 0 || empty($estadoProducto)) {
            echo json_encode(["status" => "error", "message" => "Todos los campos son obligatorios y deben ser válidos."]);
            exit;
        }

        // Manejar imágenes en la edición
        $fotosProducto = $_POST['fotosProductoActual'] ?? ''; // Mantener el valor actual si no hay nuevas imágenes
        if (!empty($_FILES['fotosProducto']['name'][0])) {
            $fotosProducto = ''; // Reiniciar el valor si se suben nuevas imágenes
            foreach ($_FILES['fotosProducto']['tmp_name'] as $key => $tmp_name) {
                $file_name = $_FILES['fotosProducto']['name'][$key];
                $file_tmp = $_FILES['fotosProducto']['tmp_name'][$key];
                $upload_dir = realpath('../uploads') . DIRECTORY_SEPARATOR;

                // Solo guarda el nombre del archivo, sin incluir "uploads/"
                $fotosProducto .= $file_name . ',';

                move_uploaded_file($file_tmp, $upload_dir . $file_name);
            }
            $fotosProducto = rtrim($fotosProducto, ',');
        }

        $query = "UPDATE productos SET 
                    nombreProducto = :nombreProducto, 
                    categoriaProducto = :categoriaProducto, 
                    subcategoriaProducto = :subcategoriaProducto, 
                    precioProducto = :precioProducto, 
                    stockProducto = :stockProducto, 
                    estadoProducto = :estadoProducto, 
                    tallas = :tallas, 
                    colorProducto = :colorProducto, 
                    materialProducto = :materialProducto, 
                    descripcionProducto = :descripcionProducto, 
                    fotosProducto = :fotosProducto 
                  WHERE producto_id = :producto_id";

        $stmt = $db->prepare($query);
        $stmt->bindParam(':producto_id', $producto_id);
        $stmt->bindParam(':nombreProducto', $nombreProducto);
        $stmt->bindParam(':categoriaProducto', $categoriaProducto);
        $stmt->bindParam(':subcategoriaProducto', $subcategoriaProducto);
        $stmt->bindParam(':precioProducto', $precioProducto);
        $stmt->bindParam(':stockProducto', $stockProducto);
        $stmt->bindParam(':estadoProducto', $estadoProducto);
        $stmt->bindParam(':tallas', $tallas);
        $stmt->bindParam(':colorProducto', $colorProducto);
        $stmt->bindParam(':materialProducto', $materialProducto);
        $stmt->bindParam(':descripcionProducto', $descripcionProducto);
        $stmt->bindParam(':fotosProducto', $fotosProducto);

        if ($stmt->execute()) {
            echo json_encode(["status" => "success", "message" => "Producto editado correctamente."]);
        } else {
            echo json_encode(["status" => "error", "message" => "No se pudo editar el producto."]);
        }
    } elseif ($action === 'deleteProduct') {
        $producto_id = $_POST['producto_id'] ?? '';

        $query = "DELETE FROM productos WHERE producto_id = :producto_id";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':producto_id', $producto_id);

        if ($stmt->execute()) {
            echo json_encode(["status" => "success", "message" => "Producto eliminado correctamente."]);
        } else {
            echo json_encode(["status" => "error", "message" => "No se pudo eliminar el producto."]);
        }
    } elseif ($action === 'listCategories') {
        $query = "SELECT categoria_id, nombreCategoria, subcategorias FROM categorias";
        $stmt = $db->prepare($query);
        $stmt->execute();
        $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($categories as &$category) {
            $category['subcategorias'] = explode(',', $category['subcategorias']);
        }

        if ($categories) {
            echo json_encode(["status" => "success", "data" => $categories]);
        } else {
            echo json_encode(["status" => "error", "message" => "No se encontraron categorías."]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Acción no válida."]);
    }
} catch (PDOException $e) {
    echo json_encode(["status" => "error", "message" => "Error en la base de datos: " . $e->getMessage()]);
} catch (Exception $e) {
    echo json_encode(["status" => "error", "message" => "Error inesperado: " . $e->getMessage()]);
}

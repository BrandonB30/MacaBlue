<?php
require_once dirname(__DIR__) . '/Models/CarritoModel.php';
require_once dirname(__DIR__) . '/config/conexion.php'; // Incluir la clase Conexion

class CarritoController {
    private $carritoModel;
    private $db;
    
    public function __construct() {
        // Iniciar la sesión si no está iniciada
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Verificar si el usuario ha iniciado sesión
        if (!isset($_SESSION['cliente_id'])) {
            echo "<script>
                document.addEventListener('DOMContentLoaded', function () {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Acceso denegado',
                        text: 'Debes iniciar sesión para ver tu carrito.',
                        confirmButtonText: 'Iniciar sesión',
                        allowOutsideClick: false
                    }).then(() => {
                        window.location.href = '../views/ingreso.php';
                    });
                });
            </script>";
            exit();
        }
        
        // Usar la clase Conexion para obtener la conexión
        $this->db = Conexion::obtenerConexion();
        $this->carritoModel = new CarritoModel($this->db);
    }
    
    // Mostrar el carrito
    public function mostrarCarrito() {
        $userId = $_SESSION['cliente_id'];
        
        // Obtener los items del carrito
        $cartItems = $this->carritoModel->getCartItems($userId);
        
        // Calcular el total
        $total = $this->carritoModel->calculateTotal($cartItems);
        
        // Preparar los datos para la vista
        $pageTitle = "Carrito de Compras - MacaBlue";
        
        // Incluir la vista
        require_once dirname(__DIR__) . '/view/Carrito.php';
    }
    
    // Eliminar un item del carrito
    public function eliminarItem() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['cliente_id'])) {
            echo "<script>
                document.addEventListener('DOMContentLoaded', function () {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Acceso denegado',
                        text: 'Debes iniciar sesión para realizar esta acción.',
                        confirmButtonText: 'Iniciar sesión',
                        allowOutsideClick: false
                    }).then(() => {
                        window.location.href = '../views/ingreso.php';
                    });
                });
            </script>";
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['carrito_id'])) {
            $cartId = $_POST['carrito_id'];
            $userId = $_SESSION['cliente_id'];

            if ($this->carritoModel->deleteCartItem($cartId, $userId)) {
                // Redirigir al carrito con un mensaje de éxito
                header("Location: ../view/carrito.php?success=2");
                exit();
            } else {
                echo "<script>
                    document.addEventListener('DOMContentLoaded', function () {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'No se pudo eliminar el producto del carrito.',
                            confirmButtonText: 'Aceptar'
                        }).then(() => {
                            window.location.href = '../view/carrito.php';
                        });
                    });
                </script>";
            }
        } else {
            echo "<script>
                document.addEventListener('DOMContentLoaded', function () {
                    Swal.fire({
                        icon: 'error',
                        title: 'Datos inválidos',
                        text: 'No se pudo procesar la solicitud.',
                        confirmButtonText: 'Aceptar'
                    }).then(() => {
                        window.location.href = '../view/carrito.php';
                    });
                });
            </script>";
        }
    }
    
    // Añadir producto al carrito
    public function agregarProducto() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['cliente_id'])) {
            echo "<script>
                document.addEventListener('DOMContentLoaded', function () {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Acceso denegado',
                        text: 'Debes iniciar sesión para añadir productos al carrito.',
                        confirmButtonText: 'Iniciar sesión',
                        allowOutsideClick: false
                    }).then(() => {
                        window.location.href = '../view/ingreso.php';
                    });
                });
            </script>";
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['producto_id']) && isset($_POST['cantidad'])) {
            $productoId = $_POST['producto_id'];
            $cantidad = $_POST['cantidad'];
            $userId = $_SESSION['cliente_id'];

            if ($cantidad > 0) {
                if ($this->carritoModel->addProductToCart($productoId, $cantidad, $userId)) {
                    // Redirigir a carrito.php con un mensaje de éxito
                    header("Location: ../view/carrito.php?success=1");
                    exit();
                } else {
                    echo "<script>
                        document.addEventListener('DOMContentLoaded', function () {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'No se pudo añadir el producto al carrito.',
                                confirmButtonText: 'Aceptar'
                            }).then(() => {
                                window.location.href = '../view/productos.php';
                            });
                        });
                    </script>";
                }
            } else {
                echo "<script>
                    document.addEventListener('DOMContentLoaded', function () {
                        Swal.fire({
                            icon: 'error',
                            title: 'Cantidad inválida',
                            text: 'La cantidad debe ser mayor a 0.',
                            confirmButtonText: 'Aceptar'
                        }).then(() => {
                            window.location.href = '../view/productos.php';
                        });
                    });
                </script>";
            }
        } else {
            echo "<script>
                document.addEventListener('DOMContentLoaded', function () {
                    Swal.fire({
                        icon: 'error',
                        title: 'Datos inválidos',
                        text: 'No se pudo procesar la solicitud.',
                        confirmButtonText: 'Aceptar'
                    }).then(() => {
                        window.location.href = '../view/productos.php';
                    });
                });
            </script>";
        }
    }
        
    //Actualizar cantidad de un producto
    public function actualizarCantidad() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['cliente_id'])) {
            echo "<script>
                document.addEventListener('DOMContentLoaded', function () {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Acceso denegado',
                        text: 'Debes iniciar sesión para realizar esta acción.',
                        confirmButtonText: 'Iniciar sesión',
                        allowOutsideClick: false
                    }).then(() => {
                        window.location.href = '../ingreso.php';
                    });
                });
            </script>";
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['carrito_id']) && isset($_POST['cantidad'])) {
            $cartId = $_POST['carrito_id'];
            $quantity = $_POST['cantidad'];
            $userId = $_SESSION['cliente_id'];

            if ($quantity > 0) {
                if ($this->carritoModel->updateCartItem($cartId, $quantity, $userId)) {
                    echo "<script>
                        document.addEventListener('DOMContentLoaded', function () {
                            Swal.fire({
                                icon: 'success',
                                title: 'Carrito actualizado',
                                text: 'La cantidad del producto ha sido actualizada.',
                                confirmButtonText: 'Aceptar'
                            }).then(() => {
                                window.location.href = 'carrito.php';
                            });
                        });
                    </script>";
                } else {
                    echo "<script>
                        document.addEventListener('DOMContentLoaded', function () {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'No se pudo actualizar el carrito.',
                                confirmButtonText: 'Aceptar'
                            }).then(() => {
                                window.location.href = 'carrito.php';
                            });
                        });
                    </script>";
                }
            } else {
                echo "<script>
                    document.addEventListener('DOMContentLoaded', function () {
                        Swal.fire({
                            icon: 'error',
                            title: 'Cantidad inválida',
                            text: 'La cantidad debe ser mayor a 0.',
                            confirmButtonText: 'Aceptar'
                        }).then(() => {
                            window.location.href = 'carrito.php';
                        });
                    });
                </script>";
            }
        } else {
            echo "<script>
                document.addEventListener('DOMContentLoaded', function () {
                    Swal.fire({
                        icon: 'error',
                        title: 'Datos inválidos',
                        text: 'No se pudo procesar la solicitud.',
                        confirmButtonText: 'Aceptar'
                    }).then(() => {
                        window.location.href = 'carrito.php';
                    });
                });
            </script>";
        }
    }
    
    // Proceder al pago
    public function procesarPago() {
        $userId = $_SESSION['cliente_id'];
        
        // Obtener los items del carrito
        $cartItems = $this->carritoModel->getCartItems($userId);
        
        // Calcular el total
        $total = $this->carritoModel->calculateTotal($cartItems);
        
        // Verificar si hay productos en el carrito
        if (empty($cartItems)) {
            $_SESSION['error'] = "Tu carrito está vacío";
            header("Location: carrito.php");
            exit();
        }
        
        // Preparar los datos para la vista
        $pageTitle = "Procesar Pago - MacaBlue";
        
        // Incluir la vista de pago
        include_once '../views/carrito/pago_view.php';
    }

    // Punto de entrada para manejar acciones
    public function handleRequest() {
        if (isset($_GET['action'])) {
            $action = $_GET['action'];
    
            switch ($action) {
                case 'mostrarCarrito':
                    $this->mostrarCarrito();
                    break;
                case 'eliminarItem':
                    $this->eliminarItem();
                    break;
                case 'agregarProducto':
                    $this->agregarProducto();
                    break;
                case 'actualizarCantidad':
                    $this->actualizarCantidad();
                    break;
                case 'procesarPago':
                    $this->procesarPago();
                    break;
                default:
                    echo "<script>
                        document.addEventListener('DOMContentLoaded', function () {
                            Swal.fire({
                                icon: 'error',
                                title: 'Acción no válida',
                                text: 'La acción solicitada no es válida.',
                                confirmButtonText: 'Aceptar'
                            }).then(() => {
                                window.location.href = 'carrito.php';
                            });
                        });
                    </script>";
                    break;
            }
        } else {
            // Si no se especifica acción, mostrar el carrito
            $this->mostrarCarrito();
        }
    }
} 

// Crear una instancia del controlador y manejar la solicitud
$controller = new CarritoController();
$controller->handleRequest();
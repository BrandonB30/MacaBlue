-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 15, 2025 at 04:28 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `macablue`
--
CREATE DATABASE IF NOT EXISTS `macablue` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `macablue`;

-- --------------------------------------------------------

--
-- Table structure for table `carrito`
--

CREATE TABLE `carrito` (
  `carrito_id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `producto_id` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL DEFAULT 1,
  `fecha_agregado` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `carrito`
--

INSERT INTO `carrito` (`carrito_id`, `usuario_id`, `producto_id`, `cantidad`, `fecha_agregado`) VALUES
(2, 2, 27, 1, '2025-03-07 16:22:46'),
(3, 2, 33, 1, '2025-03-07 16:22:52');

-- --------------------------------------------------------

--
-- Table structure for table `categorias`
--

CREATE TABLE `categorias` (
  `categoria_id` int(11) NOT NULL,
  `nombreCategoria` varchar(100) NOT NULL,
  `subcategorias` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categorias`
--

INSERT INTO `categorias` (`categoria_id`, `nombreCategoria`, `subcategorias`, `created_at`) VALUES
(1, 'Damas', 'Blusas, Pantalones, Vestidos, Chaquetas', '2024-11-09 15:12:05');

-- --------------------------------------------------------

--
-- Table structure for table `clientes`
--

CREATE TABLE `clientes` (
  `cliente_id` int(11) NOT NULL,
  `nombreCliente` varchar(50) NOT NULL,
  `apellidoCliente` varchar(50) NOT NULL,
  `emailCliente` varchar(100) NOT NULL,
  `passwordCliente` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `clientes`
--

INSERT INTO `clientes` (`cliente_id`, `nombreCliente`, `apellidoCliente`, `emailCliente`, `passwordCliente`, `created_at`) VALUES
(1, 'Juan', 'Quinto', 'juan@gmail.com', '$2y$10$NXTDfjpFyLLdI6UbsbGP7upr9w4Da9yRf1AYwv9KBy7rrwAif4Hce', '2024-11-09 15:18:35'),
(2, 'Brandon ', 'Bernal Rodriguez', 'brandon@gmail.com', '$2y$10$UcmPB/kOiOEfCps7SNGwQuKMeAbB63dsrekP4C4aLu2HZB0bBUpNy', '2025-03-01 00:43:58'),
(4, 'Brandon', 'Bernal', 'brandons@gmail.com', '$2y$10$hQLKrXKXhXmere6SWCKmwuZiGEN26aZ559jAyfDdvV1DE8KDFzmAG', '2025-03-14 23:57:29');

-- --------------------------------------------------------

--
-- Table structure for table `detalle_pedido`
--

CREATE TABLE `detalle_pedido` (
  `detalle_id` int(11) NOT NULL,
  `pedido_id` int(11) NOT NULL,
  `producto_id` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `detalle_pedido`
--

INSERT INTO `detalle_pedido` (`detalle_id`, `pedido_id`, `producto_id`, `cantidad`, `precio`) VALUES
(1, 1, 26, 1, 45000.00);

-- --------------------------------------------------------

--
-- Table structure for table `pedidos`
--

CREATE TABLE `pedidos` (
  `pedido_id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `estado` varchar(20) NOT NULL,
  `direccion_envio` text NOT NULL,
  `metodo_pago` varchar(50) NOT NULL,
  `fecha_pedido` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pedidos`
--

INSERT INTO `pedidos` (`pedido_id`, `usuario_id`, `total`, `estado`, `direccion_envio`, `metodo_pago`, `fecha_pedido`) VALUES
(1, 2, 45000.00, 'Pendiente', 'cra 75 # 522', 'tarjeta', '2025-03-07 16:21:56');

-- --------------------------------------------------------

--
-- Table structure for table `productos`
--

CREATE TABLE `productos` (
  `producto_id` int(11) NOT NULL,
  `nombreProducto` varchar(255) NOT NULL,
  `categoriaProducto` int(11) DEFAULT NULL,
  `subcategoriaProducto` varchar(255) DEFAULT NULL,
  `precioProducto` decimal(10,2) NOT NULL,
  `stockProducto` int(11) NOT NULL,
  `estadoProducto` enum('Disponible','No disponible') NOT NULL,
  `tallas` varchar(255) DEFAULT NULL,
  `colorProducto` varchar(50) DEFAULT NULL,
  `materialProducto` varchar(50) DEFAULT NULL,
  `descripcionProducto` text DEFAULT NULL,
  `fotosProducto` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `productos`
--

INSERT INTO `productos` (`producto_id`, `nombreProducto`, `categoriaProducto`, `subcategoriaProducto`, `precioProducto`, `stockProducto`, `estadoProducto`, `tallas`, `colorProducto`, `materialProducto`, `descripcionProducto`, `fotosProducto`) VALUES
(26, 'Blusa Casual Blanca', 1, 'Blusas', 45000.00, 20, 'Disponible', 'M,L', 'Blanco', 'Poliéster', 'Blusa blanca con diseño casual y cuello en V, ideal para ocasiones informales.', 'Imagen1.jpg'),
(27, 'Blusa Rosa Suave', 1, 'Blusas', 38000.00, 15, 'Disponible', 'S,M,L', 'Rosa', 'Algodón', 'Blusa en color rosa suave con mangas dobladas, perfecta para días casuales.', 'Imagen2.jpg'),
(28, 'Blusa Floral', 1, 'Blusas', 52000.00, 10, 'Disponible', 'M,XL', 'Multicolor', 'Poliéster', 'Blusa con estampado floral en tonos cálidos, ideal para una apariencia fresca y veraniega.', 'Imagen3.jpg'),
(29, 'Blusa Clásica Beige', 1, 'Blusas', 40000.00, 8, 'Disponible', 'M,L', 'Beige', 'Lino', 'Blusa beige con botones, estilo clásico y sofisticado.', 'Imagen4.jpg'),
(30, 'Blusa Elegante Amarilla', 1, 'Blusas', 48000.00, 12, 'Disponible', 'S,M,L,XL', 'Amarillo', 'Poliéster', 'Blusa amarilla de manga larga y cuello en V, ideal para ocasiones especiales.', 'Imagen5.jpg'),
(31, 'Pantalón Negro Casual', 1, 'Pantalones', 60000.00, 18, 'Disponible', 'M,L,XL', 'Negro', 'Algodón', 'Pantalón negro casual perfecto para combinar con distintas prendas.', 'Imagen6.jpg'),
(32, 'Pantalón Cuadros Gris', 1, 'Pantalones', 58000.00, 10, 'Disponible', 'S,M,L', 'Gris', 'Poliéster', 'Pantalón con diseño de cuadros en tonos grises, ideal para una apariencia formal.', 'Imagen7.jpg'),
(33, 'Pantalón Beige Elegante', 1, 'Pantalones', 62000.00, 14, 'Disponible', 'M,L', 'Beige', 'Lino', 'Pantalón beige clásico, cómodo y versátil para múltiples ocasiones.', 'Imagen8.jpg'),
(34, 'Pantalón Negro Ajustado', 1, 'Pantalones', 55000.00, 8, 'Disponible', 'S,M,L,XL', 'Negro', 'Poliéster', 'Pantalón negro ajustado que complementa un estilo moderno y elegante.', 'Imagen9.jpg'),
(35, 'Pantalón Estampado', 1, 'Pantalones', 53000.00, 5, 'Disponible', 'M,L', 'Blanco', 'Algodón', 'Pantalón con estampado colorido, ideal para un look llamativo y fresco.', 'Imagen10.jpg'),
(36, 'Vestido Beige Casual', 1, 'Vestidos', 75000.00, 12, 'Disponible', 'S,M,L', 'Beige', 'Lino', 'Vestido beige de estilo casual, cómodo y versátil para distintas ocasiones.', 'Imagen11.jpg'),
(37, 'Vestido Largo Elegante', 1, 'Vestidos', 85000.00, 8, 'Disponible', 'M,L,XL', 'Negro', 'Seda', 'Vestido largo en negro, ideal para eventos elegantes.', 'Imagen12.jpg'),
(38, 'Vestido Floral Veraniego', 1, 'Vestidos', 70000.00, 10, 'Disponible', 'S,M,L', 'Multicolor', 'Algodón', 'Vestido con estampado floral, perfecto para el verano.', 'Imagen13.jpg'),
(39, 'Vestido Rojo Ajustado', 1, 'Vestidos', 78000.00, 5, 'Disponible', 'M,L', 'Rojo', 'Poliéster', 'Vestido rojo ajustado, ideal para una salida nocturna.', 'Imagen14.jpg'),
(40, 'Vestido Blanco de Encaje', 1, 'Vestidos', 90000.00, 6, 'Disponible', 'S,M,L', 'Blanco', 'Encaje', 'Vestido blanco de encaje con un toque romántico.', 'Imagen15.jpg'),
(41, 'Chaqueta Negra Acolchada', 1, 'Chaquetas', 120000.00, 15, 'Disponible', 'M,L,XL', 'Negro', 'Nylon', 'Chaqueta negra acolchada, perfecta para el invierno.', 'Imagen16.jpg'),
(42, 'Chaqueta Casual Azul', 1, 'Chaquetas', 95000.00, 20, 'Disponible', 'S,M,L', 'Azul', 'Algodón', 'Chaqueta azul casual, ideal para días frescos.', 'Imagen17.jpg'),
(43, 'Chaqueta Verde Militar', 1, 'Chaquetas', 110000.00, 8, 'Disponible', 'M,L', 'Verde', 'Poliéster', 'Chaqueta estilo militar en verde, perfecta para un look urbano.', 'Imagen18.jpg'),
(44, 'Chaqueta de Cuero Negra', 1, 'Chaquetas', 150000.00, 5, 'Disponible', 'M,L', 'Negro', 'Cuero', 'Chaqueta de cuero negra clásica, imprescindible en cualquier guardarropa.', 'Imagen19.jpg'),
(45, 'Chaqueta Ligera Beige', 1, 'Chaquetas', 85000.00, 12, 'Disponible', 'S,M,L,XL', 'Beige', 'Lino', 'Chaqueta ligera en color beige, ideal para primavera.', 'Imagen20.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `usuarios`
--

CREATE TABLE `usuarios` (
  `usuario_id` int(11) NOT NULL,
  `nombreUsuario` varchar(50) NOT NULL,
  `apellidoUsuario` varchar(50) NOT NULL,
  `emailUsuario` varchar(100) NOT NULL,
  `rolUsuario` enum('Administrador','Empleado','Supervisor') NOT NULL,
  `passwordUsuario` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `usuarios`
--

INSERT INTO `usuarios` (`usuario_id`, `nombreUsuario`, `apellidoUsuario`, `emailUsuario`, `rolUsuario`, `passwordUsuario`, `created_at`) VALUES
(1, 'Juan', 'Quinto', 'juan@gmail.com', 'Administrador', '1234', '2024-11-08 08:31:32'),
(2, 'Brandon', 'Bernal', 'brandona@gmail.com', 'Administrador', '$2y$10$rhwI5c2konbmm2IJUAPrOOx4cH3pdmNr7UlivT1fukSx.akWfA4ES', '2025-03-01 00:45:59'),
(3, 'Kevin', 'Rodridguez', 'kevin@gmail.com', 'Empleado', '$2y$10$EULpzoxKtdOZorg7vx/teeeI9iOTC/n/3pzUh7fweuGhp9ETK8C.y', '2025-03-08 15:46:02');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `carrito`
--
ALTER TABLE `carrito`
  ADD PRIMARY KEY (`carrito_id`),
  ADD KEY `usuario_id` (`usuario_id`),
  ADD KEY `producto_id` (`producto_id`);

--
-- Indexes for table `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`categoria_id`);

--
-- Indexes for table `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`cliente_id`),
  ADD UNIQUE KEY `emailCliente` (`emailCliente`);

--
-- Indexes for table `detalle_pedido`
--
ALTER TABLE `detalle_pedido`
  ADD PRIMARY KEY (`detalle_id`),
  ADD KEY `pedido_id` (`pedido_id`),
  ADD KEY `producto_id` (`producto_id`);

--
-- Indexes for table `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`pedido_id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Indexes for table `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`producto_id`),
  ADD KEY `categoriaProducto` (`categoriaProducto`);

--
-- Indexes for table `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`usuario_id`),
  ADD UNIQUE KEY `emailUsuario` (`emailUsuario`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `carrito`
--
ALTER TABLE `carrito`
  MODIFY `carrito_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `categorias`
--
ALTER TABLE `categorias`
  MODIFY `categoria_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `clientes`
--
ALTER TABLE `clientes`
  MODIFY `cliente_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `detalle_pedido`
--
ALTER TABLE `detalle_pedido`
  MODIFY `detalle_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `pedido_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `productos`
--
ALTER TABLE `productos`
  MODIFY `producto_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `usuario_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `carrito`
--
ALTER TABLE `carrito`
  ADD CONSTRAINT `carrito_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `clientes` (`cliente_id`),
  ADD CONSTRAINT `carrito_ibfk_2` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`producto_id`);

--
-- Constraints for table `detalle_pedido`
--
ALTER TABLE `detalle_pedido`
  ADD CONSTRAINT `detalle_pedido_ibfk_1` FOREIGN KEY (`pedido_id`) REFERENCES `pedidos` (`pedido_id`),
  ADD CONSTRAINT `detalle_pedido_ibfk_2` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`producto_id`);

--
-- Constraints for table `pedidos`
--
ALTER TABLE `pedidos`
  ADD CONSTRAINT `pedidos_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `clientes` (`cliente_id`);

--
-- Constraints for table `productos`
--
ALTER TABLE `productos`
  ADD CONSTRAINT `productos_ibfk_1` FOREIGN KEY (`categoriaProducto`) REFERENCES `categorias` (`categoria_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 09-04-2025 a las 21:18:48
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `neocaja`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `accounts`
--

CREATE TABLE `accounts` (
  `id` int(11) NOT NULL,
  `cedula` varchar(10) NOT NULL,
  `names` varchar(100) NOT NULL,
  `surnames` varchar(100) NOT NULL,
  `address` text NOT NULL,
  `is_student` tinyint(1) NOT NULL,
  `scholarship` int(11) DEFAULT NULL,
  `scholarship_coverage` int(11) DEFAULT NULL,
  `company` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `banks`
--

CREATE TABLE `banks` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `code` varchar(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `coins`
--

CREATE TABLE `coins` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `url` text NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `coin_history`
--

CREATE TABLE `coin_history` (
  `id` int(11) NOT NULL,
  `coin` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `companies`
--

CREATE TABLE `companies` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `rif_letter` char(1) NOT NULL,
  `rif` varchar(9) NOT NULL,
  `address` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `concepts`
--

CREATE TABLE `concepts` (
  `id` int(11) NOT NULL,
  `product` int(11) NOT NULL,
  `price` float(10,2) NOT NULL,
  `invoice` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `invoices`
--

CREATE TABLE `invoices` (
  `id` int(11) NOT NULL,
  `invoice_number` int(11) NOT NULL,
  `control_number` int(11) NOT NULL,
  `account` int(11) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `invoice_payment_method`
--

CREATE TABLE `invoice_payment_method` (
  `id` int(11) NOT NULL,
  `invoice` int(11) NOT NULL,
  `payment_method` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `coin` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `payment_methods`
--

CREATE TABLE `payment_methods` (
  `id` int(11) NOT NULL,
  `type` int(11) NOT NULL,
  `bank` int(11) DEFAULT NULL,
  `sale_point` int(11) DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `payment_method_types`
--

CREATE TABLE `payment_method_types` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `product_history`
--

CREATE TABLE `product_history` (
  `id` int(11) NOT NULL,
  `product` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sale_points`
--

CREATE TABLE `sale_points` (
  `id` int(11) NOT NULL,
  `code` varchar(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `scholarship_types`
--

CREATE TABLE `scholarship_types` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `coverage` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `account_company` (`company`),
  ADD KEY `account_scholarchip_type` (`scholarship`);

--
-- Indices de la tabla `banks`
--
ALTER TABLE `banks`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `coins`
--
ALTER TABLE `coins`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `coin_history`
--
ALTER TABLE `coin_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `coin_coin_history` (`coin`);

--
-- Indices de la tabla `companies`
--
ALTER TABLE `companies`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `concepts`
--
ALTER TABLE `concepts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `concepts_invoice` (`invoice`);

--
-- Indices de la tabla `invoices`
--
ALTER TABLE `invoices`
  ADD PRIMARY KEY (`id`),
  ADD KEY `invoice_account` (`account`);

--
-- Indices de la tabla `invoice_payment_method`
--
ALTER TABLE `invoice_payment_method`
  ADD PRIMARY KEY (`id`),
  ADD KEY `invoice_payment_method_invoice` (`invoice`),
  ADD KEY `invoice_payment_method_payment_method` (`payment_method`),
  ADD KEY `payment_method_coin` (`coin`);

--
-- Indices de la tabla `payment_methods`
--
ALTER TABLE `payment_methods`
  ADD PRIMARY KEY (`id`),
  ADD KEY `payment_method_payment_method_type` (`type`),
  ADD KEY `payment:method_bank` (`bank`),
  ADD KEY `payment_method_sale_point` (`sale_point`);

--
-- Indices de la tabla `payment_method_types`
--
ALTER TABLE `payment_method_types`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `product_history`
--
ALTER TABLE `product_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_history_product` (`product`);

--
-- Indices de la tabla `sale_points`
--
ALTER TABLE `sale_points`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `scholarship_types`
--
ALTER TABLE `scholarship_types`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `accounts`
--
ALTER TABLE `accounts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `banks`
--
ALTER TABLE `banks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `coins`
--
ALTER TABLE `coins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `coin_history`
--
ALTER TABLE `coin_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `companies`
--
ALTER TABLE `companies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `concepts`
--
ALTER TABLE `concepts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `invoices`
--
ALTER TABLE `invoices`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `invoice_payment_method`
--
ALTER TABLE `invoice_payment_method`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `payment_methods`
--
ALTER TABLE `payment_methods`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `payment_method_types`
--
ALTER TABLE `payment_method_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `product_history`
--
ALTER TABLE `product_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `sale_points`
--
ALTER TABLE `sale_points`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `scholarship_types`
--
ALTER TABLE `scholarship_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `accounts`
--
ALTER TABLE `accounts`
  ADD CONSTRAINT `account_company` FOREIGN KEY (`company`) REFERENCES `companies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `account_scholarchip_type` FOREIGN KEY (`scholarship`) REFERENCES `scholarship_types` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `coin_history`
--
ALTER TABLE `coin_history`
  ADD CONSTRAINT `coin_coin_history` FOREIGN KEY (`coin`) REFERENCES `coins` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `concepts`
--
ALTER TABLE `concepts`
  ADD CONSTRAINT `concepts_invoice` FOREIGN KEY (`invoice`) REFERENCES `invoices` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `invoices`
--
ALTER TABLE `invoices`
  ADD CONSTRAINT `invoice_account` FOREIGN KEY (`account`) REFERENCES `accounts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `invoice_payment_method`
--
ALTER TABLE `invoice_payment_method`
  ADD CONSTRAINT `invoice_payment_method_invoice` FOREIGN KEY (`invoice`) REFERENCES `invoices` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `invoice_payment_method_payment_method` FOREIGN KEY (`payment_method`) REFERENCES `payment_methods` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `payment_method_coin` FOREIGN KEY (`coin`) REFERENCES `coin_history` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `payment_methods`
--
ALTER TABLE `payment_methods`
  ADD CONSTRAINT `payment:method_bank` FOREIGN KEY (`bank`) REFERENCES `banks` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `payment_method_payment_method_type` FOREIGN KEY (`type`) REFERENCES `payment_method_types` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `payment_method_sale_point` FOREIGN KEY (`sale_point`) REFERENCES `sale_points` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `product_history`
--
ALTER TABLE `product_history`
  ADD CONSTRAINT `product_history_product` FOREIGN KEY (`product`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

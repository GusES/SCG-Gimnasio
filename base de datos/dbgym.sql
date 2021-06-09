-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 09-06-2021 a las 23:18:43
-- Versión del servidor: 10.4.19-MariaDB
-- Versión de PHP: 8.0.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `dbgym`
--
CREATE DATABASE IF NOT EXISTS `dbgym` DEFAULT CHARACTER SET utf8 COLLATE utf8_spanish_ci;
USE `dbgym`;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asignaciones`
--

CREATE TABLE `asignaciones` (
  `idasignaciones` int(11) NOT NULL,
  `idcliente` int(11) DEFAULT NULL,
  `idgrupo` int(11) DEFAULT NULL,
  `idmembresia` int(11) DEFAULT NULL,
  `fecha_vencimiento` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asistencias`
--

CREATE TABLE `asistencias` (
  `idasistencias` int(11) NOT NULL,
  `idcliente` int(11) DEFAULT NULL,
  `fecha_asistencia` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes`
--

CREATE TABLE `clientes` (
  `idclientes` int(11) NOT NULL,
  `nombre` varchar(100) COLLATE utf8_spanish_ci DEFAULT NULL,
  `apellido` varchar(100) COLLATE utf8_spanish_ci DEFAULT NULL,
  `dni` int(8) DEFAULT NULL,
  `clave` int(4) DEFAULT NULL,
  `notacredito` float DEFAULT 0,
  `nacimiento` date DEFAULT NULL,
  `direccion` varchar(100) COLLATE utf8_spanish_ci DEFAULT NULL,
  `estado` tinyint(1) DEFAULT 1,
  `genero` varchar(100) COLLATE utf8_spanish_ci DEFAULT NULL,
  `telefono` varchar(100) COLLATE utf8_spanish_ci DEFAULT NULL,
  `correo` varchar(100) COLLATE utf8_spanish_ci DEFAULT NULL,
  `facebook` varchar(100) COLLATE utf8_spanish_ci DEFAULT NULL,
  `fecha_alta` timestamp NOT NULL DEFAULT current_timestamp(),
  `ult_ingreso` timestamp NOT NULL DEFAULT current_timestamp(),
  `ult_fecha_pago` timestamp NOT NULL DEFAULT current_timestamp(),
  `observacion` varchar(1000) COLLATE utf8_spanish_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ejercicios`
--

CREATE TABLE `ejercicios` (
  `idejercicios` int(11) NOT NULL,
  `grupomuscular` varchar(100) COLLATE utf8_spanish_ci DEFAULT NULL,
  `nombre` varchar(100) COLLATE utf8_spanish_ci DEFAULT NULL,
  `numero` varchar(3) COLLATE utf8_spanish_ci DEFAULT NULL,
  `path` varchar(350) COLLATE utf8_spanish_ci DEFAULT NULL,
  `detalles` varchar(350) COLLATE utf8_spanish_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `facturacion`
--

CREATE TABLE `facturacion` (
  `idfacturacion` int(11) NOT NULL,
  `idcliente` int(8) DEFAULT NULL,
  `idgrupos` int(11) DEFAULT NULL,
  `tipo` varchar(10) COLLATE utf8_spanish_ci DEFAULT NULL,
  `nom_completo` varchar(250) COLLATE utf8_spanish_ci DEFAULT NULL,
  `nom_grupos` varchar(250) COLLATE utf8_spanish_ci DEFAULT NULL,
  `disciplina` varchar(250) COLLATE utf8_spanish_ci DEFAULT NULL,
  `valores` varchar(255) COLLATE utf8_spanish_ci DEFAULT NULL,
  `precio` int(11) DEFAULT NULL,
  `abonado` int(11) DEFAULT NULL,
  `num_factura` varchar(50) COLLATE utf8_spanish_ci DEFAULT NULL,
  `fecha_pago` timestamp NULL DEFAULT NULL,
  `fecha_vence` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `grupos_clie`
--

CREATE TABLE `grupos_clie` (
  `idgrupos` int(11) NOT NULL,
  `nom_grupos` varchar(100) COLLATE utf8_spanish_ci DEFAULT NULL,
  `num_clie` tinyint(1) DEFAULT NULL,
  `cliente1` int(8) DEFAULT NULL,
  `cliente2` int(8) DEFAULT NULL,
  `cliente3` int(8) DEFAULT NULL,
  `cliente4` int(8) DEFAULT NULL,
  `cliente5` int(8) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ingresos_mem`
--

CREATE TABLE `ingresos_mem` (
  `idingresos` int(11) NOT NULL,
  `idcliente` int(11) DEFAULT NULL,
  `idmembresia` int(11) DEFAULT NULL,
  `ult_ingreso` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `membresias`
--

CREATE TABLE `membresias` (
  `idmembresias` int(11) NOT NULL,
  `nom_modulo` varchar(100) COLLATE utf8_spanish_ci DEFAULT NULL,
  `precio` int(11) DEFAULT NULL,
  `actividades` tinyint(1) DEFAULT 0,
  `cant_per` tinyint(1) DEFAULT NULL,
  `detalle` varchar(350) COLLATE utf8_spanish_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `planes`
--

CREATE TABLE `planes` (
  `idplanes` int(11) NOT NULL,
  `nombre` varchar(200) COLLATE utf8_spanish_ci DEFAULT NULL,
  `idejercicio` varchar(350) COLLATE utf8_spanish_ci DEFAULT NULL,
  `rep` varchar(350) COLLATE utf8_spanish_ci DEFAULT NULL,
  `series` varchar(350) COLLATE utf8_spanish_ci DEFAULT NULL,
  `dia` varchar(200) COLLATE utf8_spanish_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `idroles` int(11) NOT NULL,
  `tipo` int(1) DEFAULT NULL,
  `rol` varchar(100) COLLATE utf8_spanish_ci DEFAULT NULL,
  `usuario` varchar(100) COLLATE utf8_spanish_ci DEFAULT NULL,
  `contrasena` varchar(100) COLLATE utf8_spanish_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`idroles`, `tipo`, `rol`, `usuario`, `contrasena`) VALUES
(1, 1, 'Administrador', 'admin', 'admin');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rutinas`
--

CREATE TABLE `rutinas` (
  `idrutinas` int(11) NOT NULL,
  `tipo` varchar(100) COLLATE utf8_spanish_ci DEFAULT NULL,
  `idcliente` varchar(45) COLLATE utf8_spanish_ci DEFAULT NULL,
  `fecha` date DEFAULT NULL,
  `idejercicio` varchar(350) COLLATE utf8_spanish_ci DEFAULT NULL,
  `rep` varchar(350) COLLATE utf8_spanish_ci DEFAULT NULL,
  `series` varchar(350) COLLATE utf8_spanish_ci DEFAULT NULL,
  `dia` varchar(200) COLLATE utf8_spanish_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `asignaciones`
--
ALTER TABLE `asignaciones`
  ADD PRIMARY KEY (`idasignaciones`);

--
-- Indices de la tabla `asistencias`
--
ALTER TABLE `asistencias`
  ADD PRIMARY KEY (`idasistencias`);

--
-- Indices de la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`idclientes`);

--
-- Indices de la tabla `ejercicios`
--
ALTER TABLE `ejercicios`
  ADD PRIMARY KEY (`idejercicios`);

--
-- Indices de la tabla `facturacion`
--
ALTER TABLE `facturacion`
  ADD PRIMARY KEY (`idfacturacion`) USING BTREE;

--
-- Indices de la tabla `grupos_clie`
--
ALTER TABLE `grupos_clie`
  ADD PRIMARY KEY (`idgrupos`);

--
-- Indices de la tabla `ingresos_mem`
--
ALTER TABLE `ingresos_mem`
  ADD PRIMARY KEY (`idingresos`);

--
-- Indices de la tabla `membresias`
--
ALTER TABLE `membresias`
  ADD PRIMARY KEY (`idmembresias`);

--
-- Indices de la tabla `planes`
--
ALTER TABLE `planes`
  ADD PRIMARY KEY (`idplanes`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`idroles`);

--
-- Indices de la tabla `rutinas`
--
ALTER TABLE `rutinas`
  ADD PRIMARY KEY (`idrutinas`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `asignaciones`
--
ALTER TABLE `asignaciones`
  MODIFY `idasignaciones` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `asistencias`
--
ALTER TABLE `asistencias`
  MODIFY `idasistencias` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `clientes`
--
ALTER TABLE `clientes`
  MODIFY `idclientes` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `ejercicios`
--
ALTER TABLE `ejercicios`
  MODIFY `idejercicios` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `facturacion`
--
ALTER TABLE `facturacion`
  MODIFY `idfacturacion` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `grupos_clie`
--
ALTER TABLE `grupos_clie`
  MODIFY `idgrupos` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `ingresos_mem`
--
ALTER TABLE `ingresos_mem`
  MODIFY `idingresos` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `membresias`
--
ALTER TABLE `membresias`
  MODIFY `idmembresias` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `planes`
--
ALTER TABLE `planes`
  MODIFY `idplanes` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `idroles` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `rutinas`
--
ALTER TABLE `rutinas`
  MODIFY `idrutinas` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

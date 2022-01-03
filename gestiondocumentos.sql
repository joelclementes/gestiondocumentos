-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 03-01-2022 a las 03:48:14
-- Versión del servidor: 10.4.22-MariaDB
-- Versión de PHP: 7.4.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `gestiondocumentos`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `admmenu`
--

CREATE TABLE `admmenu` (
  `idMenu` int(11) NOT NULL,
  `idDiv` varchar(100) DEFAULT NULL,
  `paginaHref` varchar(100) DEFAULT NULL,
  `tituloMenu` varchar(100) DEFAULT NULL,
  `descripcionDelMenu` varchar(100) DEFAULT NULL,
  `iconoDelMenu` varchar(100) DEFAULT NULL,
  `orden` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `admmenu`
--

INSERT INTO `admmenu` (`idMenu`, `idDiv`, `paginaHref`, `tituloMenu`, `descripcionDelMenu`, `iconoDelMenu`, `orden`) VALUES
(1, 'catEtiquetasEntrada', 'CatEtiquetas', 'Etiquetas', '', 'fa fa-users', 1),
(5, 'admUsuarios', 'Usuarios', 'Administración de usuarios', '', 'fa fa-user-circle', 6),
(15, 'CapturaDocumentos', 'RegistroDocumentos', 'Registro de documentos', NULL, 'fa fa-users', 1),
(11, 'admRespalda', 'Respalda', 'Respaldar base de datos', '', 'fa fa-download', 7),
(16, 'CatOrigen', 'CatOrigen', 'Catálogo de origen', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `admusuariomenu`
--

CREATE TABLE `admusuariomenu` (
  `id` int(11) NOT NULL,
  `idUsuario` int(11) NOT NULL,
  `idMenu` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `admusuariomenu`
--

INSERT INTO `admusuariomenu` (`id`, `idUsuario`, `idMenu`) VALUES
(137, 1, 1),
(5, 1, 5),
(135, 1, 15),
(136, 1, 16),
(138, 11, 15),
(139, 11, 1),
(140, 11, 16);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `admusuarios`
--

CREATE TABLE `admusuarios` (
  `idUsuario` int(11) NOT NULL,
  `nombreUsuario` varchar(100) DEFAULT NULL,
  `clave` varchar(20) DEFAULT NULL,
  `pwd` varchar(50) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `admusuarios`
--

INSERT INTO `admusuarios` (`idUsuario`, `nombreUsuario`, `clave`, `pwd`) VALUES
(1, 'Lic. Joel Clemente Serrano', 'jclemente', '906de634c48fb7d34136160b4c353ae4'),
(11, 'Uziel Clemente Cruz', 'uclemente', '52c7915d4e0b6d93268b1f63bfd4578b');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `catetiquetas`
--

CREATE TABLE `catetiquetas` (
  `idEtiqueta` int(11) NOT NULL,
  `nombre` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `catetiquetas`
--

INSERT INTO `catetiquetas` (`idEtiqueta`, `nombre`) VALUES
(5, 'Apoyo económico'),
(6, 'Gestión de pavimentación'),
(7, 'Gestión de alumbrado');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `catorigen`
--

CREATE TABLE `catorigen` (
  `idOrigen` int(11) NOT NULL,
  `nombre` varchar(200) DEFAULT NULL COMMENT '	',
  `extensionDepartamento` varchar(5) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `catorigen`
--

INSERT INTO `catorigen` (`idOrigen`, `nombre`, `extensionDepartamento`) VALUES
(1, 'Secretaría General', ''),
(2, 'Coordinación de Archivo, Biblioteca y Hemeroteca', ''),
(3, 'Oficina de Biblioteca', ''),
(4, 'Coordinación de Comunicación Social', ''),
(5, 'Coordinación de Investigaciones Legislativas', ''),
(6, 'Coordinación de Informática', ''),
(7, 'Oficina de Oficialía de Partes', ''),
(8, 'Oficina de Servicios Médicos', ''),
(9, 'Oficina de Seguridad', ''),
(10, 'Dirección del Centro de Estudios para la Igualdad de Género y los Derechos Humanos', ''),
(11, 'Secretaría de Servicios Legislativos', ''),
(12, 'Dirección de Asistencia Técnica Legislativa', ''),
(13, 'Departamento de Asistencia a Sesiones', ''),
(14, 'Departamento de Asistencia a Comisiones', ''),
(15, 'Dirección de Registro Legislativo y Publicaciones Oficiales', ''),
(16, 'Departamento de Registro Documental Legislativo', ''),
(17, 'Departamento de Diario de los Debates', ''),
(18, 'Secretaría de Serivicios Administrativos y Financieros', ''),
(19, 'Dirección de Tesorería', ''),
(20, 'Departamento de Finanzas', ''),
(21, 'Departamento de Programación y Presupuesto', ''),
(22, 'Departamento de Contabilidad', ''),
(23, 'Dirección de Recursos Humanos', ''),
(24, 'Departamento de Control de Personal', ''),
(25, 'Departamento de Nómina', ''),
(26, 'Departamento de Organización y Métodos', ''),
(27, 'Dirección de Recursos Materiales y Servicios Generales', ''),
(28, 'Departamento de Recursos Materiales', ''),
(29, 'Departamento de Servicios Generales', ''),
(30, 'Departamento de Adquisiciones', ''),
(31, 'Secretaría de Fiscalización', ''),
(32, 'Dirección de Auditoría y Revisión Financiera', ''),
(33, 'Departamento de Auditoría y Análisis de la Cuenta Pública', ''),
(34, 'Departamento de Capacitación, Asesoría, Revisión y Supervisión a Municipios', ''),
(35, 'Dirección de Normatividad Control y Seguimiento', ''),
(36, 'Departamento de Registro de Deuda Pública y Programas Institucionales', ''),
(37, 'Departamento de Responsabilidades Administrativas de Servidores Públicos', ''),
(38, 'Dirección de Servicios Jurídicos', ''),
(39, 'Departamento de Amparos', ''),
(40, 'Departamento de Fundo Legal', ''),
(41, 'Subdirección de Servicios Jurídicos', ''),
(43, 'Visitante', ''),
(44, 'Área de diputados', ''),
(48, 'Ayudantía', ''),
(49, 'Almacén', ''),
(50, 'Inventarios', ''),
(51, 'Servicio de fotocopiado', ''),
(52, 'Unidad de Transparencia', ''),
(53, 'Oficina del Sindicato', ''),
(54, 'Oficina de equidad y género', ''),
(56, 'JUCOPO', ''),
(59, 'Asistencia a sesiones', ''),
(60, 'Caja', ''),
(61, 'Asesores ', ''),
(62, 'Diputados', ''),
(63, 'Amparos', ''),
(64, 'Normatividad ', ''),
(68, 'Prensa', ''),
(75, 'CONALEP', NULL),
(76, 'Registro Civil', NULL),
(77, 'Com. Social Grupo Morena', NULL),
(82, 'Secretaría de Fiscalización ', NULL),
(92, 'Contraloría', NULL),
(97, 'Subcontraloría', NULL),
(102, 'Capacitación a municipios', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `documento`
--

CREATE TABLE `documento` (
  `idDocumento` int(11) NOT NULL,
  `idOrigen` int(11) NOT NULL,
  `idRecibio` int(11) NOT NULL,
  `numeroOficio` varchar(100) NOT NULL,
  `fechaOficio` date DEFAULT NULL,
  `firmadoPor` varchar(200) NOT NULL,
  `asunto` text NOT NULL,
  `etiquetasEntrada` text NOT NULL,
  `fecha` datetime NOT NULL DEFAULT current_timestamp(),
  `archivo` varchar(250) DEFAULT NULL,
  `notas` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `documentohistorial`
--

CREATE TABLE `documentohistorial` (
  `id` int(11) NOT NULL,
  `idDocumento` int(11) NOT NULL,
  `idUsuario` int(11) NOT NULL,
  `nota` text NOT NULL,
  `fecha` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `admmenu`
--
ALTER TABLE `admmenu`
  ADD PRIMARY KEY (`idMenu`);

--
-- Indices de la tabla `admusuariomenu`
--
ALTER TABLE `admusuariomenu`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_admusuariomenu_admusuarios1` (`idUsuario`),
  ADD KEY `fk_admusuariomenu_admmenu1` (`idMenu`);

--
-- Indices de la tabla `admusuarios`
--
ALTER TABLE `admusuarios`
  ADD PRIMARY KEY (`idUsuario`);

--
-- Indices de la tabla `catetiquetas`
--
ALTER TABLE `catetiquetas`
  ADD PRIMARY KEY (`idEtiqueta`);

--
-- Indices de la tabla `catorigen`
--
ALTER TABLE `catorigen`
  ADD PRIMARY KEY (`idOrigen`);

--
-- Indices de la tabla `documento`
--
ALTER TABLE `documento`
  ADD PRIMARY KEY (`idDocumento`);

--
-- Indices de la tabla `documentohistorial`
--
ALTER TABLE `documentohistorial`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `admmenu`
--
ALTER TABLE `admmenu`
  MODIFY `idMenu` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de la tabla `admusuariomenu`
--
ALTER TABLE `admusuariomenu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=143;

--
-- AUTO_INCREMENT de la tabla `admusuarios`
--
ALTER TABLE `admusuarios`
  MODIFY `idUsuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `catetiquetas`
--
ALTER TABLE `catetiquetas`
  MODIFY `idEtiqueta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `catorigen`
--
ALTER TABLE `catorigen`
  MODIFY `idOrigen` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=120;

--
-- AUTO_INCREMENT de la tabla `documento`
--
ALTER TABLE `documento`
  MODIFY `idDocumento` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `documentohistorial`
--
ALTER TABLE `documentohistorial`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

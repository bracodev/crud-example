-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Versión del servidor:         10.1.25-MariaDB - mariadb.org binary distribution
-- SO del servidor:              Win32
-- HeidiSQL Versión:             9.5.0.5253
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Volcando estructura para tabla crud.usuarios
CREATE TABLE IF NOT EXISTS `usuarios` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cedula` varchar(30) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `nombres` varchar(30) DEFAULT NULL,
  `apellidos` varchar(30) DEFAULT NULL,
  `nacimiento` date DEFAULT NULL,
  `edad` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;

-- Volcando datos para la tabla crud.usuarios: 11 rows
/*!40000 ALTER TABLE `usuarios` DISABLE KEYS */;
INSERT INTO `usuarios` (`id`, `cedula`, `email`, `nombres`, `apellidos`, `nacimiento`, `edad`) VALUES
	(1, '1645080766499', 'dictum.eleifend.nunc@Crasdolor.co.uk', 'Burton', 'May', '2000-09-07', 32),
	(2, '1684100552599', 'eu.erat.semper@mollis.net', 'Barry', 'Mullins', '2006-07-01', 32),
	(3, '1617111225299', 'risus@vitae.org', 'Blaze', 'Paul', '2001-03-11', 47),
	(5, '1688120573599', 'scelerisque.scelerisque@justoeuarcu.net', 'Quinn', 'Donaldson', '2008-01-27', 23),
	(7, '1623042613599', 'nec.ante@consequatauctornunc.ca', 'Silas', 'Gibson', '2002-09-26', 27),
	(8, '1667050677299', 'id.blandit.at@neceleifendnon.ca', 'Maxwell', 'Hanson', '2005-03-10', 53),
	(9, '1653080373899', 'aliquet.odio@ipsumprimisin.co.uk', 'Berk', 'Finley', '2006-07-23', 40),
	(11, '1664102423199', 'nulla.Cras.eu@enim.org', 'Patrick', 'Berger', '2007-06-21', 43),
	(12, '1616021027299', 'sagittis.felis@neceleifendnon.org', 'Dean', 'Rojas', '2008-03-16', 22),
	(13, '1624062070399', 'at.augue.id@semegetmassa.net', 'Adam', 'Lamb', '2008-03-17', 21),
	(14, '1648020948399', 'odio.auctor@Pellentesqueultriciesdignissim.edu', 'Colin', 'Winters', '2007-06-30', 22);
/*!40000 ALTER TABLE `usuarios` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;

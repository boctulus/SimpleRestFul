-- phpMyAdmin SQL Dump
-- version 4.9.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 18-12-2019 a las 01:51:41
-- Versión del servidor: 10.4.8-MariaDB
-- Versión de PHP: 7.3.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `simplerest`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `folders`
--

CREATE TABLE `folders` (
  `id` int(11) NOT NULL,
  `resource_table` varchar(40) NOT NULL,
  `name` varchar(40) NOT NULL,
  `belongs_to` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `folders`
--

INSERT INTO `folders` (`id`, `resource_table`, `name`, `belongs_to`) VALUES
(9, 'products', 'comparto', 4),
(10, 'products', 'electricos', 125),
(11, 'products', 'jardineria', 125),
(5, 'products', 'lista', 87),
(4, 'products', 'lista publica', 90),
(8, 'products', 'lista10', 90),
(6, 'products', 'lista2', 90),
(1, 'products', 'mylist', 1),
(2, 'products', 'otralista', 72),
(3, 'products', 'super', 89);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `group_permissions`
--

CREATE TABLE `group_permissions` (
  `id` int(11) NOT NULL,
  `folder_id` int(11) NOT NULL,
  `belongs_to` int(11) NOT NULL,
  `member` int(11) NOT NULL,
  `r` tinyint(4) NOT NULL,
  `w` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `group_permissions`
--

INSERT INTO `group_permissions` (`id`, `folder_id`, `belongs_to`, `member`, `r`, `w`) VALUES
(1, 1, 1, 4, 1, 1),
(2, 2, 72, 79, 1, 1),
(3, 4, 90, 87, 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `from_email` varchar(320) NOT NULL,
  `from_name` varchar(60) NOT NULL,
  `to_email` varchar(320) NOT NULL,
  `to_name` varchar(40) DEFAULT NULL,
  `subject` varchar(40) NOT NULL,
  `body` text NOT NULL,
  `created_at` datetime NOT NULL,
  `sent_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `messages`
--

INSERT INTO `messages` (`id`, `from_email`, `from_name`, `to_email`, `to_name`, `subject`, `body`, `created_at`, `sent_at`) VALUES
(38, 'no_responder@simplerest.mapapulque.ro', 'No responder', 'san@', '', 'Cambio de contraseña', 'Para cambiar la contraseña siga el enlace:<br/><a href=\'http://simplerest.lan/login/change_pass/eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImlhdCI6MTU3MzQ4ODI3MSwiZXhwIjoxNTc0MDkzMDcxLCJlbWFpbCI6InNhbkAifQ.hgMVBC9M2CDsSCtOivQnPtx7sVRpfEYm6NrscdBri-0/1574093071\'>http://simplerest.lan/login/change_pass/eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImlhdCI6MTU3MzQ4ODI3MSwiZXhwIjoxNTc0MDkzMDcxLCJlbWFpbCI6InNhbkAifQ.hgMVBC9M2CDsSCtOivQnPtx7sVRpfEYm6NrscdBri-0/1574093071</a>', '2019-11-11 13:04:31', NULL),
(39, 'no_responder@simplerest.mapapulque.ro', 'No responder', 'boctulus@gmail.com', '', 'Cambio de contraseña', 'Para cambiar la contraseña siga el enlace:<br/><a href=\'http://simplerest.lan/login/change_pass/eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImlhdCI6MTU3NDYyMDU5MywiZXhwIjoxNTc1MjI1MzkzLCJlbWFpbCI6ImJvY3R1bHVzQGdtYWlsLmNvbSJ9.8zrO6DydRYrQRxKgEH8go95bOxf9rTMrCHVxqPkIJfM/1575225393\'>http://simplerest.lan/login/change_pass/eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImlhdCI6MTU3NDYyMDU5MywiZXhwIjoxNTc1MjI1MzkzLCJlbWFpbCI6ImJvY3R1bHVzQGdtYWlsLmNvbSJ9.8zrO6DydRYrQRxKgEH8go95bOxf9rTMrCHVxqPkIJfM/1575225393</a>', '2019-11-24 15:36:33', NULL),
(40, 'no_responder@simplerest.mapapulque.ro', 'No responder', 'pbozzolo@gmail.com', '', 'Cambio de contraseña', 'Para cambiar la contraseña siga el enlace:<br/><a href=\'http://simplerest.lan/login/change_pass/eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImlhdCI6MTU3NDYyMDg0MiwiZXhwIjoxNTc1MjI1NjQyLCJlbWFpbCI6InBib3p6b2xvQGdtYWlsLmNvbSJ9.slGk_5Xvx1C8YcG8HL54WY0nJmuY1eNY_Ughq6yZSTc/1575225642\'>http://simplerest.lan/login/change_pass/eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImlhdCI6MTU3NDYyMDg0MiwiZXhwIjoxNTc1MjI1NjQyLCJlbWFpbCI6InBib3p6b2xvQGdtYWlsLmNvbSJ9.slGk_5Xvx1C8YcG8HL54WY0nJmuY1eNY_Ughq6yZSTc/1575225642</a>', '2019-11-24 15:40:42', NULL),
(41, 'no_responder@simplerest.mapapulque.ro', 'No responder', 'boo@', 'Boo ', 'Confirmación de correo', 'Por favor confirme su correo siguiendo el enlace:<br/><a href=\'http://simplerest.lan/login/confirm_email/eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImlhdCI6MTU3NDg4MzkzNCwiZXhwIjoxNTc1NDg4NzM0LCJlbWFpbCI6ImJvb0AifQ.2TDt5II0L1yqHZtAkDNcL54K3MGGThEq1XDst9cn0s4/1575488734\'>http://simplerest.lan/login/confirm_email/eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImlhdCI6MTU3NDg4MzkzNCwiZXhwIjoxNTc1NDg4NzM0LCJlbWFpbCI6ImJvb0AifQ.2TDt5II0L1yqHZtAkDNcL54K3MGGThEq1XDst9cn0s4/1575488734</a>', '2019-11-27 16:45:34', NULL),
(42, 'no_responder@simplerest.mapapulque.ro', 'No responder', 'uub@', 'Uub ', 'Confirmación de correo', 'Por favor confirme su correo siguiendo el enlace:<br/><a href=\'http://simplerest.lan/login/confirm_email/eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImlhdCI6MTU3NDg4ODM1MywiZXhwIjoxNTc1NDkzMTUzLCJlbWFpbCI6InV1YkAifQ.dqY6xEd-PX8AcP0e-OKPJQ0ucrNhNlqXhO2iZUqyJTQ/1575493153\'>http://simplerest.lan/login/confirm_email/eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImlhdCI6MTU3NDg4ODM1MywiZXhwIjoxNTc1NDkzMTUzLCJlbWFpbCI6InV1YkAifQ.dqY6xEd-PX8AcP0e-OKPJQ0ucrNhNlqXhO2iZUqyJTQ/1575493153</a>', '2019-11-27 17:59:13', NULL),
(43, 'no_responder@simplerest.mapapulque.ro', 'No responder', 'asdfgh2', ' ', 'Confirmación de correo', 'Por favor confirme su correo siguiendo el enlace:<br/><a href=\'http://simplerest.lan/login/confirm_email/eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImlhdCI6MTU3NDkwOTQxNiwiZXhwIjoxNTc1NTE0MjE2LCJlbWFpbCI6ImFzZGZnaDIifQ.tSUdGnYpfWjMLTHT-L3h19SFQw4tbMGT5uxXtupbMPc/1575514216\'>http://simplerest.lan/login/confirm_email/eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImlhdCI6MTU3NDkwOTQxNiwiZXhwIjoxNTc1NTE0MjE2LCJlbWFpbCI6ImFzZGZnaDIifQ.tSUdGnYpfWjMLTHT-L3h19SFQw4tbMGT5uxXtupbMPc/1575514216</a>', '2019-11-27 23:50:16', NULL),
(44, 'no_responder@simplerest.mapapulque.ro', 'No responder', 'asdfgh23', ' ', 'Confirmación de correo', 'Por favor confirme su correo siguiendo el enlace:<br/><a href=\'http://simplerest.lan/login/confirm_email/eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImlhdCI6MTU3NDkwOTU5OCwiZXhwIjoxNTc1NTE0Mzk4LCJlbWFpbCI6ImFzZGZnaDIzIn0.uJ3ssjicRJrW6pSZN05vDCf5Z8D-ubCypNzYU3JLSko/1575514398\'>http://simplerest.lan/login/confirm_email/eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImlhdCI6MTU3NDkwOTU5OCwiZXhwIjoxNTc1NTE0Mzk4LCJlbWFpbCI6ImFzZGZnaDIzIn0.uJ3ssjicRJrW6pSZN05vDCf5Z8D-ubCypNzYU3JLSko/1575514398</a>', '2019-11-27 23:53:18', NULL),
(45, 'no_responder@simplerest.mapapulque.ro', 'No responder', 'asdfgh234', ' ', 'Confirmación de correo', 'Por favor confirme su correo siguiendo el enlace:<br/><a href=\'http://simplerest.lan/login/confirm_email/eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImlhdCI6MTU3NDkwOTcyOCwiZXhwIjoxNTc1NTE0NTI4LCJlbWFpbCI6ImFzZGZnaDIzNCJ9.2z7EN8DjsM7XeRKSwvm1oS6EaGmRsGlR9xZhBXQaB-Y/1575514528\'>http://simplerest.lan/login/confirm_email/eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImlhdCI6MTU3NDkwOTcyOCwiZXhwIjoxNTc1NTE0NTI4LCJlbWFpbCI6ImFzZGZnaDIzNCJ9.2z7EN8DjsM7XeRKSwvm1oS6EaGmRsGlR9xZhBXQaB-Y/1575514528</a>', '2019-11-27 23:55:28', NULL),
(46, 'no_responder@simplerest.mapapulque.ro', 'No responder', 'pepe@', ' ', 'Confirmación de correo', 'Por favor confirme su correo siguiendo el enlace:<br/><a href=\'http://simplerest.lan/login/confirm_email/eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImlhdCI6MTU3NDkwOTk5NCwiZXhwIjoxNTc1NTE0Nzk0LCJlbWFpbCI6InBlcGVAIn0.BDVslxr-m_MBfW_WZ-50inDMp81SZLKKkUlT7-0s_SU/1575514794\'>http://simplerest.lan/login/confirm_email/eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImlhdCI6MTU3NDkwOTk5NCwiZXhwIjoxNTc1NTE0Nzk0LCJlbWFpbCI6InBlcGVAIn0.BDVslxr-m_MBfW_WZ-50inDMp81SZLKKkUlT7-0s_SU/1575514794</a>', '2019-11-27 23:59:54', NULL),
(47, 'no_responder@simplerest.mapapulque.ro', 'No responder', 'asdffff@g.c', ' ', 'Email confirmation', 'Please confirm your account by following the link bellow:<br/><a href=\'http://simplerest.lan/login/confirm_email/eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImlhdCI6MTU3NTEyNzkyOSwiZXhwIjoxNTc1NzMyNzI5LCJlbWFpbCI6ImFzZGZmZmZAZy5jIn0.OiIQYFKYrdzeGin43jfAEPUsT6SDfKP8SvpGq_xQNyU/1575732729\'>http://simplerest.lan/login/confirm_email/eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImlhdCI6MTU3NTEyNzkyOSwiZXhwIjoxNTc1NzMyNzI5LCJlbWFpbCI6ImFzZGZmZmZAZy5jIn0.OiIQYFKYrdzeGin43jfAEPUsT6SDfKP8SvpGq_xQNyU/1575732729</a>', '2019-11-30 12:32:09', NULL),
(48, 'no_responder@simplerest.mapapulque.ro', 'No responder', 'feli@delacasita50.com', '', 'Cambio de contraseña', 'Para cambiar la contraseña siga el enlace:<br/><a href=\'http://simplerest.lan/login/change_pass/eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImlhdCI6MTU3NTE1NzkxOSwiZXhwIjoxNTc1NzYyNzE5LCJlbWFpbCI6ImZlbGlAZGVsYWNhc2l0YTUwLmNvbSJ9.nqkUxmbK8wKFq2yD6Nv_P4sSxgShLSxL_rPp7p_TeGk/1575762719\'>http://simplerest.lan/login/change_pass/eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImlhdCI6MTU3NTE1NzkxOSwiZXhwIjoxNTc1NzYyNzE5LCJlbWFpbCI6ImZlbGlAZGVsYWNhc2l0YTUwLmNvbSJ9.nqkUxmbK8wKFq2yD6Nv_P4sSxgShLSxL_rPp7p_TeGk/1575762719</a>', '2019-11-30 20:51:59', NULL),
(49, 'no_responder@simplerest.mapapulque.ro', 'No responder', 'boctulus@gmail.com', '', 'Cambio de contraseña', 'Para cambiar la contraseña siga el enlace:<br/><a href=\'http://simplerest.lan/login/change_pass/eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImlhdCI6MTU3NTE2Nzc0MCwiZXhwIjoxNTc1NzcyNTQwLCJlbWFpbCI6ImJvY3R1bHVzQGdtYWlsLmNvbSJ9.PL7m9-B7bkxJWm6Xw18AhyAfo-W33hlRb02LRnl6zE4/1575772540\'>http://simplerest.lan/login/change_pass/eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImlhdCI6MTU3NTE2Nzc0MCwiZXhwIjoxNTc1NzcyNTQwLCJlbWFpbCI6ImJvY3R1bHVzQGdtYWlsLmNvbSJ9.PL7m9-B7bkxJWm6Xw18AhyAfo-W33hlRb02LRnl6zE4/1575772540</a>', '2019-11-30 23:35:40', NULL),
(50, 'no_responder@simplerest.mapapulque.ro', 'No responder', 'uva@g.c', ' ', 'Email confirmation', 'Please confirm your account by following the link bellow:<br/><a href=\'http://simplerest.lan/login/confirm_email/eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImlhdCI6MTU3NTQ2MTg2NSwiZXhwIjoxNTc2MDY2NjY1LCJlbWFpbCI6InV2YUBnLmMifQ.EXvkwaJ_-tX7HWQcKuU5L6s4wUC1aFmJ0kVM82pftTw/1576066665\'>http://simplerest.lan/login/confirm_email/eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImlhdCI6MTU3NTQ2MTg2NSwiZXhwIjoxNTc2MDY2NjY1LCJlbWFpbCI6InV2YUBnLmMifQ.EXvkwaJ_-tX7HWQcKuU5L6s4wUC1aFmJ0kVM82pftTw/1576066665</a>', '2019-12-04 09:17:45', NULL),
(51, 'no_responder@simplerest.mapapulque.ro', 'No responder', 'tester3@g.c', '', 'Cambio de contraseña', 'Para cambiar la contraseña siga el enlace:<br/><a href=\'http://simplerest.lan/login/change_pass/eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImlhdCI6MTU3NTU2ODg3MSwiZXhwIjoxNTc2MTczNjcxLCJlbWFpbCI6InRlc3RlcjNAZy5jIn0.6Chux6IaLrfq5V6dCs4UtxejSClOFYIJr-7kqDcG-HM/1576173671\'>http://simplerest.lan/login/change_pass/eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImlhdCI6MTU3NTU2ODg3MSwiZXhwIjoxNTc2MTczNjcxLCJlbWFpbCI6InRlc3RlcjNAZy5jIn0.6Chux6IaLrfq5V6dCs4UtxejSClOFYIJr-7kqDcG-HM/1576173671</a>', '2019-12-05 15:01:12', NULL),
(52, 'no_responder@simplerest.mapapulque.ro', 'No responder', 'ffff1@g.c', ' ', 'Email confirmation', 'Please confirm your account by following the link bellow:<br/><a href=\'http://simplerest.lan/login/confirm_email/eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImlhdCI6MTU3NTYzOTQwMiwiZXhwIjoxNTc2MjQ0MjAyLCJlbWFpbCI6ImZmZmYxQGcuYyJ9.P1BSWcqwER08My4ft-obu5EToER2-YvfhqHVmxhlqLQ/1576244202\'>http://simplerest.lan/login/confirm_email/eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImlhdCI6MTU3NTYzOTQwMiwiZXhwIjoxNTc2MjQ0MjAyLCJlbWFpbCI6ImZmZmYxQGcuYyJ9.P1BSWcqwER08My4ft-obu5EToER2-YvfhqHVmxhlqLQ/1576244202</a>', '2019-12-06 10:36:42', NULL),
(53, 'no_responder@simplerest.mapapulque.ro', 'No responder', 'newuser97865wxy@g.c', ' ', 'Email confirmation', 'Please confirm your account by following the link bellow:<br/><a href=\'http://simplerest.lan/login/confirm_email/eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImlhdCI6MTU3NTY0MDE2NCwiZXhwIjoxNTc2MjQ0OTY0LCJlbWFpbCI6Im5ld3VzZXI5Nzg2NXd4eUBnLmMifQ.ymslBIA7FtENAHVEP7GGUWrHcBqJH3H_A3rntM79pOk/1576244964\'>http://simplerest.lan/login/confirm_email/eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImlhdCI6MTU3NTY0MDE2NCwiZXhwIjoxNTc2MjQ0OTY0LCJlbWFpbCI6Im5ld3VzZXI5Nzg2NXd4eUBnLmMifQ.ymslBIA7FtENAHVEP7GGUWrHcBqJH3H_A3rntM79pOk/1576244964</a>', '2019-12-06 10:49:24', NULL),
(54, 'no_responder@simplerest.mapapulque.ro', 'No responder', 'nnn@g.c', ' ', 'Email confirmation', 'Please confirm your account by following the link bellow:<br/><a href=\'http://simplerest.lan/login/confirm_email/eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImlhdCI6MTU3NTY0MTI4OCwiZXhwIjoxNTc2MjQ2MDg4LCJlbWFpbCI6Im5ubkBnLmMifQ.V4z1uGkCqwjUA3aWc8Xy1tZ9z-EhfKdIRMqSPAJHlkc/1576246088\'>http://simplerest.lan/login/confirm_email/eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImlhdCI6MTU3NTY0MTI4OCwiZXhwIjoxNTc2MjQ2MDg4LCJlbWFpbCI6Im5ubkBnLmMifQ.V4z1uGkCqwjUA3aWc8Xy1tZ9z-EhfKdIRMqSPAJHlkc/1576246088</a>', '2019-12-06 11:08:08', NULL),
(55, 'no_responder@simplerest.mapapulque.ro', 'No responder', 'nn_j23483j401wx@g.c', ' ', 'Email confirmation', 'Please confirm your account by following the link bellow:<br/><a href=\'http://simplerest.lan/login/confirm_email/eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImlhdCI6MTU3NTY0MTY2NiwiZXhwIjoxNTc2MjQ2NDY2LCJlbWFpbCI6Im5uX2oyMzQ4M2o0MDF3eEBnLmMifQ.Vh3A4BbJzbpZ5hrHfpDEMCOQWB1IFTaw6UU2UrbCH5k/1576246466\'>http://simplerest.lan/login/confirm_email/eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImlhdCI6MTU3NTY0MTY2NiwiZXhwIjoxNTc2MjQ2NDY2LCJlbWFpbCI6Im5uX2oyMzQ4M2o0MDF3eEBnLmMifQ.Vh3A4BbJzbpZ5hrHfpDEMCOQWB1IFTaw6UU2UrbCH5k/1576246466</a>', '2019-12-06 11:14:26', NULL),
(56, 'no_responder@simplerest.mapapulque.ro', 'No responder', 'nn_x23483j401wx@g.c', ' ', 'Email confirmation', 'Please confirm your account by following the link bellow:<br/><a href=\'http://simplerest.lan/login/confirm_email/eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImlhdCI6MTU3NTY0MTczMywiZXhwIjoxNTc2MjQ2NTMzLCJlbWFpbCI6Im5uX3gyMzQ4M2o0MDF3eEBnLmMifQ.uQzIOct36eb-4r3-yPG8O71CqjRvcatxvWEd1GS6gVs/1576246533\'>http://simplerest.lan/login/confirm_email/eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImlhdCI6MTU3NTY0MTczMywiZXhwIjoxNTc2MjQ2NTMzLCJlbWFpbCI6Im5uX3gyMzQ4M2o0MDF3eEBnLmMifQ.uQzIOct36eb-4r3-yPG8O71CqjRvcatxvWEd1GS6gVs/1576246533</a>', '2019-12-06 11:15:33', NULL),
(57, 'no_responder@simplerest.mapapulque.ro', 'No responder', 'nn_x23483j401wx@g.c', ' ', 'Email confirmation', 'Please confirm your account by following the link bellow:<br/><a href=\'http://simplerest.lan/login/confirm_email/eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImlhdCI6MTU3NTY0MTczNiwiZXhwIjoxNTc2MjQ2NTM2LCJlbWFpbCI6Im5uX3gyMzQ4M2o0MDF3eEBnLmMifQ.TK_eXrsGd7KMDrWq7kJteP8D3EBL9uTfVCIlgtis3mE/1576246536\'>http://simplerest.lan/login/confirm_email/eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImlhdCI6MTU3NTY0MTczNiwiZXhwIjoxNTc2MjQ2NTM2LCJlbWFpbCI6Im5uX3gyMzQ4M2o0MDF3eEBnLmMifQ.TK_eXrsGd7KMDrWq7kJteP8D3EBL9uTfVCIlgtis3mE/1576246536</a>', '2019-12-06 11:15:36', NULL),
(58, 'no_responder@simplerest.mapapulque.ro', 'No responder', 'nn_x23483j401wx@g.c', ' ', 'Email confirmation', 'Please confirm your account by following the link bellow:<br/><a href=\'http://simplerest.lan/login/confirm_email/eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImlhdCI6MTU3NTY0MTgxNCwiZXhwIjoxNTc2MjQ2NjE0LCJlbWFpbCI6Im5uX3gyMzQ4M2o0MDF3eEBnLmMifQ.P6iiL17B2PtFLXEh98luqGI1wsk-q8VUiBwcoEi9-QA/1576246614\'>http://simplerest.lan/login/confirm_email/eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImlhdCI6MTU3NTY0MTgxNCwiZXhwIjoxNTc2MjQ2NjE0LCJlbWFpbCI6Im5uX3gyMzQ4M2o0MDF3eEBnLmMifQ.P6iiL17B2PtFLXEh98luqGI1wsk-q8VUiBwcoEi9-QA/1576246614</a>', '2019-12-06 11:16:54', NULL),
(59, 'no_responder@simplerest.mapapulque.ro', 'No responder', 'nn_x23483j401wx@g.c', ' ', 'Email confirmation', 'Please confirm your account by following the link bellow:<br/><a href=\'http://simplerest.lan/login/confirm_email/eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImlhdCI6MTU3NTY0MjI4OCwiZXhwIjoxNTc2MjQ3MDg4LCJlbWFpbCI6Im5uX3gyMzQ4M2o0MDF3eEBnLmMifQ.8Z437B6qnB-SaMZ0BnfgZVXz3ysLUmn0aOtP3kMop9g/1576247088\'>http://simplerest.lan/login/confirm_email/eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImlhdCI6MTU3NTY0MjI4OCwiZXhwIjoxNTc2MjQ3MDg4LCJlbWFpbCI6Im5uX3gyMzQ4M2o0MDF3eEBnLmMifQ.8Z437B6qnB-SaMZ0BnfgZVXz3ysLUmn0aOtP3kMop9g/1576247088</a>', '2019-12-06 11:24:48', NULL),
(60, 'no_responder@simplerest.mapapulque.ro', 'No responder', 'nn_x23483j401wx@g.c', ' ', 'Email confirmation', 'Please confirm your account by following the link bellow:<br/><a href=\'http://simplerest.lan/login/confirm_email/eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImlhdCI6MTU3NTY0MjMwMywiZXhwIjoxNTc2MjQ3MTAzLCJlbWFpbCI6Im5uX3gyMzQ4M2o0MDF3eEBnLmMifQ.yHzO2J2povVfVeI-Fd6k3bGpfZVXpv0BQZ5A9k9e7N0/1576247103\'>http://simplerest.lan/login/confirm_email/eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImlhdCI6MTU3NTY0MjMwMywiZXhwIjoxNTc2MjQ3MTAzLCJlbWFpbCI6Im5uX3gyMzQ4M2o0MDF3eEBnLmMifQ.yHzO2J2povVfVeI-Fd6k3bGpfZVXpv0BQZ5A9k9e7N0/1576247103</a>', '2019-12-06 11:25:03', NULL),
(61, 'no_responder@simplerest.mapapulque.ro', 'No responder', 'nn_x23483j401wx@g.c', ' ', 'Email confirmation', 'Please confirm your account by following the link bellow:<br/><a href=\'http://simplerest.lan/login/confirm_email/eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImlhdCI6MTU3NTY0MjMzNSwiZXhwIjoxNTc2MjQ3MTM1LCJlbWFpbCI6Im5uX3gyMzQ4M2o0MDF3eEBnLmMifQ.gO6aC7K-NSwxcCokkQw0Cy5RjlvoongGBHte297OEk0/1576247135\'>http://simplerest.lan/login/confirm_email/eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImlhdCI6MTU3NTY0MjMzNSwiZXhwIjoxNTc2MjQ3MTM1LCJlbWFpbCI6Im5uX3gyMzQ4M2o0MDF3eEBnLmMifQ.gO6aC7K-NSwxcCokkQw0Cy5RjlvoongGBHte297OEk0/1576247135</a>', '2019-12-06 11:25:35', NULL),
(62, 'no_responder@simplerest.mapapulque.ro', 'No responder', 'nn_x23483j401wx@g.c', ' ', 'Email confirmation', 'Please confirm your account by following the link bellow:<br/><a href=\'http://simplerest.lan/login/confirm_email/eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImlhdCI6MTU3NTY0MjM4MywiZXhwIjoxNTc2MjQ3MTgzLCJlbWFpbCI6Im5uX3gyMzQ4M2o0MDF3eEBnLmMifQ.9IWbstDR_pnhT22gaENgrVU7eoPCwUtGpoVxUmmGlB0/1576247183\'>http://simplerest.lan/login/confirm_email/eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImlhdCI6MTU3NTY0MjM4MywiZXhwIjoxNTc2MjQ3MTgzLCJlbWFpbCI6Im5uX3gyMzQ4M2o0MDF3eEBnLmMifQ.9IWbstDR_pnhT22gaENgrVU7eoPCwUtGpoVxUmmGlB0/1576247183</a>', '2019-12-06 11:26:23', NULL),
(63, 'no_responder@simplerest.mapapulque.ro', 'No responder', 'nn_x23483j401wx@g.c', ' ', 'Email confirmation', 'Please confirm your account by following the link bellow:<br/><a href=\'http://simplerest.lan/login/confirm_email/eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImlhdCI6MTU3NTY0MjQwOSwiZXhwIjoxNTc2MjQ3MjA5LCJlbWFpbCI6Im5uX3gyMzQ4M2o0MDF3eEBnLmMifQ.uquhBmhmQdE2el_xbKILjATcSYMeHtXwahZloUm8o8A/1576247209\'>http://simplerest.lan/login/confirm_email/eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImlhdCI6MTU3NTY0MjQwOSwiZXhwIjoxNTc2MjQ3MjA5LCJlbWFpbCI6Im5uX3gyMzQ4M2o0MDF3eEBnLmMifQ.uquhBmhmQdE2el_xbKILjATcSYMeHtXwahZloUm8o8A/1576247209</a>', '2019-12-06 11:26:49', NULL),
(64, 'no_responder@simplerest.mapapulque.ro', 'No responder', 'nn_x23483j401wx@g.c', ' ', 'Email confirmation', 'Please confirm your account by following the link bellow:<br/><a href=\'http://simplerest.lan/login/confirm_email/eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImlhdCI6MTU3NTY0MjY3NiwiZXhwIjoxNTc2MjQ3NDc2LCJlbWFpbCI6Im5uX3gyMzQ4M2o0MDF3eEBnLmMifQ.RCZ9U_NGU8MSK9pnVCFt4T1Hxjf9HptJWeiuC6wCa1w/1576247476\'>http://simplerest.lan/login/confirm_email/eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImlhdCI6MTU3NTY0MjY3NiwiZXhwIjoxNTc2MjQ3NDc2LCJlbWFpbCI6Im5uX3gyMzQ4M2o0MDF3eEBnLmMifQ.RCZ9U_NGU8MSK9pnVCFt4T1Hxjf9HptJWeiuC6wCa1w/1576247476</a>', '2019-12-06 11:31:16', NULL),
(65, 'no_responder@simplerest.mapapulque.ro', 'No responder', 'nn_x23483j401wx@g.c', ' ', 'Email confirmation', 'Please confirm your account by following the link bellow:<br/><a href=\'http://simplerest.lan/login/confirm_email/eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImlhdCI6MTU3NTY0ODM4NSwiZXhwIjoxNTc2MjUzMTg1LCJlbWFpbCI6Im5uX3gyMzQ4M2o0MDF3eEBnLmMifQ.8h99urmivHkkDyAz1mWiqm1iAMNMWeoFeZ0A4j4NOw0/1576253185\'>http://simplerest.lan/login/confirm_email/eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImlhdCI6MTU3NTY0ODM4NSwiZXhwIjoxNTc2MjUzMTg1LCJlbWFpbCI6Im5uX3gyMzQ4M2o0MDF3eEBnLmMifQ.8h99urmivHkkDyAz1mWiqm1iAMNMWeoFeZ0A4j4NOw0/1576253185</a>', '2019-12-06 13:06:25', NULL),
(66, 'no_responder@simplerest.mapapulque.ro', 'No responder', 'nn_x23483j401wx@g.c', ' ', 'Email confirmation', 'Please confirm your account by following the link bellow:<br/><a href=\'http://simplerest.lan/login/confirm_email/eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImlhdCI6MTU3NTY0ODYwNywiZXhwIjoxNTc2MjUzNDA3LCJlbWFpbCI6Im5uX3gyMzQ4M2o0MDF3eEBnLmMifQ.sBXniIniQs3hLQs8rJeSBo6v2rgzR35In_nkYXJy8Vo/1576253407\'>http://simplerest.lan/login/confirm_email/eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImlhdCI6MTU3NTY0ODYwNywiZXhwIjoxNTc2MjUzNDA3LCJlbWFpbCI6Im5uX3gyMzQ4M2o0MDF3eEBnLmMifQ.sBXniIniQs3hLQs8rJeSBo6v2rgzR35In_nkYXJy8Vo/1576253407</a>', '2019-12-06 13:10:07', NULL),
(67, 'no_responder@simplerest.mapapulque.ro', 'No responder', 'nn_x23483j401wx@g.c', ' ', 'Email confirmation', 'Please confirm your account by following the link bellow:<br/><a href=\'http://simplerest.lan/login/confirm_email/eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImlhdCI6MTU3NTY0ODYyMSwiZXhwIjoxNTc2MjUzNDIxLCJlbWFpbCI6Im5uX3gyMzQ4M2o0MDF3eEBnLmMifQ._oVuyPKehYrjZs4vTS0JZ_aqY2R5G5Ebb17MjdJ8Lko/1576253421\'>http://simplerest.lan/login/confirm_email/eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImlhdCI6MTU3NTY0ODYyMSwiZXhwIjoxNTc2MjUzNDIxLCJlbWFpbCI6Im5uX3gyMzQ4M2o0MDF3eEBnLmMifQ._oVuyPKehYrjZs4vTS0JZ_aqY2R5G5Ebb17MjdJ8Lko/1576253421</a>', '2019-12-06 13:10:21', NULL),
(68, 'no_responder@simplerest.mapapulque.ro', 'No responder', 'nn_x23483j401wx@g.c', ' ', 'Email confirmation', 'Please confirm your account by following the link bellow:<br/><a href=\'http://simplerest.lan/login/confirm_email/eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImlhdCI6MTU3NTY0ODY1NSwiZXhwIjoxNTc2MjUzNDU1LCJlbWFpbCI6Im5uX3gyMzQ4M2o0MDF3eEBnLmMifQ.JPruRw4qb7L2G4nzhf2lRynrUpRu6OuPh6h63Z46CAg/1576253455\'>http://simplerest.lan/login/confirm_email/eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImlhdCI6MTU3NTY0ODY1NSwiZXhwIjoxNTc2MjUzNDU1LCJlbWFpbCI6Im5uX3gyMzQ4M2o0MDF3eEBnLmMifQ.JPruRw4qb7L2G4nzhf2lRynrUpRu6OuPh6h63Z46CAg/1576253455</a>', '2019-12-06 13:10:55', NULL),
(69, 'no_responder@simplerest.mapapulque.ro', 'No responder', 'nn_x23483j401wx@g.c', ' ', 'Email confirmation', 'Please confirm your account by following the link bellow:<br/><a href=\'http://simplerest.lan/login/confirm_email/eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImlhdCI6MTU3NTY3OTAxMywiZXhwIjoxNTc2MjgzODEzLCJlbWFpbCI6Im5uX3gyMzQ4M2o0MDF3eEBnLmMifQ.vYq5AulsLZywL7haz5lrou_brIlMlNl-_Dj38wN8v18/1576283813\'>http://simplerest.lan/login/confirm_email/eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImlhdCI6MTU3NTY3OTAxMywiZXhwIjoxNTc2MjgzODEzLCJlbWFpbCI6Im5uX3gyMzQ4M2o0MDF3eEBnLmMifQ.vYq5AulsLZywL7haz5lrou_brIlMlNl-_Dj38wN8v18/1576283813</a>', '2019-12-06 21:36:53', NULL),
(70, 'no_responder@simplerest.mapapulque.ro', 'No responder', 'nn_x23483j401wx@g.c', ' ', 'Email confirmation', 'Please confirm your account by following the link bellow:<br/><a href=\'http://simplerest.lan/login/confirm_email/eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImlhdCI6MTU3NTY3OTA1MSwiZXhwIjoxNTc2MjgzODUxLCJlbWFpbCI6Im5uX3gyMzQ4M2o0MDF3eEBnLmMifQ.RKiARdF7arJLHRNlOG-ivYwkVwRo59ZPiqETItH3Vgk/1576283851\'>http://simplerest.lan/login/confirm_email/eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImlhdCI6MTU3NTY3OTA1MSwiZXhwIjoxNTc2MjgzODUxLCJlbWFpbCI6Im5uX3gyMzQ4M2o0MDF3eEBnLmMifQ.RKiARdF7arJLHRNlOG-ivYwkVwRo59ZPiqETItH3Vgk/1576283851</a>', '2019-12-06 21:37:31', NULL),
(71, 'no_responder@simplerest.mapapulque.ro', 'No responder', 'nn_x23483j401wx@g.c', ' ', 'Email confirmation', 'Please confirm your account by following the link bellow:<br/><a href=\'http://simplerest.lan/login/confirm_email/eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImlhdCI6MTU3NTY3OTA4MCwiZXhwIjoxNTc2MjgzODgwLCJlbWFpbCI6Im5uX3gyMzQ4M2o0MDF3eEBnLmMifQ.yeDO0bxGCITfd9Bsshl9RcdibR87xjBfv0NuO0Uvm60/1576283880\'>http://simplerest.lan/login/confirm_email/eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImlhdCI6MTU3NTY3OTA4MCwiZXhwIjoxNTc2MjgzODgwLCJlbWFpbCI6Im5uX3gyMzQ4M2o0MDF3eEBnLmMifQ.yeDO0bxGCITfd9Bsshl9RcdibR87xjBfv0NuO0Uvm60/1576283880</a>', '2019-12-06 21:38:00', NULL),
(72, 'no_responder@simplerest.mapapulque.ro', 'No responder', 'nn_x23483j401wx@g.c', ' ', 'Email confirmation', 'Please confirm your account by following the link bellow:<br/><a href=\'http://simplerest.lan/login/confirm_email/eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImlhdCI6MTU3NTY3OTE0NSwiZXhwIjoxNTc2MjgzOTQ1LCJlbWFpbCI6Im5uX3gyMzQ4M2o0MDF3eEBnLmMifQ.zUY51N9g0bYda2iLlj5lewioCB99tsQyHMvQ7x25aws/1576283945\'>http://simplerest.lan/login/confirm_email/eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImlhdCI6MTU3NTY3OTE0NSwiZXhwIjoxNTc2MjgzOTQ1LCJlbWFpbCI6Im5uX3gyMzQ4M2o0MDF3eEBnLmMifQ.zUY51N9g0bYda2iLlj5lewioCB99tsQyHMvQ7x25aws/1576283945</a>', '2019-12-06 21:39:05', NULL),
(73, 'no_responder@simplerest.mapapulque.ro', 'No responder', 'nn_x23483j401wx@g.c', ' ', 'Email confirmation', 'Please confirm your account by following the link bellow:<br/><a href=\'http://simplerest.lan/login/confirm_email/eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImlhdCI6MTU3NTY3OTE2NiwiZXhwIjoxNTc2MjgzOTY2LCJlbWFpbCI6Im5uX3gyMzQ4M2o0MDF3eEBnLmMifQ.5ib1B4hgTpV6dP5aWbYq1ipnR6xryVJ2Bsa31TIXtH0/1576283966\'>http://simplerest.lan/login/confirm_email/eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImlhdCI6MTU3NTY3OTE2NiwiZXhwIjoxNTc2MjgzOTY2LCJlbWFpbCI6Im5uX3gyMzQ4M2o0MDF3eEBnLmMifQ.5ib1B4hgTpV6dP5aWbYq1ipnR6xryVJ2Bsa31TIXtH0/1576283966</a>', '2019-12-06 21:39:26', NULL),
(74, 'no_responder@simplerest.mapapulque.ro', 'No responder', 'nn_x23483j401wx@g.c', ' ', 'Email confirmation', 'Please confirm your account by following the link bellow:<br/><a href=\'http://simplerest.lan/login/confirm_email/eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImlhdCI6MTU3NTY3OTI3NCwiZXhwIjoxNTc2Mjg0MDc0LCJlbWFpbCI6Im5uX3gyMzQ4M2o0MDF3eEBnLmMifQ.Y8iokpmNKdGBZ6pSZfWMjh94myQBAl_IY-5DP5sEZRI/1576284074\'>http://simplerest.lan/login/confirm_email/eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImlhdCI6MTU3NTY3OTI3NCwiZXhwIjoxNTc2Mjg0MDc0LCJlbWFpbCI6Im5uX3gyMzQ4M2o0MDF3eEBnLmMifQ.Y8iokpmNKdGBZ6pSZfWMjh94myQBAl_IY-5DP5sEZRI/1576284074</a>', '2019-12-06 21:41:14', NULL),
(75, 'no_responder@simplerest.mapapulque.ro', 'No responder', 'nn_x23483j401wx@g.c', ' ', 'Email confirmation', 'Please confirm your account by following the link bellow:<br/><a href=\'http://simplerest.lan/login/confirm_email/eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImlhdCI6MTU3NTY3OTMwNywiZXhwIjoxNTc2Mjg0MTA3LCJlbWFpbCI6Im5uX3gyMzQ4M2o0MDF3eEBnLmMifQ.m5gBYnPoPjOWLkoLZgLI2kofgD9_y1zVdVc9Dxf2I0Y/1576284107\'>http://simplerest.lan/login/confirm_email/eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImlhdCI6MTU3NTY3OTMwNywiZXhwIjoxNTc2Mjg0MTA3LCJlbWFpbCI6Im5uX3gyMzQ4M2o0MDF3eEBnLmMifQ.m5gBYnPoPjOWLkoLZgLI2kofgD9_y1zVdVc9Dxf2I0Y/1576284107</a>', '2019-12-06 21:41:47', NULL),
(76, 'no_responder@simplerest.mapapulque.ro', 'No responder', 'nn_x23483j401wx@g.c', ' ', 'Email confirmation', 'Please confirm your account by following the link bellow:<br/><a href=\'http://simplerest.lan/login/confirm_email/eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImlhdCI6MTU3NTY3OTQ0NiwiZXhwIjoxNTc2Mjg0MjQ2LCJlbWFpbCI6Im5uX3gyMzQ4M2o0MDF3eEBnLmMifQ.WnkSa5Dko39PLjaFbv26XSEnd3dNUBLGNArA7jEKTEE/1576284246\'>http://simplerest.lan/login/confirm_email/eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImlhdCI6MTU3NTY3OTQ0NiwiZXhwIjoxNTc2Mjg0MjQ2LCJlbWFpbCI6Im5uX3gyMzQ4M2o0MDF3eEBnLmMifQ.WnkSa5Dko39PLjaFbv26XSEnd3dNUBLGNArA7jEKTEE/1576284246</a>', '2019-12-06 21:44:06', NULL),
(77, 'no_responder@simplerest.mapapulque.ro', 'No responder', 'nn_x23483j401wx@g.c', ' ', 'Email confirmation', 'Please confirm your account by following the link bellow:<br/><a href=\'http://simplerest.lan/login/confirm_email/eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImlhdCI6MTU3NTY3OTQ5MCwiZXhwIjoxNTc2Mjg0MjkwLCJlbWFpbCI6Im5uX3gyMzQ4M2o0MDF3eEBnLmMifQ.-XmKfvtOqGYu_T3EJuYTEhY0YA9PGofYCz6Q8CPyZXQ/1576284290\'>http://simplerest.lan/login/confirm_email/eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImlhdCI6MTU3NTY3OTQ5MCwiZXhwIjoxNTc2Mjg0MjkwLCJlbWFpbCI6Im5uX3gyMzQ4M2o0MDF3eEBnLmMifQ.-XmKfvtOqGYu_T3EJuYTEhY0YA9PGofYCz6Q8CPyZXQ/1576284290</a>', '2019-12-06 21:44:50', NULL),
(78, 'no_responder@simplerest.mapapulque.ro', 'No responder', 'nn_x23483j401wx@g.c', ' ', 'Email confirmation', 'Please confirm your account by following the link bellow:<br/><a href=\'http://simplerest.lan/login/confirm_email/eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImlhdCI6MTU3NTY3OTUwMiwiZXhwIjoxNTc2Mjg0MzAyLCJlbWFpbCI6Im5uX3gyMzQ4M2o0MDF3eEBnLmMifQ.pqjele0ESK3S-fiqD65eNqN4KglaevKqo5LVL5bk5lk/1576284302\'>http://simplerest.lan/login/confirm_email/eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImlhdCI6MTU3NTY3OTUwMiwiZXhwIjoxNTc2Mjg0MzAyLCJlbWFpbCI6Im5uX3gyMzQ4M2o0MDF3eEBnLmMifQ.pqjele0ESK3S-fiqD65eNqN4KglaevKqo5LVL5bk5lk/1576284302</a>', '2019-12-06 21:45:02', NULL),
(79, 'no_responder@simplerest.mapapulque.ro', 'No responder', 'nn_x23483j401wx@g.c', ' ', 'Email confirmation', 'Please confirm your account by following the link bellow:<br/><a href=\'http://simplerest.lan/login/confirm_email/eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImlhdCI6MTU3NTY3OTYwNCwiZXhwIjoxNTc2Mjg0NDA0LCJlbWFpbCI6Im5uX3gyMzQ4M2o0MDF3eEBnLmMifQ.4eKhvUEfJB5xk0N65L-doIL8afCU6ykLCgsk2433S6M/1576284404\'>http://simplerest.lan/login/confirm_email/eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImlhdCI6MTU3NTY3OTYwNCwiZXhwIjoxNTc2Mjg0NDA0LCJlbWFpbCI6Im5uX3gyMzQ4M2o0MDF3eEBnLmMifQ.4eKhvUEfJB5xk0N65L-doIL8afCU6ykLCgsk2433S6M/1576284404</a>', '2019-12-06 21:46:44', NULL),
(80, 'no_responder@simplerest.mapapulque.ro', 'No responder', 'nn_x23483j401wx@g.c', ' ', 'Email confirmation', 'Please confirm your account by following the link bellow:<br/><a href=\'http://simplerest.lan/login/confirm_email/eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImlhdCI6MTU3NTY3OTY1OSwiZXhwIjoxNTc2Mjg0NDU5LCJlbWFpbCI6Im5uX3gyMzQ4M2o0MDF3eEBnLmMifQ.YQUQRR1rGxG7m2_mrnxa1gmZ9YCwEFRdKbj18G0iQ3A/1576284459\'>http://simplerest.lan/login/confirm_email/eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImlhdCI6MTU3NTY3OTY1OSwiZXhwIjoxNTc2Mjg0NDU5LCJlbWFpbCI6Im5uX3gyMzQ4M2o0MDF3eEBnLmMifQ.YQUQRR1rGxG7m2_mrnxa1gmZ9YCwEFRdKbj18G0iQ3A/1576284459</a>', '2019-12-06 21:47:39', NULL),
(81, 'no_responder@simplerest.mapapulque.ro', 'No responder', 'nn_x23483j401wx@g.c', ' ', 'Email confirmation', 'Please confirm your account by following the link bellow:<br/><a href=\'http://simplerest.lan/login/confirm_email/eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImlhdCI6MTU3NTY4NDg0MywiZXhwIjoxNTc2Mjg5NjQzLCJlbWFpbCI6Im5uX3gyMzQ4M2o0MDF3eEBnLmMifQ.PbP7SEKaRt9y1TrlQ2MWJ269VJVkUMtqAVqHDR1zi7A/1576289643\'>http://simplerest.lan/login/confirm_email/eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImlhdCI6MTU3NTY4NDg0MywiZXhwIjoxNTc2Mjg5NjQzLCJlbWFpbCI6Im5uX3gyMzQ4M2o0MDF3eEBnLmMifQ.PbP7SEKaRt9y1TrlQ2MWJ269VJVkUMtqAVqHDR1zi7A/1576289643</a>', '2019-12-06 23:14:03', NULL),
(82, 'no_responder@simplerest.mapapulque.ro', 'No responder', 'nn_x23483j401wx@g.c', ' ', 'Email confirmation', 'Please confirm your account by following the link bellow:<br/><a href=\'http://simplerest.lan/login/confirm_email/eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImlhdCI6MTU3NjI1NDMwNCwiZXhwIjoxNTc2ODU5MTA0LCJlbWFpbCI6Im5uX3gyMzQ4M2o0MDF3eEBnLmMifQ.VqdQ6wUj84I86-cyKcUdFaFoNOcKXPU8R8Kgczgt1dM/1576859104\'>http://simplerest.lan/login/confirm_email/eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImlhdCI6MTU3NjI1NDMwNCwiZXhwIjoxNTc2ODU5MTA0LCJlbWFpbCI6Im5uX3gyMzQ4M2o0MDF3eEBnLmMifQ.VqdQ6wUj84I86-cyKcUdFaFoNOcKXPU8R8Kgczgt1dM/1576859104</a>', '2019-12-13 13:25:04', NULL),
(83, 'no_responder@simplerest.mapapulque.ro', 'No responder', 'nn_x23483j401wx@g.c', ' ', 'Email confirmation', 'Please confirm your account by following the link bellow:<br/><a href=\'http://simplerest.lan/login/confirm_email/eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImlhdCI6MTU3NjM4NDUxNCwiZXhwIjoxNTc2OTg5MzE0LCJlbWFpbCI6Im5uX3gyMzQ4M2o0MDF3eEBnLmMifQ.yUiq_qyKkjwpfSjMlMSxY7S0l6GQ0zd-jeUnIYrI6SM/1576989314\'>http://simplerest.lan/login/confirm_email/eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImlhdCI6MTU3NjM4NDUxNCwiZXhwIjoxNTc2OTg5MzE0LCJlbWFpbCI6Im5uX3gyMzQ4M2o0MDF3eEBnLmMifQ.yUiq_qyKkjwpfSjMlMSxY7S0l6GQ0zd-jeUnIYrI6SM/1576989314</a>', '2019-12-15 01:35:14', NULL),
(84, 'no_responder@simplerest.mapapulque.ro', 'No responder', 'asdffffffffffff@g.c', ' ', 'Email confirmation', 'Please confirm your account by following the link bellow:<br/><a href=\'http://simplerest.lan/login/confirm_email/eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImlhdCI6MTU3NjYwMjAzOSwiZXhwIjoxNTc3MjA2ODM5LCJlbWFpbCI6ImFzZGZmZmZmZmZmZmZmZkBnLmMifQ._0kMrkb4hIM4gAolIWK7NYBzCusOOeGvN67yMkAA730/1577206839\'>http://simplerest.lan/login/confirm_email/eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImlhdCI6MTU3NjYwMjAzOSwiZXhwIjoxNTc3MjA2ODM5LCJlbWFpbCI6ImFzZGZmZmZmZmZmZmZmZkBnLmMifQ._0kMrkb4hIM4gAolIWK7NYBzCusOOeGvN67yMkAA730/1577206839</a>', '2019-12-17 14:00:39', NULL),
(85, 'no_responder@simplerest.mapapulque.ro', 'No responder', 'asdfggg@g.c', ' ', 'Email confirmation', 'Please confirm your account by following the link bellow:<br/><a href=\'http://simplerest.lan/login/confirm_email/eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImlhdCI6MTU3NjYwMjA2NiwiZXhwIjoxNTc3MjA2ODY2LCJlbWFpbCI6ImFzZGZnZ2dAZy5jIn0.pfFfw2mJ7bay255uXacThhRPo60lhO7kFkjQ2jBdccM/1577206866\'>http://simplerest.lan/login/confirm_email/eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImlhdCI6MTU3NjYwMjA2NiwiZXhwIjoxNTc3MjA2ODY2LCJlbWFpbCI6ImFzZGZnZ2dAZy5jIn0.pfFfw2mJ7bay255uXacThhRPo60lhO7kFkjQ2jBdccM/1577206866</a>', '2019-12-17 14:01:06', NULL),
(86, 'no_responder@simplerest.mapapulque.ro', 'No responder', 'asdfggtttg@g.c', ' ', 'Email confirmation', 'Please confirm your account by following the link bellow:<br/><a href=\'http://simplerest.lan/login/confirm_email/eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImlhdCI6MTU3NjYwMjEyMSwiZXhwIjoxNTc3MjA2OTIxLCJlbWFpbCI6ImFzZGZnZ3R0dGdAZy5jIn0.00wJ-bDyC4wHod5QtvtuXaq7NxfiSLfwboOjq3ZYEQY/1577206921\'>http://simplerest.lan/login/confirm_email/eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImlhdCI6MTU3NjYwMjEyMSwiZXhwIjoxNTc3MjA2OTIxLCJlbWFpbCI6ImFzZGZnZ3R0dGdAZy5jIn0.00wJ-bDyC4wHod5QtvtuXaq7NxfiSLfwboOjq3ZYEQY/1577206921</a>', '2019-12-17 14:02:01', NULL),
(87, 'no_responder@simplerest.mapapulque.ro', 'No responder', 'asdfggtdddddtdddtg@g.c', ' ', 'Email confirmation', 'Please confirm your account by following the link bellow:<br/><a href=\'http://simplerest.lan/login/confirm_email/eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImlhdCI6MTU3NjYwMjE4NCwiZXhwIjoxNTc3MjA2OTg0LCJlbWFpbCI6ImFzZGZnZ3RkZGRkZHRkZGR0Z0BnLmMifQ.c-C4Myxg6otrAbkrCHs0qwY3BD7v5CSvXT9-lR0k8Ko/1577206984\'>http://simplerest.lan/login/confirm_email/eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImlhdCI6MTU3NjYwMjE4NCwiZXhwIjoxNTc3MjA2OTg0LCJlbWFpbCI6ImFzZGZnZ3RkZGRkZHRkZGR0Z0BnLmMifQ.c-C4Myxg6otrAbkrCHs0qwY3BD7v5CSvXT9-lR0k8Ko/1577206984</a>', '2019-12-17 14:03:04', NULL),
(88, 'no_responder@simplerest.mapapulque.ro', 'No responder', 'asdfggtdtdddtg@g.c', ' ', 'Email confirmation', 'Please confirm your account by following the link bellow:<br/><a href=\'http://simplerest.lan/login/confirm_email/eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImlhdCI6MTU3NjYwMjI2NiwiZXhwIjoxNTc3MjA3MDY2LCJlbWFpbCI6ImFzZGZnZ3RkdGRkZHRnQGcuYyJ9.YQ43sVqd69EDCzylTwuvpNmDheh2roPFwxDQKATyEv4/1577207066\'>http://simplerest.lan/login/confirm_email/eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImlhdCI6MTU3NjYwMjI2NiwiZXhwIjoxNTc3MjA3MDY2LCJlbWFpbCI6ImFzZGZnZ3RkdGRkZHRnQGcuYyJ9.YQ43sVqd69EDCzylTwuvpNmDheh2roPFwxDQKATyEv4/1577207066</a>', '2019-12-17 14:04:26', NULL),
(89, 'no_responder@simplerest.mapapulque.ro', 'No responder', 'asdfgg5tdftdddtg@g.c', ' ', 'Email confirmation', 'Please confirm your account by following the link bellow:<br/><a href=\'http://simplerest.lan/login/confirm_email/eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImlhdCI6MTU3NjYwMjM0NywiZXhwIjoxNTc3MjA3MTQ3LCJlbWFpbCI6ImFzZGZnZzV0ZGZ0ZGRkdGdAZy5jIn0.Zbogy742FVgJ7l-f9G16rbITMvfVdH7Pat61xcJ3HCQ/1577207147\'>http://simplerest.lan/login/confirm_email/eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImlhdCI6MTU3NjYwMjM0NywiZXhwIjoxNTc3MjA3MTQ3LCJlbWFpbCI6ImFzZGZnZzV0ZGZ0ZGRkdGdAZy5jIn0.Zbogy742FVgJ7l-f9G16rbITMvfVdH7Pat61xcJ3HCQ/1577207147</a>', '2019-12-17 14:05:47', NULL),
(90, 'no_responder@simplerest.mapapulque.ro', 'No responder', 'asdfgg5tudftdddtg@g.c', ' ', 'Email confirmation', 'Please confirm your account by following the link bellow:<br/><a href=\'http://simplerest.lan/login/confirm_email/eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImlhdCI6MTU3NjYwMjY1MywiZXhwIjoxNTc3MjA3NDUzLCJlbWFpbCI6ImFzZGZnZzV0dWRmdGRkZHRnQGcuYyJ9.cNQegPZIjopHqjZiPNfcydKE557vGDiX3QDjDQFmqs4/1577207453\'>http://simplerest.lan/login/confirm_email/eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImlhdCI6MTU3NjYwMjY1MywiZXhwIjoxNTc3MjA3NDUzLCJlbWFpbCI6ImFzZGZnZzV0dWRmdGRkZHRnQGcuYyJ9.cNQegPZIjopHqjZiPNfcydKE557vGDiX3QDjDQFmqs4/1577207453</a>', '2019-12-17 14:10:53', NULL),
(91, 'no_responder@simplerest.mapapulque.ro', 'No responder', 'asdfgg5dftdddtg@g.c', ' ', 'Email confirmation', 'Please confirm your account by following the link bellow:<br/><a href=\'http://simplerest.lan/login/confirm_email/eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImlhdCI6MTU3NjYwMjgwMCwiZXhwIjoxNTc3MjA3NjAwLCJlbWFpbCI6ImFzZGZnZzVkZnRkZGR0Z0BnLmMifQ.bATqy_svCrgh86AQMpDB3GltDH4ZMCSn1-jLGJNp_s4/1577207600\'>http://simplerest.lan/login/confirm_email/eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImlhdCI6MTU3NjYwMjgwMCwiZXhwIjoxNTc3MjA3NjAwLCJlbWFpbCI6ImFzZGZnZzVkZnRkZGR0Z0BnLmMifQ.bATqy_svCrgh86AQMpDB3GltDH4ZMCSn1-jLGJNp_s4/1577207600</a>', '2019-12-17 14:13:20', NULL),
(92, 'no_responder@simplerest.mapapulque.ro', 'No responder', 'boctulus@gmail.com', '', 'Cambio de contraseña', 'Para cambiar la contraseña siga el enlace:<br/><a href=\'http://simplerest.lan/login/change_pass/eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImlhdCI6MTU3NjYxOTI0OSwiZXhwIjoxNTc3MjI0MDQ5LCJlbWFpbCI6ImJvY3R1bHVzQGdtYWlsLmNvbSJ9.mf01KPHnHMBRGcdrOMIzH8_DUhXexUxOk_dP1w1762c/1577224049\'>http://simplerest.lan/login/change_pass/eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImlhdCI6MTU3NjYxOTI0OSwiZXhwIjoxNTc3MjI0MDQ5LCJlbWFpbCI6ImJvY3R1bHVzQGdtYWlsLmNvbSJ9.mf01KPHnHMBRGcdrOMIzH8_DUhXexUxOk_dP1w1762c/1577224049</a>', '2019-12-17 18:47:29', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `other_permissions`
--

CREATE TABLE `other_permissions` (
  `id` int(11) NOT NULL,
  `folder_id` int(11) NOT NULL,
  `belongs_to` int(11) NOT NULL,
  `guest` tinyint(4) NOT NULL DEFAULT 0,
  `r` tinyint(4) NOT NULL,
  `w` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `other_permissions`
--

INSERT INTO `other_permissions` (`id`, `folder_id`, `belongs_to`, `guest`, `r`, `w`) VALUES
(1, 4, 90, 0, 0, 0),
(2, 5, 87, 0, 1, 1),
(4, 6, 90, 1, 1, 0),
(5, 9, 4, 1, 1, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` varchar(240) DEFAULT NULL,
  `size` varchar(30) NOT NULL,
  `cost` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `modified_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `active` tinyint(11) DEFAULT 1,
  `locked` tinyint(4) DEFAULT 0,
  `workspace` varchar(40) DEFAULT NULL,
  `belongs_to` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `size`, `cost`, `created_at`, `modified_at`, `deleted_at`, `active`, `locked`, `workspace`, `belongs_to`) VALUES
(100, 'Vodka', NULL, '2 1/4 L', 200, '2019-07-04 00:00:00', '2019-11-24 22:46:44', '2019-11-25 02:46:44', 1, 1, '', 90),
(103, 'Juguito ric0', 'Delicious juic333333', '1 Litros', 150, '2019-09-13 00:00:00', '2019-11-24 22:46:46', '2019-11-25 02:46:46', 1, 1, NULL, 90),
(105, 'Agua mineral', 'De Córdoba', '1L', 525, '2019-03-15 00:00:00', '2019-11-24 22:46:48', '2019-11-25 02:46:48', 1, 1, 'lista publica', 90),
(106, 'Vodka', 'Rusiaaaaaa', '1L', 400, '2019-02-16 00:00:00', '2019-11-24 22:46:50', '2019-11-25 02:46:50', 1, 1, NULL, 4),
(113, 'Vodka', 'URU', '1L', 550, '2019-03-31 00:00:00', '2019-11-24 22:46:52', '2019-11-25 02:46:52', 1, 1, NULL, 86),
(114, 'AAABBBCCCcccD', 'cccccC', '29', 200, '2019-01-23 00:00:00', '2019-11-24 22:46:54', '2019-11-25 02:46:54', 1, 1, NULL, 4),
(119, 'CocaCola', 'gaseosa', '1L', 44, '2018-10-15 00:00:00', '2019-11-24 22:46:56', '2019-11-25 02:46:56', 1, 1, 'lista2', 90),
(120, 'MiBebida', 'Rica Rica', '1L', 100, '2018-12-23 00:00:00', '2019-11-23 13:59:32', '2019-11-23 17:59:32', 1, 0, NULL, 90),
(121, 'OtraBebida', 'gaseosa', '1L', 25, '2019-09-28 00:00:00', '2019-11-24 22:46:58', '2019-11-25 02:46:58', 1, 1, 'lista2', 90),
(122, 'Cerveza de malta', 'Pichu', '1L', 100, '2018-12-29 00:00:00', '2019-11-24 22:47:00', '2019-11-25 02:47:00', 1, 1, NULL, 90),
(123, 'PesiLoca', 'x_x', '2L', 30, '2018-12-16 00:00:00', '2019-11-17 07:48:25', '2019-11-17 07:48:25', 1, 0, 'mylist', 90),
(125, 'Vodka', '', '3L', 350, '2017-01-10 00:00:00', '2019-12-13 08:54:23', '2019-12-13 12:54:23', 1, 0, 'lista publica', 90),
(126, 'Uvas fermentadas', 'Espectacular', '5L', 300, '2019-06-24 00:00:00', '2019-12-15 13:17:08', NULL, 1, 0, 'lista publica', 90),
(127, 'Vodka venezolanoooooooooooo', 'del caribe', '2L', 100, '2019-07-12 00:00:00', '2019-12-03 17:22:10', NULL, 1, 1, NULL, 90),
(131, 'Vodka', 'de Estados Unidos', '1L', 550, '2019-06-04 00:00:00', NULL, NULL, 1, 0, 'secreto', 4),
(132, 'Ron venezolano', 'Ricooo', '1L', 100, '2019-10-03 00:00:00', '2019-11-27 16:56:52', NULL, 1, 0, NULL, 90),
(133, 'Vodka venezolano', 'de Vzla', '1.5L', 100, '2019-09-19 00:00:00', '2019-11-12 12:48:31', NULL, 1, 0, NULL, 90),
(137, 'Agua ardiente', 'Si que arde!', '1L', 120, '2019-07-16 00:00:00', '2019-11-03 20:46:12', NULL, 1, 0, 'lista', 90),
(143, 'Agua ', '--', '1L', 100, '2019-06-03 00:00:00', '2019-11-27 16:51:42', '2019-11-27 20:51:42', 1, 0, NULL, 90),
(145, 'Juguito XII', 'de manzanas exprimidas', '1L', 350, '2019-02-09 00:00:00', NULL, NULL, 1, 0, 'lista24', 90),
(146, 'Wisky', '', '2L', 230, '2019-08-31 00:00:00', '2019-11-27 16:57:21', NULL, 1, 0, 'lista24', 90),
(147, 'Aqua fresh', 'Rico', '1L', 105, '2019-03-20 00:00:00', '2019-11-30 19:21:07', '2019-11-30 23:21:07', 1, 0, 'comparto', 90),
(148, 'Alcohol etílico', '', '1L', 100, '2019-04-21 00:00:00', '2019-11-03 21:37:48', NULL, 1, 0, 'comparto', 90),
(151, 'Juguito XIII', 'Rico', '1L', 355, '2019-10-03 00:00:00', '2019-10-15 17:00:58', NULL, 1, 0, 'lista24', 90),
(155, 'Super-jugo', 'BBB', '12', 100, '2019-09-22 00:00:00', '2019-11-04 17:00:18', NULL, 1, 0, NULL, 90),
(159, 'Agua minerale', 'x_x', '2L', 90, '2019-10-14 18:08:45', '2019-11-11 13:15:58', NULL, 1, 0, NULL, 90),
(160, 'Limonada', 'Rica', '500ML', 100, '2019-10-23 14:05:30', '2019-11-04 13:19:08', '2019-12-12 00:00:00', 1, 0, NULL, 90),
(162, 'Juguito de Mabelita', 'de manzanas exprimidas', '2L', 250, '2019-10-25 08:36:26', '2019-11-12 12:49:52', NULL, 1, 0, NULL, 113),
(163, 'ABC', 'XYZ', '6L', 600, '2019-10-26 10:05:00', '2019-11-07 00:29:25', NULL, 1, 1, NULL, 1),
(164, 'Vodka', 'de Holanda', '33L', 333, '2019-10-26 19:48:26', '2019-10-29 18:33:57', NULL, 1, 0, NULL, 112),
(165, 'Vodka', 'de Suecia', '0.5L', 100, '2019-10-26 22:38:39', '2019-11-04 13:04:26', NULL, 1, 0, NULL, 90),
(166, 'UUU', 'uuu uuu uu u', '0.5L', 100, '2019-10-26 22:38:39', '2019-11-04 12:57:49', NULL, 1, 1, NULL, 90),
(167, 'Vodka', 'de Francia', '10L', 100, '2019-11-02 08:14:46', '2019-11-03 23:16:17', NULL, 1, 1, NULL, 90),
(169, 'Clavos de techo', 'largos', '12 cm', 25, '2019-11-02 16:06:31', '2019-11-03 20:46:12', NULL, 1, 0, NULL, 125),
(170, 'Escalera', 'para electricista', '2 metros', 200, '2019-11-02 16:07:10', NULL, NULL, 1, 0, NULL, 125),
(171, 'Ruedas', 'plastico', '20 cm', 50, '2019-11-02 16:07:51', '2019-12-07 00:39:42', NULL, 1, 0, NULL, 125),
(172, 'Clavos para madera', 'bronce', '2.5 cm', 10, '2019-11-02 16:08:35', '2019-11-03 20:46:12', NULL, 1, 0, NULL, 125),
(173, 'Escalera pintor', 'metal', '5 metros', 80, '2019-11-02 20:41:55', '2019-12-07 00:38:05', NULL, 1, 0, NULL, 125),
(174, 'Caja de herramientas', 'metal', 'M', 90, '2019-11-02 20:42:52', '2019-12-07 00:38:47', NULL, 1, 0, NULL, 125),
(175, 'Caja de herramientas', 'plastico', 'M', 30, '2019-11-02 20:43:14', '2019-12-07 00:39:18', NULL, 1, 0, NULL, 125),
(176, 'Alambre', 'Precio por kilo', '1 mm', 400, '2019-11-02 20:44:28', NULL, NULL, 1, 0, NULL, 125),
(177, 'Cable de 2 hilos telefónico', 'Por metro', '', 10, '2019-11-02 20:45:10', '2019-12-07 10:32:49', NULL, 1, 0, 'electricos', 125),
(178, 'Agua destilada', '', '1L', 0, '2019-11-02 20:46:05', '2019-12-07 10:32:07', NULL, 1, 0, NULL, 125),
(179, 'Agua mineral', '', '1L', 10, '2019-11-02 20:46:20', '2019-11-03 20:46:12', NULL, 1, 0, NULL, 125),
(180, 'Pintura blanca exteriores', '', '5L', 200, '2019-11-02 21:19:06', NULL, NULL, 1, 0, NULL, 125),
(181, 'Pintura blanca exteriores', '', '2L', 100, '2019-11-02 21:19:22', '2019-11-03 20:46:12', NULL, 1, 0, NULL, 125),
(182, 'Pintura blanca interiores', NULL, '2L', 80, '2019-11-02 21:20:00', '2019-11-03 20:46:12', NULL, 1, 0, NULL, 125),
(183, 'Tuercas', '', '', 0, '2019-11-03 21:33:20', NULL, NULL, 1, 0, NULL, 125),
(185, 'ABC', '', '', 0, '2019-11-03 23:55:18', NULL, NULL, 1, 0, NULL, 132),
(186, 'Toma-corrientes hembra pared', 'color: blanco', '', 20, '2019-11-04 09:26:55', NULL, NULL, 1, 0, 'electricos', 125),
(187, 'Crush', 'x_x', '1L', 20, '2019-11-04 13:06:04', '2019-11-11 13:15:58', NULL, 1, 1, NULL, 90),
(189, 'AAAAAAAAAAAAAaaaaa', '', '', 0, '2019-11-04 17:04:51', '2019-11-04 17:05:00', NULL, 1, 0, NULL, 87),
(191, 'Wisky', '', '1L', 100, '2019-11-05 21:36:40', '2019-11-10 11:54:03', NULL, 1, 0, NULL, 113),
(192, 'Jugo Naranjin', 'Delicious juicEEEE', '1 L', 350, '2019-11-06 23:45:29', NULL, NULL, 1, 0, NULL, 4),
(193, 'Re-Jugo', 'Delicious juicEEEEXXX', '1 L', 350, '2019-11-07 00:18:25', NULL, NULL, 1, 0, NULL, 4),
(194, 'Re-Jugo', 'Delicious juicEEEEXXXYZ', '1 L', 350, '2019-11-07 00:20:53', NULL, NULL, 1, 0, NULL, 135),
(195, 'Boo', 'Delicious juicEEEEXXXYZ4444444444444444444444444', '1 L', 350, '2019-11-07 01:31:43', NULL, NULL, 1, 0, NULL, 4),
(196, 'NaranjAAAAAAA', 'OK', '', 0, '2019-11-07 22:58:51', '2019-11-24 17:49:34', NULL, 1, 0, NULL, 137),
(197, 'HEYYYYYY', '', '', 0, '2019-11-10 00:57:51', NULL, NULL, 1, 0, NULL, 150),
(198, 'AAAAA', 'x_x', '', 22, '2019-11-11 10:38:19', '2019-11-11 13:15:58', NULL, 1, 0, NULL, 90),
(199, 'cuzbgmbhiudjqvrmzwqf', 'x_x', '1L', 66, '2019-11-11 10:42:57', '2019-11-11 13:15:58', NULL, 1, 0, 'lista publica', 90),
(200, 'AAA', '', '', 0, '2019-11-11 11:44:51', NULL, NULL, 1, 0, NULL, 156),
(201, 'vzukvnjjhzintijexhjd', 'x_x', '1L', 66, '2019-11-11 11:48:37', '2019-11-11 13:15:58', NULL, 1, 0, NULL, 90),
(202, 'VVVBBB', '', '', 0, '2019-11-11 11:59:23', '2019-11-11 13:10:30', NULL, 1, 0, NULL, 148),
(203, 'Super-gas', '', '2L', 50, '2019-11-11 14:00:47', NULL, NULL, 1, 0, NULL, 87),
(204, 'Gas2', '', '', 0, '2019-11-11 14:01:49', NULL, NULL, 1, 0, 'lista', 87),
(205, 'Supreme jugooo', 'de manzanas exprimidas', '1L', 250, '2019-11-11 14:09:52', NULL, NULL, 1, 0, 'lista', 87),
(206, 'Juguito de tomate de árbol', 'Ecuador', '1L', 200, '2019-11-11 15:14:36', '2019-11-11 16:26:34', NULL, 1, 0, 'lista publica', 90),
(207, 'Juguito de tomate papaya', NULL, '1L', 150, '2019-11-11 15:15:05', '2019-11-11 15:41:32', NULL, 1, 0, 'lista', 87),
(208, 'Juguito de tomate pitaya', NULL, '1L', 450, '2019-11-11 15:15:16', NULL, NULL, 1, 0, 'lista', 87),
(209, 'AAA', '', '', 0, '2019-11-12 12:50:01', '2019-11-12 12:50:04', '2019-11-12 12:50:04', 1, 0, NULL, 113),
(210, 'GGG', '', '', 0, '2019-11-12 13:02:55', '2019-11-12 13:03:10', '2019-11-12 13:03:10', 1, 0, NULL, 113),
(211, 'EEEE', '', '', 50, '2019-11-27 17:06:24', '2019-11-30 23:22:41', '2019-12-01 03:22:41', 1, 0, NULL, 159),
(212, 'RRR', '', '', 0, '2019-11-27 18:01:44', '2019-11-27 18:01:50', '2019-11-27 22:01:50', 1, 0, NULL, 160),
(213, 'E%$', '', '', 0, '2019-11-27 18:02:17', NULL, NULL, 1, 0, NULL, 160),
(214, 'TTT', '', '', 0, '2019-11-28 00:01:02', NULL, NULL, 1, 0, NULL, 167),
(215, 'Vino tinto', '', '', 100, '2019-11-30 11:13:05', '2019-11-30 11:26:13', NULL, 1, 0, NULL, 168),
(216, 'BBB', '', '', 0, '2019-11-30 23:23:29', NULL, NULL, 1, 0, NULL, 159),
(218, 'Caja organizadora', 'plastico', 'M', 100, '2019-12-07 10:25:46', NULL, NULL, 1, 0, NULL, 125),
(219, 'Vodka', 'de Canada', '2L', 250, '2019-12-13 08:29:11', '2019-12-13 08:29:28', NULL, 1, 0, NULL, 90),
(220, 'Agua', 'sabor limón', '', 0, '2019-12-13 18:35:19', NULL, NULL, 1, 0, NULL, 90),
(221, 'Agua', 'sabor lima', '', 20, '2019-12-13 18:35:35', NULL, NULL, 1, 0, NULL, 90),
(222, 'Agua', 'mineral', '', 15, '2019-12-13 18:35:49', NULL, NULL, 1, 0, NULL, 90),
(223, 'Agua', 'sabor pomelo', '', 20, '2019-12-13 18:36:21', '2019-12-13 18:36:39', '2019-12-13 22:36:39', 1, 0, NULL, 90),
(224, 'Ron', 'caribeño', '', 0, '2019-12-13 18:37:16', '2019-12-13 18:38:05', '2019-12-13 22:38:05', 1, 0, NULL, 90),
(225, 'Ron', 'de Trinidad', '', 0, '2019-12-13 18:37:34', '2019-12-13 18:38:02', '2019-12-13 22:38:02', 1, 0, NULL, 90),
(226, 'Ron', 'de Cuba', '', 0, '2019-12-13 18:37:47', '2019-12-13 18:37:54', '2019-12-13 22:37:54', 1, 0, NULL, 90);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(15) NOT NULL,
  `email` varchar(60) NOT NULL,
  `confirmed_email` tinyint(4) NOT NULL DEFAULT 0,
  `firstname` varchar(50) DEFAULT NULL,
  `lastname` varchar(80) DEFAULT NULL,
  `password` varchar(60) DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `belongs_to` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `confirmed_email`, `firstname`, `lastname`, `password`, `deleted_at`, `belongs_to`) VALUES
(1, 'boctulus1', 'boctulus1@gmail.com', 1, '', '0', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 1),
(4, 'pbozzolo', 'pbozzolo@gmail.com', 1, 'Paulinoxxxy', 'Bozzoxxxy', '$2y$10$jAKcStnGqtcOslt1Std7ceYqq3mMIh6Lis/Ug4Z6IDQV65tyyP2Xe', NULL, 4),
(5, 'pepe', 'pepe@gmail.com', 1, 'Pepe', 'Gonzalez', '$2y$10$J.KPjyFukfxcKg83TvQGaeCTrLN9XyYXTgtTDZdZ91DJTdE73VIDK', NULL, 5),
(9, 'dios', 'dios@gmail.com', 1, 'Paulinoxxxxxyyz', 'Bozzoxx000555zZ', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 9),
(11, 'diosdado', 'diosdado@gmail.com', 1, 'Sr', 'Z', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 11),
(13, 'diosdado2', 'diosdado2@gmail.com', 1, 'Sr', 'D', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 13),
(14, 'juancho', 'juancho@aaa.com', 1, 'Juan', 'Perez', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 14),
(15, 'juancho11', 'juancho11@aaa.com', 1, 'Juan XI', 'Perez 10', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 15),
(16, 'mabel', 'mabel@aaa.com', 1, 'Mabel', 'S', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 16),
(17, 'a', 'a@b.commmm', 1, 'Nicos', 'Buzzi', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 17),
(20, 'a1', 'a@b.commmmmmmmmmmm', 1, 'Nicos', 'AAA', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 20),
(34, 'peter', 'peter@abc.com', 1, 'Nicos', 'Buzzi', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 34),
(38, 'udcsoqjyrdg', 'udcsoqjyrdgnhqqtukhupeter@abc.com', 1, 'Nicos', 'Buzzi', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 38),
(39, 'qbosmfvwezo', 'qbosmfvwezohbutpifbopeter@abc.com', 1, 'Nicos', 'Buzzi', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 39),
(40, 'gjappgiduiq', 'gjappgiduiqczagnousspeter@abc.com', 1, 'Nicos', 'Buzzi', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 40),
(41, 'ymcshlekdzh', 'ymcshlekdzhugvmwbjpipeter@abc.com', 1, 'Nicos', 'Buzzi', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 41),
(43, 'vydqkgqszpn', 'vydqkgqszpncijwhxeiapeter@abc.com', 1, 'Nicos', 'Buzzi', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 43),
(44, 'itbrknzsfna', 'itbrknzsfnawnhxgmockpeter@abc.com', 1, 'Nicos', 'Buzzi', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 44),
(45, 'cproifnsfxv', 'cproifnsfxvkxtppbgdupeter@abc.com', 1, 'Nicos', 'Buzzi', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 45),
(46, 'xxxxxxxxxxx', 'xxxxxxxxxxxxxxxxyz@abc.com', 1, 'Nicolayyyy', 'Buzzi', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 46),
(48, 'gates', 'gates@outlook.com', 1, 'Bill', 'Gates', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 48),
(51, 'kkk', 'kkk@bbbbbb.com', 1, 'Sr K', 'NS/NC', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 51),
(52, 'tito', 'tito@gmail.com', 1, 'Tito', 'El Grande', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 52),
(53, 'ooooiiii', 'ooooiiii@gmail.com', 1, 'Oooo', 'iiiiiiii', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 53),
(54, 'booooiiii', 'booooiiii@gmail.com', 1, 'AAA', 'BBB', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 54),
(55, 'iooobooooii', 'iooobooooiiii@gmail.com', 1, 'IIoo', 'ahaha', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 55),
(56, 'iooobooooii5', 'iooobooooiiioooi@gmail.com', 1, 'IIoo', 'ahaha', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 56),
(57, 'iooobooooii57', 'iooobooooiiioooi@gmail.commmm', 1, 'IIoo', 'ahaha', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 57),
(58, 'kkk1', 'kkk@bbbbbb.commmm', 1, 'Sr K', 'NS/NC', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 58),
(59, 'kkkbooooiii', 'kkkbooooiiii@gmail.com', 1, 'Ooookkk', 'kkkk', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 59),
(60, 'aaa', 'aaa@bbbb.com', 1, 'Jjjj', 'kkk', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 60),
(61, 'aaa7', 'aaa@bbbb.commmm', 1, 'Jjjj', 'kkk', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 61),
(62, 'kkk6', 'kkk@bbbbbb.commmmmmm', 1, 'Sr K', 'NS/NC', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 62),
(63, 'aaa5', 'aaa@bbbb.commmmmmm', 1, 'Jjjj', 'kkk', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 63),
(64, 'booooiiiixx', 'booooiiiixxxx@gmail.com', 1, 'xxx', 'xxxxxxxxxx', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 64),
(65, 'aaa9', 'aaa@bbbb.commmmmmmuuuuu', 1, 'Jjjj', 'kkk', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 65),
(66, 'aaa54', 'aaa@dgdgd.cococ', 1, 'ajajaj', 'ajajaj', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 66),
(67, 'booooiiifer', 'booooiiiferfr@gmail.com', 1, 'BillY', 'GGGG', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 67),
(68, 'aaa78', 'aaa@dgdgd.cococo', 1, 'ajajaj', 'ajajaj', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 68),
(69, 'test', 'test@gmail.com', 1, 'TEST', '---', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 69),
(70, 'kkk65', 'kkk@bbJJJJJJJ', 1, 'Sr K', 'NS/NC', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 70),
(72, 'aie', 'aie@b.c', 1, 'Sr K', 'NS/NC', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 72),
(73, 'mabelf450', 'mabelf450@gmail.com', 1, 'Mabel', 'F', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 73),
(74, 'abc', 'abc@def.com', 1, 'Sr K', 'NS/NC', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 74),
(75, 'abc4', 'abc@def.commm', 1, 'Sr K', 'NS/NC', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 75),
(76, 'abc6', 'abc@def.net', 1, 'Sr K', 'NS/NC', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 76),
(77, 'abc62', 'abc@def.co', 1, 'Sr K', 'NS/NC', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 77),
(78, 'abc9', 'abc@def.cox', 1, 'Sr K', 'NS/NC', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 78),
(79, 'feli', 'feli@', 1, 'Sr K', 'NS/NC', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 79),
(80, 'feli3', 'feli@casa', 1, 'Sr K', 'NS/NC', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 80),
(81, 'feli5', 'feli@casa.com', 1, 'Sr K', 'NS/NC', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 81),
(82, 'feli4', 'feli@casa.net', 1, 'Sr K', 'NS/NC', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 82),
(83, 'feli9', 'feli@casa.neto', 1, 'Sr K', 'NS/NC', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 83),
(84, 'pablo', 'pablo@', 1, 'Nicos', 'AAA', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 84),
(85, 'feli6', 'feli@teamo', 1, 'Felipe', 'Bozzolo', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 85),
(86, 'nuevo', 'nuevo@gmail.com', 1, 'Norberto', 'Nullo', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 86),
(87, 'pedro', 'pedro@gmail.com', 1, 'Pedro', 'Picapiedras', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 87),
(88, 'feli8', 'feli@abc', 1, 'Felipe', 'Bozzzolo', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 88),
(89, 'h', 'h@', 1, 'Sr H', 'J', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 89),
(90, 'nano', 'nano@g.c', 1, 'NA', 'NA', '$2y$10$qmCo8ZeT1XJWPZ1kuSeFjuj7rEDT9J/YDV4yD3BsVoE.pz0ryfhE2', NULL, 90),
(102, 'feli61', 'feli@delacasita', 1, 'Sr K', 'NS/NC', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 102),
(103, 'feli1', 'feli@delacasita2', 1, 'Sr K', 'NS/NC', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 103),
(104, 'feli7', 'feli@delacasita5', 1, 'Sr K', 'NS/NC', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 104),
(105, 'feli80', 'feli@delacasita50.com', 1, 'Sr K', 'NS/NC', '$2y$10$N6fTRdVfyusWVkAchTWmSO1OAscI/X.ZU5YU14imTrfR0gLlbncVO', NULL, 105),
(106, 'feli72', 'feli@delacasita50000', 1, 'Sr K', 'NS/NC', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 106),
(107, 'feli36', 'feli@delacasita50000700', 1, 'Sr K', 'NS/NC', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 107),
(108, 'feli31', 'feli@delacasita50000700800', 1, 'Sr K', 'NS/NC', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 108),
(109, 'feli34', 'feli@compeon', 1, 'Sr K', 'NS/NC', '$2y$10$ocJZwzelZ3W.3Eas5ig/q.qaBm79ottSuJ8ee2wXv9584INHL2RpW', NULL, 109),
(110, 'feli67', 'feli@compeon_mundial', 1, 'Sr K', 'NS/NC', '$2y$10$k4kFWXmQacW4LDS.j4gVk./LqKPUtOc9XaObNWojsAzmFnIxbLZ8u', NULL, 110),
(111, 'feli76', 'feli@compeon_dios', 1, 'Sr K', 'NS/NC', '$2y$10$nYh5nGXM6mVwwAP93G4Nv.m8P8aQmJKLm5fODQBqdmzzSCEZzDiOC', NULL, 111),
(112, 'superpepe', 'superpepe@', 1, 'Super Pepe', '', '$2y$10$hITAKY1zsMPMIe0KCO6YuuG5Xke8FeZlw00Uw1Mz4J57LU0lLfvja', NULL, 112),
(113, 'mabelsusana', 'mabelsusanaf@gmail.com', 1, NULL, NULL, NULL, NULL, 113),
(114, 'xyz', 'xyz@', 1, 'XYZ', 'ZZZ', '$2y$10$mMaJuMmAt7hgTo8KljlDRuavoOg4F0ZGw.hAK/vmpNQJ0xvA/ZEmG', NULL, 114),
(115, 'x', 'x@', 1, 'Sr K', 'NS/NC', '$2y$10$K64bJLscpFCH7Geal..vhuKeMzZOP9MWD21eXPj7VpfYiHw4AaEMC', NULL, 115),
(116, 'xy', 'xy@', 1, 'Sr K', 'NS/NC', '$2y$10$86ztfpWglgqvK/MyJf12VOYY8rqjJ4Qhi1XhpOsNW0u.BMM3odtUG', NULL, 116),
(117, 'aaa3', 'aaa@', 1, 'A', 'AB', '$2y$10$KP8rEs5DracAVvcdMY/ATuB3xwEz7Rjwqj5DilPiszMi8wMRKNAhK', NULL, 117),
(118, 'aaaC', 'aaaC@', 1, 'A', 'AB', '$2y$10$T59OjL8Rxb/QZeArKW.PK.GVVG7V3Ao846KSimwt6xGbf7tx0oik.', NULL, 118),
(119, 'aaaCD', 'aaaCD@', 1, 'A', 'AB', '$2y$10$SvAYgvxsszn1Z/cfMW/w9eopOcih3CzADDEDd2q0wKwqwTBWVPZoi', NULL, 119),
(120, 'xyza', 'xyza@', 1, 'XYZA', 'ZZZA', '$2y$10$rp40xaVPNtHqSsDYXVBNCespVSLBpvwdp1oyV7NY6nJk011q2/Iri', NULL, 120),
(121, 'nono', 'nono@', 1, 'Nono', 'viecco', '$2y$10$W5HaPfOxbAE9rRb04XCzeO/sS0tlHQ4ZTnmXzZPnd2s1qZt26OFdS', NULL, 121),
(122, 'sss', 'sss@', 1, 'Regular', 'Tester', '$2y$10$8FiysajEjA0.5eyXuLNUZeqQkUBgxCYVonwk1Z5k4rTf/rPMLu.2y', NULL, 122),
(123, 'ppp', 'ppp@', 1, 'Regular', 'Tester', '$2y$10$J3C1J2pqvJbuQXEekf6vf.AL0lEJbpGrhPtZCujXRfyv16nEzQvdm', NULL, 123),
(124, 'w', 'w@', 1, 'AA', 'BB', '$2y$10$899qRrlzAXbnyE/5CHLZVezdK9beIDJqrUmb/TcdgepkPHYTMLJTK', NULL, 124),
(125, 'tester3', 'tester3@g.c', 1, 'Tester', '', '$2y$10$hfB4/MQ8ULXY4fhaeAhXqOw7V.U1ifhJeLe5/Xx7mwXA.uFbHBwai', NULL, 125),
(126, 'jk', 'jk@', 1, 'J', 'K', '$2y$10$irie63zGURJ/JQiJuyph/uVkVmAJRiSvvDRGaBokyvVLpi8tqganW', NULL, 126),
(127, 'aaaCDEF', 'aaaCDEF@', 1, 'A', 'AB', '$2y$10$juP31/p3B.P7F/b2MXxGF.kiN/HG1zwIyehkNWjef5yhFYSGq.YwC', NULL, 127),
(128, 'aaaCDEFG', 'aaaCDEFG@', 1, 'A', 'AB', '$2y$10$nkIhbPrL4Y/oJJOe6JdOO.57U8Njn57IRM5cYG7FtPDA2jffOsFNm', NULL, 128),
(129, 'aaaCDEFGI', 'aaaCDEFGI@', 1, 'A', 'AB', '$2y$10$VeyVsSP/.2SgfBJq25FXIOPq2iXceFiEgRiEVuOTi5oPIMWc8Vteq', NULL, 129),
(130, 'aaaCDEFGIJK', 'aaaCDEFGIJK@', 1, 'A', 'AB', '$2y$10$ann6qT5V/SkaYk/InT27ouVPhrkNaVOlvwgY2nf27lhlT5hm0hnTK', NULL, 130),
(131, 'aaaCDEFGIJK9', 'aaaCDEFGIJKLM@', 1, 'A', 'AB', '$2y$10$N2hoKh4E9.aYnAPmMPuSb.mgkLAJA1mB0pAnpjsLRU7DuyX1.b1ZC', NULL, 131),
(132, 'tt', 'tt@', 1, 'T', 'TT', '$2y$10$qXJ25mY64hef.47EjJkBNeYDgG.zHNH8QsodGK3OW13EYQdpaifFG', NULL, 132),
(133, 'jc', 'jc@', 1, 'Juan', 'Carlos', '$2y$10$gMgqAHr1A5.phZ2/n.RuVuJEM8QnfPVAiTGMVqvCOf7mlWXrGNjXK', NULL, 133),
(135, 'b', 'b@gmail.com', 1, 'Pablo', 'Bozzolo', '$2y$10$k0dfh9fPueuBlpPr0zC6V.kEr0CR4uHjEc3IUKipfRR3sDnnSvieu', NULL, 135),
(136, 'bill', 'bill@', 1, 'Bill', 'O', '$2y$10$GHiCUTrFu01EiVcVgTvRluGweRBx8rF6V2qgbhNH82Oi86ATE0RO2', NULL, 136),
(137, 'san', 'san@', 1, 'San', 'Pepe', '$2y$10$OVUu5rTU1JPSTv2HeXq/puWq86vZT24VAuYTW.sAT.pqzxZEJPFou', NULL, 137),
(138, 'j', 'j@', 1, 'AJ', 'J', '$2y$10$TOUciinPf3DEBTjALBLnaOhMLzHrwBRkvczOWYD5OsZOy9aVGVfnC', NULL, 138),
(139, 'aaaCDEFGIJK6', 'aaaCDEFGIJKLMSSS@', 1, 'A', 'AB', '$2y$10$0YDH5aE9l3lQwxFp25bLXepmGQF8fM4XcK4zt/lNzS/2.M7mVgz4S', NULL, 139),
(140, 'ZZZ', 'ZZZ@', 1, 'A', 'AB', '$2y$10$QosozVg0npjSEimSeOZ74OPyXXZO0SwPsZevPOJlQ0GnHDK7DcsR6', NULL, 140),
(141, 'ZZZ5', 'ZZZ@PEPE.com', 1, 'A', 'AB', '$2y$10$XIpys1..n4XcQ9CWP89vce9j7NeAiMYYeOqonYtGgUi.9nZcaccB2', NULL, 141),
(142, 'A5', 'A@PEPE.COM', 1, 'A', 'AB', '$2y$10$Fn5Wkt8masdaOdqBHIaU0uDuRbi7mEVDSzlHgAK8wfJSesSlQWAZu', NULL, 142),
(143, 'Ab', 'Ab@PEPE.COM', 1, 'A', 'AB', '$2y$10$ehfF6Uwrvdl3NoqYkqhnIexzzCX49pCdbEXOYKrI1O2jsvdHLEpCa', NULL, 143),
(147, 'ABCDEF', 'ABCDEF@PEPE.COM', 1, 'A', 'AB', '$2y$10$67kVEbzj5C7eGl8b9f01jeMvbU6Cy2xGYADhYl5PvMmUAXAenmmb2', NULL, 147),
(148, 'ABCDEFG', 'ABCDEFG@PEPE.COM', 1, 'A', 'AB', '$2y$10$p/KGmzQsMmlDDJlYmhr9GOUmkcO4A.A8sefXd7bN3GthEIEA4YCSO', NULL, 148),
(149, 'X8', 'X@PEPE.COM', 1, 'A', 'AB', '$2y$10$R9EtFdhCWUjMMFkLZxLiA.8XJ2hd2SE8Q3eDuyUa8KOV0TsTGNRX.', NULL, 149),
(150, 'Y', 'Y@PEPE.COM', 1, 'A', 'AB', '$2y$10$WeO4iScCcFe/yV4d7iUbFeYOfTR6H3TGDjunJLskrJg/.9raTP7IG', NULL, 150),
(151, 'F', 'F@PEPE.COM', 1, 'A', 'AB', '$2y$10$LDtPjcxYtrwMVfae.8MsNuRaoRgsnVtagH2fbYG3m3MIYOeAWB4fC', NULL, 151),
(152, 'G', 'G@PEPE.COM', 1, 'A', 'AB', '$2y$10$TY9RyLYn.E24qAeBn9LCzuy8bRT1/UReSDDodfdHouQ7jRN6GqMTq', NULL, 152),
(153, 'GG', 'GG@PEPE.COM', 1, 'A', 'AB', '$2y$10$qt79FFFbCbQNya/a8fMMn.tcstyJYUHRXIw36pGDTLExLueDKbJby', NULL, 153),
(154, 'GGG', 'GGG@PEPE.COM', 1, 'A', 'AB', '$2y$10$bIqxFs1bOo.CkImQj5t26.R6fnp5UO8913B5NkxNDy5gP0bhNoP2O', NULL, 154),
(155, 'elpiojo', 'elpiojo@', 1, 'Ojo', '', '$2y$10$UvEXPDfjiZ/PPits.tThwurSYCz844ZMh9mCVBC5Y9s7Hp8KwlR.2', NULL, 155),
(156, 'elpiojo2', 'elpiojo2@', 1, 'Ojo', '', '$2y$10$aPWXX4L3MNnPcz0Q.zLFRO97ChkGYQoUdyvk.RzVDuwRaQDiMxkUS', NULL, 156),
(159, 'boo', 'boo@', 1, 'Boo', '', '$2y$10$eZfHERLi3AlNU7zsPzV.OOqF0Hs7jkh.LXRKdM/3XVABpLujf/f.G', NULL, 159),
(160, 'uub', 'uub@', 1, 'Uub', '', '$2y$10$ylqsjqzOYFRrfRNYGaruteJ1uLnqcDLyyJTE6If1wb3GNCvEwTk/u', NULL, 160),
(163, 'asdfgh', 'asdfgh', 0, NULL, NULL, '$2y$10$3qyTA2frHg.CNo2VQTh/cenZoi4y4dtoKhGQNe6P8lqL.u5jS3MFu', NULL, 163),
(164, 'asdfgh2', 'asdfgh2', 0, NULL, NULL, '$2y$10$lWC.2LcTeHNX65n1NDwDsuSekR0zYC0WNBTOzXEesuRSkKt3krTxa', NULL, 164),
(165, 'asdfgh23', 'asdfgh23', 0, NULL, NULL, '$2y$10$3ehTASOEPlBjoNYdrZE.WeoKNoV35.DvjpDW1S7IkihN3ByKlCRse', NULL, 165),
(166, 'asdfgh234', 'asdfgh234', 0, NULL, NULL, '$2y$10$AgMfAavv9tAZjJNPZWAceeEq6gBnBgbKiQRrnqtBIioMk8zMvZrKi', NULL, 166),
(167, 'pepem', 'pepe@', 1, NULL, NULL, '$2y$10$E7MLf1GxIdRnT4uwOYr03e6mrs3BXd1SApL6EzvzqTs4EkyzttjKm', NULL, 167),
(168, 'boctulus', 'boctulus@gmail.com', 0, 'Pablo', 'Bozzolo', '$2y$10$266K2AVhSao58S1J2VJBmOQgTikkuNtRJUUJL3jjb0kcNrbDtaoLe', NULL, 168),
(170, 'uva@g.c', 'uva@g.c', 0, NULL, NULL, '$2y$10$tqA8gO2X8m8aNJoWkqjlJObThy4ZkzTxrV0V0srP7o8QWm/VFsQyO', NULL, 170),
(196, 'doe1979', 'testing_create@g.com', 0, 'Jhon', 'Doe', 'pass', NULL, NULL),
(200, 'doe1980', 'testing1@g.com', 0, 'Jhon', 'Doe', 'pass', NULL, NULL),
(223, 'ffff1', 'ffff1@g.c', 0, NULL, NULL, '$2y$10$FmWYwubCrUXNoyUC16zsquFyCn0R8OzE6eicAgCd6cnDUNXqr2fIa', NULL, 223),
(224, 'newuser97865wxy', 'newuser97865wxy@g.c', 0, NULL, NULL, '$2y$10$E0bUrCzhD12YWQWtRPE4CeQspqlKWhJXNyLuBi3iFzPLmiZ6fW10W', NULL, 224),
(225, 'nnn', 'nnn@g.c', 0, NULL, NULL, '$2y$10$C0sO.yRmK1xm./jm.OTMw.citGYFci11M/BHecY/pBYY9BplqsYg2', NULL, 225),
(226, 'nn_j23483j401wx', 'nn_j23483j401wx@g.c', 0, NULL, NULL, '$2y$10$vHi9kf2VLIIyZUn8P8ACQuQ0SIT7REfVTPxnMvGwY7g.MrMUi2Sn2', NULL, 226),
(301, 'nano111', 'asdffffffffffff@g.c', 0, NULL, NULL, '$2y$10$Xu6Lv.AjKrwYV4NOfJIce.l1HTwHUkSlOcMQQGkATFLwmDt2i5K9i', NULL, 301),
(302, 'nano111__$', 'asdfggg@g.c', 0, NULL, NULL, '$2y$10$PMVMWFDhKnaITfqHimZ1JOyaYnqOA6ne26AQPHVMQAUFEYA.vXEc6', NULL, 302),
(303, 'nano111__$u', 'asdfggtttg@g.c', 0, NULL, NULL, '$2y$10$Ioz50NWbl86r9ZC8F/tXoOZHbiArx07z51quWp4smvsn.3sNXLXb6', NULL, 303),
(304, 'nandddo111__$u', 'asdfggtdddddtdddtg@g.c', 0, NULL, NULL, '$2y$10$w/DJt2PJB0jjUe3piamNNeUN2.JzMGfkcW69boizhjVhrbO75anOK', NULL, 304),
(305, 'no1fff11__$u', 'asdfggtdtdddtg@g.c', 0, NULL, NULL, '$2y$10$.YHFBcObFtZo72RWZ54hj.7qAQqGghb4/He5YZA5xTqL8ZXxZk9c6', NULL, 305),
(306, 'no51fff11__$u', 'asdfgg5tdftdddtg@g.c', 0, NULL, NULL, '$2y$10$MU4hhwbx2qDQp8J/M7lUSOiU/q0mPfwfmz3bZ6lVBc5Ofymo41fmu', NULL, 306),
(307, 'no51fuff11u', 'asdfgg5tudftdddtg@g.c', 0, NULL, NULL, '$2y$10$eIIdO34JhpW4hlmrssn7e.ZvnuQLph8yN/IhZAnEb7nzv/ZvgpjuG', NULL, 307),
(308, 'no1fuff11u', 'asdfgg5dftdddtg@g.c', 0, NULL, NULL, '$2y$10$UP.z8IaCnMC5lZRAvVy/tetjg4ql5LLXubwIzZwrnY7QGE8Vi4vGu', NULL, 308);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `user_roles`
--

CREATE TABLE `user_roles` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `modification_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `user_roles`
--

INSERT INTO `user_roles` (`id`, `user_id`, `role_id`, `created_at`, `modification_date`) VALUES
(1, 5, 2, '2019-10-18 21:58:10', '2019-10-18 21:58:10'),
(2, 1, 100, '2019-10-18 21:58:10', '2019-10-18 21:58:10'),
(3, 4, 100, '2019-10-18 21:58:10', '2019-10-18 21:58:10'),
(5, 9, 100, '2019-10-18 21:58:10', '2019-10-18 21:58:10'),
(6, 11, 100, '2019-10-18 21:58:10', '2019-10-18 21:58:10'),
(7, 13, 100, '2019-10-18 21:58:10', '2019-10-18 21:58:10'),
(9, 4, 2, '2019-10-18 21:58:10', '2019-10-18 21:58:10'),
(10, 48, 2, '2019-10-18 21:58:10', '2019-10-18 21:58:10'),
(11, 86, 2, '2019-10-18 21:58:10', '2019-10-18 21:58:10'),
(12, 86, 3, '2019-10-18 21:58:10', '2019-10-18 21:58:10'),
(14, 86, 100, '2019-10-18 21:58:10', '2019-10-18 21:58:10'),
(15, 87, 3, '2019-10-18 21:58:10', '2019-10-18 21:58:10'),
(16, 89, 3, '2019-10-18 21:58:10', '2019-10-18 21:58:10'),
(17, 4, 3, '2019-10-18 21:58:10', '2019-10-18 21:58:10'),
(18, 1, 3, '2019-10-18 21:58:10', '2019-10-18 21:58:10'),
(20, 90, 3, '2019-10-18 21:58:10', '2019-10-18 21:58:10'),
(21, 85, 2, '2019-10-18 21:58:10', '2019-10-18 21:58:10'),
(22, 48, 3, '2019-10-18 21:58:10', '2019-10-18 21:58:10'),
(23, 105, 2, '2019-10-18 22:12:34', NULL),
(24, 106, 2, '2019-10-19 11:06:17', NULL),
(25, 107, 3, '2019-10-19 11:31:55', NULL),
(26, 108, 3, '2019-10-19 11:37:13', NULL),
(35, 113, 3, '2019-10-24 11:34:25', NULL),
(36, 112, 3, '2019-10-26 19:47:48', NULL),
(40, 122, 3, '2019-11-02 15:49:04', NULL),
(41, 123, 3, '2019-11-02 15:53:03', NULL),
(42, 124, 3, '2019-11-02 16:03:44', NULL),
(43, 125, 3, '2019-11-02 16:05:58', NULL),
(44, 129, 3, '2019-11-03 23:46:30', NULL),
(45, 130, 3, '2019-11-03 23:49:30', NULL),
(46, 131, 3, '2019-11-03 23:50:11', NULL),
(47, 132, 3, '2019-11-03 23:55:09', NULL),
(48, 133, 2, '2019-11-04 17:05:47', NULL),
(49, 135, 3, '2019-11-05 21:04:03', NULL),
(50, 136, 3, '2019-11-07 00:54:05', NULL),
(51, 137, 3, '2019-11-07 22:57:33', NULL),
(53, 139, 3, '2019-11-08 20:43:38', NULL),
(54, 140, 3, '2019-11-09 10:46:12', NULL),
(55, 141, 3, '2019-11-09 10:46:39', NULL),
(59, 147, 3, '2019-11-09 21:18:10', NULL),
(62, 149, 3, '2019-11-10 00:45:45', NULL),
(63, 150, 3, '2019-11-10 00:50:39', NULL),
(64, 151, 3, '2019-11-10 22:03:23', NULL),
(65, 152, 3, '2019-11-10 22:04:17', NULL),
(66, 153, 3, '2019-11-10 22:08:29', NULL),
(67, 154, 3, '2019-11-10 22:08:41', NULL),
(68, 156, 3, '2019-11-11 11:42:16', NULL),
(69, 148, 3, '2019-11-11 11:58:20', NULL),
(70, 159, 3, '2019-11-27 16:45:34', NULL),
(71, 160, 3, '2019-11-27 17:59:13', NULL),
(72, 164, 3, '2019-11-27 23:50:16', NULL),
(73, 165, 3, '2019-11-27 23:53:18', NULL),
(74, 166, 3, '2019-11-27 23:55:28', NULL),
(75, 167, 3, '2019-11-27 23:59:54', NULL),
(76, 168, 3, '2019-11-30 11:12:33', NULL),
(78, 170, 3, '2019-12-04 09:17:45', NULL),
(79, 223, 3, '2019-12-06 10:36:42', NULL),
(80, 224, 3, '2019-12-06 10:49:24', NULL),
(81, 225, 3, '2019-12-06 11:08:08', NULL),
(82, 226, 3, '2019-12-06 11:14:26', NULL),
(111, 301, 3, '2019-12-17 14:00:39', NULL),
(112, 302, 3, '2019-12-17 14:01:06', NULL),
(113, 303, 3, '2019-12-17 14:02:01', NULL),
(114, 304, 3, '2019-12-17 14:03:04', NULL),
(115, 305, 3, '2019-12-17 14:04:26', NULL),
(116, 306, 3, '2019-12-17 14:05:47', NULL),
(117, 307, 3, '2019-12-17 14:10:53', NULL),
(118, 308, 3, '2019-12-17 14:13:20', NULL);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `folders`
--
ALTER TABLE `folders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `resource_table` (`resource_table`,`name`,`belongs_to`),
  ADD KEY `owner` (`belongs_to`);

--
-- Indices de la tabla `group_permissions`
--
ALTER TABLE `group_permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `folder_id` (`folder_id`,`member`),
  ADD KEY `member` (`member`),
  ADD KEY `belongs_to` (`belongs_to`);

--
-- Indices de la tabla `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `other_permissions`
--
ALTER TABLE `other_permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `folder_id` (`folder_id`),
  ADD KEY `belongs_to` (`belongs_to`);

--
-- Indices de la tabla `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `created_by` (`belongs_to`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `belongs_to` (`belongs_to`);

--
-- Indices de la tabla `user_roles`
--
ALTER TABLE `user_roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id_2` (`user_id`,`role_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `role_id` (`role_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `folders`
--
ALTER TABLE `folders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `group_permissions`
--
ALTER TABLE `group_permissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=93;

--
-- AUTO_INCREMENT de la tabla `other_permissions`
--
ALTER TABLE `other_permissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=252;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=309;

--
-- AUTO_INCREMENT de la tabla `user_roles`
--
ALTER TABLE `user_roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=119;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `folders`
--
ALTER TABLE `folders`
  ADD CONSTRAINT `folders_ibfk_1` FOREIGN KEY (`belongs_to`) REFERENCES `users` (`id`);

--
-- Filtros para la tabla `group_permissions`
--
ALTER TABLE `group_permissions`
  ADD CONSTRAINT `group_permissions_ibfk_1` FOREIGN KEY (`folder_id`) REFERENCES `folders` (`id`),
  ADD CONSTRAINT `group_permissions_ibfk_2` FOREIGN KEY (`member`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `group_permissions_ibfk_3` FOREIGN KEY (`belongs_to`) REFERENCES `users` (`id`);

--
-- Filtros para la tabla `other_permissions`
--
ALTER TABLE `other_permissions`
  ADD CONSTRAINT `other_permissions_ibfk_1` FOREIGN KEY (`folder_id`) REFERENCES `folders` (`id`),
  ADD CONSTRAINT `other_permissions_ibfk_2` FOREIGN KEY (`belongs_to`) REFERENCES `users` (`id`);

--
-- Filtros para la tabla `user_roles`
--
ALTER TABLE `user_roles`
  ADD CONSTRAINT `user_roles_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

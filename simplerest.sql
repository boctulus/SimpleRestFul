-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Nov 11, 2019 at 12:44 PM
-- Server version: 5.7.27-0ubuntu0.18.04.1
-- PHP Version: 7.2.24-0ubuntu0.18.04.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `simplerest`
--

-- --------------------------------------------------------

--
-- Table structure for table `folders`
--

CREATE TABLE `folders` (
  `id` int(11) NOT NULL,
  `resource_table` varchar(40) NOT NULL,
  `value` varchar(40) NOT NULL,
  `belongs_to` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `folders`
--

INSERT INTO `folders` (`id`, `resource_table`, `value`, `belongs_to`) VALUES
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
-- Table structure for table `group_permissions`
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
-- Dumping data for table `group_permissions`
--

INSERT INTO `group_permissions` (`id`, `folder_id`, `belongs_to`, `member`, `r`, `w`) VALUES
(1, 1, 1, 4, 1, 1),
(2, 2, 72, 79, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `messages`
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
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `from_email`, `from_name`, `to_email`, `to_name`, `subject`, `body`, `created_at`, `sent_at`) VALUES
(24, 'no_responder@simplerest.mapapulque.ro', 'No responder', 'pbozzolo@gmail.com', '   ', 'Cambio de contraseña', 'Para cambiar la contraseña siga el enlace:<br/><a href=\'http//simplerest.co/login/change_pass/eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImlhdCI6MTU3MzM1OTg5NywiZXhwIjoxNTczOTY0Njk3LCJlbWFpbCI6InBib3p6b2xvQGdtYWlsLmNvbSJ9.Qn9M-1oOVg-h7gpo_X1PKulV__alHca6JqhDkIWgVzs/1573964697\'>http//simplerest.co/login/change_pass/eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImlhdCI6MTU3MzM1OTg5NywiZXhwIjoxNTczOTY0Njk3LCJlbWFpbCI6InBib3p6b2xvQGdtYWlsLmNvbSJ9.Qn9M-1oOVg-h7gpo_X1PKulV__alHca6JqhDkIWgVzs/1573964697</a>', '2019-11-10 01:24:57', NULL),
(25, 'no_responder@simplerest.mapapulque.ro', 'No responder', 'pbozzolo@gmail.com', '   ', 'Cambio de contraseña', 'Para cambiar la contraseña siga el enlace:<br/><a href=\'http//simplerest.co/login/change_pass/eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImlhdCI6MTU3MzM2MDQxOSwiZXhwIjoxNTczOTY1MjE5LCJlbWFpbCI6InBib3p6b2xvQGdtYWlsLmNvbSJ9.ZK1vC0MCC46FYZtMDAJzejdpZLOdbZMrELrwdeRZutI/1573965219\'>http//simplerest.co/login/change_pass/eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImlhdCI6MTU3MzM2MDQxOSwiZXhwIjoxNTczOTY1MjE5LCJlbWFpbCI6InBib3p6b2xvQGdtYWlsLmNvbSJ9.ZK1vC0MCC46FYZtMDAJzejdpZLOdbZMrELrwdeRZutI/1573965219</a>', '2019-11-10 01:33:39', NULL),
(26, 'no_responder@simplerest.mapapulque.ro', 'No responder', 'pbozzolo@gmail.com', '   ', 'Cambio de contraseña', 'Para cambiar la contraseña siga el enlace:<br/><a href=\'http//simplerest.co/login/change_pass/eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImlhdCI6MTU3MzM2MDQyMiwiZXhwIjoxNTczOTY1MjIyLCJlbWFpbCI6InBib3p6b2xvQGdtYWlsLmNvbSJ9.xGUPWMR_cdnVOQhjZDfzVwnYJbALWFm53BXe-3l6ES8/1573965222\'>http//simplerest.co/login/change_pass/eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImlhdCI6MTU3MzM2MDQyMiwiZXhwIjoxNTczOTY1MjIyLCJlbWFpbCI6InBib3p6b2xvQGdtYWlsLmNvbSJ9.xGUPWMR_cdnVOQhjZDfzVwnYJbALWFm53BXe-3l6ES8/1573965222</a>', '2019-11-10 01:33:42', NULL),
(27, 'no_responder@simplerest.mapapulque.ro', 'No responder', 'pbozzolo@gmail.com', '   ', 'Cambio de contraseña', 'Para cambiar la contraseña siga el enlace:<br/><a href=\'http//simplerest.co/login/change_pass/eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImlhdCI6MTU3MzM2MDQ3NiwiZXhwIjoxNTczOTY1Mjc2LCJlbWFpbCI6InBib3p6b2xvQGdtYWlsLmNvbSJ9.nUmhdDsRUy2cr_kesmSOS_08ppv37M106MajsYXuPDo/1573965276\'>http//simplerest.co/login/change_pass/eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImlhdCI6MTU3MzM2MDQ3NiwiZXhwIjoxNTczOTY1Mjc2LCJlbWFpbCI6InBib3p6b2xvQGdtYWlsLmNvbSJ9.nUmhdDsRUy2cr_kesmSOS_08ppv37M106MajsYXuPDo/1573965276</a>', '2019-11-10 01:34:36', NULL),
(28, 'no_responder@simplerest.mapapulque.ro', 'No responder', 'Y@PEPE.COM', '   ', 'Cambio de contraseña', 'Para cambiar la contraseña siga el enlace:<br/><a href=\'http//simplerest.lan/login/change_pass/eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImlhdCI6MTU3MzM5MDY3NywiZXhwIjoxNTczOTk1NDc3LCJlbWFpbCI6IllAUEVQRS5DT00ifQ.W4Lsw0Pq8g_3Erm1NcgOmAcqIL4jla_uuAkTAvpItxo/1573995477\'>http//simplerest.lan/login/change_pass/eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImlhdCI6MTU3MzM5MDY3NywiZXhwIjoxNTczOTk1NDc3LCJlbWFpbCI6IllAUEVQRS5DT00ifQ.W4Lsw0Pq8g_3Erm1NcgOmAcqIL4jla_uuAkTAvpItxo/1573995477</a>', '2019-11-10 09:57:57', NULL),
(29, 'no_responder@simplerest.mapapulque.ro', 'No responder', 'Y@PEPE.COM', '   ', 'Cambio de contraseña', 'Para cambiar la contraseña siga el enlace:<br/><a href=\'http//simplerest.lan/login/change_pass/eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImlhdCI6MTU3MzM5MDc5NiwiZXhwIjoxNTczOTk1NTk2LCJlbWFpbCI6IllAUEVQRS5DT00ifQ.cZ38A0zXJrqykkCUUPIgDXMCIG8nx9lk40cr1IcIgLE/1573995596\'>http//simplerest.lan/login/change_pass/eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImlhdCI6MTU3MzM5MDc5NiwiZXhwIjoxNTczOTk1NTk2LCJlbWFpbCI6IllAUEVQRS5DT00ifQ.cZ38A0zXJrqykkCUUPIgDXMCIG8nx9lk40cr1IcIgLE/1573995596</a>', '2019-11-10 09:59:56', NULL),
(30, 'no_responder@simplerest.mapapulque.ro', 'No responder', 'Y@PEPE.COM', '   ', 'Cambio de contraseña', 'Para cambiar la contraseña siga el enlace:<br/><a href=\'http//simplerest.lan/login/change_pass/eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImlhdCI6MTU3MzM5MDg0NywiZXhwIjoxNTczOTk1NjQ3LCJlbWFpbCI6IllAUEVQRS5DT00ifQ.UWlpjpACiKQmtaNnNxyROyvlDhLdhXFHV-joPQOe1AI/1573995647\'>http//simplerest.lan/login/change_pass/eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImlhdCI6MTU3MzM5MDg0NywiZXhwIjoxNTczOTk1NjQ3LCJlbWFpbCI6IllAUEVQRS5DT00ifQ.UWlpjpACiKQmtaNnNxyROyvlDhLdhXFHV-joPQOe1AI/1573995647</a>', '2019-11-10 10:00:47', NULL),
(31, 'no_responder@simplerest.mapapulque.ro', 'No responder', 'Y@PEPE.COM', '   ', 'Cambio de contraseña', 'Para cambiar la contraseña siga el enlace:<br/><a href=\'http//simplerest.lan/login/change_pass/eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImlhdCI6MTU3MzM5MTUzNywiZXhwIjoxNTczOTk2MzM3LCJlbWFpbCI6IllAUEVQRS5DT00ifQ.zYtd94XaIEHFVrFLvWRK4v1Jhy2iePmQWIe_mMX4lho/1573996337\'>http//simplerest.lan/login/change_pass/eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImlhdCI6MTU3MzM5MTUzNywiZXhwIjoxNTczOTk2MzM3LCJlbWFpbCI6IllAUEVQRS5DT00ifQ.zYtd94XaIEHFVrFLvWRK4v1Jhy2iePmQWIe_mMX4lho/1573996337</a>', '2019-11-10 10:12:17', NULL),
(33, 'no_responder@simplerest.mapapulque.ro', 'No responder', 'nano@', '', 'Cambio de contraseña', 'Para cambiar la contraseña siga el enlace:<br/><a href=\'http//simplerest.co/login/change_pass/eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImlhdCI6MTU3MzM5MjQwMywiZXhwIjoxNTczOTk3MjAzLCJlbWFpbCI6Im5hbm9AIn0.80a1dBv5FjD9mj9q73G0x7TKjAvvrLRbnKVBgos_H44/1573997203\'>http//simplerest.co/login/change_pass/eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImlhdCI6MTU3MzM5MjQwMywiZXhwIjoxNTczOTk3MjAzLCJlbWFpbCI6Im5hbm9AIn0.80a1dBv5FjD9mj9q73G0x7TKjAvvrLRbnKVBgos_H44/1573997203</a>', '2019-11-10 10:26:43', NULL),
(34, 'no_responder@simplerest.mapapulque.ro', 'No responder', 'nano@', '', 'Cambio de contraseña', 'Para cambiar la contraseña siga el enlace:<br/><a href=\'http://simplerest.lan/login/change_pass/eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImlhdCI6MTU3MzM5MjQ3MiwiZXhwIjoxNTczOTk3MjcyLCJlbWFpbCI6Im5hbm9AIn0.5lDll9HUDGWIyXM3bKvCYl5Dt2yufQlHwFb9WPSm1-I/1573997272\'>http://simplerest.lan/login/change_pass/eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImlhdCI6MTU3MzM5MjQ3MiwiZXhwIjoxNTczOTk3MjcyLCJlbWFpbCI6Im5hbm9AIn0.5lDll9HUDGWIyXM3bKvCYl5Dt2yufQlHwFb9WPSm1-I/1573997272</a>', '2019-11-10 10:27:52', NULL),
(35, 'no_responder@simplerest.mapapulque.ro', 'No responder', 'GG@PEPE.COM', 'A AB', 'Confirmación de correo', 'Por favor confirme su correo siguiendo el enlace:<br/><a href=\'http://simplerest.lan/login/confirm_email/eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImlhdCI6MTU3MzQzNDUwOSwiZXhwIjoxNTc0MDM5MzA5LCJlbWFpbCI6IkdHQFBFUEUuQ09NIn0.O-2RVbZR3YG5KONtE5IuFoaQaWiNFeyr-j4BHJbA8yA/1574039309\'>http://simplerest.lan/login/confirm_email/eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImlhdCI6MTU3MzQzNDUwOSwiZXhwIjoxNTc0MDM5MzA5LCJlbWFpbCI6IkdHQFBFUEUuQ09NIn0.O-2RVbZR3YG5KONtE5IuFoaQaWiNFeyr-j4BHJbA8yA/1574039309</a>', '2019-11-10 22:08:29', NULL),
(36, 'no_responder@simplerest.mapapulque.ro', 'No responder', 'GGG@PEPE.COM', 'A AB', 'Confirmación de correo', 'Por favor confirme su correo siguiendo el enlace:<br/><a href=\'http://simplerest.lan/login/confirm_email/eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImlhdCI6MTU3MzQzNDUyMSwiZXhwIjoxNTc0MDM5MzIxLCJlbWFpbCI6IkdHR0BQRVBFLkNPTSJ9.Q5_KkbXyG4bk-EGZ0mpLAuH2FiRX5BppB_OO5nXojiI/1574039321\'>http://simplerest.lan/login/confirm_email/eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImlhdCI6MTU3MzQzNDUyMSwiZXhwIjoxNTc0MDM5MzIxLCJlbWFpbCI6IkdHR0BQRVBFLkNPTSJ9.Q5_KkbXyG4bk-EGZ0mpLAuH2FiRX5BppB_OO5nXojiI/1574039321</a>', '2019-11-10 22:08:41', NULL),
(37, 'no_responder@simplerest.mapapulque.ro', 'No responder', 'elpiojo2@', 'Ojo ', 'Confirmación de correo', 'Por favor confirme su correo siguiendo el enlace:<br/><a href=\'http://simplerest.lan/login/confirm_email/eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImlhdCI6MTU3MzQ4MzMzNywiZXhwIjoxNTc0MDg4MTM3LCJlbWFpbCI6ImVscGlvam8yQCJ9.VU330VhU0IF8AaM8vdoryRZ3PSt16q8IID4eQnc0cME/1574088137\'>http://simplerest.lan/login/confirm_email/eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImlhdCI6MTU3MzQ4MzMzNywiZXhwIjoxNTc0MDg4MTM3LCJlbWFpbCI6ImVscGlvam8yQCJ9.VU330VhU0IF8AaM8vdoryRZ3PSt16q8IID4eQnc0cME/1574088137</a>', '2019-11-11 11:42:17', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `other_permissions`
--

CREATE TABLE `other_permissions` (
  `id` int(11) NOT NULL,
  `folder_id` int(11) NOT NULL,
  `belongs_to` int(11) NOT NULL,
  `guest` tinyint(4) NOT NULL DEFAULT '0',
  `r` tinyint(4) NOT NULL,
  `w` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `other_permissions`
--

INSERT INTO `other_permissions` (`id`, `folder_id`, `belongs_to`, `guest`, `r`, `w`) VALUES
(1, 4, 90, 0, 0, 0),
(2, 5, 87, 0, 1, 1),
(4, 6, 90, 1, 1, 0),
(5, 9, 4, 1, 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `products`
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
  `active` tinyint(11) DEFAULT '1',
  `locked` tinyint(4) DEFAULT '0',
  `workspace` varchar(40) DEFAULT NULL,
  `belongs_to` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `size`, `cost`, `created_at`, `modified_at`, `deleted_at`, `active`, `locked`, `workspace`, `belongs_to`) VALUES
(100, 'Vodka', 'chino', '2 1/4 L', 137, '2019-07-04 00:00:00', '2019-11-04 16:45:10', '2019-11-04 16:45:10', 1, 1, '', 90),
(103, 'Juguito ric0', 'Delicious juic333333', '1 Litros', 150, '2019-09-13 00:00:00', '2019-11-07 00:19:19', '5019-12-05 21:30:01', 1, 0, NULL, 90),
(105, 'Agua mineral', 'De Córdoba', '1L', 525, '2019-03-15 00:00:00', '2019-10-22 22:01:58', '2019-10-23 19:30:16', 1, 0, 'lista publica', 90),
(106, 'Vodka', 'Rusiaaaaaa', '1L', 400, '2019-02-16 00:00:00', '2019-11-07 00:51:15', NULL, 1, 0, NULL, 4),
(113, 'Vodkaaaa', 'URU', '1L', 550, '2019-03-31 00:00:00', '2019-10-29 17:33:39', '2019-10-29 17:33:39', 1, 0, NULL, 86),
(114, 'AAABBBCCCcccD', 'cccccC', '29', 200, '2019-01-23 00:00:00', '2019-10-29 18:40:30', '2019-10-29 18:35:39', 1, 0, NULL, 4),
(119, 'CocaCola', 'gaseosa', '1L', 44, '2018-10-15 00:00:00', '2019-11-10 22:26:00', NULL, 1, 0, NULL, 90),
(120, 'MiBebida', 'Rica Rica', '1L', 100, '2018-12-23 00:00:00', '2019-11-03 21:37:48', '2019-10-16 21:44:17', 1, 0, NULL, 90),
(121, 'OtraBebida', 'wibjrbpklhbbkcshtqxd', '1L', 25, '2019-09-28 00:00:00', '2019-11-10 00:27:24', NULL, 1, 0, NULL, 90),
(122, 'Cerveza de malta', 'Pichu', '1L', 100, '2018-12-29 00:00:00', '2019-11-03 21:37:48', '2019-10-19 16:32:00', 1, 0, NULL, 90),
(123, 'PesiLoca', 'bebida cola', '2L', 30, '2018-12-16 00:00:00', '2019-11-10 20:47:37', '2019-11-10 20:47:37', 1, 0, 'mylist', 90),
(125, 'Vodka', 'Genial', '3L', 250, '2017-01-10 00:00:00', '2019-11-07 11:22:48', '2019-11-07 11:22:48', 1, 0, 'lista publica', 90),
(126, 'Uvas fermentadas', 'Espectacular', '5L', 300, '2019-06-24 00:00:00', '2019-10-14 22:39:51', '2019-10-16 21:43:47', 1, 0, 'lista publica', 90),
(127, 'Vodka venezolanoooooooooooo', 'del caribe', '1L', 100, '2019-07-12 00:00:00', '2019-11-04 12:08:36', '2019-11-04 12:02:20', 1, 1, NULL, 90),
(131, 'Vodkaaaabc', 'Rusia', '1L', 550, '2019-06-04 00:00:00', NULL, NULL, 1, 0, 'secreto', 4),
(132, 'Ron venezolano', 'Rico', '1L', 100, '2019-10-03 00:00:00', '2019-11-03 21:37:48', NULL, 1, 0, NULL, 90),
(133, 'Vodka venezolano', 'de Vzla', '1L', 100, '2019-09-19 00:00:00', '2019-11-03 21:37:48', NULL, 1, 0, NULL, 90),
(137, 'Agua ardiente', 'Si que arde!', '1L', 120, '2019-07-16 00:00:00', '2019-11-03 20:46:12', '2019-10-16 19:36:57', 1, 0, 'lista', 90),
(143, 'Agua ', '--', '1L', 100, '2019-06-03 00:00:00', '2019-11-03 21:37:48', '2019-10-16 21:44:20', 1, 0, NULL, 90),
(145, 'Juguito XII', 'de manzanas exprimidas', '1L', 350, '2019-02-09 00:00:00', NULL, '2019-10-23 15:58:37', 1, 0, 'lista24', 90),
(146, 'Wisky', NULL, '2L', 255, '2019-08-31 00:00:00', '2019-10-16 10:28:20', '2019-10-16 21:43:50', 1, 0, 'lista24', 90),
(147, 'Aqua fresh', 'Rico', '1L', 100, '2019-03-20 00:00:00', '2019-11-04 11:53:06', '2019-11-04 11:53:06', 1, 0, 'comparto', 90),
(148, 'Alcohol etílico', '', '1L', 100, '2019-04-21 00:00:00', '2019-11-03 21:37:48', '2019-10-16 21:44:24', 1, 0, 'comparto', 90),
(151, 'Juguito XIII', 'Rico', '1L', 355, '2019-10-03 00:00:00', '2019-10-15 17:00:58', '2019-10-23 14:42:24', 1, 0, 'lista24', 90),
(155, 'Super-jugo', 'BBB', '12', 100, '2019-09-22 00:00:00', '2019-11-04 17:00:18', '2019-11-04 17:00:18', 1, 0, NULL, 90),
(159, 'Agua minerale', 'De Cba', '2L', 90, '2019-10-14 18:08:45', '2019-11-07 11:22:03', NULL, 1, 0, NULL, 90),
(160, 'Limonada', 'Rica', '500ML', 100, '2019-10-23 14:05:30', '2019-11-04 13:19:08', '2019-11-04 13:19:08', 1, 0, NULL, 90),
(162, 'Juguito de Mabelita', 'de manzanas exprimidas', '2L', 150, '2019-10-25 08:36:26', '2019-11-05 21:36:25', NULL, 1, 0, NULL, 113),
(163, 'ABC', 'XYZ', '6L', 600, '2019-10-26 10:05:00', '2019-11-07 00:29:25', '2019-11-07 00:22:27', 1, 1, NULL, 1),
(164, 'AAA', 'BBB', '33L', 333, '2019-10-26 19:48:26', '2019-10-29 18:33:57', NULL, 1, 0, NULL, 112),
(165, 'ZZZ', 'zzz', '0.5L', 100, '2019-10-26 22:38:39', '2019-11-04 13:04:26', '2019-11-04 13:04:26', 1, 0, NULL, 90),
(166, 'UUU', 'uuu uuu uu u', '0.5L', 100, '2019-10-26 22:38:39', '2019-11-04 12:57:49', '2019-11-04 12:57:49', 1, 1, NULL, 90),
(167, 'JA JA', 'diverttido', '10L', 100, '2019-11-02 08:14:46', '2019-11-03 23:16:17', '2019-11-03 23:16:17', 1, 1, NULL, 90),
(169, 'Clavos de techo', 'largos', '12 cm', 25, '2019-11-02 16:06:31', '2019-11-03 20:46:12', NULL, 1, 0, NULL, 125),
(170, 'Escalera', 'para electricista', '2 metros', 200, '2019-11-02 16:07:10', NULL, NULL, 1, 0, NULL, 125),
(171, 'Ruedas', 'cochecito', '', 50, '2019-11-02 16:07:51', '2019-11-03 20:46:12', NULL, 1, 0, NULL, 125),
(172, 'Clavos para madera', 'bronce', '2.5 cm', 10, '2019-11-02 16:08:35', '2019-11-03 20:46:12', NULL, 1, 0, NULL, 125),
(173, 'Escalera pintor', '', '5 metros', 80, '2019-11-02 20:41:55', '2019-11-03 20:46:12', NULL, 1, 0, NULL, 125),
(174, 'Caja de herramientas', 'Metal', '', 90, '2019-11-02 20:42:52', '2019-11-03 20:46:12', NULL, 1, 0, NULL, 125),
(175, 'Caja de herramientas', 'Plastico', '', 30, '2019-11-02 20:43:14', '2019-11-03 20:46:12', NULL, 1, 0, NULL, 125),
(176, 'Alambre', 'Precio por kilo', '1 mm', 400, '2019-11-02 20:44:28', NULL, NULL, 1, 0, NULL, 125),
(177, 'Cable de 2 hilos telefónico', 'Por metro', '', 20, '2019-11-02 20:45:10', '2019-11-03 20:46:12', NULL, 1, 0, 'electricos', 125),
(178, 'Agua destilada', '', '1L', 30, '2019-11-02 20:46:05', '2019-11-03 20:46:12', NULL, 1, 0, NULL, 125),
(179, 'Agua mineral', '', '1L', 10, '2019-11-02 20:46:20', '2019-11-03 20:46:12', NULL, 1, 0, NULL, 125),
(180, 'Pintura blanca exteriores', '', '5L', 200, '2019-11-02 21:19:06', NULL, NULL, 1, 0, NULL, 125),
(181, 'Pintura blanca exteriores', '', '2L', 100, '2019-11-02 21:19:22', '2019-11-03 20:46:12', NULL, 1, 0, NULL, 125),
(182, 'Pintura blanca interiores', '', '2L', 80, '2019-11-02 21:20:00', '2019-11-03 20:46:12', NULL, 1, 0, NULL, 125),
(183, 'Tuercas', '', '', 0, '2019-11-03 21:33:20', NULL, NULL, 1, 0, NULL, 125),
(184, 'AA', 'BB', '2L', 23, '2019-11-03 23:54:40', NULL, NULL, 1, 0, NULL, 131),
(185, 'ABC', '', '', 0, '2019-11-03 23:55:18', NULL, NULL, 1, 0, NULL, 132),
(186, 'Toma-corrientes hembra pared', 'color: blanco', '', 20, '2019-11-04 09:26:55', NULL, NULL, 1, 0, 'electricos', 125),
(187, 'Crush', '', '1L', 20, '2019-11-04 13:06:04', '2019-11-04 13:18:30', '2019-11-04 13:18:30', 1, 1, NULL, 90),
(188, 'AA', 'cv', '', 0, '2019-11-04 17:03:42', '2019-11-09 22:00:49', '2019-11-09 22:00:49', 1, 1, NULL, 48),
(189, 'AAAAAAAAAAAAAaaaaa', '', '', 0, '2019-11-04 17:04:51', '2019-11-04 17:05:00', '2019-11-04 17:05:00', 1, 0, NULL, 87),
(190, 'AA', 'BB', '', 0, '2019-11-05 21:04:15', NULL, NULL, 1, 0, NULL, 135),
(191, 'Wisky', '', '1L', 100, '2019-11-05 21:36:40', '2019-11-10 11:54:03', '2019-11-10 11:54:03', 1, 0, NULL, 113),
(192, 'Jugo Naranjin', 'Delicious juicEEEE', '1 L', 350, '2019-11-06 23:45:29', NULL, NULL, 1, 0, NULL, 4),
(193, 'Re-Jugo', 'Delicious juicEEEEXXX', '1 L', 350, '2019-11-07 00:18:25', NULL, NULL, 1, 0, NULL, 4),
(194, 'Re-Jugo', 'Delicious juicEEEEXXXYZ', '1 L', 350, '2019-11-07 00:20:53', NULL, NULL, 1, 0, NULL, 4),
(195, 'Boo', 'Delicious juicEEEEXXXYZ4444444444444444444444444', '1 L', 350, '2019-11-07 01:31:43', NULL, NULL, 1, 0, NULL, 4),
(196, 'AAAAAAA', 'TTTTTTT', '', 0, '2019-11-07 22:58:51', NULL, NULL, 1, 0, NULL, 137),
(197, 'HEYYYYYY', '', '', 0, '2019-11-10 00:57:51', NULL, NULL, 1, 0, NULL, 150),
(198, 'AAAAA', NULL, '', 22, '2019-11-11 10:38:19', NULL, NULL, 1, 0, NULL, 90),
(199, 'cuzbgmbhiudjqvrmzwqf', 'Esto es una prueba', '1L', 66, '2019-11-11 10:42:57', NULL, NULL, 1, 0, NULL, 90),
(200, 'AAA', '', '', 0, '2019-11-11 11:44:51', NULL, NULL, 1, 0, NULL, 156),
(201, 'vzukvnjjhzintijexhjd', 'Esto es una prueba', '1L', 66, '2019-11-11 11:48:37', NULL, NULL, 1, 0, NULL, 90),
(202, 'VVVBBBC', '', '', 0, '2019-11-11 11:59:23', '2019-11-11 12:39:24', NULL, 1, 0, NULL, 148);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(60) NOT NULL,
  `confirmed_email` tinyint(4) NOT NULL DEFAULT '0',
  `firstname` varchar(50) DEFAULT NULL,
  `lastname` varchar(80) DEFAULT NULL,
  `password` varchar(60) DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `belongs_to` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `confirmed_email`, `firstname`, `lastname`, `password`, `deleted_at`, `belongs_to`) VALUES
(1, 'boctulus1@gmail.com', 1, '', '0', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 0),
(4, 'pbozzolo@gmail.com', 1, 'Paulinoxxx', 'Bozzoxx', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 0),
(5, 'pepe@gmail.com', 1, 'Pepe', 'Gonzalez', '$2y$10$J.KPjyFukfxcKg83TvQGaeCTrLN9XyYXTgtTDZdZ91DJTdE73VIDK', NULL, 1),
(9, 'dios@gmail.com', 1, 'Paulinoxxxxxyyz', 'Bozzoxx000555zZ', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 0),
(11, 'diosdado@gmail.com', 1, 'Sr', 'Z', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 0),
(13, 'diosdado2@gmail.com', 1, 'Sr', 'D', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 13),
(14, 'juancho@aaa.com', 1, 'Juan', 'Perez', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 0),
(15, 'juancho11@aaa.com', 1, 'Juan XI', 'Perez 10', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 0),
(16, 'mabel@aaa.com', 1, 'Mabel', 'S', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 0),
(17, 'a@b.commmm', 1, 'Nicos', 'Buzzi', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 17),
(20, 'a@b.commmmmmmmmmmm', 1, 'Nicos', 'AAA', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 0),
(34, 'peter@abc.com', 1, 'Nicos', 'Buzzi', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 0),
(36, 'ndrrxdjrtewwrxdhgxwbpeter@abc.com', 1, 'Nicos', 'Buzzi', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', '2019-10-28 17:15:32', 0),
(37, 'xjzrzfiibkjvdeczoeeepeter@abc.com', 1, 'Nicos', 'Buzzi', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', '2019-10-28 17:15:45', 0),
(38, 'udcsoqjyrdgnhqqtukhupeter@abc.com', 1, 'Nicos', 'Buzzi', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 0),
(39, 'qbosmfvwezohbutpifbopeter@abc.com', 1, 'Nicos', 'Buzzi', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 0),
(40, 'gjappgiduiqczagnousspeter@abc.com', 1, 'Nicos', 'Buzzi', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 0),
(41, 'ymcshlekdzhugvmwbjpipeter@abc.com', 1, 'Nicos', 'Buzzi', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 0),
(43, 'vydqkgqszpncijwhxeiapeter@abc.com', 1, 'Nicos', 'Buzzi', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 0),
(44, 'itbrknzsfnawnhxgmockpeter@abc.com', 1, 'Nicos', 'Buzzi', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 0),
(45, 'cproifnsfxvkxtppbgdupeter@abc.com', 1, 'Nicos', 'Buzzi', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 0),
(46, 'xxxxxxxxxxxxxxxxyz@abc.com', 1, 'Nicolayyyy', 'Buzzi', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 0),
(47, 'atlsqcgxgszbpcrzydykpeter@abc.com', 1, 'Nicos', 'Buzzi', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', '2019-11-07 08:12:33', 0),
(48, 'gates@outlook.com', 1, 'Bill', 'Gates', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 0),
(51, 'kkk@bbbbbb.com', 1, 'Sr K', 'NS/NC', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 0),
(52, 'tito@gmail.com', 1, 'Tito', 'El Grande', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 0),
(53, 'ooooiiii@gmail.com', 1, 'Oooo', 'iiiiiiii', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 0),
(54, 'booooiiii@gmail.com', 1, 'AAA', 'BBB', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 0),
(55, 'iooobooooiiii@gmail.com', 1, 'IIoo', 'ahaha', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 0),
(56, 'iooobooooiiioooi@gmail.com', 1, 'IIoo', 'ahaha', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 0),
(57, 'iooobooooiiioooi@gmail.commmm', 1, 'IIoo', 'ahaha', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 0),
(58, 'kkk@bbbbbb.commmm', 1, 'Sr K', 'NS/NC', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 0),
(59, 'kkkbooooiiii@gmail.com', 1, 'Ooookkk', 'kkkk', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 0),
(60, 'aaa@bbbb.com', 1, 'Jjjj', 'kkk', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 0),
(61, 'aaa@bbbb.commmm', 1, 'Jjjj', 'kkk', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 0),
(62, 'kkk@bbbbbb.commmmmmm', 1, 'Sr K', 'NS/NC', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 0),
(63, 'aaa@bbbb.commmmmmm', 1, 'Jjjj', 'kkk', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 0),
(64, 'booooiiiixxxx@gmail.com', 1, 'xxx', 'xxxxxxxxxx', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 0),
(65, 'aaa@bbbb.commmmmmmuuuuu', 1, 'Jjjj', 'kkk', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 0),
(66, 'aaa@dgdgd.cococ', 1, 'ajajaj', 'ajajaj', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 0),
(67, 'booooiiiferfr@gmail.com', 1, 'BillY', 'GGGG', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 0),
(68, 'aaa@dgdgd.cococo', 1, 'ajajaj', 'ajajaj', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 0),
(69, 'test@gmail.com', 1, 'TEST', '---', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 0),
(70, 'kkk@bbJJJJJJJ', 1, 'Sr K', 'NS/NC', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 0),
(72, 'aie@b.c', 1, 'Sr K', 'NS/NC', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 0),
(73, 'mabelf450@gmail.com', 1, 'Mabel', 'F', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 0),
(74, 'abc@def.com', 1, 'Sr K', 'NS/NC', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 0),
(75, 'abc@def.commm', 1, 'Sr K', 'NS/NC', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 0),
(76, 'abc@def.net', 1, 'Sr K', 'NS/NC', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 0),
(77, 'abc@def.co', 1, 'Sr K', 'NS/NC', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 0),
(78, 'abc@def.cox', 1, 'Sr K', 'NS/NC', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 0),
(79, 'feli@', 1, 'Sr K', 'NS/NC', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 0),
(80, 'feli@casa', 1, 'Sr K', 'NS/NC', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 0),
(81, 'feli@casa.com', 1, 'Sr K', 'NS/NC', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 0),
(82, 'feli@casa.net', 1, 'Sr K', 'NS/NC', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 0),
(83, 'feli@casa.neto', 1, 'Sr K', 'NS/NC', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 0),
(84, 'pablo@', 1, 'Nicos', 'AAA', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 0),
(85, 'feli@teamo', 1, 'Felipe', 'Bozzolo', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 0),
(86, 'nuevo@gmail.com', 1, 'Norberto', 'Nullo', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 0),
(87, 'pedro@gmail.com', 1, 'Pedro', 'Picapiedras', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 0),
(88, 'feli@abc', 1, 'Felipe', 'Bozzzolo', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 0),
(89, 'h@', 1, 'Sr H', 'J', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 0),
(90, 'nano@', 1, 'NA', 'NA', '$2y$10$qmCo8ZeT1XJWPZ1kuSeFjuj7rEDT9J/YDV4yD3BsVoE.pz0ryfhE2', NULL, 90),
(102, 'feli@delacasita', 1, 'Sr K', 'NS/NC', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 102),
(103, 'feli@delacasita2', 1, 'Sr K', 'NS/NC', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 103),
(104, 'feli@delacasita5', 1, 'Sr K', 'NS/NC', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 104),
(105, 'feli@delacasita50', 1, 'Sr K', 'NS/NC', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 105),
(106, 'feli@delacasita50000', 1, 'Sr K', 'NS/NC', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 106),
(107, 'feli@delacasita50000700', 1, 'Sr K', 'NS/NC', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 107),
(108, 'feli@delacasita50000700800', 1, 'Sr K', 'NS/NC', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 108),
(109, 'feli@compeon', 1, 'Sr K', 'NS/NC', '$2y$10$ocJZwzelZ3W.3Eas5ig/q.qaBm79ottSuJ8ee2wXv9584INHL2RpW', NULL, 109),
(110, 'feli@compeon_mundial', 1, 'Sr K', 'NS/NC', '$2y$10$k4kFWXmQacW4LDS.j4gVk./LqKPUtOc9XaObNWojsAzmFnIxbLZ8u', NULL, 110),
(111, 'feli@compeon_dios', 1, 'Sr K', 'NS/NC', '$2y$10$nYh5nGXM6mVwwAP93G4Nv.m8P8aQmJKLm5fODQBqdmzzSCEZzDiOC', NULL, 111),
(112, 'superpepe@', 1, 'Super Pepe', '', '$2y$10$hITAKY1zsMPMIe0KCO6YuuG5Xke8FeZlw00Uw1Mz4J57LU0lLfvja', NULL, 112),
(113, 'mabelsusanaf@gmail.com', 1, NULL, NULL, NULL, NULL, 113),
(114, 'xyz@', 1, 'XYZ', 'ZZZ', '$2y$10$mMaJuMmAt7hgTo8KljlDRuavoOg4F0ZGw.hAK/vmpNQJ0xvA/ZEmG', NULL, NULL),
(115, 'x@', 1, 'Sr K', 'NS/NC', '$2y$10$K64bJLscpFCH7Geal..vhuKeMzZOP9MWD21eXPj7VpfYiHw4AaEMC', NULL, NULL),
(116, 'xy@', 1, 'Sr K', 'NS/NC', '$2y$10$86ztfpWglgqvK/MyJf12VOYY8rqjJ4Qhi1XhpOsNW0u.BMM3odtUG', NULL, NULL),
(117, 'aaa@', 1, 'A', 'AB', '$2y$10$KP8rEs5DracAVvcdMY/ATuB3xwEz7Rjwqj5DilPiszMi8wMRKNAhK', NULL, NULL),
(118, 'aaaC@', 1, 'A', 'AB', '$2y$10$T59OjL8Rxb/QZeArKW.PK.GVVG7V3Ao846KSimwt6xGbf7tx0oik.', NULL, NULL),
(119, 'aaaCD@', 1, 'A', 'AB', '$2y$10$SvAYgvxsszn1Z/cfMW/w9eopOcih3CzADDEDd2q0wKwqwTBWVPZoi', NULL, 119),
(120, 'xyza@', 1, 'XYZA', 'ZZZA', '$2y$10$rp40xaVPNtHqSsDYXVBNCespVSLBpvwdp1oyV7NY6nJk011q2/Iri', NULL, 120),
(121, 'nono@', 1, 'Nono', 'viecco', '$2y$10$W5HaPfOxbAE9rRb04XCzeO/sS0tlHQ4ZTnmXzZPnd2s1qZt26OFdS', NULL, 121),
(122, 'sss@', 1, 'Regular', 'Tester', '$2y$10$8FiysajEjA0.5eyXuLNUZeqQkUBgxCYVonwk1Z5k4rTf/rPMLu.2y', NULL, 122),
(123, 'ppp@', 1, 'Regular', 'Tester', '$2y$10$J3C1J2pqvJbuQXEekf6vf.AL0lEJbpGrhPtZCujXRfyv16nEzQvdm', NULL, 123),
(124, 'w@', 1, 'AA', 'BB', '$2y$10$899qRrlzAXbnyE/5CHLZVezdK9beIDJqrUmb/TcdgepkPHYTMLJTK', NULL, 124),
(125, 'tester3@', 1, 'Tester', '', '$2y$10$u5L16gB3HEQUROjNFBbrd.KslpcM4J6ref0k/cUcrWZcTolZZZfa.', NULL, 125),
(126, 'jk@', 1, 'J', 'K', '$2y$10$irie63zGURJ/JQiJuyph/uVkVmAJRiSvvDRGaBokyvVLpi8tqganW', NULL, NULL),
(127, 'aaaCDEF@', 1, 'A', 'AB', '$2y$10$juP31/p3B.P7F/b2MXxGF.kiN/HG1zwIyehkNWjef5yhFYSGq.YwC', NULL, NULL),
(128, 'aaaCDEFG@', 1, 'A', 'AB', '$2y$10$nkIhbPrL4Y/oJJOe6JdOO.57U8Njn57IRM5cYG7FtPDA2jffOsFNm', NULL, NULL),
(129, 'aaaCDEFGI@', 1, 'A', 'AB', '$2y$10$VeyVsSP/.2SgfBJq25FXIOPq2iXceFiEgRiEVuOTi5oPIMWc8Vteq', NULL, NULL),
(130, 'aaaCDEFGIJK@', 1, 'A', 'AB', '$2y$10$ann6qT5V/SkaYk/InT27ouVPhrkNaVOlvwgY2nf27lhlT5hm0hnTK', NULL, 130),
(131, 'aaaCDEFGIJKLM@', 1, 'A', 'AB', '$2y$10$N2hoKh4E9.aYnAPmMPuSb.mgkLAJA1mB0pAnpjsLRU7DuyX1.b1ZC', NULL, 131),
(132, 'tt@', 1, 'T', 'TT', '$2y$10$qXJ25mY64hef.47EjJkBNeYDgG.zHNH8QsodGK3OW13EYQdpaifFG', NULL, 132),
(133, 'jc@', 1, 'Juan', 'Carlos', '$2y$10$gMgqAHr1A5.phZ2/n.RuVuJEM8QnfPVAiTGMVqvCOf7mlWXrGNjXK', NULL, 133),
(135, 'boctulus@gmail.com', 1, 'Pablo', 'Bozzolo', NULL, NULL, 135),
(136, '', 1, 'Bill', 'O', '$2y$10$GHiCUTrFu01EiVcVgTvRluGweRBx8rF6V2qgbhNH82Oi86ATE0RO2', NULL, 136),
(137, 'san@', 1, 'San', 'Pepe', '$2y$10$OVUu5rTU1JPSTv2HeXq/puWq86vZT24VAuYTW.sAT.pqzxZEJPFou', NULL, 137),
(138, 'j@', 1, 'AJ', 'J', '$2y$10$TOUciinPf3DEBTjALBLnaOhMLzHrwBRkvczOWYD5OsZOy9aVGVfnC', NULL, 138),
(139, 'aaaCDEFGIJKLMSSS@', 1, 'A', 'AB', '$2y$10$0YDH5aE9l3lQwxFp25bLXepmGQF8fM4XcK4zt/lNzS/2.M7mVgz4S', NULL, 139),
(140, 'ZZZ@', 1, 'A', 'AB', '$2y$10$QosozVg0npjSEimSeOZ74OPyXXZO0SwPsZevPOJlQ0GnHDK7DcsR6', NULL, 140),
(141, 'ZZZ@PEPE.com', 1, 'A', 'AB', '$2y$10$XIpys1..n4XcQ9CWP89vce9j7NeAiMYYeOqonYtGgUi.9nZcaccB2', NULL, 141),
(142, 'A@PEPE.COM', 1, 'A', 'AB', '$2y$10$Fn5Wkt8masdaOdqBHIaU0uDuRbi7mEVDSzlHgAK8wfJSesSlQWAZu', NULL, 142),
(143, 'Ab@PEPE.COM', 1, 'A', 'AB', '$2y$10$ehfF6Uwrvdl3NoqYkqhnIexzzCX49pCdbEXOYKrI1O2jsvdHLEpCa', NULL, 143),
(147, 'ABCDEF@PEPE.COM', 1, 'A', 'AB', '$2y$10$67kVEbzj5C7eGl8b9f01jeMvbU6Cy2xGYADhYl5PvMmUAXAenmmb2', NULL, 147),
(148, 'ABCDEFG@PEPE.COM', 1, 'A', 'AB', '$2y$10$p/KGmzQsMmlDDJlYmhr9GOUmkcO4A.A8sefXd7bN3GthEIEA4YCSO', NULL, 148),
(149, 'X@PEPE.COM', 1, 'A', 'AB', '$2y$10$R9EtFdhCWUjMMFkLZxLiA.8XJ2hd2SE8Q3eDuyUa8KOV0TsTGNRX.', NULL, 149),
(150, 'Y@PEPE.COM', 1, 'A', 'AB', '$2y$10$WeO4iScCcFe/yV4d7iUbFeYOfTR6H3TGDjunJLskrJg/.9raTP7IG', NULL, 150),
(151, 'F@PEPE.COM', 1, 'A', 'AB', '$2y$10$LDtPjcxYtrwMVfae.8MsNuRaoRgsnVtagH2fbYG3m3MIYOeAWB4fC', NULL, 151),
(152, 'G@PEPE.COM', 1, 'A', 'AB', '$2y$10$TY9RyLYn.E24qAeBn9LCzuy8bRT1/UReSDDodfdHouQ7jRN6GqMTq', NULL, 152),
(153, 'GG@PEPE.COM', 1, 'A', 'AB', '$2y$10$qt79FFFbCbQNya/a8fMMn.tcstyJYUHRXIw36pGDTLExLueDKbJby', NULL, 153),
(154, 'GGG@PEPE.COM', 1, 'A', 'AB', '$2y$10$bIqxFs1bOo.CkImQj5t26.R6fnp5UO8913B5NkxNDy5gP0bhNoP2O', NULL, 154),
(155, 'elpiojo@', 1, 'Ojo', '', '$2y$10$UvEXPDfjiZ/PPits.tThwurSYCz844ZMh9mCVBC5Y9s7Hp8KwlR.2', NULL, 155),
(156, 'elpiojo2@', 1, 'Ojo', '', '$2y$10$aPWXX4L3MNnPcz0Q.zLFRO97ChkGYQoUdyvk.RzVDuwRaQDiMxkUS', NULL, 156);

-- --------------------------------------------------------

--
-- Table structure for table `user_roles`
--

CREATE TABLE `user_roles` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `modification_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `user_roles`
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
(69, 148, 3, '2019-11-11 11:58:20', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `folders`
--
ALTER TABLE `folders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `resource_table` (`resource_table`,`value`,`belongs_to`),
  ADD KEY `owner` (`belongs_to`);

--
-- Indexes for table `group_permissions`
--
ALTER TABLE `group_permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `folder_id` (`folder_id`,`member`),
  ADD KEY `member` (`member`),
  ADD KEY `belongs_to` (`belongs_to`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `other_permissions`
--
ALTER TABLE `other_permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `folder_id` (`folder_id`),
  ADD KEY `belongs_to` (`belongs_to`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `created_by` (`belongs_to`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `belongs_to` (`belongs_to`);

--
-- Indexes for table `user_roles`
--
ALTER TABLE `user_roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id_2` (`user_id`,`role_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `role_id` (`role_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `folders`
--
ALTER TABLE `folders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `group_permissions`
--
ALTER TABLE `group_permissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `other_permissions`
--
ALTER TABLE `other_permissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=203;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=157;

--
-- AUTO_INCREMENT for table `user_roles`
--
ALTER TABLE `user_roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=70;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `folders`
--
ALTER TABLE `folders`
  ADD CONSTRAINT `folders_ibfk_1` FOREIGN KEY (`belongs_to`) REFERENCES `users` (`id`);

--
-- Constraints for table `group_permissions`
--
ALTER TABLE `group_permissions`
  ADD CONSTRAINT `group_permissions_ibfk_1` FOREIGN KEY (`folder_id`) REFERENCES `folders` (`id`),
  ADD CONSTRAINT `group_permissions_ibfk_2` FOREIGN KEY (`member`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `group_permissions_ibfk_3` FOREIGN KEY (`belongs_to`) REFERENCES `users` (`id`);

--
-- Constraints for table `other_permissions`
--
ALTER TABLE `other_permissions`
  ADD CONSTRAINT `other_permissions_ibfk_1` FOREIGN KEY (`folder_id`) REFERENCES `folders` (`id`),
  ADD CONSTRAINT `other_permissions_ibfk_2` FOREIGN KEY (`belongs_to`) REFERENCES `users` (`id`);

--
-- Constraints for table `user_roles`
--
ALTER TABLE `user_roles`
  ADD CONSTRAINT `user_roles_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

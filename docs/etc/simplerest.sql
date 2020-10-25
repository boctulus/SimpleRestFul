-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Oct 25, 2020 at 06:09 PM
-- Server version: 5.7.31-0ubuntu0.18.04.1
-- PHP Version: 7.4.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `az`
--

-- --------------------------------------------------------

--
-- Table structure for table `collections`
--

CREATE TABLE `collections` (
  `id` int(11) NOT NULL,
  `entity` varchar(80) NOT NULL,
  `refs` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `belongs_to` int(11) NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `collections`
--

INSERT INTO `collections` (`id`, `entity`, `refs`, `belongs_to`, `created_at`) VALUES
(9, 'users', '[90,48]', 90, '0000-00-00 00:00:00'),
(11, 'messages', '[195,196]', 332, '2020-10-17 10:19:03'),
(12, 'messages', '[195,196]', 332, '2020-10-17 10:19:25'),
(13, 'messages', '[195,196]', 332, '2020-10-17 10:19:53'),
(14, 'messages', '[195,196]', 332, '2020-10-17 10:21:16'),
(15, 'messages', '[195,196]', 332, '2020-10-17 10:21:42'),
(16, 'messages', '[195,196]', 332, '2020-10-17 10:23:10'),
(17, 'messages', '[195,196]', 332, '2020-10-17 10:26:37'),
(18, 'messages', '[195,196]', 332, '2020-10-17 10:26:41'),
(19, 'messages', '[195,196]', 332, '2020-10-17 10:27:01'),
(20, 'messages', '[195,196]', 332, '2020-10-17 10:27:31'),
(21, 'messages', '[195,196]', 332, '2020-10-17 10:27:42'),
(22, 'messages', '[195,196]', 332, '2020-10-17 10:27:59'),
(23, 'messages', '[195,196]', 332, '2020-10-17 10:28:04'),
(24, 'messages', '[195,196]', 332, '2020-10-17 10:28:10'),
(25, 'messages', '[195,196]', 332, '2020-10-17 10:28:59'),
(26, 'messages', '[195,196]', 332, '2020-10-17 10:28:59'),
(27, 'messages', '[195,196]', 332, '2020-10-17 10:28:59'),
(28, 'messages', '[195,196]', 332, '2020-10-17 10:28:59'),
(29, 'messages', '[195,196]', 332, '2020-10-17 10:28:59'),
(30, 'messages', '[195,196]', 332, '2020-10-17 10:28:59'),
(31, 'messages', '[195,196]', 332, '2020-10-17 10:28:59'),
(32, 'messages', '[195,196]', 332, '2020-10-17 10:28:59'),
(33, 'messages', '[195,196]', 332, '2020-10-17 10:28:59'),
(34, 'messages', '[195,196]', 332, '2020-10-17 10:28:59'),
(35, 'messages', '[195,196]', 332, '2020-10-17 10:29:00'),
(36, 'messages', '[195,196]', 332, '2020-10-17 10:29:00'),
(37, 'messages', '[195,196]', 332, '2020-10-17 10:29:00'),
(38, 'messages', '[195,196]', 332, '2020-10-17 10:29:00'),
(39, 'messages', '[195,196]', 332, '2020-10-17 10:29:00'),
(40, 'messages', '[195,196]', 332, '2020-10-17 10:29:00'),
(41, 'messages', '[195,196]', 332, '2020-10-17 10:29:00'),
(42, 'messages', '[195,196]', 332, '2020-10-17 10:29:00'),
(43, 'messages', '[195,196]', 332, '2020-10-17 10:29:00'),
(44, 'messages', '[195,196]', 332, '2020-10-17 10:29:00'),
(45, 'messages', '[195,196]', 332, '2020-10-17 10:29:00'),
(46, 'messages', '[195,196]', 332, '2020-10-17 10:29:00'),
(47, 'messages', '[195,196]', 332, '2020-10-17 10:29:00'),
(48, 'messages', '[195,196]', 332, '2020-10-17 10:29:00'),
(49, 'messages', '[195,196]', 332, '2020-10-17 10:29:00'),
(50, 'messages', '[195,196]', 332, '2020-10-17 10:29:00'),
(51, 'messages', '[195,196]', 332, '2020-10-17 10:29:00'),
(52, 'messages', '[195,196]', 332, '2020-10-17 10:29:00'),
(53, 'messages', '[195,196]', 332, '2020-10-17 10:29:00'),
(54, 'messages', '[195,196]', 332, '2020-10-17 10:29:00'),
(55, 'messages', '[195,196]', 332, '2020-10-17 10:29:00'),
(56, 'messages', '[195,196]', 332, '2020-10-17 10:29:00'),
(57, 'messages', '[195,196]', 332, '2020-10-17 10:29:00'),
(58, 'messages', '[195,196]', 332, '2020-10-17 10:29:00'),
(59, 'messages', '[195,196]', 332, '2020-10-17 10:29:00'),
(60, 'messages', '[195,196]', 332, '2020-10-17 10:29:00'),
(61, 'messages', '[195,196]', 332, '2020-10-17 10:29:00'),
(62, 'messages', '[195,196]', 332, '2020-10-17 10:29:00'),
(63, 'messages', '[195,196]', 332, '2020-10-17 10:29:00'),
(64, 'messages', '[195,196]', 332, '2020-10-17 10:29:00'),
(65, 'messages', '[195,196]', 332, '2020-10-17 10:29:00'),
(66, 'messages', '[195,196]', 332, '2020-10-17 10:29:00'),
(67, 'messages', '[195,196]', 332, '2020-10-17 10:29:00'),
(68, 'messages', '[195,196]', 332, '2020-10-17 10:29:00'),
(69, 'messages', '[195,196]', 332, '2020-10-17 10:29:00'),
(70, 'messages', '[195,196]', 332, '2020-10-17 10:29:00'),
(71, 'messages', '[195,196]', 332, '2020-10-17 10:29:00'),
(72, 'messages', '[195,196]', 332, '2020-10-17 10:29:00'),
(73, 'messages', '[195,196]', 332, '2020-10-17 10:29:01'),
(74, 'messages', '[195,196]', 332, '2020-10-17 10:29:01'),
(75, 'messages', '[195,196]', 332, '2020-10-17 10:29:01'),
(76, 'messages', '[195,196]', 332, '2020-10-17 10:29:01'),
(77, 'messages', '[195,196]', 332, '2020-10-17 10:29:01'),
(78, 'messages', '[195,196]', 332, '2020-10-17 10:29:01'),
(79, 'messages', '[195,196]', 332, '2020-10-17 10:29:01'),
(80, 'messages', '[195,196]', 332, '2020-10-17 10:29:01'),
(81, 'messages', '[195,196]', 332, '2020-10-17 10:29:01'),
(82, 'messages', '[195,196]', 332, '2020-10-17 10:29:01'),
(83, 'messages', '[195,196]', 332, '2020-10-17 10:29:01'),
(84, 'messages', '[195,196]', 332, '2020-10-17 10:29:01'),
(85, 'messages', '[195,196]', 332, '2020-10-17 10:29:01'),
(86, 'messages', '[195,196]', 332, '2020-10-17 10:29:01'),
(87, 'messages', '[195,196]', 332, '2020-10-17 10:29:01'),
(88, 'messages', '[195,196]', 332, '2020-10-17 10:29:01'),
(89, 'messages', '[195,196]', 332, '2020-10-17 10:29:01'),
(90, 'messages', '[195,196]', 332, '2020-10-17 10:29:01'),
(91, 'messages', '[195,196]', 332, '2020-10-17 10:29:01'),
(92, 'messages', '[195,196]', 332, '2020-10-17 10:29:01'),
(93, 'messages', '[195,196]', 332, '2020-10-17 10:29:01'),
(94, 'messages', '[195,196]', 332, '2020-10-17 10:29:01'),
(95, 'messages', '[195,196]', 332, '2020-10-17 10:29:01'),
(96, 'messages', '[195,196]', 332, '2020-10-17 10:29:01'),
(97, 'messages', '[195,196]', 332, '2020-10-17 10:29:01'),
(98, 'messages', '[195,196]', 332, '2020-10-17 10:29:01'),
(99, 'messages', '[195,196]', 332, '2020-10-17 10:29:01'),
(100, 'messages', '[195,196]', 332, '2020-10-17 10:29:01'),
(101, 'messages', '[195,196]', 332, '2020-10-17 10:29:01'),
(102, 'messages', '[195,196]', 332, '2020-10-17 10:29:01'),
(103, 'messages', '[195,196]', 332, '2020-10-17 10:29:01'),
(104, 'messages', '[195,196]', 332, '2020-10-17 10:29:01'),
(105, 'messages', '[195,196]', 332, '2020-10-17 10:29:01'),
(106, 'messages', '[195,196]', 332, '2020-10-17 10:29:01'),
(107, 'messages', '[195,196]', 332, '2020-10-17 10:29:01'),
(108, 'messages', '[195,196]', 332, '2020-10-17 10:29:01'),
(109, 'messages', '[195,196]', 332, '2020-10-17 10:29:01'),
(110, 'messages', '[195,196]', 332, '2020-10-17 10:29:01'),
(111, 'messages', '[195,196]', 332, '2020-10-17 10:29:02'),
(112, 'messages', '[195,196]', 332, '2020-10-17 10:29:02'),
(113, 'messages', '[195,196]', 332, '2020-10-17 10:29:02'),
(114, 'messages', '[195,196]', 332, '2020-10-17 10:29:02'),
(115, 'messages', '[195,196]', 332, '2020-10-17 10:29:02'),
(116, 'messages', '[195,196]', 332, '2020-10-17 10:29:02'),
(117, 'messages', '[195,196]', 332, '2020-10-17 10:29:02'),
(118, 'messages', '[195,196]', 332, '2020-10-17 10:29:02'),
(119, 'messages', '[195,196]', 332, '2020-10-17 10:29:02'),
(120, 'messages', '[195,196]', 332, '2020-10-17 10:29:02'),
(121, 'messages', '[195,196]', 332, '2020-10-17 10:29:02'),
(122, 'messages', '[195,196]', 332, '2020-10-17 10:29:02'),
(123, 'messages', '[195,196]', 332, '2020-10-17 10:29:02'),
(124, 'messages', '[195,196]', 332, '2020-10-17 10:29:02'),
(125, 'messages', '[195,196]', 332, '2020-10-17 10:29:02'),
(126, 'messages', '[195,196]', 332, '2020-10-17 10:29:02'),
(127, 'messages', '[195,196]', 332, '2020-10-17 10:29:02'),
(128, 'messages', '[195,196]', 332, '2020-10-17 10:29:02'),
(129, 'messages', '[195,196]', 332, '2020-10-17 10:29:02'),
(130, 'messages', '[195,196]', 332, '2020-10-17 10:29:02'),
(131, 'messages', '[195,196]', 332, '2020-10-17 10:29:02'),
(132, 'messages', '[195,196]', 332, '2020-10-17 10:29:02'),
(133, 'messages', '[195,196]', 332, '2020-10-17 10:29:02'),
(134, 'messages', '[195,196]', 332, '2020-10-17 10:29:02'),
(135, 'messages', '[195,196]', 332, '2020-10-17 10:29:02'),
(136, 'messages', '[195,196]', 332, '2020-10-17 10:29:02'),
(137, 'messages', '[195,196]', 332, '2020-10-17 10:29:02'),
(138, 'messages', '[195,196]', 332, '2020-10-17 10:29:02'),
(139, 'messages', '[195,196]', 332, '2020-10-17 10:29:02'),
(140, 'messages', '[195,196]', 332, '2020-10-17 10:29:03'),
(141, 'messages', '[195,196]', 332, '2020-10-17 10:29:04'),
(142, 'messages', '[195,196]', 332, '2020-10-17 10:29:04'),
(143, 'messages', '[195,196]', 332, '2020-10-17 10:29:04'),
(144, 'messages', '[195,196]', 332, '2020-10-17 10:29:04'),
(145, 'messages', '[195,196]', 332, '2020-10-17 10:29:04'),
(146, 'messages', '[195,196]', 332, '2020-10-17 10:29:04'),
(147, 'messages', '[195,196]', 332, '2020-10-17 10:29:04'),
(148, 'messages', '[195,196]', 332, '2020-10-17 10:29:04'),
(149, 'messages', '[195,196]', 332, '2020-10-17 10:29:04'),
(150, 'messages', '[195,196]', 332, '2020-10-17 10:29:04'),
(151, 'messages', '[195,196]', 332, '2020-10-17 10:29:04'),
(152, 'messages', '[195,196]', 332, '2020-10-17 10:29:04'),
(153, 'messages', '[195,196]', 332, '2020-10-17 10:29:04'),
(154, 'messages', '[195,196]', 332, '2020-10-17 10:29:04'),
(155, 'messages', '[195,196]', 332, '2020-10-17 10:29:04'),
(156, 'messages', '[195,196]', 332, '2020-10-17 10:29:05'),
(157, 'messages', '[195,196]', 332, '2020-10-17 10:29:05'),
(158, 'messages', '[195,196]', 332, '2020-10-17 10:29:05'),
(159, 'messages', '[195,196]', 332, '2020-10-17 10:29:05'),
(160, 'messages', '[195,196]', 332, '2020-10-17 10:29:05'),
(161, 'messages', '[195,196]', 332, '2020-10-17 10:29:05'),
(162, 'messages', '[195,196]', 332, '2020-10-17 10:29:05'),
(163, 'messages', '[195,196]', 332, '2020-10-17 10:29:05'),
(164, 'messages', '[195,196]', 332, '2020-10-17 10:29:05'),
(165, 'messages', '[195,196]', 332, '2020-10-17 10:29:05'),
(166, 'messages', '[195,196]', 332, '2020-10-17 10:29:05'),
(167, 'messages', '[195,196]', 332, '2020-10-17 10:29:05'),
(168, 'messages', '[195,196]', 332, '2020-10-17 10:29:05'),
(169, 'messages', '[195,196]', 332, '2020-10-17 10:29:05'),
(170, 'messages', '[195,196]', 332, '2020-10-17 10:29:05'),
(171, 'messages', '[195,196]', 332, '2020-10-17 10:29:05'),
(172, 'messages', '[195,196]', 332, '2020-10-17 10:29:05'),
(173, 'messages', '[195,196]', 332, '2020-10-17 10:29:05'),
(174, 'messages', '[195,196]', 332, '2020-10-17 10:29:05'),
(175, 'messages', '[195,196]', 332, '2020-10-17 10:29:05'),
(176, 'messages', '[195,196]', 332, '2020-10-17 10:29:05'),
(177, 'messages', '[195,196]', 332, '2020-10-17 10:29:05'),
(178, 'messages', '[195,196]', 332, '2020-10-17 10:29:05'),
(179, 'messages', '[195,196]', 332, '2020-10-17 10:29:13'),
(180, 'messages', '[195,196]', 332, '2020-10-17 10:29:13'),
(181, 'messages', '[195,196]', 332, '2020-10-17 10:29:14'),
(182, 'messages', '[195,196]', 332, '2020-10-17 10:29:14'),
(183, 'messages', '[195,196]', 332, '2020-10-17 10:29:14'),
(184, 'messages', '[195,196]', 332, '2020-10-17 10:29:19'),
(185, 'messages', '[195,196]', 332, '2020-10-17 10:29:19'),
(186, 'messages', '[195,196]', 332, '2020-10-17 10:29:19'),
(187, 'messages', '[195,196]', 332, '2020-10-17 10:29:20'),
(188, 'messages', '[195,196]', 332, '2020-10-17 10:29:20'),
(189, 'messages', '[195,196]', 332, '2020-10-17 10:29:22'),
(190, 'messages', '[195,196]', 332, '2020-10-17 10:29:22'),
(191, 'messages', '[195,196]', 332, '2020-10-17 10:29:22'),
(192, 'messages', '[195,196]', 332, '2020-10-17 10:29:22'),
(193, 'messages', '[195,196]', 332, '2020-10-17 10:29:22'),
(194, 'messages', '[195,196]', 332, '2020-10-17 10:29:24'),
(195, 'messages', '[195,196]', 332, '2020-10-17 10:29:25'),
(196, 'messages', '[195,196]', 332, '2020-10-17 10:29:25'),
(197, 'messages', '[195,196]', 332, '2020-10-17 10:29:25'),
(198, 'messages', '[195,196]', 332, '2020-10-17 10:29:25'),
(199, 'messages', '[195,196]', 332, '2020-10-17 11:23:46'),
(200, 'messages', '[195,196]', 332, '2020-10-17 11:23:54'),
(201, 'messages', '[195,196]', 332, '2020-10-17 11:23:54'),
(202, 'messages', '[195,196]', 332, '2020-10-17 11:23:54'),
(203, 'messages', '[195,196]', 332, '2020-10-17 11:23:54'),
(204, 'messages', '[195,196]', 332, '2020-10-17 11:23:54'),
(205, 'messages', '[195,196]', 332, '2020-10-17 11:24:10'),
(206, 'messages', '[195,196]', 332, '2020-10-17 11:24:11'),
(207, 'messages', '[195,196]', 332, '2020-10-17 11:24:12'),
(208, 'messages', '[195,196]', 332, '2020-10-17 11:24:12'),
(209, 'messages', '[195,196]', 332, '2020-10-17 11:24:13'),
(210, 'messages', '[195,196]', 332, '2020-10-17 11:24:41'),
(211, 'messages', '[195,196]', 332, '2020-10-17 11:24:44'),
(212, 'messages', '[195,196]', 332, '2020-10-17 11:24:46'),
(213, 'messages', '[195,196]', 332, '2020-10-17 11:28:00'),
(214, 'messages', '[195,196]', 332, '2020-10-17 11:28:13'),
(215, 'messages', '[195,196]', 332, '2020-10-17 11:28:16'),
(216, 'messages', '[195,196]', 332, '2020-10-17 11:28:18'),
(217, 'messages', '[195,196]', 332, '2020-10-17 11:29:18'),
(218, 'messages', '[195,196]', 332, '2020-10-17 11:29:20'),
(219, 'messages', '[195,196]', 332, '2020-10-17 11:29:21'),
(220, 'messages', '[195,196]', 332, '2020-10-17 11:29:23'),
(221, 'messages', '[195,196]', 332, '2020-10-17 11:29:31');

-- --------------------------------------------------------

--
-- Table structure for table `emails`
--

CREATE TABLE `emails` (
  `id` int(11) NOT NULL,
  `text` varchar(60) COLLATE utf16_spanish_ci NOT NULL,
  `confirmed` tinyint(4) NOT NULL DEFAULT '0',
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf16 COLLATE=utf16_spanish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `facturas`
--

CREATE TABLE `facturas` (
  `id` int(10) UNSIGNED NOT NULL,
  `edad` int(10) UNSIGNED NOT NULL,
  `firstname` varchar(60) NOT NULL,
  `lastname` varchar(50) CHARACTER SET utf8 COLLATE utf8_esperanto_ci DEFAULT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(128) NOT NULL,
  `password_char` char(1) NOT NULL,
  `texto_vb` varbinary(300) NOT NULL,
  `texto` text NOT NULL,
  `texto_tiny` tinytext NOT NULL,
  `texto_md` mediumtext NOT NULL,
  `texto_long` longtext NOT NULL,
  `codigo` blob NOT NULL,
  `blob_tiny` tinyblob NOT NULL,
  `blob_md` mediumblob NOT NULL,
  `blob_long` longblob NOT NULL,
  `bb` binary(255) NOT NULL,
  `json_str` json NOT NULL,
  `karma` int(11) NOT NULL DEFAULT '100',
  `code` int(10) UNSIGNED ZEROFILL NOT NULL,
  `big_num` bigint(20) NOT NULL,
  `ubig` bigint(20) UNSIGNED NOT NULL,
  `medium` mediumint(9) NOT NULL,
  `small` smallint(6) NOT NULL,
  `tiny` tinyint(4) NOT NULL,
  `saldo` decimal(15,4) NOT NULL,
  `flotante` float NOT NULL,
  `doble_p` double NOT NULL,
  `num_real` double NOT NULL,
  `some_bits` bit(3) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `paused` tinyint(1) NOT NULL DEFAULT '1',
  `flavors` set('strawberry','vanilla') NOT NULL,
  `role` enum('admin','normal') NOT NULL,
  `hora` time NOT NULL,
  `birth_year` year(4) NOT NULL,
  `fecha` date NOT NULL,
  `vencimiento` timestamp NULL DEFAULT NULL,
  `ts` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `correo` varchar(60) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `files`
--

CREATE TABLE `files` (
  `id` int(11) NOT NULL,
  `filename` varchar(255) NOT NULL,
  `file_ext` varchar(30) NOT NULL,
  `filename_as_stored` varchar(60) NOT NULL,
  `belongs_to` int(11) DEFAULT NULL,
  `guest_access` tinyint(4) DEFAULT NULL,
  `locked` tinyint(4) NOT NULL DEFAULT '0',
  `broken` tinyint(4) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `files`
--

INSERT INTO `files` (`id`, `filename`, `file_ext`, `filename_as_stored`, `belongs_to`, `guest_access`, `locked`, `broken`, `created_at`, `deleted_at`) VALUES
(240, '65394374_10158090117128840_7189038881361100800_o.jpg', 'jpg', '87-5f63d609a595dc2.69295218.jpg', 90, 1, 0, NULL, '2020-09-11 14:47:20', NULL),
(242, '79528120_1205478162980483_7100254245131124736_n.jpg', 'jpg', '87-5f63d609a595c2.69295215.jpg', 87, 0, 0, NULL, '2020-09-17 18:32:57', NULL),
(243, '1_dF7xdJ8zEOx4z1jPkr52-Q.png', 'png', '87-5f63d609aad343.97437503.png', 87, 0, 0, NULL, '2020-09-17 18:32:57', NULL),
(244, '79528120_1205478162980483_7100254245131124736_n.jpg', 'jpg', '87-5f63d635043929.14699523.jpg', 87, 1, 0, NULL, '2020-09-17 18:33:41', NULL),
(245, '1_dF7xdJ8zEOx4z1jPkr52-Q.png', 'png', '87-5f63d6350461c0.12545473.png', 87, 0, 0, 1, '2020-09-17 18:33:41', NULL),
(247, '1_dF7xdJ8zEOx4z1jPkr52-Q.png', 'png', '9-5f6fe37c268a63.40979105.png', 9, 0, 0, NULL, '0000-00-00 00:00:00', NULL),
(248, '977604_10152409748463840_189162970_o.jpg', 'jpg', '9-5f6ff16a8eb7f3.28854283.jpg', 9, 0, 0, NULL, '2020-09-26 22:56:58', '2020-09-26 22:57:44'),
(249, '1486130_10152856320498840_1063104767_o.jpg', 'jpg', '9-5f6ff16a8eef79.76828284.jpg', 9, 0, 0, NULL, '2020-09-26 22:56:59', '2020-09-26 22:58:24'),
(250, '977604_10152409748463840_189162970_o.jpg', 'jpg', '9-5f6ff17d3905a2.42507190.jpg', 9, 0, 0, NULL, '2020-09-26 22:57:17', NULL),
(251, '1486130_10152856320498840_1063104767_o.jpg', 'jpg', '9-5f6ff17d393680.92701475.jpg', 9, 0, 0, NULL, '2020-09-26 22:57:17', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `folders`
--

CREATE TABLE `folders` (
  `id` int(11) NOT NULL,
  `tb` varchar(40) NOT NULL,
  `name` varchar(40) NOT NULL,
  `belongs_to` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `folders`
--

INSERT INTO `folders` (`id`, `tb`, `name`, `belongs_to`) VALUES
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
-- Table structure for table `folder_other_permissions`
--

CREATE TABLE `folder_other_permissions` (
  `id` int(11) NOT NULL,
  `folder_id` int(11) NOT NULL,
  `belongs_to` int(11) NOT NULL,
  `guest` tinyint(4) NOT NULL DEFAULT '0',
  `r` tinyint(4) NOT NULL,
  `w` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `folder_other_permissions`
--

INSERT INTO `folder_other_permissions` (`id`, `folder_id`, `belongs_to`, `guest`, `r`, `w`) VALUES
(1, 4, 90, 1, 1, 0),
(4, 6, 90, 1, 1, 0),
(5, 9, 4, 1, 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `folder_permissions`
--

CREATE TABLE `folder_permissions` (
  `id` int(11) NOT NULL,
  `folder_id` int(11) NOT NULL,
  `belongs_to` int(11) NOT NULL,
  `access_to` int(11) NOT NULL,
  `r` tinyint(4) NOT NULL,
  `w` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `folder_permissions`
--

INSERT INTO `folder_permissions` (`id`, `folder_id`, `belongs_to`, `access_to`, `r`, `w`) VALUES
(1, 1, 1, 4, 1, 1),
(3, 4, 90, 87, 1, 1),
(4, 5, 87, 360, 1, 1);

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
(97, 'no_responder@az.mapapulque.ro', 'No responder', 'nano2@g.c', '', 'Cambio de contraseña', 'Para cambiar la contraseña siga el enlace:<br/><a href=\'http://az.lan/login/change_pass/eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImlhdCI6MTU3NzQ1NzgxMCwiZXhwIjoxNTc4MDYyNjEwLCJlbWFpbCI6Im5hbm8yQGcuYyJ9.LpHFXBpw5cxwoi_jdTaIBAIzcXXdWIAEOJikBRvUMFI/1578062610\'>http://az.lan/login/change_pass/eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImlhdCI6MTU3NzQ1NzgxMCwiZXhwIjoxNTc4MDYyNjEwLCJlbWFpbCI6Im5hbm8yQGcuYyJ9.LpHFXBpw5cxwoi_jdTaIBAIzcXXdWIAEOJikBRvUMFI/1578062610</a>', '2019-12-27 11:43:30', NULL),
(98, 'no_responder@az.mapapulque.ro', 'No responder', 'nano3@g.c', '', 'Cambio de contraseña', 'Para cambiar la contraseña siga el enlace:<br/><a href=\'http://az.lan/login/change_pass/eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImlhdCI6MTU3NzQ2ODA5MCwiZXhwIjoxNTc4MDcyODkwLCJlbWFpbCI6Im5hbm8zQGcuYyJ9.NHFG4GzzyNQhlhBxTK4n4ADF0DUlIMKpemDuOfOR1lA/1578072890\'>http://az.lan/login/change_pass/eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImlhdCI6MTU3NzQ2ODA5MCwiZXhwIjoxNTc4MDcyODkwLCJlbWFpbCI6Im5hbm8zQGcuYyJ9.NHFG4GzzyNQhlhBxTK4n4ADF0DUlIMKpemDuOfOR1lA/1578072890</a>', '2019-12-27 14:34:50', NULL),
(99, 'no_responder@az.mapapulque.ro', 'No responder', 'putazo@g.c', ' ', 'Email confirmation', 'Please confirm your account by following the link bellow:<br/><a href=\'http://az.lan/login/confirm_email/eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImlhdCI6MTU3OTEzNjIwNSwiZXhwIjoxNTc5NzQxMDA1LCJlbWFpbCI6InB1dGF6b0BnLmMifQ.R6r08tcLBgR2i4gw5kp04wSYlgnSQQXM28mv0k0zrG4/1579741005\'>http://az.lan/login/confirm_email/eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImlhdCI6MTU3OTEzNjIwNSwiZXhwIjoxNTc5NzQxMDA1LCJlbWFpbCI6InB1dGF6b0BnLmMifQ.R6r08tcLBgR2i4gw5kp04wSYlgnSQQXM28mv0k0zrG4/1579741005</a>', '2020-01-15 21:56:45', NULL),
(100, 'no_responder@az.mapapulque.ro', 'No responder', 'simplon@g.c', ' ', 'Email confirmation', 'Please confirm your account by following the link bellow:<br/><a href=\'http://az.lan/login/confirm_email/eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImlhdCI6MTU3OTEzNzcyNSwiZXhwIjoxNTc5NzQyNTI1LCJlbWFpbCI6InNpbXBsb25AZy5jIn0.fBmO5BRuhvASi2S6QvOE_E6fI8KP5xO3r0drRwXCUAM/1579742525\'>http://az.lan/login/confirm_email/eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImlhdCI6MTU3OTEzNzcyNSwiZXhwIjoxNTc5NzQyNTI1LCJlbWFpbCI6InNpbXBsb25AZy5jIn0.fBmO5BRuhvASi2S6QvOE_E6fI8KP5xO3r0drRwXCUAM/1579742525</a>', '2020-01-15 22:22:05', NULL),
(101, 'no_responder@az.mapapulque.ro', 'No responder', 'jjjjj@g.c', ' ', 'Email confirmation', 'Please confirm your account by following the link bellow:<br/><a href=\'http://az.lan/login/confirm_email/eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImlhdCI6MTU3OTM4MTYwNiwiZXhwIjoxNTc5OTg2NDA2LCJpcCI6IjEyNy4wLjAuMSJ9.ngOefJiwZl20v57cIJSZokOZplypNusooa5ZbOk4UrM/1579986406\'>http://az.lan/login/confirm_email/eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImlhdCI6MTU3OTM4MTYwNiwiZXhwIjoxNTc5OTg2NDA2LCJpcCI6IjEyNy4wLjAuMSJ9.ngOefJiwZl20v57cIJSZokOZplypNusooa5ZbOk4UrM/1579986406</a>', '2020-01-18 18:06:46', NULL),
(102, 'no_responder@az.mapapulque.ro', 'No responder', 'jj@g.c', ' ', 'Email confirmation', 'Please confirm your account by following the link bellow:<br/><a href=\'http://az.lan/login/confirm_email/eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImlhdCI6MTU3OTM4MTg0NSwiZXhwIjoxNTc5OTg2NjQ1LCJpcCI6IjEyNy4wLjAuMSJ9.2yZxZpL2gL5Ib7-Gf4hhEd_FhtmKC3zBRk6wpcHUtLw/1579986645\'>http://az.lan/login/confirm_email/eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImlhdCI6MTU3OTM4MTg0NSwiZXhwIjoxNTc5OTg2NjQ1LCJpcCI6IjEyNy4wLjAuMSJ9.2yZxZpL2gL5Ib7-Gf4hhEd_FhtmKC3zBRk6wpcHUtLw/1579986645</a>', '2020-01-18 18:10:45', NULL);

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
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL,
  `active` tinyint(11) DEFAULT '1',
  `locked` tinyint(4) DEFAULT '0',
  `workspace` varchar(40) DEFAULT NULL,
  `belongs_to` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `size`, `cost`, `created_at`, `created_by`, `updated_at`, `updated_by`, `deleted_at`, `deleted_by`, `active`, `locked`, `workspace`, `belongs_to`) VALUES
(100, 'Vodka', NULL, '2 1/4 L', 200, '2019-07-04 00:00:00', NULL, '2019-11-24 22:46:44', NULL, '2019-11-25 02:46:44', NULL, 1, 1, '', 90),
(103, 'Juguito ric0', 'Delicious juic333333', '1 Litros', 105, '2019-09-13 00:00:00', NULL, '2020-10-16 13:50:51', 90, '2019-11-25 02:46:46', NULL, 1, 1, NULL, 90),
(105, 'Agua mineral', 'De Córdoba', '1L', 525, '2019-03-15 00:00:00', NULL, '2019-11-24 22:46:48', NULL, '2019-11-25 02:46:48', NULL, 1, 1, 'lista publica', 90),
(106, 'Vodka', 'Rusiaaaaaa', '1L', 400, '2019-02-16 00:00:00', NULL, '2019-11-24 22:46:50', NULL, '2019-11-25 02:46:50', NULL, 1, 1, NULL, 4),
(113, 'Vodka', 'URU', '1L', 550, '2019-03-31 00:00:00', NULL, '2019-11-24 22:46:52', NULL, '2019-11-25 02:46:52', NULL, 1, 1, NULL, 86),
(114, 'AAABBBCCCcccD', 'cccccC', '29', 200, '2019-01-23 00:00:00', NULL, '2019-11-24 22:46:54', NULL, '2019-11-25 02:46:54', NULL, 1, 1, NULL, 4),
(119, 'CocaCola', 'gaseosa', '1L', 44, '2018-10-15 00:00:00', NULL, '2019-11-24 22:46:56', NULL, '2019-11-25 02:46:56', NULL, 1, 1, 'lista2', 90),
(120, 'MiBebida', 'Rica Rica', '1L', 50, '2018-12-23 00:00:00', NULL, '2020-01-18 19:55:35', NULL, '2019-11-23 17:59:32', NULL, 1, 0, NULL, 90),
(121, 'OtraBebida', 'gaseosa', '1L', 25, '2019-09-28 00:00:00', NULL, '2019-11-24 22:46:58', NULL, '2019-11-25 02:46:58', NULL, 1, 1, 'lista2', 90),
(122, 'Cerveza de malta', 'Pichu', '1L', 100, '2018-12-29 00:00:00', NULL, '2019-11-24 22:47:00', NULL, '2019-11-25 02:47:00', NULL, 1, 1, NULL, 90),
(123, 'PesiLoca', 'x_x', '2L', 30, '2018-12-16 00:00:00', NULL, '2019-11-17 07:48:25', NULL, '2019-11-17 07:48:25', NULL, 1, 0, 'mylist', 90),
(125, 'Vodka', '', '3L', 350, '2017-01-10 00:00:00', NULL, '2019-12-13 08:54:23', NULL, '2019-12-13 12:54:23', NULL, 1, 0, 'lista publica', 90),
(126, 'Uvas ricas', 'Espectaculare', '5L', 52, '2019-06-24 00:00:00', NULL, '2020-09-22 16:05:08', 90, NULL, NULL, 1, 0, 'lista publica', 90),
(131, 'Vodka', 'de Estados Unidos!', '1L', 499, '2019-06-04 00:00:00', NULL, '2020-01-03 21:18:16', 90, NULL, NULL, 1, 0, 'secreto', 4),
(132, 'Ron venezolano', 'Rico rico', '1L', 100, '2019-10-03 00:00:00', NULL, '2019-12-22 10:11:31', NULL, '2019-12-22 14:11:31', NULL, 1, 0, NULL, 90),
(133, 'Vodka venezolano', 'de Vzla', '1.15L', 100, '2019-09-19 00:00:00', NULL, '2020-01-03 21:18:00', 90, NULL, NULL, 1, 0, NULL, 90),
(137, 'Agua ardiente', 'Si que arde!', '1.1L', 125, '2019-07-16 00:00:00', NULL, '2020-10-11 19:40:08', 90, NULL, NULL, 1, 0, 'lista', 90),
(143, 'Agua ', '--', '1L', 100, '2019-06-03 00:00:00', NULL, '2019-11-27 16:51:42', NULL, '2019-11-27 20:51:42', NULL, 1, 0, NULL, 90),
(145, 'Juguito XII', 'de manzanas exprimidas', '1L', 350, '2019-02-09 00:00:00', NULL, '2020-01-16 21:27:43', 168, NULL, NULL, 1, 0, 'lista24', 168),
(146, 'Wisky', '', '2L', 230, '2019-08-31 00:00:00', NULL, '2019-11-27 16:57:21', NULL, '2020-09-30 19:27:29', NULL, 1, 0, 'lista24', 90),
(147, 'Aqua fresh', 'Rico', '1L', 105, '2019-03-20 00:00:00', NULL, '2019-11-30 19:21:07', NULL, NULL, NULL, 1, 0, 'otralista', 72),
(148, 'Alcohol etílico', '', '1L', 100, '2019-04-21 00:00:00', NULL, '2019-11-03 21:37:48', NULL, '2020-09-30 19:27:29', NULL, 1, 0, 'comparto', 90),
(151, 'Juguito XIII', 'Rico', '1L', 355, '2019-10-03 00:00:00', NULL, '2019-10-15 17:00:58', NULL, NULL, NULL, 1, 0, 'lista24', 90),
(155, 'Super-jugo', 'BBB', '12', 100, '2019-09-22 00:00:00', NULL, '2020-01-17 11:15:24', NULL, NULL, NULL, 1, 1, NULL, 5),
(160, 'Limonada', 'Rica', '500ML', 3001, '2019-10-23 14:05:30', NULL, '2020-10-16 13:58:58', 90, '2020-10-16 14:02:28', NULL, 1, 1, NULL, 90),
(162, 'Juguito de Mabelita', 'de manzanas exprimidas', '2L', 250, '2019-10-25 08:36:26', NULL, '2019-11-12 12:49:52', NULL, '2020-09-22 15:50:49', 9, 1, 0, NULL, 113),
(163, 'ABC', 'XYZ', '6L', 600, '2019-10-26 10:05:00', NULL, '2019-11-07 00:29:25', NULL, NULL, NULL, 1, 1, NULL, 1),
(164, 'Vodka', 'de Holanda', '33L', 333, '2019-10-26 19:48:26', NULL, '2019-10-29 18:33:57', NULL, NULL, NULL, 1, 0, NULL, 112),
(165, 'Vodka', 'de Suecia', '0.5L', 105, '2019-10-26 22:38:39', NULL, '2020-01-17 22:47:48', 90, '2020-01-18 02:47:48', NULL, 1, 0, NULL, 90),
(166, 'UUU', 'uuu uuu uu u', '0.5L', 100, '2019-10-26 22:38:39', NULL, '2019-11-04 12:57:49', NULL, NULL, NULL, 1, 1, NULL, 90),
(167, 'Vodka', 'de Francia', '10L', 100, '2019-11-02 08:14:46', NULL, '2019-11-03 23:16:17', NULL, NULL, NULL, 1, 1, NULL, 90),
(169, 'Clavos de techo', 'largos', '12 cm', 25, '2019-11-02 16:06:31', NULL, '2019-11-03 20:46:12', NULL, NULL, NULL, 1, 0, NULL, 125),
(170, 'Escalera', 'para electricista', '2 metros', 200, '2019-11-02 16:07:10', NULL, NULL, NULL, NULL, NULL, 1, 0, NULL, 125),
(171, 'Ruedas', 'plastico', '20 cm', 50, '2019-11-02 16:07:51', NULL, '2019-12-07 00:39:42', NULL, NULL, NULL, 1, 0, NULL, 125),
(172, 'Clavos para madera', 'bronce', '2.5 cm', 10, '2019-11-02 16:08:35', NULL, '2019-11-03 20:46:12', NULL, NULL, NULL, 1, 0, NULL, 125),
(173, 'Escalera pintor', 'metal', '5 metros', 80, '2019-11-02 20:41:55', NULL, '2019-12-07 00:38:05', NULL, NULL, NULL, 1, 0, NULL, 125),
(174, 'Caja de herramientas', 'metal', 'M', 90, '2019-11-02 20:42:52', NULL, '2019-12-07 00:38:47', NULL, NULL, NULL, 1, 0, NULL, 125),
(175, 'Caja de herramientas', 'plastico', 'M', 30, '2019-11-02 20:43:14', NULL, '2019-12-07 00:39:18', NULL, NULL, NULL, 1, 0, NULL, 125),
(176, 'Alambre', 'Precio por kilo', '1 mm', 400, '2019-11-02 20:44:28', NULL, NULL, NULL, NULL, NULL, 1, 0, NULL, 125),
(177, 'Cable de 2 hilos telefónico', 'Por metro', '', 10, '2019-11-02 20:45:10', NULL, '2019-12-07 10:32:49', NULL, NULL, NULL, 1, 0, 'electricos', 125),
(178, 'Agua destilada', '', '1L', 50, '2019-11-02 20:46:05', NULL, '2019-12-07 10:32:07', NULL, NULL, NULL, 1, 0, NULL, 125),
(179, 'Agua mineral', '', '1L', 10, '2019-11-02 20:46:20', NULL, '2019-11-03 20:46:12', NULL, NULL, NULL, 1, 0, NULL, 125),
(180, 'Pintura blanca exteriores', '', '5L', 200, '2019-11-02 21:19:06', NULL, NULL, NULL, NULL, NULL, 1, 0, NULL, 125),
(181, 'Pintura blanca exteriores', '', '2L', 100, '2019-11-02 21:19:22', NULL, '2019-11-03 20:46:12', NULL, NULL, NULL, 1, 0, NULL, 125),
(182, 'Pintura blanca interiores', NULL, '2L', 80, '2019-11-02 21:20:00', NULL, '2019-11-03 20:46:12', NULL, NULL, NULL, 1, 0, NULL, 125),
(183, 'Tuercas', '', '', 50, '2019-11-03 21:33:20', NULL, NULL, NULL, NULL, NULL, 1, 0, NULL, 125),
(185, 'ABC', '', '', 50, '2019-11-03 23:55:18', NULL, NULL, NULL, NULL, NULL, 1, 0, NULL, 132),
(186, 'Toma-corrientes hembra pared', 'color: blanco', '', 20, '2019-11-04 09:26:55', NULL, NULL, NULL, NULL, NULL, 1, 0, 'electricos', 125),
(187, 'Crush', 'x_x', '1L', 20, '2019-11-04 13:06:04', NULL, '2019-11-11 13:15:58', NULL, NULL, NULL, 1, 1, NULL, 90),
(189, 'AAAAAAAAAAAAAaaaaa', '', '', 50, '2019-11-04 17:04:51', NULL, '2019-11-04 17:05:00', NULL, NULL, NULL, 1, 0, NULL, 87),
(191, 'Wisky', '', '1.15L', 100, '2019-11-05 21:36:40', NULL, '2019-12-22 19:56:57', 90, NULL, NULL, 1, 0, NULL, 113),
(192, 'Jugo Naranjin', 'Delicious juicEEEE', '1 L', 350, '2019-11-06 23:45:29', NULL, NULL, NULL, NULL, NULL, 1, 0, NULL, 4),
(193, 'Re-Jugo', 'Delicious juicEEEEXXX', '1 L', 350, '2019-11-07 00:18:25', NULL, NULL, NULL, NULL, NULL, 1, 0, NULL, 4),
(194, 'Re-Jugo', 'Delicious juicEEEEXXXYZ', '1 L', 350, '2019-11-07 00:20:53', NULL, NULL, NULL, NULL, NULL, 1, 0, NULL, 135),
(195, 'Boo', 'Delicious juicEEEEXXXYZ4444444444444444444444444', '1 L', 350, '2019-11-07 01:31:43', NULL, NULL, NULL, NULL, NULL, 1, 0, NULL, 4),
(196, 'NaranjAAAAAAA', 'OK', '', 50, '2019-11-07 22:58:51', NULL, '2019-11-24 17:49:34', NULL, NULL, NULL, 1, 0, NULL, 137),
(197, 'HEYYYYYY', '', '', 50, '2019-11-10 00:57:51', NULL, NULL, NULL, NULL, NULL, 1, 0, NULL, 150),
(198, 'AAAAA', 'x_x', '', 99, '2019-11-11 10:38:19', NULL, '2020-09-11 11:50:52', 4, '2020-09-11 11:51:46', NULL, 1, 0, NULL, 90),
(199, 'cuzbgmbhiudjqvrmzwqf', 'x_x', '1L', 99, '2019-11-11 10:42:57', NULL, '2020-09-11 11:50:52', 4, '2020-09-11 11:51:46', NULL, 1, 0, 'lista publica', 90),
(200, 'AAA', '', '', 99, '2019-11-11 11:44:51', NULL, '2020-09-11 11:50:52', 4, '2020-09-11 11:51:46', NULL, 1, 0, NULL, 156),
(201, 'vzukvnjjhzintijexhjd', 'x_x', '1L', 66, '2019-11-11 11:48:37', NULL, '2019-11-11 13:15:58', NULL, NULL, NULL, 1, 0, NULL, 90),
(202, 'VVVBBB', '', '', 50, '2019-11-11 11:59:23', NULL, '2019-11-11 13:10:30', NULL, NULL, NULL, 1, 0, NULL, 148),
(203, 'Super-gas', '', '2L', 50, '2019-11-11 14:00:47', NULL, NULL, NULL, NULL, NULL, 1, 0, NULL, 87),
(205, 'Supreme jugooo', 'de manzanas exprimidas', '1L', 250, '2019-11-11 14:09:52', NULL, NULL, NULL, NULL, NULL, 1, 0, 'lista', 87),
(206, 'Juguito de tomate de árbol', 'Ecuador', '1L', 200, '2019-11-11 15:14:36', NULL, '2019-11-11 16:26:34', NULL, NULL, NULL, 1, 0, 'lista publica', 90),
(207, 'Juguito de tomate papaya', NULL, '1L', 150, '2019-11-11 15:15:05', NULL, '2019-11-11 15:41:32', NULL, NULL, NULL, 1, 0, 'lista', 87),
(208, 'Juguito de tomate pitaya', NULL, '1.1L', 450, '2019-11-11 15:15:16', NULL, '2020-09-18 23:12:54', 360, '2020-09-18 23:14:30', 360, 1, 0, 'lista', 87),
(209, 'AAA', '', '', 50, '2019-11-12 12:50:01', NULL, '2019-11-12 12:50:04', NULL, '2019-11-12 12:50:04', NULL, 1, 0, NULL, 113),
(211, 'EEEE', '', '', 50, '2019-11-27 17:06:24', NULL, '2019-11-30 23:22:41', NULL, '2019-12-01 03:22:41', NULL, 1, 0, NULL, 159),
(212, 'Uvas ricas 2', '', '', 50, '2019-11-27 18:01:44', NULL, '2020-09-22 22:59:36', 168, '2020-09-22 22:59:55', 168, 1, 0, NULL, 168),
(213, 'E%$', '', '', 50, '2019-11-27 18:02:17', NULL, NULL, NULL, NULL, NULL, 1, 0, NULL, 160),
(214, 'TTT', '', '', 50, '2019-11-28 00:01:02', NULL, NULL, NULL, NULL, NULL, 1, 0, NULL, 167),
(215, 'Vino tinto', '', '', 100, '2019-11-30 11:13:05', NULL, '2019-11-30 11:26:13', NULL, NULL, NULL, 1, 0, NULL, 168),
(216, 'BBB', '', '', 50, '2019-11-30 23:23:29', NULL, NULL, NULL, NULL, NULL, 1, 0, NULL, 159),
(218, 'Caja organizadora', 'plastico', 'M', 100, '2019-12-07 10:25:46', NULL, NULL, NULL, NULL, NULL, 1, 0, NULL, 125),
(219, 'Vodka', 'de Canada', '2L', 250, '2019-12-13 08:29:11', NULL, '2019-12-13 08:29:28', NULL, NULL, NULL, 1, 0, NULL, 90),
(220, 'Agua', 'sabor limón', '', 50, '2019-12-13 18:35:19', NULL, NULL, NULL, NULL, NULL, 1, 0, NULL, 90),
(221, 'Agua', 'sabor lima', '', 20, '2019-12-13 18:35:35', NULL, NULL, NULL, NULL, NULL, 1, 0, NULL, 90),
(222, 'Agua', 'mineral', '', 15, '2019-12-13 18:35:49', NULL, '2020-01-17 22:28:02', NULL, '2020-01-18 02:28:02', NULL, 1, 0, NULL, 9),
(223, 'Agua', 'sabor pomelo', '', 20, '2019-12-13 18:36:21', NULL, '2019-12-13 18:36:39', NULL, '2019-12-13 22:36:39', NULL, 1, 0, NULL, 9),
(224, 'Ron', 'caribeño', '', 50, '2019-12-13 18:37:16', NULL, '2019-12-13 18:38:05', NULL, '2019-12-13 22:38:05', NULL, 1, 0, NULL, 9),
(225, 'Ron', 'de Trinidad', '', 50, '2019-12-13 18:37:34', NULL, '2019-12-13 18:38:02', NULL, '2019-12-13 22:38:02', NULL, 1, 0, NULL, 9),
(226, 'Ron', 'de Cuba', '', 50, '2019-12-13 18:37:47', NULL, '2019-12-13 18:37:54', NULL, '2019-12-13 22:37:54', NULL, 1, 0, NULL, 9),
(256, 'XXX', NULL, '', 10, '2019-12-22 19:51:19', 90, NULL, NULL, NULL, NULL, 1, 0, NULL, 90),
(257, 'Koke', 'Rica', '0.5L', 20, '2020-01-03 21:27:41', 90, '2020-01-03 21:28:45', 90, NULL, NULL, 1, 0, NULL, 90),
(259, 'Wiksy', 'from Bielorussia', '1L', 100, '2020-01-04 01:31:42', 90, NULL, NULL, NULL, NULL, 1, 0, NULL, 90),
(271, 'Wisky', NULL, '', 400, '2020-01-16 01:19:08', 323, NULL, NULL, NULL, NULL, 1, 0, NULL, 323),
(272, 'Wisky Super', NULL, '', 500, '2020-01-16 01:19:41', 324, '2020-01-16 01:31:31', 323, NULL, NULL, 1, 0, 'VVV', 324),
(273, 'EEE90', NULL, '', 50, '2020-01-16 16:09:22', 323, NULL, NULL, NULL, NULL, 1, 0, NULL, 323),
(274, 'EEE4', NULL, '', 50, '2020-01-16 16:10:34', 4, '2020-01-18 19:32:19', 90, '2020-01-18 23:32:19', NULL, 1, 0, NULL, 90),
(276, 'some product', NULL, '', 700, '2020-09-18 11:20:39', 360, NULL, NULL, NULL, NULL, 1, 0, NULL, 360),
(277, 'other product', NULL, '', 750, '2020-09-18 11:20:50', 360, NULL, NULL, NULL, NULL, 1, 0, NULL, 360),
(278, 'expensive product', NULL, '', 1000, '2020-09-18 11:21:27', 360, NULL, NULL, NULL, NULL, 1, 0, NULL, 360),
(279, 'ssss', NULL, '4.1L', 110, '2020-09-19 19:44:15', 48, '2020-09-30 01:00:44', 48, NULL, NULL, 1, 0, NULL, 48),
(280, 'uuu', NULL, '', 770, '2020-09-19 19:44:28', 48, NULL, NULL, '2020-09-25 20:20:07', 9, 1, 0, NULL, 48),
(281, 'Uvas cool', 'Frescas', '', 500, '2020-09-25 20:11:50', 9, NULL, NULL, NULL, NULL, 1, 0, NULL, 9),
(300, 'Uvas coolllll', 'Frescasssss', '', 600, '2020-09-25 20:12:51', 400, NULL, NULL, NULL, NULL, 1, 0, NULL, 400),
(301, 'Pepinitos', 'Frescosssss', 'indef.', 50, '2020-09-25 22:17:25', 48, '2020-09-30 22:31:54', 168, '2020-09-30 22:59:51', NULL, 1, 0, NULL, 48),
(302, 'Pepinitos super', 'Super Frescosssss', 'INDEF', 78, '2020-09-25 22:17:38', 48, '2020-09-26 02:00:18', 9, NULL, 9, 1, 0, NULL, 48),
(303, 'Pepinitos Ultra', 'Ultra Frescosssss', '', 108, '2020-09-25 22:17:56', 48, NULL, NULL, '2020-09-26 11:12:17', 9, 1, 0, NULL, 48),
(304, 'Pepinitos Ultra 222', 'Ultra Frescosssss', '', 50, '2020-09-26 11:55:30', 48, '2020-09-30 22:31:54', 168, '2020-09-30 22:59:51', NULL, 1, 0, NULL, 48),
(666, 'Pepinitos Ultra 222', 'Ultra Frescosssss', '', 108, '2020-09-26 11:56:12', 400, NULL, NULL, NULL, NULL, 1, 0, NULL, 400),
(669, 'auawkgyrysbpgoushcjn', 'Esto es una prueba', '1L', 66, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, NULL, 90),
(670, 'pwrbvbkskvmanpdxskvq', 'Esto es una prueba', '1L', 66, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, NULL, 90),
(671, 'quiefkvfitegpiaamsea', 'Esto es una prueba', '1L', 66, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, NULL, 90),
(672, 'gkydabrgmjftbwkkwwsl', 'Esto es una prueba', '1L', 66, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, NULL, 90),
(673, 'vdcorgzjkpunvxicthcz', 'Esto es una prueba', '1L', 66, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, NULL, 90),
(5017, 'hbpzgwqoruriynwdponu', 'Esto es una prueba 77', '100L', 66, '2020-09-28 13:58:13', NULL, NULL, NULL, NULL, NULL, 1, 0, NULL, 90),
(5018, 'ukyzokrgwjbipnisgdmt', 'Esto es una prueba 77', '100L', 66, '2020-09-28 13:59:55', NULL, NULL, NULL, NULL, NULL, 1, 0, NULL, 90),
(5032, 'JJJ', NULL, '', 50, '2020-09-28 14:25:48', 455, '2020-09-30 22:31:54', 168, NULL, NULL, 1, 0, NULL, 455),
(5033, 'xxxy', 'Espectaculare', '5L', 52, '2020-09-29 21:57:18', 90, '2020-09-30 19:36:20', 90, '2020-09-30 21:52:50', NULL, 1, 0, NULL, 90),
(5034, 'xxxy', 'Espectaculare', '5L', 52, '2020-09-29 21:57:53', 90, '2020-09-30 19:36:20', 90, '2020-09-30 21:52:50', NULL, 1, 0, NULL, 90),
(5035, 'Uvas ricas', 'Espectaculare', '5L', 52, '2020-09-29 21:58:29', 90, NULL, NULL, NULL, NULL, 1, 0, NULL, 1),
(5039, 'manzanin', 'rico rico', '0.5L', 80, '2020-09-30 00:34:12', 48, NULL, NULL, NULL, NULL, 1, 0, NULL, 48),
(5040, 'manzanin', 'rico rico', '0.5L', 80, '2020-09-30 00:34:42', 48, NULL, NULL, NULL, NULL, 1, 0, NULL, 48),
(5041, 'manzanin', 'rico rico', '0.5L', 80, '2020-09-30 00:52:22', NULL, NULL, NULL, '2020-09-30 00:53:42', 1, 1, 1, NULL, 1),
(5042, 'pepinos', NULL, '', 600, '2020-10-01 11:51:38', 16, NULL, NULL, NULL, NULL, 1, 0, NULL, 16),
(5043, 'pepinos 2', NULL, '', 650, '2020-10-01 11:51:49', 16, NULL, NULL, NULL, NULL, 1, 0, NULL, 16),
(5044, 'pepinos nivel Dios', NULL, '', 800, '2020-10-01 11:51:58', 16, NULL, NULL, NULL, NULL, 1, 0, NULL, 16),
(5045, 'pepinos nivel Dios', NULL, '', 800, '2020-10-05 18:40:24', 16, NULL, NULL, NULL, NULL, 1, 0, NULL, 16),
(5046, 'pepinos nivel Dios', NULL, '', 800, '2020-10-05 18:47:41', 16, NULL, NULL, NULL, NULL, 1, 0, NULL, 16),
(5047, 'pepinos nivel Dios', NULL, '', 800, '2020-10-05 18:47:45', 16, NULL, NULL, NULL, NULL, 1, 0, NULL, 16),
(5048, 'pepinos nivel Dios', NULL, '', 800, '2020-10-05 18:47:48', 16, NULL, NULL, NULL, NULL, 1, 0, NULL, 16),
(5049, 'pepinos nivel Dios', NULL, '', 800, '2020-10-05 18:47:49', 16, NULL, NULL, NULL, NULL, 1, 0, NULL, 16),
(5050, 'pepinos nivel Dios', NULL, '', 800, '2020-10-05 18:48:01', 16, NULL, NULL, NULL, NULL, 1, 0, NULL, 16),
(5051, 'pepinos nivel Dios', NULL, '', 800, '2020-10-05 18:48:32', 16, NULL, NULL, NULL, NULL, 1, 0, NULL, 16),
(5052, 'pepinos nivel Dios', NULL, '', 800, '2020-10-05 18:48:33', 16, NULL, NULL, NULL, NULL, 1, 0, NULL, 16),
(5053, 'pepinos nivel Dios', NULL, '', 800, '2020-10-05 18:48:34', 16, NULL, NULL, NULL, NULL, 1, 0, NULL, 16),
(5054, 'pepinos nivel Dios', NULL, '', 800, '2020-10-05 18:48:48', 16, NULL, NULL, NULL, NULL, 1, 0, NULL, 16),
(5055, 'pepinos nivel Dios', NULL, '', 800, '2020-10-05 18:48:52', 16, NULL, NULL, NULL, NULL, 1, 0, NULL, 16),
(5056, 'pepinos nivel !!', NULL, '', 8004, '2020-10-05 18:49:00', 16, NULL, NULL, NULL, NULL, 1, 0, NULL, 16),
(5057, 'pepinos nivel !!', NULL, '', 8004, '2020-10-05 22:11:18', 16, NULL, NULL, NULL, NULL, 1, 0, NULL, 16),
(5058, 'pepinos nivel !!', NULL, '', 8004, '2020-10-05 22:44:48', 16, NULL, NULL, NULL, NULL, 1, 0, NULL, 16),
(5059, 'pepinos nivel Dios', NULL, '10 cm', 800, '2020-10-10 23:16:23', 16, NULL, NULL, NULL, NULL, 1, 0, NULL, 16),
(5060, 'Uvas ricas', 'Espectaculare', '5L', 1, '2020-10-12 02:09:20', 168, NULL, NULL, NULL, NULL, 1, 0, NULL, 168),
(5061, 'Uvas ricas', 'Espectaculare', 'peque', 99, '2020-10-20 13:30:15', 168, NULL, NULL, NULL, NULL, 1, 0, NULL, 168),
(5062, 'Uvas ricas', 'Espectaculare', 'peque', 99, '2020-10-20 13:30:28', 168, NULL, NULL, NULL, NULL, 1, 0, NULL, 168),
(5063, 'Uvas ricas', 'Espectaculare', 'peque', 99, '2020-10-20 13:30:31', 168, NULL, NULL, NULL, NULL, 1, 0, NULL, 168),
(5064, 'Uvas ricas', 'Espectaculare', 'peque', 99, '2020-10-20 13:30:32', 168, NULL, NULL, NULL, NULL, 1, 0, NULL, 168),
(5065, 'Uvas ricas', 'Espectaculare', 'peque', 99, '2020-10-20 13:30:34', 168, NULL, NULL, NULL, NULL, 1, 0, NULL, 168),
(5066, 'Uvas ricas', 'Espectaculare', 'peque', 99, '2020-10-20 13:30:38', 168, NULL, NULL, NULL, NULL, 1, 0, NULL, 168);

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`) VALUES
(100, 'admin'),
(2, 'basic'),
(-1, 'guest'),
(1, 'registered'),
(3, 'regular'),
(500, 'superadmin'),
(502, 'supervisor');

-- --------------------------------------------------------

--
-- Table structure for table `sp_permissions`
--

CREATE TABLE `sp_permissions` (
  `id` int(11) NOT NULL,
  `name` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `sp_permissions`
--

INSERT INTO `sp_permissions` (`id`, `name`) VALUES
(10, 'fill_all'),
(11, 'grant'),
(9, 'impersonate'),
(7, 'lock'),
(1, 'read_all'),
(3, 'read_all_folders'),
(5, 'read_all_trashcan'),
(8, 'transfer'),
(2, 'write_all'),
(12, 'write_all_collections'),
(4, 'write_all_folders'),
(6, 'write_all_trashcan');

-- --------------------------------------------------------

--
-- Table structure for table `super_cool_table`
--

CREATE TABLE `super_cool_table` (
  `id` int(11) NOT NULL,
  `name` varchar(45) NOT NULL,
  `age` int(11) NOT NULL,
  `active` tinyint(4) NOT NULL,
  `belongs_to` int(11) NOT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `locked` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `super_cool_table`
--

INSERT INTO `super_cool_table` (`id`, `name`, `age`, `active`, `belongs_to`, `deleted_at`, `locked`) VALUES
(500, 'Etereo', 0, 1, 90, NULL, 0),
(504, 'Etereo oculto II', 0, 0, 90, NULL, 0),
(505, 'Jota jota', 0, 1, 90, NULL, 0),
(506, 'Jota jota', 0, 1, 90, NULL, 0),
(777, 'SUPER', 22, 0, 0, NULL, 0),
(778, 'SUPER', 22, 0, 0, NULL, 0),
(779, 'SUPER', 22, 0, 0, NULL, 0),
(780, 'SUPER', 22, 0, 0, NULL, 0),
(781, 'SUPER', 22, 0, 0, NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(15) NOT NULL,
  `active` tinyint(4) DEFAULT NULL,
  `locked` tinyint(4) NOT NULL DEFAULT '0',
  `email` varchar(60) NOT NULL,
  `confirmed_email` tinyint(4) DEFAULT '0',
  `firstname` varchar(50) DEFAULT NULL,
  `lastname` varchar(80) DEFAULT NULL,
  `password` varchar(60) DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `belongs_to` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `active`, `locked`, `email`, `confirmed_email`, `firstname`, `lastname`, `password`, `deleted_at`, `belongs_to`) VALUES
(1, 'super', 1, 0, 'boctu.l.us@gmail.com', 0, 'P', 'bzz578000', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 1),
(4, 'pbozzolo', 1, 0, 'pbozzolo@gmail.com', 1, 'Paulinoxxxy', 'Bozzoxxxy', '$2y$10$jAKcStnGqtcOslt1Std7ceYqq3mMIh6Lis/Ug4Z6IDQV65tyyP2Xe', NULL, 4),
(5, 'pepe', 1, 0, 'pepe@gmail.com', 1, 'Pepe', 'Gonzalez', '$2y$10$J.KPjyFukfxcKg83TvQGaeCTrLN9XyYXTgtTDZdZ91DJTdE73VIDK', NULL, 5),
(9, 'dios', 0, 0, 'dios@gmail.com', 1, 'Paulino', 'Bozzo', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 9),
(11, 'diosdado', 1, 0, 'diosdado@gmail.com', 1, 'Sr', 'Z', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 11),
(13, 'diosdado2', 1, 0, 'diosdado2@gmail.com', 1, 'Sr', 'D', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 13),
(14, 'juancho', 1, 0, 'juancho@aaa.com', 1, 'Juan', 'Perez', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 14),
(15, 'juancho11', 1, 0, 'juancho11@aaa.com', 1, 'Juan XI', 'Perez 10', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 15),
(16, 'mabel', 1, 0, 'mabel@aaa.com', 1, 'Mabel', 'S', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 16),
(17, 'a', 1, 0, 'a@b.commmm', 1, 'Nicos', 'Buzzi', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 17),
(20, 'a1', 1, 0, 'a@b.commmmmmmmmmmm', 1, 'Nicos', 'AAA', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 20),
(34, 'peter', 1, 0, 'peter@abc.com', 1, 'Nicos', 'Buzzi', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 34),
(38, 'udcsoqjyrdg', 1, 0, 'udcsoqjyrdgnhqqtukhupeter@abc.com', 1, 'Nicos', 'Buzzi', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 38),
(39, 'qbosmfvwezo', 1, 0, 'qbosmfvwezohbutpifbopeter@abc.com', 1, 'Nicos', 'Buzzi', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 39),
(40, 'gjappgiduiq', 1, 0, 'gjappgiduiqczagnousspeter@abc.com', 1, 'Nicos', 'Buzzi', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 40),
(41, 'ymcshlekdzh', 1, 0, 'ymcshlekdzhugvmwbjpipeter@abc.com', 1, 'Nicos', 'Buzzi', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 41),
(43, 'vydqkgqszpn', 1, 0, 'vydqkgqszpncijwhxeiapeter@abc.com', 1, 'Nicos', 'Buzzi', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 43),
(44, 'itbrknzsfna', 1, 0, 'itbrknzsfnawnhxgmockpeter@abc.com', 1, 'Nicos', 'Buzzi', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 44),
(46, 'xxxxxxxxxxx', 1, 0, 'xxxxxxxxxxxxxxxxyz@abc.com', 1, 'Nicolayyyy', 'Buzzi', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 46),
(48, 'gates', 1, 0, 'gates@outlook.com', 1, 'Bill', 'Gates', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 48),
(51, 'kkk', 1, 0, 'kkk@bbbbbb.com', 1, 'Sr K', 'NS/NC', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 51),
(52, 'tito', 1, 0, 'tito@gmail.com', 1, 'Tito', 'El Grande', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 52),
(53, 'ooooiiii', 1, 0, 'ooooiiii@gmail.com', 1, 'Oooo', 'iiiiiiii', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 53),
(54, 'booooiiii', 1, 0, 'booooiiii@gmail.com', 1, 'AAA', 'BBB', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 54),
(55, 'iooobooooii', 1, 0, 'iooobooooiiii@gmail.com', 1, 'IIoo', 'ahaha', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 55),
(56, 'iooobooooii5', 1, 0, 'iooobooooiiioooi@gmail.com', 1, 'IIoo', 'ahaha', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 56),
(57, 'iooobooooii57', 1, 0, 'iooobooooiiioooi@gmail.commmm', 1, 'IIoo', 'ahaha', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 57),
(58, 'kkk1', 1, 0, 'kkk@bbbbbb.commmm', 1, 'Sr K', 'NS/NC', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 58),
(59, 'kkkbooooiii', 1, 0, 'kkkbooooiiii@gmail.com', 1, 'Ooookkk', 'kkkk', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 59),
(60, 'aaa', 1, 0, 'aaa@bbbb.com', 1, 'Jjjj', 'kkk', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 60),
(61, 'aaa7', 1, 0, 'aaa@bbbb.commmm', 1, 'Jjjj', 'kkk', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 61),
(62, 'kkk6', 1, 0, 'kkk@bbbbbb.commmmmmm', 1, 'Sr K', 'NS/NC', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 62),
(63, 'aaa5', 1, 0, 'aaa@bbbb.commmmmmm', 1, 'Jjjj', 'kkk', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 63),
(64, 'booooiiiixx', 1, 0, 'booooiiiixxxx@gmail.com', 1, 'xxx', 'xxxxxxxxxx', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 64),
(65, 'aaa9', 1, 0, 'aaa@bbbb.commmmmmmuuuuu', 1, 'Jjjj', 'kkk', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 65),
(66, 'aaa54', 1, 0, 'aaa@dgdgd.cococ', 1, 'ajajaj', 'ajajaj', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 66),
(67, 'booooiiifer', 1, 0, 'booooiiiferfr@gmail.com', 1, 'BillY', 'GGGG', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 67),
(68, 'aaa78', 1, 0, 'aaa@dgdgd.cococo', 1, 'ajajaj', 'ajajaj', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 68),
(69, 'test', 1, 0, 'test@gmail.com', 1, 'TEST', '---', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 69),
(70, 'kkk65', 1, 0, 'kkk@bbJJJJJJJ', 1, 'Sr K', 'NS/NC', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 70),
(72, 'aie', 1, 0, 'aie@b.c', 1, 'Sr K', 'NS/NC', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 72),
(73, 'mabelf450', 1, 0, 'mabelf450@gmail.com', 1, 'Mabel', 'F', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 73),
(74, 'abc', 1, 0, 'abc@def.com', 1, 'Sr K', 'NS/NC', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 74),
(75, 'abc4', 1, 0, 'abc@def.commm', 1, 'Sr K', 'NS/NC', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 75),
(76, 'abc6', 1, 0, 'abc@def.net', 1, 'Sr K', 'NS/NC', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 76),
(77, 'abc62', 1, 0, 'abc@def.co', 1, 'Sr K', 'NS/NC', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 77),
(78, 'abc9', 1, 0, 'abc@def.cox', 1, 'Sr K', 'NS/NC', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 78),
(79, 'feli', 1, 0, 'feli@', 1, 'Sr K', 'NS/NC', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 79),
(80, 'feli3', 1, 0, 'feli@casa', 1, 'Sr K', 'NS/NC', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 80),
(81, 'feli5', 1, 0, 'feli@casa.com', 1, 'Sr K', 'NS/NC', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 81),
(82, 'feli4', 1, 0, 'feli@casa.net', 1, 'Sr K', 'NS/NC', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 82),
(83, 'feli9', 1, 0, 'feli@casa.neto', 1, 'Sr K', 'NS/NC', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 83),
(84, 'pablo', 1, 0, 'pablo@', 1, 'Nicos', 'AAA', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 84),
(85, 'feli6', 1, 0, 'feli@teamo', 1, 'Felipe', 'Bozzolo', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 85),
(86, 'nuevo', 1, 0, 'nuevo@gmail.com', 1, 'Norberto', 'Nullo', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 86),
(87, 'pedro', 1, 0, 'pedro@gmail.com', 1, 'Pedro', 'Picapiedras', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 87),
(88, 'feli8', 1, 0, 'feli@abc', 1, 'Felipe', 'Bozzzolo', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 88),
(89, 'h', 1, 0, 'h@', 1, 'Sr H', 'J', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 89),
(90, 'nano', 1, 0, 'nano@gmail.com', 0, 'Paulo', 'Bzz', '$2y$10$QrlBRrdiLlkdq4SP7wz2OuhoFPz3klM4vAA3iHb450EocwMsMJPIS', NULL, 90),
(102, 'feli61', 1, 0, 'feli@delacasita', 1, 'Sr K', 'NS/NC', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 102),
(103, 'feli1', 1, 0, 'feli@delacasita2', 1, 'Sr K', 'NS/NC', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 103),
(104, 'feli7', 1, 0, 'feli@delacasita5', 1, 'Sr K', 'NS/NC', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 104),
(105, 'feli80', 1, 0, 'feli@delacasita50.com', 1, 'Sr K', 'NS/NC', '$2y$10$N6fTRdVfyusWVkAchTWmSO1OAscI/X.ZU5YU14imTrfR0gLlbncVO', NULL, 105),
(106, 'feli72', 1, 0, 'feli@delacasita50000', 1, 'Sr K', 'NS/NC', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 106),
(107, 'feli36', 1, 0, 'feli@delacasita50000700', 1, 'Sr K', 'NS/NC', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 107),
(108, 'feli31', 1, 0, 'feli@delacasita50000700800', 1, 'Sr K', 'NS/NC', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 108),
(109, 'feli34', 1, 0, 'feli@compeon', 1, 'Sr K', 'NS/NC', '$2y$10$ocJZwzelZ3W.3Eas5ig/q.qaBm79ottSuJ8ee2wXv9584INHL2RpW', NULL, 109),
(110, 'feli67', 1, 0, 'feli@compeon_mundial', 1, 'Sr K', 'NS/NC', '$2y$10$k4kFWXmQacW4LDS.j4gVk./LqKPUtOc9XaObNWojsAzmFnIxbLZ8u', NULL, 110),
(111, 'feli76', 1, 0, 'feli@compeon_dios', 1, 'Sr K', 'NS/NC', '$2y$10$nYh5nGXM6mVwwAP93G4Nv.m8P8aQmJKLm5fODQBqdmzzSCEZzDiOC', NULL, 111),
(112, 'superpepe', 1, 0, 'superpepe@', 1, 'Super Pepe', '', '$2y$10$hITAKY1zsMPMIe0KCO6YuuG5Xke8FeZlw00Uw1Mz4J57LU0lLfvja', NULL, 112),
(113, 'mabelsusana', 1, 0, 'mabelsusanaf@gmail.com', 1, NULL, NULL, NULL, NULL, 113),
(114, 'xyz', 1, 0, 'xyz@', 1, 'XYZ', 'ZZZ', '$2y$10$mMaJuMmAt7hgTo8KljlDRuavoOg4F0ZGw.hAK/vmpNQJ0xvA/ZEmG', NULL, 114),
(115, 'x', 1, 0, 'x@', 1, 'Sr K', 'NS/NC', '$2y$10$K64bJLscpFCH7Geal..vhuKeMzZOP9MWD21eXPj7VpfYiHw4AaEMC', NULL, 115),
(116, 'xy', 1, 0, 'xy@', 1, 'Sr K', 'NS/NC', '$2y$10$86ztfpWglgqvK/MyJf12VOYY8rqjJ4Qhi1XhpOsNW0u.BMM3odtUG', NULL, 116),
(117, 'aaa3', 1, 0, 'aaa@', 1, 'A', 'AB', '$2y$10$KP8rEs5DracAVvcdMY/ATuB3xwEz7Rjwqj5DilPiszMi8wMRKNAhK', NULL, 117),
(118, 'aaaC', 1, 0, 'aaaC@', 1, 'A', 'AB', '$2y$10$T59OjL8Rxb/QZeArKW.PK.GVVG7V3Ao846KSimwt6xGbf7tx0oik.', NULL, 118),
(119, 'aaaCD', 1, 0, 'aaaCD@', 1, 'A', 'AB', '$2y$10$SvAYgvxsszn1Z/cfMW/w9eopOcih3CzADDEDd2q0wKwqwTBWVPZoi', NULL, 119),
(120, 'xyza', 1, 0, 'xyza@', 1, 'XYZA', 'ZZZA', '$2y$10$rp40xaVPNtHqSsDYXVBNCespVSLBpvwdp1oyV7NY6nJk011q2/Iri', NULL, 120),
(121, 'nono', 1, 0, 'nono@', 1, 'Nono', 'viecco', '$2y$10$W5HaPfOxbAE9rRb04XCzeO/sS0tlHQ4ZTnmXzZPnd2s1qZt26OFdS', NULL, 121),
(122, 'sss', 1, 0, 'sss@', 1, 'Regular', 'Tester', '$2y$10$8FiysajEjA0.5eyXuLNUZeqQkUBgxCYVonwk1Z5k4rTf/rPMLu.2y', NULL, 122),
(123, 'ppp', 1, 0, 'ppp@', 1, 'Regular', 'Tester', '$2y$10$J3C1J2pqvJbuQXEekf6vf.AL0lEJbpGrhPtZCujXRfyv16nEzQvdm', NULL, 123),
(124, 'w', 1, 0, 'w@', 1, 'AA', 'BB', '$2y$10$899qRrlzAXbnyE/5CHLZVezdK9beIDJqrUmb/TcdgepkPHYTMLJTK', NULL, 124),
(125, 'tester3', 1, 0, 'tester3@g.c', 1, 'Tester', '', '$2y$10$hfB4/MQ8ULXY4fhaeAhXqOw7V.U1ifhJeLe5/Xx7mwXA.uFbHBwai', NULL, 125),
(126, 'jk', 1, 0, 'jk@', 1, 'J', 'K', '$2y$10$irie63zGURJ/JQiJuyph/uVkVmAJRiSvvDRGaBokyvVLpi8tqganW', NULL, 126),
(127, 'aaaCDEF', 1, 0, 'aaaCDEF@', 1, 'A', 'AB', '$2y$10$juP31/p3B.P7F/b2MXxGF.kiN/HG1zwIyehkNWjef5yhFYSGq.YwC', NULL, 127),
(128, 'aaaCDEFG', 1, 0, 'aaaCDEFG@', 1, 'A', 'AB', '$2y$10$nkIhbPrL4Y/oJJOe6JdOO.57U8Njn57IRM5cYG7FtPDA2jffOsFNm', NULL, 128),
(129, 'aaaCDEFGI', 1, 0, 'aaaCDEFGI@', 1, 'A', 'AB', '$2y$10$VeyVsSP/.2SgfBJq25FXIOPq2iXceFiEgRiEVuOTi5oPIMWc8Vteq', NULL, 129),
(130, 'aaaCDEFGIJK', 1, 0, 'aaaCDEFGIJK@', 1, 'A', 'AB', '$2y$10$ann6qT5V/SkaYk/InT27ouVPhrkNaVOlvwgY2nf27lhlT5hm0hnTK', NULL, 130),
(131, 'aaaCDEFGIJK9', 1, 0, 'aaaCDEFGIJKLM@', 1, 'A', 'AB', '$2y$10$N2hoKh4E9.aYnAPmMPuSb.mgkLAJA1mB0pAnpjsLRU7DuyX1.b1ZC', NULL, 131),
(132, 'tt', 1, 0, 'tt@', 1, 'T', 'TT', '$2y$10$qXJ25mY64hef.47EjJkBNeYDgG.zHNH8QsodGK3OW13EYQdpaifFG', NULL, 132),
(133, 'jc', 1, 0, 'jc@', 1, 'Juan', 'Carlos', '$2y$10$gMgqAHr1A5.phZ2/n.RuVuJEM8QnfPVAiTGMVqvCOf7mlWXrGNjXK', NULL, 133),
(135, 'b', 1, 0, 'b@gmail.com', 1, 'Pablo', 'Bozzolo', '$2y$10$k0dfh9fPueuBlpPr0zC6V.kEr0CR4uHjEc3IUKipfRR3sDnnSvieu', NULL, 135),
(136, 'bill', 1, 0, 'bill@', 1, 'Bill', 'O', '$2y$10$GHiCUTrFu01EiVcVgTvRluGweRBx8rF6V2qgbhNH82Oi86ATE0RO2', NULL, 136),
(137, 'san', 1, 0, 'san@', 1, 'San', 'Pepe', '$2y$10$OVUu5rTU1JPSTv2HeXq/puWq86vZT24VAuYTW.sAT.pqzxZEJPFou', NULL, 137),
(138, 'j', 1, 0, 'j@', 1, 'AJ', 'J', '$2y$10$TOUciinPf3DEBTjALBLnaOhMLzHrwBRkvczOWYD5OsZOy9aVGVfnC', NULL, 138),
(139, 'aaaCDEFGIJK6', 1, 0, 'aaaCDEFGIJKLMSSS@', 1, 'A', 'AB', '$2y$10$0YDH5aE9l3lQwxFp25bLXepmGQF8fM4XcK4zt/lNzS/2.M7mVgz4S', NULL, 139),
(140, 'ZZZ', 1, 0, 'ZZZ@', 1, 'A', 'AB', '$2y$10$QosozVg0npjSEimSeOZ74OPyXXZO0SwPsZevPOJlQ0GnHDK7DcsR6', NULL, 140),
(141, 'ZZZ5', 1, 0, 'ZZZ@PEPE.com', 1, 'A', 'AB', '$2y$10$XIpys1..n4XcQ9CWP89vce9j7NeAiMYYeOqonYtGgUi.9nZcaccB2', NULL, 141),
(142, 'A5', 1, 0, 'A@PEPE.COM', 1, 'A', 'AB', '$2y$10$Fn5Wkt8masdaOdqBHIaU0uDuRbi7mEVDSzlHgAK8wfJSesSlQWAZu', NULL, 142),
(143, 'Ab', 1, 0, 'Ab@PEPE.COM', 1, 'A', 'AB', '$2y$10$ehfF6Uwrvdl3NoqYkqhnIexzzCX49pCdbEXOYKrI1O2jsvdHLEpCa', NULL, 143),
(147, 'ABCDEF', 1, 0, 'ABCDEF@PEPE.COM', 1, 'A', 'AB', '$2y$10$67kVEbzj5C7eGl8b9f01jeMvbU6Cy2xGYADhYl5PvMmUAXAenmmb2', NULL, 147),
(148, 'ABCDEFG', 1, 0, 'ABCDEFG@PEPE.COM', 1, 'A', 'AB', '$2y$10$p/KGmzQsMmlDDJlYmhr9GOUmkcO4A.A8sefXd7bN3GthEIEA4YCSO', NULL, 148),
(149, 'X8', 1, 0, 'X@PEPE.COM', 1, 'A', 'AB', '$2y$10$R9EtFdhCWUjMMFkLZxLiA.8XJ2hd2SE8Q3eDuyUa8KOV0TsTGNRX.', NULL, 149),
(150, 'Y', 1, 0, 'Y@PEPE.COM', 1, 'A', 'AB', '$2y$10$WeO4iScCcFe/yV4d7iUbFeYOfTR6H3TGDjunJLskrJg/.9raTP7IG', NULL, 150),
(151, 'F', 1, 0, 'F@PEPE.COM', 1, 'A', 'AB', '$2y$10$LDtPjcxYtrwMVfae.8MsNuRaoRgsnVtagH2fbYG3m3MIYOeAWB4fC', NULL, 151),
(152, 'G', 1, 0, 'G@PEPE.COM', 1, 'A', 'AB', '$2y$10$TY9RyLYn.E24qAeBn9LCzuy8bRT1/UReSDDodfdHouQ7jRN6GqMTq', NULL, 152),
(153, 'GG', 1, 0, 'GG@PEPE.COM', 1, 'A', 'AB', '$2y$10$qt79FFFbCbQNya/a8fMMn.tcstyJYUHRXIw36pGDTLExLueDKbJby', NULL, 153),
(154, 'GGG', 1, 0, 'GGG@PEPE.COM', 1, 'A', 'AB', '$2y$10$bIqxFs1bOo.CkImQj5t26.R6fnp5UO8913B5NkxNDy5gP0bhNoP2O', NULL, 154),
(155, 'elpiojo', 1, 0, 'elpiojo@', 1, 'Ojo', '', '$2y$10$UvEXPDfjiZ/PPits.tThwurSYCz844ZMh9mCVBC5Y9s7Hp8KwlR.2', NULL, 155),
(156, 'elpiojo2', 1, 0, 'elpiojo2@', 1, 'Ojo', '', '$2y$10$aPWXX4L3MNnPcz0Q.zLFRO97ChkGYQoUdyvk.RzVDuwRaQDiMxkUS', NULL, 156),
(159, 'boo', 1, 0, 'boo@', 1, 'Boo', '', '$2y$10$eZfHERLi3AlNU7zsPzV.OOqF0Hs7jkh.LXRKdM/3XVABpLujf/f.G', NULL, 159),
(160, 'uub', 1, 0, 'uub@', 1, 'Uub', '', '$2y$10$ylqsjqzOYFRrfRNYGaruteJ1uLnqcDLyyJTE6If1wb3GNCvEwTk/u', NULL, 160),
(163, 'asdfgh', 1, 0, 'asdfgh', 0, NULL, NULL, '$2y$10$3qyTA2frHg.CNo2VQTh/cenZoi4y4dtoKhGQNe6P8lqL.u5jS3MFu', NULL, 163),
(164, 'asdfgh2', 1, 0, 'asdfgh2', 0, NULL, NULL, '$2y$10$lWC.2LcTeHNX65n1NDwDsuSekR0zYC0WNBTOzXEesuRSkKt3krTxa', NULL, 164),
(165, 'asdfgh23', 1, 0, 'asdfgh23', 0, NULL, NULL, '$2y$10$3ehTASOEPlBjoNYdrZE.WeoKNoV35.DvjpDW1S7IkihN3ByKlCRse', NULL, 165),
(166, 'asdfgh234', 1, 0, 'asdfgh234', 0, NULL, NULL, '$2y$10$AgMfAavv9tAZjJNPZWAceeEq6gBnBgbKiQRrnqtBIioMk8zMvZrKi', NULL, 166),
(167, 'pepem', 1, 0, 'pepe@', 1, NULL, NULL, '$2y$10$E7MLf1GxIdRnT4uwOYr03e6mrs3BXd1SApL6EzvzqTs4EkyzttjKm', NULL, 167),
(168, 'boctulus', 1, 0, 'boctulus@gmail.com', 0, 'Pablo', 'Bozzolo', '$2y$10$hCFiLuBsGdmjFCjcqjt/5O8yz5JYy7xUYSGABRcq4B98Olt5cJ/P.', NULL, 168),
(170, 'uva@g.c', 1, 0, 'uva@g.c', 0, NULL, NULL, '$2y$10$tqA8gO2X8m8aNJoWkqjlJObThy4ZkzTxrV0V0srP7o8QWm/VFsQyO', NULL, 170),
(196, 'doe1979', 1, 0, 'testing_create@g.com', 0, 'Jhon', 'Doe', 'pass', NULL, NULL),
(200, 'doe1980', 1, 0, 'testing1@g.com', 0, 'Jhon', 'Doe', 'pass', NULL, NULL),
(223, 'ffff1', 1, 0, 'ffff1@g.c', 0, NULL, NULL, '$2y$10$FmWYwubCrUXNoyUC16zsquFyCn0R8OzE6eicAgCd6cnDUNXqr2fIa', NULL, 223),
(224, 'newuser97865wxy', 1, 0, 'newuser97865wxy@g.c', 0, NULL, NULL, '$2y$10$E0bUrCzhD12YWQWtRPE4CeQspqlKWhJXNyLuBi3iFzPLmiZ6fW10W', NULL, 224),
(225, 'nnn', 1, 0, 'nnn@g.c', 0, NULL, NULL, '$2y$10$C0sO.yRmK1xm./jm.OTMw.citGYFci11M/BHecY/pBYY9BplqsYg2', NULL, 225),
(226, 'nn_j23483j401wx', 1, 0, 'nn_j23483j401wx@g.c', 0, NULL, NULL, '$2y$10$vHi9kf2VLIIyZUn8P8ACQuQ0SIT7REfVTPxnMvGwY7g.MrMUi2Sn2', NULL, 226),
(301, 'nano111', 1, 0, 'asdffffffffffff@g.c', 0, NULL, NULL, '$2y$10$Xu6Lv.AjKrwYV4NOfJIce.l1HTwHUkSlOcMQQGkATFLwmDt2i5K9i', NULL, 301),
(302, 'nano111__$', 1, 0, 'asdfggg@g.c', 0, NULL, NULL, '$2y$10$PMVMWFDhKnaITfqHimZ1JOyaYnqOA6ne26AQPHVMQAUFEYA.vXEc6', NULL, 302),
(303, 'nano111__$u', 1, 0, 'asdfggtttg@g.c', 0, NULL, NULL, '$2y$10$Ioz50NWbl86r9ZC8F/tXoOZHbiArx07z51quWp4smvsn.3sNXLXb6', NULL, 303),
(304, 'nandddo111__$u', 1, 0, 'asdfggtdddddtdddtg@g.c', 0, NULL, NULL, '$2y$10$w/DJt2PJB0jjUe3piamNNeUN2.JzMGfkcW69boizhjVhrbO75anOK', NULL, 304),
(305, 'no1fff11__$u', 1, 0, 'asdfggtdtdddtg@g.c', 0, NULL, NULL, '$2y$10$.YHFBcObFtZo72RWZ54hj.7qAQqGghb4/He5YZA5xTqL8ZXxZk9c6', NULL, 305),
(306, 'no51fff11__$u', 1, 0, 'asdfgg5tdftdddtg@g.c', 0, NULL, NULL, '$2y$10$MU4hhwbx2qDQp8J/M7lUSOiU/q0mPfwfmz3bZ6lVBc5Ofymo41fmu', NULL, 306),
(307, 'no51fuff11u', 1, 0, 'asdfgg5tudftdddtg@g.c', 0, NULL, NULL, '$2y$10$eIIdO34JhpW4hlmrssn7e.ZvnuQLph8yN/IhZAnEb7nzv/ZvgpjuG', NULL, 307),
(308, 'no1fuff11u', 1, 0, 'asdfgg5dftdddtg@g.c', 0, NULL, NULL, '$2y$10$UP.z8IaCnMC5lZRAvVy/tetjg4ql5LLXubwIzZwrnY7QGE8Vi4vGu', NULL, 308),
(313, 'no1fuff11uy', 1, 0, 'asdfgg5dftdddtyg@g.c', 0, NULL, NULL, '$2y$10$mxcd133mEJ7PuG2Ctjqs4e5E1skwi5kApWPhuB.0eiZNCFfOXipg.', NULL, 313),
(321, 'nano3', 1, 0, 'nano3@g.c', 1, 'Nano III', NULL, '$2y$10$BrmMGV6U0eqsDipgeEhKjeqAeWWuTPfRNLVODR04Jg1DBKiLJOXZO', NULL, 90),
(322, 'nano4', 1, 0, 'nano4@g.c', 0, NULL, NULL, '$2y$10$Jf4Ne9pxFP4eSZUysP8bR.QkyMAI3QXGVsubQQm4AV9p/XEHDPaQC', NULL, 90),
(323, 'maerik', 1, 0, 'putazo@g.c', 0, NULL, 'XXX', '$2y$10$vkbU4FHYT7jYzce4xFlqZesflLr/030EXsBBUPOlR03RKfRH/XG6m', NULL, 323),
(324, 'simplon', 1, 0, 'simplon@g.c', 0, NULL, NULL, '$2y$10$XBd2ZIJgqIVRPDxi8.6Rzu8nQfDdHZpfMP//.bj5o3NwQOG4fkHYu', NULL, 324),
(327, 'xxx', 0, 0, 'xxx@g.c', 1, 'yyy', 'ZZ', '$2y$10$k/GsKcFuucJ/MvfBTtUBS.oBY7450u.o0M0gEDGI.Aduz9RctrddW', NULL, 90),
(328, 'juanjo', 0, 0, 'jjjjj@g.c', 0, NULL, NULL, '$2y$10$kOyfUThkzFnWzsU1Ts6EguIUANb9ObJ.lQaflYZCNgsj10Ey90AcS', NULL, 328),
(329, 'jj', 0, 0, 'jj@g.c', 0, NULL, NULL, '$2y$10$UoNz79u8eEQ.n4j4DM0Q5.Xu2nKSqvDQKvJlqInLmffBB4RLCwcAK', NULL, 329),
(331, 'fulano', 1, 0, 'fulano@mail.com', 1, NULL, NULL, '$2y$10$7VlzOhXrUhobcoz/Lc20F.KctzVm13TWOU1CeJJ9opZSj.8QYzU32', NULL, 331),
(332, 'santo', 0, 0, 'sano@gmail.com', 0, NULL, NULL, '$2y$10$Ok8Q56FySFTFvPNn/3hUIeLtPaA3nQSJGBojUziTtJqNJoeLtpnaG', NULL, 332),
(333, 'santo2', 0, 0, 'sano2@gmail.com', 1, NULL, NULL, '$2y$10$PRIcsm/.i4r/o8w2eEeNT.H6psqMKDrB5qFlwY.43sC32PzNrgayC', NULL, 333),
(334, 'santo23', 0, 0, 'sano32@gmail.com', 0, NULL, NULL, '$2y$10$3K8ldfzPdNaN6byfFbvcQeHdFOiaf1oAcgSsmWA6H6y7pM8zmXH2K', NULL, 334),
(335, 'santo203', 0, 0, 'sano302@gmail.com', 0, NULL, NULL, '$2y$10$Chb6SxZBeLCg3Bw2N86/A.O9tqb6gaPgchPvP0GKiHK.3F4HODM22', NULL, 335),
(336, 'santo2083', 0, 0, 'sano3802@gmail.com', 0, NULL, NULL, '$2y$10$fTJA4ECEueGBzb0PYdz/6eMfOfivuWPrt4ktwjOACTkSYC5ufswFi', NULL, 336),
(339, 'san66to2083', 0, 0, 'sano386602@gmail.com', 0, NULL, NULL, '$2y$10$a8YWaW3rysYdKvQ2R.1xV.mZ1LpwiIphKnSRQYzI/A5e9Du56pNXe', NULL, 339),
(340, 'san66to208399', 0, 0, 'sano3896602@gmail.com', 0, NULL, NULL, '$2y$10$AfCwpXnzyIV/F18iNbvBuOZb04cmC0jcVBFTW1PGiH7RG4HnMRQCG', NULL, 340),
(341, 'maso8', 0, 0, 'mas8o@gmail.com', 0, NULL, NULL, '$2y$10$od6b7B8.Gqg25vDSpQMXgOcfDgFoIQ7OOzoyh7UdE1rU58T6TspZG', NULL, 341),
(343, 'sagto208399', 0, 0, 'aadddda@mail.com', 0, NULL, NULL, '$2y$10$.qW3iQOdpcKvr4VhJAzvv.NRoF19l4O1uUYlgcTJdPoOzWLNuGJeq', NULL, 343),
(344, 'saggto208399', 0, 0, 'aadgddda@mail.com', 0, NULL, NULL, '$2y$10$3LY9zKTpQGIBX8GTFHJ83O0kDWYtFOiW8.rVUJUzNosBHK5Fu0sca', NULL, 344),
(345, 'sagcgto208399', 0, 0, 'aacddda@mail.com', 0, NULL, NULL, '$2y$10$H2Y0dOlkA0rpO2Y/w1CCXefRYVUteYoEmAanHfblt4GEsY9qsVq16', NULL, 345),
(346, 'rrrr', 0, 0, 'rrrr@mail.com', 0, NULL, NULL, '$2y$10$3nsjVG3Anq4QyWUvYgdrBeROYoqiwGCf4VUwuaSBu3tV4FpxnV/oq', NULL, 346),
(347, 'rrrr2', 0, 0, 'rrrr2@mail.com', 0, NULL, NULL, '$2y$10$mp5xgjQ.1Y3lYeycpw3xa.PVtJM/K4je0MyW6xUUv/VuL6LIqKIZK', NULL, 347),
(348, 'bbb', 0, 0, 'bbb@mail.com', 0, NULL, NULL, '$2y$10$eL0jpgJWAFKOpIPPxQ1jMumgi05eGm/Yb1GGSQIkayzTErpu4nQrK', NULL, 348),
(349, 'brr', 0, 0, 'brr@mail.com', 0, NULL, NULL, '$2y$10$7KV8ekxJKdLfIn3p6XyMneN0Po6AITGiFehselzsnH1i8XbxMsFCi', NULL, 349),
(350, 'b2', 0, 0, 'b2@mail.com', 0, NULL, NULL, '$2y$10$8pqTW8xAE9E.rBTtx8rv1eYrCtcirwcGSiQpP.jqyDDLFA92./xPi', NULL, 350),
(351, 'nn', 0, 0, 'nn@mail.com', 0, NULL, NULL, '$2y$10$k.lBGQ3dfLK12q86F9Z2YOvxvjCr5mibLzVsSZkXdHSDB/gTz6L..', NULL, 351),
(352, 'nn22', 0, 0, 'nn22@mail.com', 0, NULL, NULL, '$2y$10$X4ti1.3i7DYFPevh2GdadOLP1MDB.4h2K04WkTKqqhOB0Os0Pnlam', NULL, 352),
(353, 'nn33', 0, 0, 'nn33@mail.com', 0, NULL, NULL, '$2y$10$iz3lt4BtmugCDQh8WHW/MuN7V5FiXIeMGi3.8gqUIAzfWV1fPBHcW', NULL, 353),
(354, 'nn44', NULL, 0, 'nn44@mail.com', 1, NULL, NULL, '$2y$10$Lh6YQx2HvwvnhNQ/tsBjrO5Suxrho59mMJ/LqfRKLTPHEQRYUvMBq', NULL, 354),
(355, 'r500', NULL, 0, '500@mail.com', 1, NULL, NULL, '$2y$10$hYayobcJ/UW4UcZWR6GH1u2106o22Oz6R38ZVfZMLIm0P6MsFgjgO', NULL, 355),
(356, 'rr500', NULL, 0, 'rr500@mail.com', 1, NULL, NULL, '$2y$10$NE/mGDJ22K3MwghbGcqqveh.K.5l1YvrwOhntCJG2Mq1TA9rsKF36', NULL, 356),
(357, 'rr5000', NULL, 0, 'rr5000@mail.com', 1, NULL, NULL, '$2y$10$0yoHyh9CshfcX6FT2F6zGuZzzZfJdKtvNJqG/uByNau7xFMwzGsbq', NULL, 357),
(360, 'rr5001', 1, 0, 'rr5001@mail.com', 1, NULL, NULL, '$2y$10$cGezyeUuRKB3BQYNzm/2A.COwInJKgM5Tb1QszWPwrw2z127o7Pom', NULL, 360),
(400, 'master', 0, 0, 'master@gmail.com', 1, 'Peter', 'Parquer', '$2y$10$vcuEqmFdcAlGm35JNLaUCe7saJMwl5mVAK82PLvMHGC.qDpoGE78i', NULL, 9),
(403, 'maso9', NULL, 0, 'maso9@gmail.com', 0, NULL, NULL, '$2y$10$y58qXewSVi.3oc6YGwK2Tu2wnhPOBditu0QnPUm.PYk4XXeqvRhBK', NULL, 403),
(404, 'maso99', NULL, 0, 'maso99@gmail.com', 0, NULL, NULL, '$2y$10$enRfSTawRi01bHbRACdG5.z6NU8FZNZzDNgvOnP0N6iqOBkHn46Ca', NULL, 404),
(406, 'maso999', NULL, 0, 'maso999@gmail.com', 0, NULL, NULL, '$2y$10$gOE9emZlsaU/uKU5VPBufOXNbpZsLxNl1vwxNhSq/fPGlVjkWhL/2', NULL, 406),
(408, 'maso9993', NULL, 0, 'maso9993@gmail.com', 0, NULL, NULL, '$2y$10$rtxNkcFEPhfRYqIq/dZ2/eJAC.h45Sg0fnVyH1pUnETFfnuk7V0TC', NULL, 408),
(409, 'maso99935', NULL, 0, 'maso99935@gmail.com', 0, NULL, NULL, '$2y$10$VlBILSeSPpxgGNGw7str.OnH6Q8oRyXJXlIEw0GadS6V8fBP7vEmm', NULL, 409),
(410, 'maso999350', NULL, 0, 'maso999350@gmail.com', 0, NULL, NULL, '$2y$10$wMnwEDCrZNTd9uuZ2xLerucB.1yfLCIXX9Ma2ctufZ.lJoKt42diC', NULL, 410),
(411, 'maso99935000', NULL, 0, 'maso99935000@gmail.com', 0, NULL, NULL, '$2y$10$gdORtSJ6E05g3hc31wN06.R7tbUqhzwxBx3wiLtZMq743o1bjqcj2', NULL, 411),
(412, 'maso000000', NULL, 0, 'maso9993500000@gmail.com', 0, NULL, NULL, '$2y$10$51IOae3wLsrPAj9K12Cscu1Ry1c0UejY1icfAJIxq9QTBtWIRECg2', NULL, 412),
(413, 'yymaso000000', NULL, 0, 'yyy@gmail.com', 0, NULL, NULL, '$2y$10$OwiOMLoPQ98VVxmDlGb0PO9TKklFzAAJLoPcEVP1So49.56p8PXwW', NULL, 413),
(415, 'y7ymaso000000', NULL, 0, 'yyy7@gmail.com', 0, NULL, NULL, '$2y$10$tfqM3a27XcYs2hAxApLTVukgy153akgfXj2iLuQJIVIpVRQhgReQm', NULL, 415),
(416, 'y7ym0000', NULL, 0, 'yyyff7@gmail.com', 0, NULL, NULL, '$2y$10$bxYA0tLO0CwY3BhUkIKkhuJFFV7kS3lSChmteVQyAtk46Ec6c8QGO', NULL, 416),
(417, 'y7yym0000', NULL, 0, 'yyyyff7@gmail.com', 0, NULL, NULL, '$2y$10$CLTU4FL5/K4CKKiuHtNZ5u1HQ/LJYCIBX8Z8OnnYtXOc.RwsXIenS', NULL, 417),
(419, 'y7yffffym0000', NULL, 0, 'f@gmail.com', 0, NULL, NULL, '$2y$10$79eAU3KAL8ZQSAGtyQPLnu9jPnV.CzrvbQHXZYY36Xltm1xhy2JR6', NULL, 419),
(420, 'y7yuffffym0000', NULL, 0, 'fu@gmail.com', 0, NULL, NULL, '$2y$10$EArW4r7DXtyWsvHkRpePQuBi1Bol8LIrSREEx2v9N6rAIJdLAy3Ce', NULL, 420),
(421, 'y78yym0000', NULL, 0, 'fttuutu99@gmail.com', 0, NULL, NULL, '$2y$10$S4QEdwA6DB0gytGAULM/huZw5ki.dZm1qbQeu5Y2oT4rswbh0KEua', NULL, 421),
(423, 'y78yymi0000', NULL, 0, 'fttuutiu99@gmail.com', 0, NULL, NULL, '$2y$10$DQPVUGZrHAIx894xS1EjdOC0r793pCV5eWNHWR1spMrpr1ZrlMtDS', NULL, 423),
(430, 'y78yym8i0000', NULL, 0, 'fttuu8tiu99@gmail.com', 0, NULL, NULL, '$2y$10$fAj8BEHQNTSndjXljDb/tuPo.NmOfN/nViJ.zVBfN6IPYDbH.iAFC', NULL, 430),
(433, 'y78yy7i0000', NULL, 0, 'fttuu8799@gmail.com', 0, NULL, NULL, '$2y$10$AwzGIyRbJ3LmznmeRungDuNEcHzUn7YLqL/Hd8/6ewg4uvsZ1C/M6', NULL, 433),
(434, 'uuuuuu', NULL, 0, 'rrruuu99@gmail.com', 0, NULL, NULL, '$2y$10$nasfgZqK9w44BbjYSsEnuu/MEOLRw/uvuAIA3B9L7/ZkHtg.3ykSK', NULL, 434),
(435, 'uuuuuu7', NULL, 0, 'rrruuu997@gmail.com', 0, NULL, NULL, '$2y$10$ToVcr4ZVKXhwLAa.65edVua3KhWnNr3MxH8iV9VLFFi1WeQA/efBO', NULL, 435),
(436, 'uuuuiuu7', NULL, 0, 'rrriuuu997@gmail.com', 0, NULL, NULL, '$2y$10$UAKRyMfKsc5ur8CgUORxbO/70K.UPVdGdkECMpVjdwEnYlZYJO9vK', NULL, 436),
(437, 'uuouuiuu7', NULL, 0, 'rrriouuu997@gmail.com', 0, NULL, NULL, '$2y$10$3N4h7/aECTj9VZCZRxpw/u1.4IWRcHGwj8JLWODGI8jkv2cpk8PCq', NULL, 437),
(438, '8uuiuu7', NULL, 0, 'rrr8ouuu997@gmail.com', 0, NULL, NULL, '$2y$10$qXdfVoZcRxXWvw7JksaPdOeaPqtf5aWMjwpUGV5wj4Mzo4hMh691i', NULL, 438),
(439, '8uuiy7', NULL, 0, 'rrr8yuu997@gmail.com', 0, NULL, NULL, '$2y$10$eU0nwMgsydfQv/DQ60J6keRSi/tYlbgfIaYGMucEtXBzIIfBOSsYS', NULL, 439),
(440, '8uuuiy7', NULL, 0, 'rrru8yuu997@gmail.com', 0, NULL, NULL, '$2y$10$uOz.x7S/VHNjTFjfywcvcuhdcmhrsAxmVJEgTsNvRYM2wO/guY6.O', NULL, 440),
(441, 'tiuu7', NULL, 0, 'rrtouuu997@gmail.com', 0, NULL, NULL, '$2y$10$hYPZefgfOvBo0ISyb901ce1bmndcdy.bVcbqiOzjWSRMVUw2ML61G', NULL, 441),
(446, 'tu77', NULL, 0, 'rruuu9797@gmail.com', 0, NULL, NULL, '$2y$10$VI1056RsYiAVS0oEi/RXb.CHP7B/JVMoId.EUsL.bkDHkTI2enXLi', NULL, 446),
(447, 'tutt77', NULL, 0, 'rruttuu9797@gmail.com', 0, NULL, NULL, '$2y$10$aLzXlZ5fz2YgJxPw/bUWaOP4nGfpXfg4uVg4IpQmkOq6d.eh6Km8W', NULL, 447),
(448, 'tutt771', NULL, 0, 'rruttuu97971@gmail.com', 1, NULL, NULL, '$2y$10$DaVHIBbtbklYUn6hKBKuMO6mHHW.4j4D6ChwZTR6Lt.deCfHMljlC', NULL, 448),
(450, 'tutt7712', NULL, 0, 'rruttuu979712@gmail.com', 0, NULL, NULL, '$2y$10$nt6SqgvuS3OveoQ5kB6o.Ohm7btBtatZvkYg1HrBDC4hE2RPJasXe', NULL, 450),
(451, 'tt7712', NULL, 0, 'rrut979712@gmail.com', 0, NULL, NULL, '$2y$10$V9YHsQ0dF0uzKcY7a83oa.nKHH56XLlQzkY.q7MoqFoC0LtylCYAa', NULL, 451),
(454, 'vik', NULL, 0, 'vicktor1989@gmail.com', 0, NULL, NULL, '$2y$10$v7Y0wA2DEcMbrgf8oVYCx.Nyqi4FqYpMWXaa6H9GZShK59CY6p1Ne', NULL, 454),
(455, 'vik4', NULL, 0, 'vicktor1991@gmail.com', 1, 'Perez', NULL, '$2y$10$GkbbmHVPtnzKcOeQJb1JReYR36FLf2LXd3cTqMaH2Yzq5nzxMElmC', NULL, 455),
(456, 'superman', 1, 0, 'superman@gmail.com', 1, NULL, NULL, '$2y$10$q7A9e0w7BRboYpF97O7MQuVRJuXx75cZMtAfeFIo0WyJPMmFA7Swa', NULL, NULL),
(457, 'superman2', NULL, 0, 'superman2@gmail.com', 0, NULL, NULL, '$2y$10$GfTTAg1rnEIm6uiE1ZmXNer12jHRxL7EMeJz8pjOuMR6r6L146FqW', NULL, NULL),
(458, 'superman_dios', NULL, 0, 'superman_dios@gmail.com', 0, NULL, NULL, '$2y$10$a8kZcrtMUQ6PD/lNj8mhOecXwGEQNYeaChQ3EjaRaMt8zw6ZRzm2G', NULL, NULL),
(471, 'doe2000', NULL, 0, 'doe2000@g.com', 0, 'Jhon', 'Doe', '$2y$10$9BVrMiTniozIulZFpaIg9O.VMN/3Xn3ObwEJVUBo6V.w9/dp4rvBW', NULL, NULL),
(472, 'vik45', NULL, 0, 'vik45@gmail.com', 1, NULL, NULL, '$2y$10$mxtt3myQ5hsHYYDi/HfxW.78pe86W18tW.p3QFEXxPOVaZ6OzTv0C', NULL, 472);

-- --------------------------------------------------------

--
-- Table structure for table `user_roles`
--

CREATE TABLE `user_roles` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `user_roles`
--

INSERT INTO `user_roles` (`id`, `user_id`, `role_id`, `created_at`, `updated_at`) VALUES
(1, 5, 3, '2019-10-18 21:58:10', '2020-09-30 10:41:15'),
(2, 1, 100, '2019-10-18 21:58:10', '2019-10-18 21:58:10'),
(3, 4, 100, '2019-10-18 21:58:10', '2019-10-18 21:58:10'),
(5, 9, 100, '2019-10-18 21:58:10', '2019-10-18 21:58:10'),
(6, 11, 100, '2019-10-18 21:58:10', '2019-10-18 21:58:10'),
(7, 13, 100, '2019-10-18 21:58:10', '2019-10-18 21:58:10'),
(9, 4, 2, '2019-10-18 21:58:10', '2019-10-18 21:58:10'),
(10, 48, 2, '2019-10-18 21:58:10', '2019-10-18 21:58:10'),
(12, 86, 2, '2019-10-18 21:58:10', '2020-09-30 13:18:17'),
(14, 86, 100, '2019-10-18 21:58:10', '2019-10-18 21:58:10'),
(15, 87, 2, '2019-10-18 21:58:10', '2019-10-18 21:58:10'),
(16, 89, 3, '2019-10-18 21:58:10', '2019-10-18 21:58:10'),
(17, 4, 3, '2019-10-18 21:58:10', '2019-10-18 21:58:10'),
(18, 1, 3, '2019-10-18 21:58:10', '2019-10-18 21:58:10'),
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
(70, 159, 2, '2019-11-27 16:45:34', NULL),
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
(118, 308, 3, '2019-12-17 14:13:20', NULL),
(119, 313, 3, '2019-12-22 14:27:28', NULL),
(123, 323, 3, '2020-01-15 21:56:45', NULL),
(129, 321, 2, '2020-01-16 16:14:04', NULL),
(131, 339, 100, '2020-09-15 15:26:30', NULL),
(132, 340, 100, '2020-09-15 15:31:54', NULL),
(133, 346, 3, '2020-09-15 16:35:03', NULL),
(134, 347, 3, '2020-09-15 16:36:18', NULL),
(135, 348, 2, '2020-09-15 16:36:44', NULL),
(136, 349, 3, '2020-09-15 16:53:41', NULL),
(137, 349, 2, '2020-09-15 16:53:41', NULL),
(138, 350, 2, '2020-09-15 16:54:09', NULL),
(139, 352, 2, '2020-09-15 17:58:32', NULL),
(140, 353, 2, '2020-09-15 18:23:40', NULL),
(141, 354, 2, '2020-09-15 18:24:26', NULL),
(142, 355, 3, '2020-09-15 20:34:44', NULL),
(143, 356, 3, '2020-09-15 23:58:48', NULL),
(144, 357, 3, '2020-09-16 00:03:25', NULL),
(147, 360, 3, '2020-09-16 00:12:22', NULL),
(148, 90, 2, '2020-09-16 09:58:40', NULL),
(149, 53, 2, '0000-00-00 00:00:00', NULL),
(150, 400, 500, '2020-09-23 23:43:37', NULL),
(151, 411, 1, '2020-09-24 16:49:42', NULL),
(152, 412, 1, '2020-09-24 16:50:22', NULL),
(153, 413, 1, '2020-09-24 16:51:17', NULL),
(155, 415, 1, '2020-09-24 17:04:14', NULL),
(156, 415, 2, '2020-09-24 17:04:14', NULL),
(157, 415, 3, '2020-09-24 17:04:14', NULL),
(158, 433, 1, '2020-09-24 17:48:12', NULL),
(159, 434, 1, '2020-09-24 17:49:10', NULL),
(160, 435, 1, '2020-09-24 17:49:57', NULL),
(161, 435, 2, '2020-09-24 17:49:57', NULL),
(162, 436, 1, '2020-09-24 17:50:55', NULL),
(163, 436, 2, '2020-09-24 17:50:55', NULL),
(164, 437, 1, '2020-09-24 17:51:14', NULL),
(165, 437, 2, '2020-09-24 17:51:14', NULL),
(166, 438, 1, '2020-09-24 17:58:47', NULL),
(167, 438, 2, '2020-09-24 17:58:47', NULL),
(168, 439, 1, '2020-09-24 17:59:05', NULL),
(169, 440, 1, '2020-09-24 18:00:17', NULL),
(170, 441, 1, '2020-09-24 18:00:35', NULL),
(171, 441, 2, '2020-09-24 18:00:35', NULL),
(172, 446, 2, '2020-09-24 18:04:33', NULL),
(173, 446, 3, '2020-09-24 18:04:33', NULL),
(174, 447, 1, '2020-09-24 18:05:49', NULL),
(175, 447, 2, '2020-09-24 18:05:49', NULL),
(176, 447, 3, '2020-09-24 18:05:49', NULL),
(177, 448, 1, '2020-09-24 18:06:18', NULL),
(178, 451, 3, '2020-09-24 21:58:21', NULL),
(179, 455, 3, '2020-09-28 14:23:45', NULL),
(180, 5, 2, '2020-09-30 01:27:42', NULL),
(181, 16, 502, '0000-00-00 00:00:00', NULL),
(182, 472, 3, '2020-10-16 14:25:42', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_sp_permissions`
--

CREATE TABLE `user_sp_permissions` (
  `id` int(11) NOT NULL,
  `sp_permission_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `user_sp_permissions`
--

INSERT INTO `user_sp_permissions` (`id`, `sp_permission_id`, `user_id`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 9, 90, NULL, '2020-09-27 12:07:27', NULL),
(2, 1, 90, NULL, '0000-00-00 00:00:00', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_tb_permissions`
--

CREATE TABLE `user_tb_permissions` (
  `id` int(11) NOT NULL,
  `tb` varchar(80) COLLATE utf16_spanish_ci NOT NULL,
  `can_list_all` tinyint(4) DEFAULT NULL,
  `can_show_all` tinyint(4) DEFAULT NULL,
  `can_list` tinyint(4) DEFAULT NULL,
  `can_show` tinyint(4) DEFAULT NULL,
  `can_create` tinyint(4) DEFAULT NULL,
  `can_update` tinyint(4) DEFAULT NULL,
  `can_delete` tinyint(4) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf16 COLLATE=utf16_spanish_ci;

--
-- Dumping data for table `user_tb_permissions`
--

INSERT INTO `user_tb_permissions` (`id`, `tb`, `can_list_all`, `can_show_all`, `can_list`, `can_show`, `can_create`, `can_update`, `can_delete`, `user_id`, `created_by`, `created_at`, `updated_at`) VALUES
(2, 'products', 0, 0, 0, 1, 0, 1, 1, 168, NULL, '0000-00-00 00:00:00', NULL),
(3, 'foo', 0, 0, 1, 1, 1, 0, 1, 90, NULL, '2020-01-14 00:00:00', '2020-09-27 13:22:17'),
(37, 'bar', 1, 0, 0, 1, 1, 1, 0, 168, NULL, '2020-01-14 23:09:37', '2020-01-15 06:30:55');

-- --------------------------------------------------------

--
-- Table structure for table `xxy`
--

CREATE TABLE `xxy` (
  `id` int(11) NOT NULL,
  `otro` text NOT NULL,
  `otro2` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `collections`
--
ALTER TABLE `collections`
  ADD PRIMARY KEY (`id`),
  ADD KEY `belongs_to` (`belongs_to`);

--
-- Indexes for table `emails`
--
ALTER TABLE `emails`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `facturas`
--
ALTER TABLE `facturas`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `files`
--
ALTER TABLE `files`
  ADD PRIMARY KEY (`id`),
  ADD KEY `belongs_to` (`belongs_to`);

--
-- Indexes for table `folders`
--
ALTER TABLE `folders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `resource_table` (`tb`,`name`,`belongs_to`),
  ADD KEY `owner` (`belongs_to`);

--
-- Indexes for table `folder_other_permissions`
--
ALTER TABLE `folder_other_permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `folder_id` (`folder_id`),
  ADD KEY `belongs_to` (`belongs_to`);

--
-- Indexes for table `folder_permissions`
--
ALTER TABLE `folder_permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `folder_id` (`folder_id`,`access_to`),
  ADD KEY `member` (`access_to`),
  ADD KEY `belongs_to` (`belongs_to`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `created_by` (`belongs_to`),
  ADD KEY `created_by_2` (`created_by`),
  ADD KEY `updated_by` (`updated_by`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `sp_permissions`
--
ALTER TABLE `sp_permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `super_cool_table`
--
ALTER TABLE `super_cool_table`
  ADD PRIMARY KEY (`id`),
  ADD KEY `belongs_to` (`belongs_to`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email_2` (`email`),
  ADD KEY `belongs_to` (`belongs_to`),
  ADD KEY `email` (`email`);

--
-- Indexes for table `user_roles`
--
ALTER TABLE `user_roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id_2` (`user_id`,`role_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `role_id` (`role_id`);

--
-- Indexes for table `user_sp_permissions`
--
ALTER TABLE `user_sp_permissions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `permission` (`sp_permission_id`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `user_tb_permissions`
--
ALTER TABLE `user_tb_permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_table` (`tb`,`user_id`) USING BTREE,
  ADD KEY `user_id` (`user_id`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `xxy`
--
ALTER TABLE `xxy`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `otro2` (`otro2`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `collections`
--
ALTER TABLE `collections`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=222;

--
-- AUTO_INCREMENT for table `facturas`
--
ALTER TABLE `facturas`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `files`
--
ALTER TABLE `files`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=252;

--
-- AUTO_INCREMENT for table `folders`
--
ALTER TABLE `folders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `folder_other_permissions`
--
ALTER TABLE `folder_other_permissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `folder_permissions`
--
ALTER TABLE `folder_permissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=103;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5067;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=503;

--
-- AUTO_INCREMENT for table `sp_permissions`
--
ALTER TABLE `sp_permissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `super_cool_table`
--
ALTER TABLE `super_cool_table`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=782;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=473;

--
-- AUTO_INCREMENT for table `user_roles`
--
ALTER TABLE `user_roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=183;

--
-- AUTO_INCREMENT for table `user_sp_permissions`
--
ALTER TABLE `user_sp_permissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `user_tb_permissions`
--
ALTER TABLE `user_tb_permissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `xxy`
--
ALTER TABLE `xxy`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `collections`
--
ALTER TABLE `collections`
  ADD CONSTRAINT `collections_ibfk_1` FOREIGN KEY (`belongs_to`) REFERENCES `users` (`id`);

--
-- Constraints for table `files`
--
ALTER TABLE `files`
  ADD CONSTRAINT `files_ibfk_1` FOREIGN KEY (`belongs_to`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `folders`
--
ALTER TABLE `folders`
  ADD CONSTRAINT `folders_ibfk_1` FOREIGN KEY (`belongs_to`) REFERENCES `users` (`id`);

--
-- Constraints for table `folder_other_permissions`
--
ALTER TABLE `folder_other_permissions`
  ADD CONSTRAINT `folder_other_permissions_ibfk_1` FOREIGN KEY (`belongs_to`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `folder_permissions`
--
ALTER TABLE `folder_permissions`
  ADD CONSTRAINT `folder_permissions_ibfk_1` FOREIGN KEY (`belongs_to`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`belongs_to`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user_roles`
--
ALTER TABLE `user_roles`
  ADD CONSTRAINT `user_roles_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user_sp_permissions`
--
ALTER TABLE `user_sp_permissions`
  ADD CONSTRAINT `user_sp_permissions_ibfk_1` FOREIGN KEY (`sp_permission_id`) REFERENCES `sp_permissions` (`id`),
  ADD CONSTRAINT `user_sp_permissions_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `user_tb_permissions`
--
ALTER TABLE `user_tb_permissions`
  ADD CONSTRAINT `user_tb_permissions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

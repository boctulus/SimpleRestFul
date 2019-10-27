-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Oct 27, 2019 at 08:05 AM
-- Server version: 5.7.27-0ubuntu0.18.04.1
-- PHP Version: 7.2.19-0ubuntu0.18.04.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `api_sb`
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
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `workspace` varchar(40) DEFAULT NULL,
  `belongs_to` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `size`, `cost`, `created_at`, `modified_at`, `deleted_at`, `workspace`, `belongs_to`) VALUES
(100, 'Vodka', 'Chinese', '2 1/4 L', 137, '2019-07-04 00:00:00', '2019-10-24 16:37:19', NULL, '', 4),
(103, 'Juice', 'Delicious juice', '1L', 75, '2019-09-13 00:00:00', NULL, NULL, NULL, 4),
(105, 'Agua mineral', 'De Córdoba', '1L', 525, '2019-03-15 00:00:00', '2019-10-22 22:01:58', '2019-10-23 19:30:16', 'lista publica', 90),
(106, 'Vodka', 'Rusia', '1L', 390, '2019-02-16 00:00:00', NULL, NULL, NULL, 4),
(113, 'Vodkaaaa', 'URU', '1L', 550, '2019-03-31 00:00:00', NULL, NULL, NULL, 86),
(114, 'AAABBB', 'cccccC', '29', 200, '2019-01-23 00:00:00', '2019-10-23 19:28:55', NULL, NULL, 90),
(119, 'CocaCola', 'gaseosa', '1L', 39, '2018-10-15 00:00:00', NULL, NULL, NULL, 1),
(120, 'MiBebida', 'rica rica', '1L', 50, '2018-12-23 00:00:00', NULL, '2019-10-16 21:44:17', NULL, 89),
(121, 'OtraBebida', 'otra', '1L', 20, '2019-09-28 00:00:00', NULL, NULL, NULL, 89),
(122, 'Cerveza de malta', 'Pichu', '1L', 80, '2018-12-29 00:00:00', NULL, '2019-10-19 16:32:00', NULL, 1),
(123, 'PesiLoca', 'bebida cola', '2L', 50, '2018-12-16 00:00:00', '2019-10-19 12:41:42', NULL, 'mylist', 1),
(125, 'Vodka', 'Genial', '3L', 250, '2017-01-10 00:00:00', '2019-10-16 19:56:29', NULL, 'lista publica', 90),
(126, 'Uvas fermentadas', 'Espectacular', '5L', 300, '2019-06-24 00:00:00', '2019-10-14 22:39:51', '2019-10-16 21:43:47', 'lista publica', 90),
(127, 'Vodka venezolano', 'del caribe', '1L', 15, '2019-07-12 00:00:00', NULL, NULL, NULL, 1),
(131, 'Vodkaaaabc', 'Rusia', '1L', 550, '2019-06-04 00:00:00', NULL, NULL, 'secreto', 4),
(132, 'Ron venezolano', 'Rico', '1L', 24, '2019-10-03 00:00:00', NULL, NULL, NULL, 4),
(133, 'Vodka venezolano', 'de Vzla', '1L', 15, '2019-09-19 00:00:00', NULL, NULL, NULL, 4),
(137, 'Agua ardiente', 'Si que arde!', '1L', 120, '2019-07-16 00:00:00', NULL, '2019-10-16 19:36:57', 'lista', 87),
(143, 'Agua ', '--', '1L', 10, '2019-06-03 00:00:00', NULL, '2019-10-16 21:44:20', NULL, 4),
(144, 'Juguito XII', 'de manzanas exprimidas', '2L', 150, '2019-01-12 00:00:00', NULL, NULL, 'lista2', 90),
(145, 'Juguito XII', 'de manzanas exprimidas', '1L', 350, '2019-02-09 00:00:00', NULL, '2019-10-23 15:58:37', 'lista24', 90),
(146, 'Wisky', NULL, '2L', 255, '2019-08-31 00:00:00', '2019-10-16 10:28:20', '2019-10-16 21:43:50', 'lista24', 90),
(147, 'Aqua fresh', 'Rico', '1L', 10, '2019-03-20 00:00:00', NULL, NULL, 'comparto', 4),
(148, 'Alcohol etílico', '', '1L', 5, '2019-04-21 00:00:00', NULL, '2019-10-16 21:44:24', 'comparto', 4),
(151, 'Juguito XIII', 'Rico', '1L', 355, '2019-10-03 00:00:00', '2019-10-15 17:00:58', '2019-10-23 14:42:24', 'lista24', 90),
(155, 'Super-jugo', 'BBB', '12', 12, '2019-09-22 00:00:00', '2019-10-25 09:44:10', NULL, NULL, 48),
(156, 'JJ', 'AA', '2L', 120, '2019-07-30 00:00:00', NULL, NULL, NULL, 48),
(159, 'Agua mineral', 'De Cba', '2L', 19, '2019-10-14 18:08:45', '2019-10-23 16:11:17', NULL, NULL, 90),
(160, 'Limonada', 'Rica', '500ML', 50, '2019-10-23 14:05:30', NULL, NULL, NULL, 1),
(161, 'DD', 'BB', '1L', 100, '2019-10-24 12:39:08', '2019-10-24 12:39:33', NULL, NULL, 113),
(162, 'Juguito de Mabelita', 'de manzanas exprimidas', '2L', 166, '2019-10-25 08:36:26', NULL, NULL, NULL, 113),
(163, 'ABC', 'DEF', '6L', 600, '2019-10-26 10:05:00', NULL, NULL, NULL, 1),
(164, 'AAA', 'BBB', '3L', 333, '2019-10-26 19:48:26', '2019-10-26 22:39:29', '2019-10-26 19:59:28', NULL, 112),
(165, 'ZZZ', 'zzz', '0.5L', 20, '2019-10-26 22:38:39', NULL, NULL, NULL, 112),
(166, 'DD', 'dd', '0.5L', 40, '2019-10-26 22:38:39', '2019-10-26 22:39:00', NULL, NULL, 112);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(60) NOT NULL,
  `firstname` varchar(50) DEFAULT NULL,
  `lastname` varchar(80) DEFAULT NULL,
  `password` varchar(60) DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `belongs_to` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `firstname`, `lastname`, `password`, `deleted_at`, `belongs_to`) VALUES
(1, 'boctulus@gmail.com', '', '0', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 0),
(4, 'pbozzolo@gmail.com', 'Paulinoxxx', 'Bozzoxx', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 0),
(5, 'pepe@gmail.com', 'Pepe', 'Gonzalez', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 1),
(9, 'dios@gmail.com', 'Paulinoxxx', 'Bozzoxx000555', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 0),
(11, 'diosdado@gmail.com', 'Sr', 'Z', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 0),
(13, 'diosdado2@gmail.com', 'Sr', 'D', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 13),
(14, 'juancho@aaa.com', 'Juan', 'Perez', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 0),
(15, 'juancho11@aaa.com', 'Juan XI', 'Perez 10', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 0),
(16, 'mabel@aaa.com', 'Mabel', 'S', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 0),
(17, 'a@b.commmm', 'HHH', 'AAA', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 0),
(20, 'a@b.commmmmmmmmmmm', 'HHH', 'AAA', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 0),
(33, 'a@b.commmmmmmmmmmmX', '', '', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 0),
(34, 'peter@abc.com', 'Peter', 'Norton', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 0),
(36, 'ndrrxdjrtewwrxdhgxwbpeter@abc.com', 'Peter', 'Norton', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 0),
(37, 'xjzrzfiibkjvdeczoeeepeter@abc.com', 'Peter', 'Norton', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 0),
(38, 'udcsoqjyrdgnhqqtukhupeter@abc.com', 'Peter', 'Norton', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 0),
(39, 'qbosmfvwezohbutpifbopeter@abc.com', 'Peter', 'Norton', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 0),
(40, 'gjappgiduiqczagnousspeter@abc.com', 'Peter', 'Norton', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 0),
(41, 'ymcshlekdzhugvmwbjpipeter@abc.com', 'Peter', 'Norton', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 0),
(42, 'peterrr@abc.com', 'Peterrr', 'Norton', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 1),
(43, 'vydqkgqszpncijwhxeiapeter@abc.com', 'Peter', 'Norton', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 0),
(44, 'itbrknzsfnawnhxgmockpeter@abc.com', 'Peter', 'Norton', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 0),
(45, 'cproifnsfxvkxtppbgdupeter@abc.com', 'Peter', 'Norton', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 0),
(46, 'sexdjjkbhmhqtpbtkhsnpeter@abc.com', 'Peter', 'Norton', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 0),
(47, 'atlsqcgxgszbpcrzydykpeter@abc.com', 'Peter', 'Norton', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 0),
(48, 'gates@outlook.com', 'Bill', 'Gates', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 0),
(51, 'kkk@bbbbbb.com', 'Sr K', 'NS/NC', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 0),
(52, 'tito@gmail.com', 'Tito', 'El Grande', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 0),
(53, 'ooooiiii@gmail.com', 'Oooo', 'iiiiiiii', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 0),
(54, 'booooiiii@gmail.com', 'AAA', 'BBB', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 0),
(55, 'iooobooooiiii@gmail.com', 'IIoo', 'ahaha', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 0),
(56, 'iooobooooiiioooi@gmail.com', 'IIoo', 'ahaha', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 0),
(57, 'iooobooooiiioooi@gmail.commmm', 'IIoo', 'ahaha', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 0),
(58, 'kkk@bbbbbb.commmm', 'Sr K', 'NS/NC', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 0),
(59, 'kkkbooooiiii@gmail.com', 'Ooookkk', 'kkkk', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 0),
(60, 'aaa@bbbb.com', 'Jjjj', 'kkk', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 0),
(61, 'aaa@bbbb.commmm', 'Jjjj', 'kkk', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 0),
(62, 'kkk@bbbbbb.commmmmmm', 'Sr K', 'NS/NC', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 0),
(63, 'aaa@bbbb.commmmmmm', 'Jjjj', 'kkk', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 0),
(64, 'booooiiiixxxx@gmail.com', 'xxx', 'xxxxxxxxxx', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 0),
(65, 'aaa@bbbb.commmmmmmuuuuu', 'Jjjj', 'kkk', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 0),
(66, 'aaa@dgdgd.cococ', 'ajajaj', 'ajajaj', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 0),
(67, 'booooiiiferfr@gmail.com', 'BillY', 'GGGG', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 0),
(68, 'aaa@dgdgd.cococo', 'ajajaj', 'ajajaj', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 0),
(69, 'test@gmail.com', 'TEST', '---', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 0),
(70, 'kkk@bbJJJJJJJ', 'Sr K', 'NS/NC', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 0),
(72, 'aie@b.c', 'Sr K', 'NS/NC', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 0),
(73, 'mabelf450@gmail.com', 'Mabel', 'F', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 0),
(74, 'abc@def.com', 'Sr K', 'NS/NC', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 0),
(75, 'abc@def.commm', 'Sr K', 'NS/NC', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 0),
(76, 'abc@def.net', 'Sr K', 'NS/NC', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 0),
(77, 'abc@def.co', 'Sr K', 'NS/NC', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 0),
(78, 'abc@def.cox', 'Sr K', 'NS/NC', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 0),
(79, 'feli@', 'Sr K', 'NS/NC', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 0),
(80, 'feli@casa', 'Sr K', 'NS/NC', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 0),
(81, 'feli@casa.com', 'Sr K', 'NS/NC', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 0),
(82, 'feli@casa.net', 'Sr K', 'NS/NC', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 0),
(83, 'feli@casa.neto', 'Sr K', 'NS/NC', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 0),
(84, 'pablo@', 'PPP', 'AAA', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 0),
(85, 'feli@teamo', 'Felipe', 'Bozzolo', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 0),
(86, 'nuevo@gmail.com', 'Norberto', 'Nullo', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 0),
(87, 'pedro@gmail.com', 'Pedro', 'Picapiedras', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 0),
(88, 'feli@abc', 'Felipe', 'Bozzzolo', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 0),
(89, 'h@', 'Sr H', 'J', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 0),
(90, 'nano@', 'Nano', 'Perez', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, NULL),
(102, 'feli@delacasita', 'Sr K', 'NS/NC', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 102),
(103, 'feli@delacasita2', 'Sr K', 'NS/NC', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 103),
(104, 'feli@delacasita5', 'Sr K', 'NS/NC', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 104),
(105, 'feli@delacasita50', 'Sr K', 'NS/NC', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 105),
(106, 'feli@delacasita50000', 'Sr K', 'NS/NC', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 106),
(107, 'feli@delacasita50000700', 'Sr K', 'NS/NC', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 107),
(108, 'feli@delacasita50000700800', 'Sr K', 'NS/NC', '$2y$10$/ehgjdS8p8IbRKMW4AVVOuX38p8yMIZinciIWsj79rDUfRfKH6/56', NULL, 108),
(109, 'feli@compeon', 'Sr K', 'NS/NC', '$2y$10$ocJZwzelZ3W.3Eas5ig/q.qaBm79ottSuJ8ee2wXv9584INHL2RpW', NULL, 109),
(110, 'feli@compeon_mundial', 'Sr K', 'NS/NC', '$2y$10$k4kFWXmQacW4LDS.j4gVk./LqKPUtOc9XaObNWojsAzmFnIxbLZ8u', NULL, 110),
(111, 'feli@compeon_dios', 'Sr K', 'NS/NC', '$2y$10$nYh5nGXM6mVwwAP93G4Nv.m8P8aQmJKLm5fODQBqdmzzSCEZzDiOC', NULL, 111),
(112, 'superpepe@', 'SuperPepe', '', '$2y$10$c1O8yBBSUTsso.mSroaxjelGzgSkLSmPcIKkpR6pdG0HUPVKbHesO', NULL, 112),
(113, 'mabelsusanaf@gmail.com', NULL, NULL, NULL, NULL, 113);

-- --------------------------------------------------------

--
-- Table structure for table `user_role`
--

CREATE TABLE `user_role` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  `creation_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modification_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `user_role`
--

INSERT INTO `user_role` (`id`, `user_id`, `role_id`, `creation_date`, `modification_date`) VALUES
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
(20, 90, 2, '2019-10-18 21:58:10', '2019-10-18 21:58:10'),
(21, 85, 2, '2019-10-18 21:58:10', '2019-10-18 21:58:10'),
(22, 48, 3, '2019-10-18 21:58:10', '2019-10-18 21:58:10'),
(23, 105, 2, '2019-10-18 22:12:34', NULL),
(24, 106, 2, '2019-10-19 11:06:17', NULL),
(25, 107, 3, '2019-10-19 11:31:55', NULL),
(26, 108, 3, '2019-10-19 11:37:13', NULL),
(27, 109, 1, '2019-10-19 16:07:26', NULL),
(28, 110, 1, '2019-10-23 09:31:13', NULL),
(29, 111, 1, '2019-10-23 12:16:14', NULL),
(33, 112, 1, '2019-10-24 10:43:49', NULL),
(34, 113, 1, '2019-10-24 11:31:10', NULL),
(35, 113, 3, '2019-10-24 11:34:25', NULL),
(36, 112, 3, '2019-10-26 19:47:48', NULL);

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
-- Indexes for table `user_role`
--
ALTER TABLE `user_role`
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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `group_permissions`
--
ALTER TABLE `group_permissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `other_permissions`
--
ALTER TABLE `other_permissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=167;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=114;

--
-- AUTO_INCREMENT for table `user_role`
--
ALTER TABLE `user_role`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

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
-- Constraints for table `user_role`
--
ALTER TABLE `user_role`
  ADD CONSTRAINT `user_role_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

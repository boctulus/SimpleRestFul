-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Sep 29, 2019 at 08:53 AM
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
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` varchar(240) NOT NULL,
  `size` varchar(30) NOT NULL,
  `cost` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `size`, `cost`) VALUES
(95, 'Juice', 'Delicious juice', '1L', 220),
(98, 'Vodka', 'Rusia', '1L', 460),
(100, 'Vodka', 'Bangladesh', '2 1/4 L', 222),
(103, 'Juice', 'Delicious juice', '1L', 165),
(104, 'Vodka', 'Bangladesh', '2 1/4 L', 70),
(105, 'Agua mineral', 'De Córdoba', '1L', 53),
(106, 'Vodka', 'Rusia', '1L', 290),
(108, 'Vodka', 'Bangladesh', '2 1/4 L', 70),
(109, 'Agua mineral', 'De Córdoba', '1L', 530),
(110, 'AAA', 'aaaa aaaa bbb', '1L', 100);

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `name` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`) VALUES
(100, 'admin'),
(1, 'default'),
(2, 'regular');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` int(11) NOT NULL,
  `refresh_token` varchar(120) NOT NULL,
  `login_date` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `refresh_token`, `login_date`, `user_id`) VALUES
(81, 'u382SQb69awEA+1uGFWGg+qsri1yRKoEmNFz0kPZHADRnkM12taLGfEZi76OIHyKZLTZWIr0G4bY0pD/T/eSWPUsRowPz9Ix0L1vTur/GRH0GSPDOsRC4g==', 1569674239, 4),
(90, 'b2sdacViLB12goWVhkDhSr6w5W/A3T6umQPx5VNeo2ZUxrNRCG+B8BjnpMCvGpCFqT9woZpK7rgW+rL4HQCVdigNHFfIAkL5ENHH3PapJuipB2Sfb20asA==', 1569708559, 4);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(60) NOT NULL,
  `firstname` varchar(50) NOT NULL,
  `lastname` varchar(80) DEFAULT NULL,
  `password` varchar(45) NOT NULL,
  `enabled` tinyint(1) NOT NULL DEFAULT '1',
  `quota` int(11) NOT NULL DEFAULT '10000'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `firstname`, `lastname`, `password`, `enabled`, `quota`) VALUES
(1, 'boctulus@gmail.com', '', '0', '561d352157d4dcafc9ca9ba37773711ee4d192fd', 1, 5727),
(4, 'pbozzolo@gmail.com', 'Paulinoxxx', 'Bozzoxx', '561d352157d4dcafc9ca9ba37773711ee4d192fd', 1, 3400),
(5, 'pepe@gmail.com', 'Pepe', 'Gonzalez', '9ee9c0d0bc94a1b9644cee64f1d513ca2f4dafc8', 1, 9818),
(9, 'dios@gmail.com', 'Paulinoxxx', 'Bozzoxx000555', '9ee9c0d0bc94a1b9644cee64f1d513ca2f4dafc8', 1, 10000),
(11, 'diosdado@gmail.com', 'Sr', 'Z', '9ee9c0d0bc94a1b9644cee64f1d513ca2f4dafc8', 1, 5010),
(13, 'diosdado2@gmail.com', 'Sr', 'Z', '9ee9c0d0bc94a1b9644cee64f1d513ca2f4dafc8', 1, 8369),
(14, 'juancho@aaa.com', 'Juan', 'Perez', '9ee9c0d0bc94a1b9644cee64f1d513ca2f4dafc8', 1, 6815),
(15, 'juancho11@aaa.com', 'Juan XI', 'Perez 10', '9ee9c0d0bc94a1b9644cee64f1d513ca2f4dafc8', 1, 8970),
(16, 'mabel@aaa.com', 'Mabel', 'S', '9ee9c0d0bc94a1b9644cee64f1d513ca2f4dafc8', 1, 4405),
(17, 'a@b.commmm', 'HHH', 'AAA', '9ee9c0d0bc94a1b9644cee64f1d513ca2f4dafc8', 1, 5113),
(20, 'a@b.commmmmmmmmmmm', 'HHH', 'AAA', '9ee9c0d0bc94a1b9644cee64f1d513ca2f4dafc8', 1, 2353),
(33, 'a@b.commmmmmmmmmmmX', '', '', '9ee9c0d0bc94a1b9644cee64f1d513ca2f4dafc8', 1, 6425),
(34, 'peter@abc.com', 'Peter', 'Norton', 'xxx', 1, 5065),
(36, 'ndrrxdjrtewwrxdhgxwbpeter@abc.com', 'Peter', 'Norton', 'xxx', 1, 6051),
(37, 'xjzrzfiibkjvdeczoeeepeter@abc.com', 'Peter', 'Norton', 'xxx', 1, 5058),
(38, 'udcsoqjyrdgnhqqtukhupeter@abc.com', 'Peter', 'Norton', 'xxx', 1, 7137),
(39, 'qbosmfvwezohbutpifbopeter@abc.com', 'Peter', 'Norton', 'xxx', 1, 513),
(40, 'gjappgiduiqczagnousspeter@abc.com', 'Peter', 'Norton', 'xxx', 1, 1155),
(41, 'ymcshlekdzhugvmwbjpipeter@abc.com', 'Peter', 'Norton', 'xxx', 1, 4233),
(42, 'wfipnoycrkxlpuhuyetwpeter@abc.com', 'Peter', 'Norton', 'xxx', 1, 7700),
(43, 'vydqkgqszpncijwhxeiapeter@abc.com', 'Peter', 'Norton', 'xxx', 1, 5803),
(44, 'itbrknzsfnawnhxgmockpeter@abc.com', 'Peter', 'Norton', 'xxx', 1, 5914),
(45, 'cproifnsfxvkxtppbgdupeter@abc.com', 'Peter', 'Norton', 'xxx', 1, 2161),
(46, 'sexdjjkbhmhqtpbtkhsnpeter@abc.com', 'Peter', 'Norton', 'xxx', 1, 3061),
(47, 'atlsqcgxgszbpcrzydykpeter@abc.com', 'Peter', 'Norton', 'xxx', 1, 8825),
(48, 'gates@outlook.com', 'Bill', 'Gates', '9ee9c0d0bc94a1b9644cee64f1d513ca2f4dafc8', 1, 0),
(51, 'kkk@bbbbbb.com', 'Sr K', 'NS/NC', 'bcf22dfc6fb76b7366b1f1675baf2332a0e6a7ce', 1, 10000),
(52, 'tito@gmail.com', 'Tito', 'El Grande', '561d352157d4dcafc9ca9ba37773711ee4d192fd', 1, 10000),
(53, 'ooooiiii@gmail.com', 'Oooo', 'iiiiiiii', '561d352157d4dcafc9ca9ba37773711ee4d192fd', 1, 10000),
(54, 'booooiiii@gmail.com', 'AAA', 'BBB', '561d352157d4dcafc9ca9ba37773711ee4d192fd', 1, 10000),
(55, 'iooobooooiiii@gmail.com', 'IIoo', 'ahaha', '561d352157d4dcafc9ca9ba37773711ee4d192fd', 1, 10000),
(56, 'iooobooooiiioooi@gmail.com', 'IIoo', 'ahaha', '561d352157d4dcafc9ca9ba37773711ee4d192fd', 1, 10000),
(57, 'iooobooooiiioooi@gmail.commmm', 'IIoo', 'ahaha', '561d352157d4dcafc9ca9ba37773711ee4d192fd', 1, 10000),
(58, 'kkk@bbbbbb.commmm', 'Sr K', 'NS/NC', 'bcf22dfc6fb76b7366b1f1675baf2332a0e6a7ce', 1, 10000),
(59, 'kkkbooooiiii@gmail.com', 'Ooookkk', 'kkkk', '9ee9c0d0bc94a1b9644cee64f1d513ca2f4dafc8', 1, 10000),
(60, 'aaa@bbbb.com', 'Jjjj', 'kkk', '17ba0791499db908433b80f37c5fbc89b870084b', 1, 10000),
(61, 'aaa@bbbb.commmm', 'Jjjj', 'kkk', '17ba0791499db908433b80f37c5fbc89b870084b', 1, 10000),
(62, 'kkk@bbbbbb.commmmmmm', 'Sr K', 'NS/NC', 'bcf22dfc6fb76b7366b1f1675baf2332a0e6a7ce', 1, 10000),
(63, 'aaa@bbbb.commmmmmm', 'Jjjj', 'kkk', '17ba0791499db908433b80f37c5fbc89b870084b', 1, 10000),
(64, 'booooiiiixxxx@gmail.com', 'xxx', 'xxxxxxxxxx', '561d352157d4dcafc9ca9ba37773711ee4d192fd', 1, 10000),
(65, 'aaa@bbbb.commmmmmmuuuuu', 'Jjjj', 'kkk', '17ba0791499db908433b80f37c5fbc89b870084b', 1, 10000),
(66, 'aaa@dgdgd.cococ', 'ajajaj', 'ajajaj', '2ab8e336dbdedd7eeca7b1513e11ec5a37956d4c', 1, 10000),
(67, 'booooiiiferfr@gmail.com', 'BillY', 'GGGG', '561d352157d4dcafc9ca9ba37773711ee4d192fd', 1, 10000),
(68, 'aaa@dgdgd.cococo', 'ajajaj', 'ajajaj', '17ba0791499db908433b80f37c5fbc89b870084b', 1, 10000),
(69, 'test@gmail.com', 'TEST', '---', '561d352157d4dcafc9ca9ba37773711ee4d192fd', 1, 10000),
(70, 'kkk@bbJJJJJJJ', 'Sr K', 'NS/NC', 'bcf22dfc6fb76b7366b1f1675baf2332a0e6a7ce', 1, 10000),
(72, 'aie@b.c', 'Sr K', 'NS/NC', 'b60d121b438a380c343d5ec3c2037564b82ffef3', 1, 10000),
(73, 'mabelf450@gmail.com', 'Mabel', 'F', '561d352157d4dcafc9ca9ba37773711ee4d192fd', 1, 10000),
(74, 'abc@def.com', 'Sr K', 'NS/NC', 'b60d121b438a380c343d5ec3c2037564b82ffef3', 1, 10000),
(75, 'abc@def.commm', 'Sr K', 'NS/NC', 'b60d121b438a380c343d5ec3c2037564b82ffef3', 1, 10000),
(76, 'abc@def.net', 'Sr K', 'NS/NC', 'b60d121b438a380c343d5ec3c2037564b82ffef3', 1, 10000),
(77, 'abc@def.co', 'Sr K', 'NS/NC', 'b60d121b438a380c343d5ec3c2037564b82ffef3', 1, 10000),
(78, 'abc@def.cox', 'Sr K', 'NS/NC', 'b60d121b438a380c343d5ec3c2037564b82ffef3', 1, 10000),
(79, 'feli@', 'Sr K', 'NS/NC', 'b60d121b438a380c343d5ec3c2037564b82ffef3', 1, 10000),
(80, 'feli@casa', 'Sr K', 'NS/NC', 'b60d121b438a380c343d5ec3c2037564b82ffef3', 1, 10000),
(81, 'feli@casa.com', 'Sr K', 'NS/NC', 'b60d121b438a380c343d5ec3c2037564b82ffef3', 1, 10000),
(82, 'feli@casa.net', 'Sr K', 'NS/NC', 'b60d121b438a380c343d5ec3c2037564b82ffef3', 1, 10000),
(83, 'feli@casa.neto', 'Sr K', 'NS/NC', 'b60d121b438a380c343d5ec3c2037564b82ffef3', 1, 10000),
(84, 'pablo@', 'PPP', 'AAA', 'b60d121b438a380c343d5ec3c2037564b82ffef3', 1, 2),
(85, 'feli@teamo', 'Felipe', 'Bozzolo', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 1, 10000);

-- --------------------------------------------------------

--
-- Table structure for table `user_role`
--

CREATE TABLE `user_role` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  `creation_date` int(11) NOT NULL,
  `modification_date` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `user_role`
--

INSERT INTO `user_role` (`id`, `user_id`, `role_id`, `creation_date`, `modification_date`) VALUES
(1, 5, 2, 1569757474, NULL),
(2, 1, 100, 1569757474, NULL),
(3, 4, 100, 1569757474, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_user` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

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
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=111;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=101;

--
-- AUTO_INCREMENT for table `sessions`
--
ALTER TABLE `sessions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=91;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=86;

--
-- AUTO_INCREMENT for table `user_role`
--
ALTER TABLE `user_role`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `sessions`
--
ALTER TABLE `sessions`
  ADD CONSTRAINT `sessions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `user_role`
--
ALTER TABLE `user_role`
  ADD CONSTRAINT `user_role_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `user_role_ibfk_2` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

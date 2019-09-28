-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Sep 27, 2019 at 03:58 PM
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
(87, 'Agua mineral', 'De Córdoba', '1L', 55),
(90, 'Vodka', 'Rusia', '1L', 500),
(92, 'Vodka', 'Bangladesh', '2 1/2 L', 70),
(93, 'Agua mineral', 'De Córdoba capital', '1L', 60),
(94, 'Vodka Special', 'Rusia', '1L', 450),
(95, 'Juice', 'Delicious juice', '1L', 165),
(97, 'Agua mineral', 'De Córdoba', '1L', 53),
(98, 'Vodka', 'Rusia', '1L', 259),
(99, 'Juice', 'Delicious juice', '1L', 165),
(100, 'Vodka', 'Bangladesh', '2 1/4 L', 70),
(101, 'Agua mineral', 'De Córdoba', '1L', 53),
(102, 'Vodka', 'Rusia', '1L', 259),
(103, 'Juice', 'Delicious juice', '1L', 165),
(104, 'Vodka', 'Bangladesh', '2 1/4 L', 70),
(105, 'Agua mineral', 'De Córdoba', '1L', 53),
(106, 'Vodka', 'Rusia', '1L', 290),
(108, 'Vodka', 'Bangladesh', '2 1/4 L', 70),
(109, 'Agua mineral', 'De Córdoba', '1L', 53);

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` int(11) NOT NULL,
  `login_date` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `login_date`, `user_id`) VALUES
(1, 1569609264, 74),
(2, 1569609282, 75),
(3, 1569610157, 76),
(4, 1569610180, 77);

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
  `refresh_token` varchar(120) NOT NULL,
  `enabled` tinyint(1) NOT NULL DEFAULT '1',
  `quota` int(11) NOT NULL DEFAULT '10000'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `firstname`, `lastname`, `password`, `refresh_token`, `enabled`, `quota`) VALUES
(1, 'boctulus@gmail.com', '', '0', '561d352157d4dcafc9ca9ba37773711ee4d192fd', '', 1, 5727),
(4, 'pbozzolo@gmail.com', 'Paulinoxxx', 'Bozzoxx', '561d352157d4dcafc9ca9ba37773711ee4d192fd', 'nAR8s3RjfhvyVXRD0/p6G/GNZl0zbiOaECZHmvoplio3Yfn2Bzs8/Q8+aTTC0SaJs5tTc+vKnfjq6Q==', 1, 3400),
(5, 'pepe@gmail.com', 'Pepe', 'Gonzalez', '9ee9c0d0bc94a1b9644cee64f1d513ca2f4dafc8', '', 1, 9818),
(9, 'dios@gmail.com', 'Paulinoxxx', 'Bozzoxx000555', '9ee9c0d0bc94a1b9644cee64f1d513ca2f4dafc8', '', 1, 10000),
(11, 'diosdado@gmail.com', 'Sr', 'Z', '9ee9c0d0bc94a1b9644cee64f1d513ca2f4dafc8', '', 1, 5010),
(13, 'diosdado2@gmail.com', 'Sr', 'Z', '9ee9c0d0bc94a1b9644cee64f1d513ca2f4dafc8', '', 1, 8369),
(14, 'juancho@aaa.com', 'Juan', 'Perez', '9ee9c0d0bc94a1b9644cee64f1d513ca2f4dafc8', '', 1, 6815),
(15, 'juancho11@aaa.com', 'Juan XI', 'Perez 10', '9ee9c0d0bc94a1b9644cee64f1d513ca2f4dafc8', '', 1, 8970),
(16, 'mabel@aaa.com', 'Mabel', 'S', '9ee9c0d0bc94a1b9644cee64f1d513ca2f4dafc8', '', 1, 4405),
(17, 'a@b.commmm', 'HHH', 'AAA', '9ee9c0d0bc94a1b9644cee64f1d513ca2f4dafc8', '', 1, 5113),
(20, 'a@b.commmmmmmmmmmm', 'HHH', 'AAA', '9ee9c0d0bc94a1b9644cee64f1d513ca2f4dafc8', '', 1, 2353),
(33, 'a@b.commmmmmmmmmmmX', '', '', '9ee9c0d0bc94a1b9644cee64f1d513ca2f4dafc8', '', 1, 6425),
(34, 'peter@abc.com', 'Peter', 'Norton', 'xxx', '', 1, 5065),
(36, 'ndrrxdjrtewwrxdhgxwbpeter@abc.com', 'Peter', 'Norton', 'xxx', '', 1, 6051),
(37, 'xjzrzfiibkjvdeczoeeepeter@abc.com', 'Peter', 'Norton', 'xxx', '', 1, 5058),
(38, 'udcsoqjyrdgnhqqtukhupeter@abc.com', 'Peter', 'Norton', 'xxx', '', 1, 7137),
(39, 'qbosmfvwezohbutpifbopeter@abc.com', 'Peter', 'Norton', 'xxx', '', 1, 513),
(40, 'gjappgiduiqczagnousspeter@abc.com', 'Peter', 'Norton', 'xxx', '', 1, 1155),
(41, 'ymcshlekdzhugvmwbjpipeter@abc.com', 'Peter', 'Norton', 'xxx', '', 1, 4233),
(42, 'wfipnoycrkxlpuhuyetwpeter@abc.com', 'Peter', 'Norton', 'xxx', '', 1, 7700),
(43, 'vydqkgqszpncijwhxeiapeter@abc.com', 'Peter', 'Norton', 'xxx', '', 1, 5803),
(44, 'itbrknzsfnawnhxgmockpeter@abc.com', 'Peter', 'Norton', 'xxx', '', 1, 5914),
(45, 'cproifnsfxvkxtppbgdupeter@abc.com', 'Peter', 'Norton', 'xxx', '', 1, 2161),
(46, 'sexdjjkbhmhqtpbtkhsnpeter@abc.com', 'Peter', 'Norton', 'xxx', '', 1, 3061),
(47, 'atlsqcgxgszbpcrzydykpeter@abc.com', 'Peter', 'Norton', 'xxx', '', 1, 8825),
(48, 'gates@outlook.com', 'Bill', 'Gates', '9ee9c0d0bc94a1b9644cee64f1d513ca2f4dafc8', '', 1, 0),
(51, 'kkk@bbbbbb.com', 'Sr K', 'NS/NC', 'bcf22dfc6fb76b7366b1f1675baf2332a0e6a7ce', '', 1, 10000),
(52, 'tito@gmail.com', 'Tito', 'El Grande', '561d352157d4dcafc9ca9ba37773711ee4d192fd', '', 1, 10000),
(53, 'ooooiiii@gmail.com', 'Oooo', 'iiiiiiii', '561d352157d4dcafc9ca9ba37773711ee4d192fd', '', 1, 10000),
(54, 'booooiiii@gmail.com', 'AAA', 'BBB', '561d352157d4dcafc9ca9ba37773711ee4d192fd', 'sdDGwHKr2fetU5DJSQh5f98btNI+6jfiZjxKLOII5tWpfKFr6MI7KVplS9SweYxdgdYYmusPgCydLQ==', 1, 10000),
(55, 'iooobooooiiii@gmail.com', 'IIoo', 'ahaha', '561d352157d4dcafc9ca9ba37773711ee4d192fd', '', 1, 10000),
(56, 'iooobooooiiioooi@gmail.com', 'IIoo', 'ahaha', '561d352157d4dcafc9ca9ba37773711ee4d192fd', '', 1, 10000),
(57, 'iooobooooiiioooi@gmail.commmm', 'IIoo', 'ahaha', '561d352157d4dcafc9ca9ba37773711ee4d192fd', '', 1, 10000),
(58, 'kkk@bbbbbb.commmm', 'Sr K', 'NS/NC', 'bcf22dfc6fb76b7366b1f1675baf2332a0e6a7ce', '', 1, 10000),
(59, 'kkkbooooiiii@gmail.com', 'Ooookkk', 'kkkk', '9ee9c0d0bc94a1b9644cee64f1d513ca2f4dafc8', '', 1, 10000),
(60, 'aaa@bbbb.com', 'Jjjj', 'kkk', '17ba0791499db908433b80f37c5fbc89b870084b', '', 1, 10000),
(61, 'aaa@bbbb.commmm', 'Jjjj', 'kkk', '17ba0791499db908433b80f37c5fbc89b870084b', '', 1, 10000),
(62, 'kkk@bbbbbb.commmmmmm', 'Sr K', 'NS/NC', 'bcf22dfc6fb76b7366b1f1675baf2332a0e6a7ce', '', 1, 10000),
(63, 'aaa@bbbb.commmmmmm', 'Jjjj', 'kkk', '17ba0791499db908433b80f37c5fbc89b870084b', '', 1, 10000),
(64, 'booooiiiixxxx@gmail.com', 'xxx', 'xxxxxxxxxx', '561d352157d4dcafc9ca9ba37773711ee4d192fd', '', 1, 10000),
(65, 'aaa@bbbb.commmmmmmuuuuu', 'Jjjj', 'kkk', '17ba0791499db908433b80f37c5fbc89b870084b', '', 1, 10000),
(66, 'aaa@dgdgd.cococ', 'ajajaj', 'ajajaj', '2ab8e336dbdedd7eeca7b1513e11ec5a37956d4c', '', 1, 10000),
(67, 'booooiiiferfr@gmail.com', 'BillY', 'GGGG', '561d352157d4dcafc9ca9ba37773711ee4d192fd', '', 1, 10000),
(68, 'aaa@dgdgd.cococo', 'ajajaj', 'ajajaj', '17ba0791499db908433b80f37c5fbc89b870084b', '', 1, 10000),
(69, 'test@gmail.com', 'TEST', '---', '561d352157d4dcafc9ca9ba37773711ee4d192fd', '', 1, 10000),
(70, 'kkk@bbJJJJJJJ', 'Sr K', 'NS/NC', 'bcf22dfc6fb76b7366b1f1675baf2332a0e6a7ce', '', 1, 10000),
(72, 'aie@b.c', 'Sr K', 'NS/NC', 'b60d121b438a380c343d5ec3c2037564b82ffef3', 'QBONqom9zGJMTS+BaeQDv4SnzVgonXOhqRIywFCWCaefDEXWNQj262IidnXpEmW76MZfzuW0lcpOlg==', 1, 10000),
(73, 'mabelf450@gmail.com', 'Mabel', 'F', '561d352157d4dcafc9ca9ba37773711ee4d192fd', 'gN2cjb4bP/OcI4ioTWuUSO52X7TOEdGXoWk1nm9IkjNQjuaAwv8qFM8BX62A2Dfu8kyWi02Jb782MauQnNcGePgplwjUQyHl1LbxOmdW67zrIwQX', 1, 10000),
(74, 'abc@def.com', 'Sr K', 'NS/NC', 'b60d121b438a380c343d5ec3c2037564b82ffef3', '0XJdTSb6Lps55WalU8s6IYIlynYOyJe4ZJhxdXxwOe0lOYmosTssy7cl6gT8sVbFIAV+E4GeWVGJk7QiPDQrAgK+gk3d63AMTi1E7rcm2R5jLmdq', 1, 10000),
(75, 'abc@def.commm', 'Sr K', 'NS/NC', 'b60d121b438a380c343d5ec3c2037564b82ffef3', 'pyCA9mQqH8w5hbAYP2Q25kCGv1pV2//0u9j7IOYzlUHily532X+m//oiSHD+MFtKPDsk1uifl9bBpkHm63pB3eqIQHbSRWxKV9noxp1iViP2/c55', 1, 10000),
(76, 'abc@def.net', 'Sr K', 'NS/NC', 'b60d121b438a380c343d5ec3c2037564b82ffef3', 'TPFmg8F1IooyXmc2y7Avv3IzElLvT91CKAGhLnlFe6Qamr1Wuljkqm65OiS4ZrFXUZScIE+yqSO4UHI37JNjMv5VsQ6Gcwc+Elrs4Y1tBhy3ECm2', 1, 10000),
(77, 'abc@def.co', 'Sr K', 'NS/NC', 'b60d121b438a380c343d5ec3c2037564b82ffef3', 'trWzRFr25pVoFoSQj7ezxiuxf6OGiNQ33KURlTBJxyjHowsOQsmXI+zmmD7zzNRZSMtFDMtGvP/W62bUWNN/4a4IviPe3elFCqQUacg/lQmEikFH', 1, 10000);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

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
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=110;

--
-- AUTO_INCREMENT for table `sessions`
--
ALTER TABLE `sessions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=78;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `sessions`
--
ALTER TABLE `sessions`
  ADD CONSTRAINT `sessions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Sep 19, 2019 at 08:29 PM
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
(74, 'Vodka', 'Rusia', '1L', 225),
(81, 'Juice', 'Delicious juice', '1L', 55),
(82, 'Vodka', 'Bangladesh', '2 1/4 L', 80),
(87, 'Agua mineral', 'De Córdoba', '1L', 5);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(60) NOT NULL,
  `firstname` varchar(50) NOT NULL,
  `lastname` varchar(80) DEFAULT NULL,
  `password` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `firstname`, `lastname`, `password`) VALUES
(1, 'boctulus@gmail.com', '', '0', '561d352157d4dcafc9ca9ba37773711ee4d192fd'),
(4, 'pbozzolo@gmail.com', 'Paulinoxxx', 'Bozzoxx', '561d352157d4dcafc9ca9ba37773711ee4d192fd'),
(5, 'pepe@gmail.com', 'Pepe', 'Gonzalez', '9ee9c0d0bc94a1b9644cee64f1d513ca2f4dafc8'),
(9, 'dios@gmail.com', 'Sr', 'Z', '9ee9c0d0bc94a1b9644cee64f1d513ca2f4dafc8'),
(11, 'diosdado@gmail.com', 'Sr', 'Z', '9ee9c0d0bc94a1b9644cee64f1d513ca2f4dafc8'),
(13, 'diosdado2@gmail.com', 'Sr', 'Z', '9ee9c0d0bc94a1b9644cee64f1d513ca2f4dafc8'),
(14, 'juancho@aaa.com', 'Juan', 'Perez', '9ee9c0d0bc94a1b9644cee64f1d513ca2f4dafc8'),
(15, 'juancho11@aaa.com', 'Juan XI', 'Perez 10', '9ee9c0d0bc94a1b9644cee64f1d513ca2f4dafc8'),
(16, 'mabel@aaa.com', 'Mabel', 'S', '9ee9c0d0bc94a1b9644cee64f1d513ca2f4dafc8'),
(17, 'a@b.commmm', 'HHH', 'AAA', '9ee9c0d0bc94a1b9644cee64f1d513ca2f4dafc8'),
(20, 'a@b.commmmmmmmmmmm', 'HHH', 'AAA', '9ee9c0d0bc94a1b9644cee64f1d513ca2f4dafc8'),
(33, 'a@b.commmmmmmmmmmmX', '', '', '9ee9c0d0bc94a1b9644cee64f1d513ca2f4dafc8'),
(34, 'peter@abc.com', 'Peter', 'Norton', 'xxx'),
(36, 'ndrrxdjrtewwrxdhgxwbpeter@abc.com', 'Peter', 'Norton', 'xxx'),
(37, 'xjzrzfiibkjvdeczoeeepeter@abc.com', 'Peter', 'Norton', 'xxx'),
(38, 'udcsoqjyrdgnhqqtukhupeter@abc.com', 'Peter', 'Norton', 'xxx'),
(39, 'qbosmfvwezohbutpifbopeter@abc.com', 'Peter', 'Norton', 'xxx'),
(40, 'gjappgiduiqczagnousspeter@abc.com', 'Peter', 'Norton', 'xxx');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=90;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

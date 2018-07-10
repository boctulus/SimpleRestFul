-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 10, 2018 at 10:59 PM
-- Server version: 10.1.29-MariaDB
-- PHP Version: 7.2.0

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
  `item_name` varchar(50) NOT NULL,
  `item_description` varchar(240) NOT NULL,
  `item_size` varchar(30) NOT NULL,
  `item_cost` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `item_name`, `item_description`, `item_size`, `item_cost`) VALUES
(1, 'The Itcher', 'Scratch any itch', 'S', 500),
(37, 'The Expensive', 'gfdhdhg', 'XL', 100),
(38, 'Original', 'asdf', 'XL', 6666);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(45) DEFAULT NULL,
  `password` varchar(45) NOT NULL,
  `token` varchar(350) DEFAULT NULL,
  `tokenExpiration` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `token`, `tokenExpiration`) VALUES
(1, 'boctulus', '561d352157d4dcafc9ca9ba37773711ee4d192fd', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE1MzEyNTYzNzAsImV4cCI6MTUzMTI1NjY3MCwiZGF0YSI6eyJpZCI6IjEiLCJ1c2VybmFtZSI6ImJvY3R1bHVzIn19.bHJ9qBEgW8FToUiC8D9qofF8zV3akYOMQ34O8R9PA2A', 1531256670),
(4, 'pbozzolo', '561d352157d4dcafc9ca9ba37773711ee4d192fd', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE1MzEwODA1MjAsImV4cCI6MTUzMTA4MDgyMCwiZGF0YSI6eyJpZCI6IjQiLCJ1c2VybmFtZSI6InBib3p6b2xvIn19.2AOQJfZh6kF3f7eXgDOsQ9dXw8Z47KdUiz95O-x36G0', 1531080820);

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
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

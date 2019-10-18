-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Oct 18, 2019 at 08:27 PM
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
(1, 4, 90, 0, 1, 1),
(2, 5, 87, 0, 1, 1),
(4, 6, 90, 1, 1, 1),
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
(100, 'Vodka', 'China', '2 1/4 L', 130, '2019-07-04 00:00:00', NULL, NULL, '', 4),
(103, 'Juice', 'Delicious juice', '1L', 75, '2019-09-13 00:00:00', NULL, NULL, NULL, 4),
(105, 'Agua mineral', 'De Córdoba', '1L', 525, '2019-03-15 00:00:00', NULL, NULL, NULL, 4),
(106, 'Vodka', 'Rusia', '1L', 390, '2019-02-16 00:00:00', NULL, NULL, NULL, 4),
(113, 'Vodkaaaa', 'URU', '1L', 550, '2019-03-31 00:00:00', NULL, NULL, NULL, 86),
(114, 'AAABBB', 'cccccC', '29', 200, '2019-01-23 00:00:00', NULL, NULL, NULL, 86),
(119, 'CocaCola', 'gaseosa', '1L', 39, '2018-10-15 00:00:00', NULL, NULL, NULL, 1),
(120, 'MiBebida', 'rica rica', '1L', 50, '2018-12-23 00:00:00', NULL, '2019-10-16 21:44:17', NULL, 89),
(121, 'OtraBebida', 'otra', '1L', 20, '2019-09-28 00:00:00', NULL, NULL, NULL, 89),
(122, 'Cerveza de malta', 'Pichu', '1L', 80, '2018-12-29 00:00:00', NULL, NULL, NULL, 1),
(123, 'PesiLoca', 'loca', '2L', 50, '2018-12-16 00:00:00', NULL, NULL, 'mylist', 1),
(125, 'Vodka', 'Genial', '3L', 250, '2017-01-10 00:00:00', '2019-10-16 19:56:29', NULL, 'comparto', 90),
(126, 'Uvas fermentadas', 'Espectacular', '5L', 300, '2019-06-24 00:00:00', '2019-10-14 22:39:51', '2019-10-16 21:43:47', 'comparto', 90),
(127, 'Vodka venezolano', 'del caribe', '1L', 15, '2019-07-12 00:00:00', NULL, NULL, NULL, 1),
(131, 'Vodkaaaabc', 'Rusia', '1L', 550, '2019-06-04 00:00:00', NULL, NULL, 'secreto', 4),
(132, 'Ron venezolano', 'Rico', '1L', 24, '2019-10-03 00:00:00', NULL, NULL, NULL, 4),
(133, 'Vodka venezolano', 'de Vzla', '1L', 15, '2019-09-19 00:00:00', NULL, NULL, NULL, 4),
(137, 'Agua ardiente', 'Si que arde!', '1L', 120, '2019-07-16 00:00:00', NULL, '2019-10-16 19:36:57', 'lista', 87),
(143, 'Agua ', '--', '1L', 10, '2019-06-03 00:00:00', NULL, '2019-10-16 21:44:20', NULL, 4),
(144, 'Juguito XII', 'de manzanas exprimidas', '2L', 150, '2019-01-12 00:00:00', NULL, NULL, 'lista2', 90),
(145, 'Juguito XII', 'de manzanas exprimidas', '1L', 350, '2019-02-09 00:00:00', NULL, NULL, 'lista24', 90),
(146, 'Wisky', NULL, '2L', 255, '2019-08-31 00:00:00', '2019-10-16 10:28:20', '2019-10-16 21:43:50', 'lista24', 90),
(147, 'Aqua fresh', 'Rico', '1L', 10, '2019-03-20 00:00:00', NULL, NULL, 'comparto', 4),
(148, 'Alcohol etílico', '', '1L', 5, '2019-04-21 00:00:00', NULL, '2019-10-16 21:44:24', 'comparto', 4),
(151, 'Juguito XIII', 'Rico', '1L', 355, '2019-10-03 00:00:00', '2019-10-15 17:00:58', NULL, 'lista24', 90),
(155, 'AA', 'BBB', '12', 12, '2019-09-22 00:00:00', NULL, NULL, NULL, 48),
(156, 'JJ', 'AA', '2L', 120, '2019-07-30 00:00:00', NULL, NULL, NULL, 48),
(158, 'AB', 'ab', '1.5L', 250, '2019-10-14 16:57:42', NULL, NULL, NULL, 90),
(159, 'Agua mineral', 'De Cba', '2L', 25, '2019-10-14 18:08:45', '2019-10-15 16:57:25', NULL, NULL, 90);

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `name` varchar(30) NOT NULL,
  `is_admin` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `is_admin`) VALUES
(2, 'basic', 0),
(3, 'regular', 0),
(100, 'admin', 1);

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` int(11) NOT NULL,
  `refresh_token` varchar(120) NOT NULL,
  `login_date` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `role` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `refresh_token`, `login_date`, `user_id`, `role`) VALUES
(107, '6y7wMpO7p0i3e8iQFh0yV+Kxiot7ZvRibA31UtwWmJz0Lxv+FLuCmJxtLP4/UpiQiT6r0fj5mGm2yEIoDvnrTM/HzWUiPBts9mMheXDpPq5yp2D+k+N8vQ==', 1569781245, 78, 2),
(108, 'IwG+1lZBGW4TSolhrIPNtZ/P/tcc6jPEWHk3TFX92RziwTweYDnCYiSYvHQiu5r0xzwExwtfyFshFbhvKXBa6aVYGEwZW+qipFmas/3TQM8RWNBRiRtXKw==', 1569781260, 78, 2),
(109, 'GzALVBWvCinbHGI3peettcBOa2+sMbdCx4lTcwofXCQ6JAyqnugkBScVVGNZ7/xo0HjUeUNkGwF1XRViYW0EuQKW3UbaaOJULkzsuNHXomkEJZxQZNHEtQ==', 1569781277, 78, 2),
(110, 'y0qvIrSnXjg5D9EIhJ+Q2ljCC9pfLwki0+54f1wthHPdZu8EcPj9jtbS9h1pCNFQife8psL+NAbDeONlRC9aUQiWBO9hD7bfH98ROO/mgMR+1t5nHb0uyQ==', 1569781312, 78, 2),
(111, 'di3uo1Lt+YBW/MhldXF0viS667Bs4SAOIdgmHdp/AVvYFS208dVMl5quSjA33L0mpr0SSxxGA46f/MhjFIlWuRp7NVclm2/bNVtqqmOHNEOzRVcb9euhDw==', 1569781349, 78, 100),
(117, '/ofvfcAdCSdz+2jvOsO4t0xsjbib2rEr/fwH+9tygViaW6SAkb4gJ8vPGMG/IZVXEgzLomJnrtrUFsbLFSpOmPpZhLlL4NSmmqAzDkcYBwvVitbipLatsw==', 1569783279, 78, 100),
(118, '0aaQdLF4wXJlM6Iz36qMo3DXCogTm+iCO75TkKai5Y0UwsBRLH223HVaSMegWdvx2sKGJMytg/bniFhxBatpMBTCaU/P0zZOV0L0L3wQh6zahbTed7t9wA==', 1569783316, 78, 100),
(119, 'SXhpofMhf5uuLpm36q4G9RRlCWisfujyFVEmQ5furSIVSu1I12jUdtyepU2Ati4HuQ7s54bNMs8gXwc5VHlIyI4NWezRDBiObAzSJvuDXPiE/qgcEx4enA==', 1569783319, 78, 100),
(122, '40i+vtAHc/SeyaG6v/Puph6SKVq5Df4tMwKy4TQ6027jbJOuDphH5pV34xF0aJfHV5aFZR50rbhERJdKiPKM7HeZgh1Dgh1cSsM8FO4DCNg6WFQKWqXOag==', 1569785870, 4, 100),
(123, 'Z1vonpjLM0IL3rWABXTd9gd1deDlkakx+w7hKTi2kBpmYZ53erO2+LohTSukmPx4anx+/uooikWUK9A5RuKpR5fjJbCFS1r1DCfWIub8166XyzNkvgMMug==', 1569785877, 4, 2),
(124, '/rq2qsgQ7DONiIhWk30gr6P/QAZq1F7AEHYPaU/iHn3XfRNYXNjZnk3cZZaF7ZYLx0CQBAn1d3mG41GzRsP8GtIdCNMVDexe0FFzlVuPTVQ7JuSNeZlYVA==', 1569785956, 4, 2),
(132, 'o5UTcDbvgRwc+xr7M83OFa1dLN9KIbxy/MfGQgMwwnZqt04XM1F/M1RRzhJkt1pvI9a/XzuAVBzC3wSTRsUMolCoNfAcMM3qKYwdWgSd/eOsdtqNxkvhgQ==', 1569813546, 4, 100),
(133, '+lBhjl4YMaAlrCDamjOlPN9+iDvZm6wEitc4zGJbIx0lvLBVJ5vRGqEdsZmnLkLZDoz03SfWNgXb5tmwvqPYr7b3kVmQwdB/5DMyWtkFTE+AR+HkKtJXBQ==', 1569813548, 4, 100),
(135, 'HhH/Kj6UxnstZUKzjaq5aqegVPQaDlGy4D2jTbnZVsbMi5WpCZdV3Q+df0LqG2xss47p6DY7bfJzm2m+IIWZFRBnqXmHSjvEz1a3vgN11iUqWxSMmQtCRg==', 1569813713, 4, 100),
(146, 'WZMu7gVMZguTvCqmSusbId4TfvDvJEXbD3H8apVaJw8zhjXHw+XMbxeS1xUshyYGMqffawb4bRmMT2RzSWlJq1MEtbeo60p+iJleJSHRIA79YyqpPf2qsA==', 1569867378, 86, 2),
(147, 'VubW6Fnc7CDTg54LmOnKZQ13BLkKJVNMXQNkV9b4eT3lqusoNmm5DA93knC2Ko12hDcmGQt69Cnns14Umuk5P5DU7P8pLBh5H5lg5JC75r8Wf2vc9Pzg3A==', 1569867390, 86, 2),
(148, 'pkv7rkDT17P/o5jcqnoxbVg5rpZNXVG7FuCUvgPw+BRrZrhg4yAxkVqTr7dlbl70BzkIxenMosTKbCdXGoW6VqRXL+VMPAx76ljVb5BjU8QgDz+6WGriig==', 1569867393, 86, 2),
(149, '9gIcgD2vbMLi1vJ32WQ/X8u6bqc11m6SoTmWlbvnOgD+06oxsdlBup63EMu8oOzGi/TMwUMPx0keiBDONFYzP2diyjjUHZaKu67F97JwJetovf/RUcRYLA==', 1569867397, 86, 3),
(150, 'N3qTR/6Hyfpst88IPAWg0skTz3bArZ5V6ddYadJJXUd1OTJbpf9knUQx2XTJE4Cj28ES8OW4LILFH/jzgpplR90eMDqi/5Pv14HjOgbvt5bCmAdPGpBlOw==', 1569867398, 86, 3),
(151, 'aT6lmH+UlDPj1X7UMvfOVG7By5FSZ5jn4yCZIC+PKnFENTv1b9A4jKT/hGC5v+1X4CutQ+bwo3mfU6zq2+R1cw3RAKUxzcvnp8MeAwsgC16eZpXLk6KI3Q==', 1569867398, 86, 3),
(152, 'Ou8+f7hxJ4C/Qr/bbZ1c4r2DI7qYPd2Ys6bdoPiYGHZTMc/jyKoVgimq3RbAHnZEKUmoeWKDG2g8bUXR+S3xlyKFL0RHgQLHQcM9kWsKgfXQTVzu/MnNhQ==', 1569867399, 86, 3),
(153, 'i0s+z8XWdgckIcWNrbBXc8R3gmzUejcmMeC2O6h+OAT07edyc5bMEjGgD1uWHxcC2RLjd+9FUSGkmNbcFc2D/OWkPtv8qc2kCeiA96FHVGnKF5XUg0NBTQ==', 1569867399, 86, 3),
(158, 'rbTgCcHsyhidc1JzeeynXqcQwM00NLuRQbTRi3ebDKPad2Agt9/8+O6H6FK+nIkHE+DNsiTxdFFNxcLSweomarKOm4G14HxP763zWF8XNnBmkA1HONoQFQ==', 1569868288, 4, 100),
(159, 'z8ScrU3FCZWZot14SYBFV8e1GlkfG5yuqs6pRfJlx3ClVfIp1TgOxW9bvHnnKaSxy1xPTJEpY6GPmlU41VxqCFQWeKpL0D6jhWvPlNKPqPrcDRGGRBYv0Q==', 1569868327, 4, 2),
(160, 'GvP0suIgcmGZe+Pq4eN0SU7AaW9+bZL3ZUDU/G+xAnNbOZTCbvtUnEXUj9DhDo4mX4mZ8uj/ZoWB3smdW7Nq5gq2tA8ewFKQZJP/ygVF0XVXHzMCXV8TMQ==', 1569868329, 4, 2),
(161, 'agPw21MDCzaIHdd30iNB63OtAwRgspahIvIxKW9Mpu0GfPFojrJfFlfQD4RSNKuZXgIxgbrDbsxF3vonovs99L4E1SvPexRatxH8vzWl0K7v/JcQZ0HnNw==', 1569868340, 4, 2),
(162, 'Av5BFlX2UxQ1DYFLbuopFYR17xtJFsoqm+fqyRa9ykmIE6V9sksemu9YKio333sJutr0gPwKDDC9pfLNky2ryJJQUzRnYfYo6uT9UYYhn+SorNqGJcpLgA==', 1569868344, 4, 2),
(163, 'CZNE9+IswgOjdJIGTAKuf9i8m8jtbqDvEMaUb+bpcckQY/5b8ZskR06HGcKcN6hoUt5U8fo55aeYbTPIcg9mvS5Klflzl4zwHIgCtMNSNEOG8dGHwY7lvA==', 1569868351, 4, 100),
(164, 'PjCIGlY9ioVRlRVyiIBzERf7NJsaYAv2psb6qzK3X0l/X6ByiR7nGojd4ICjL2Db7sEnAW+Hdafy7TIJyQi7LP+XMfbAy7qFeI+F3DZ1mMGFRChdlvTGeg==', 1569868368, 4, 2),
(165, 'yTDD5JXXCU/6kqZ9XMfl/Bn+4y/4xuZbbPEMJt4od8Y3ZoOiPIcBr08+KdAjYpKBS8dJu9WBSrSt0mvX5kqSYtB6N75PBoFZH252MSIs3fMQd7F7TgJS/A==', 1569868408, 4, 2),
(167, 'SI7yDwUmTEeuOaqFZbGGZ2er6/s1CyApgievL9zKnPzaqX3sQNDNzkCod7cf/1G5w8M+gZ8aKgCQaMJ3GMQUcg755NvC9bR1USYFgtStrGANgk+c5lKpcg==', 1569868671, 4, 100),
(168, 'cyP7jMzk47WJ3BHBImsXoCO8BNKV7YBcb5M7i7FVFok+1AGS4WVBNHvBUet6Inei0jgkxrMJcbpufEOUhKDBQg34oAw5W4ADPbPbVOs+QvCnAXI8eKm5RQ==', 1569868676, 4, 2),
(169, '+2b26PGKYovwbUsNNNxV6z26dv4x+4Q1r+biGYu2Hft8mnnNvlMqtwos1c8hJZ9I6CDSv6tx1DIGDkbfciPl050gqbcxPN2ltvsos3KgHyVn79RIdNzu2w==', 1569868714, 4, 100),
(170, '07Z1BuRsxD37LlFbTyPvfXUzShMwoROElKdQ2F68ca2FafbFVYlKGSAube89vL5xu7s0BBGg5GnmDYIVmvwlEYe3wDWZ/vqfKePmDrjoy7raIQvyrpBvYg==', 1569868720, 4, 2),
(171, '+qVzzWiKjn9+FgpPVDGNtw380gtqXw2FxY4Wn95Ia9t9fYrK3yIiPOeeMfokw4k9Gsfb4IC8oA1nW7VdhXQkuIkHKrnXXVy3+ZeyFk8yBIVpIuzM7ZmWaQ==', 1569868784, 4, 2),
(172, 'RjiRv4SU1SbmRrMNbaeZ1QVTIhXhUY6xzWnwvTt5ds6x57WZf3SO4Cy4JYgGREPaI5O3n1hnBapusS+oo0RqhQbo4aZm0XfWoQc6hGOU9jfMrgwoXS5ZfA==', 1569868826, 4, 2),
(173, 'WHKdbOdS0LjAbaz9HKCIPADqiXE5RPuOiC8V3w97r5nL55LFmzFwlc/vki/CjsfMzMgUiRLo8FZ8NQHvbVPmdlFvQdJeKFjZcFDNbFDGoo9bN21B4Wzlmw==', 1569868831, 4, 2),
(174, 'KEmrSdTAUbX5hs71OZTvOH8gcPRA+V/TYPry4Ga5FXgJUBjs89I674ckvnJ8+B04LM36hno0XtelQBUZOFgpDVLvnJehJZiy04spN0bKlo7Lvcf0m/6SvA==', 1569868861, 4, 2),
(175, 'fh1j5tEAdrNxxYQ1wXXm68DzIt8DhJt+AjYzHwDnW469LmB/lMYWeZO5l6hMFZZmFG/sQyHuBXfDNpbP7R9c0hHnNoOykznxkbzKDDtfZrTxn5W8YiExQQ==', 1569869113, 4, 2),
(177, '5V0MbNZomo4ezq83/KODC0C98r+CX3FqC8uXFnxRA1JHq8jsx7ljs1Nbtt5l2tB9QE6pcmV/UawphiISKTI4reOcznnoRVrzD/MmpZ3GFgMCUgni/EsAxg==', 1569869178, 4, 2),
(197, 'wR1CHy04wmASI479wKWPWuKcYJz4TAsKGnGR1FfcTLuVR1CThUecXkPyn+6AwgLupCA3m+P8ZD5rV8+ai2S14Wob7QYg1d3zpHTLzjWVLbQS+CFqKlppbg==', 1570044451, 88, 2),
(198, 'uf+8wFLnpGcITd+E/Ckb1dZshDOcU1QwUb9kkLbUgoaL66FwtBvCA7PX0ZZ5qKB77XA6H99V+w+2WkBPJm6QfFx8X19w/Y8ZI8NvcWNlqVgsBKZKXIIZ3A==', 1570045014, 86, 3),
(199, 'D/OlQd+uDH0+/onwMM87NPghiX/y1Ag/LeCaevnoU0sn7DOYplsxeeRRWx79KsrlShoqkZxJLhDZzjszCC3IWnQIQQIKjG9ipD2RjGHcpIwZsQuKbmWXsQ==', 1570045017, 86, 3),
(200, 'sTeJOBEMXHmKhezYKZ1+EqO/CWrkL7+L/uq89t0mUfLTOF6qtWRdlDXqSliS+XeRV8gSd+vPhOUHojWn0JU7u5CcGqTS+e1eXG3QyiEDCY6Hkfi/sr7MNw==', 1570045023, 86, 3),
(201, '5hFmQcpgclDxfsBkx6EIzHWG/bidqV1egOQCrBY8+DaGS8aiLWwtv2b5CwlLohaFQAajWOcjN6lp94Y/QZfFfcauvt9qCgBLRotpfk4MnNCyRUoG+i40Dg==', 1570045034, 4, 100),
(202, 'Cth29xS+i0CxdLJX0CE+nMn05n/Y47vdbvgCxdhlpfFTQRob7oG+jwYbV5ZXgueEbv5tmgA5IK2BHZqTO5Mk4xtv9qV+ytjIl8dfuw+3jrjLB3uIQpAtJA==', 1570045035, 4, 100),
(203, 'kRLqbyqhdDIGyumZ5l3ULDBHK55JAQw3piYky27Fi87NPgQWh4j1uTR3Fc3mpxxcqUrGmI0QrqJORyFFAyXuotM7DiQuOpTHj9m6JPNIDzxXokNuQgOJCw==', 1570045036, 4, 100),
(204, 'wwWUCvaYbtyvQI8qhATbQ+Kn6VVG3+wrpb3HUy0MM5QoBGTw4XSs2sI4aI2xaxCj+rC0hTznG5BxCXFXKE8G4b3sI44uH1lDiy7jdGz8hlQdsfwkFNsbuw==', 1570045037, 4, 100),
(205, '9lD6tXTf2YtcjXfUcJbN23L2+MtM53Ugw2aiWMTKN2aKfAsrtgYUxFIU5bpWowwO4dA89uoyx6ULDI2NVK/IudJmrI7ik8m/Jq46u3k4S/7hzzv5DYqITA==', 1570045037, 4, 100),
(206, 'jdb2zO7AJBuWOzhP7H+ceUZkWlXVvqRbVPXyHg8xemil5Ngw+OtYPz751ZikXm/6Vr28rvrMocTZ/4DQ5MEg25dBaLsHe1MhJoCo5Tq9EpXH4lxNyH2xwQ==', 1570045157, 4, 2),
(207, 'SqHTKwfas6eFZag856aBQ0DHAdUJU3qdAXNdDosr9lHxvFcoqtUjsnlXilZyIx0fHpYUMj5pdN6c+jwpyNftUjp0AeWy3KjaJ0PKaTYjMY+ECNzYFYdJsA==', 1570045187, 4, 2),
(208, 'yFnVOrCRKoKKJKZqEaorDtzfQ1ktAX06FwYHrN0OvJem8bD8oSR/1FzOxWc+shbN2GR+wzBAJ1exzv2uFXankCmO++SgWAoAlpDWbsnYfW/JvSfoeRqh1w==', 1570045189, 4, 2),
(209, 'aDrR2g7PvUvd8d/F0mg1f49yKfcxN/aePHsaiITK7M/Y27y3RU6METJI8SUS9GT8paEbaxwhF1oQoFsNJmMNDlYFGsQSoJEQYkyHO8p8JaJJLnkoyomQvA==', 1570045191, 4, 2),
(210, 'kfkNgDce0/SValhA5hpUnYcUGicQRcw0NDQ8uGBUgHbbq3jW45iL/eW5H7MX8naTd9NII0shkXcS5AsUQMSIEoOMyBTjzFT1vz/jd6F0i2JsFPTA7/yT1A==', 1570045192, 4, 2),
(211, 'wNAqAFyADk3mQdz9eLXX97BCXTDP6ZQYPo6wF3wQVPciMZZvTzTpYFMjUW7NM1B++mO8Zag5GVD40BA1Cn/bDlbFCUuptJlFpWbG8dnY0tIz2xD6fDzvIA==', 1570045222, 4, 2),
(212, '2wUdzfjyVw5IOGEQcRrfzAdpp7g0BkKcSyYLyRJQkvJYL/91V5sKXg1QjsyiDSwmhV9H+beXswzYOhVsrV+k3/3K+lD1z3Zukm4150hhUIjAbr8ESS9USg==', 1570045227, 4, 100),
(213, 'oUiAQ6kSFIx+EHcNNGQ0E2ogYsFiASaYexi6E/PSyjmC6lnYGDcQiMUHLZWnXUvFHe74my4hMq2xmYZPp1YC6JMap3uDpjbZC6I3c126D5v6qC0yXggWUA==', 1570045228, 4, 100),
(214, '8VaYkUk6J5Xy6b62K+6OS0Y7OJHiCSuEcEQoZI0b1n1qRTmtIfXDK4jqFt4JwJL8qvlqMX8x2Ohk1UjcKNOjkWq0c7XgAw7FzgydIDaCQnajrW86EgSJbQ==', 1570045229, 4, 100),
(215, 'VXS+6c4ioO0OI+4zVLtcQ9u0yGpoROE3OmBtKurYgkb5TFy38Va3XfDn8sPYv3M6UqfVko1VohBtR50PCSGxqQMIh8Xf1bArVm416IIKCUCL3aSKftxp0g==', 1570045288, 4, 100),
(216, 'h8BykNjnwWM0VKkNGjhtkuF58vbz5xAbzLKsF7bpwYmpko0ZA/lNS+/hH1+ApoynDwUArikSlO6/mSxfbRQeJBJxcMcmyvX0vGdavWz6kvIMOsTPSTt7ZQ==', 1570045364, 4, 100),
(217, '+40HXUjJn2z7gCP2VbKA7DGuS2hX6Q4ujtlncoPaKtil0TruXX9KnGaMqBoxXQMoBGSz3oU88zTStRkv/gkGVZfMfP651xjzOwNinN2goDJ5VxLpIkSuQg==', 1570045496, 4, 100),
(218, 'pw1izsCIlSuloKS7gHiTXbEOfW5LSiPn3PUZiqos/1BKDFmW8JUgfwKp4MqdT4ORPFL3N67leewxCjszs64yLDNxsPxOskT6O0wS+HkTyAO2RVpxMXozWw==', 1570045513, 4, 100),
(220, 'mbv6nHrVRZErHrlZVwAKaRiEZ0faUtyjQ2sIiwc/svJsOXiXUqT/VpYEshZqDl+VoB+qyZW6uiNhRaDWY/gRSF88Hq1z/0SKEsFD1MBB7Lw/otxAKzscsQ==', 1570045642, 89, 2),
(221, 'y92DK5tV+2pKhBoD7fq/pY1hXBiHz/YY9VJ0PyhqNo4He7zC4VatJw3bizxNgKxG2xyYOfTI3pIk0zCCmVZDi6PGBPiO6Oin2rPdkA9MyGX2A3WTxIJxRg==', 1570045691, 89, 3),
(226, '69VUxY2LBZJd6y8lxUwcTRakvxbQyT7QJBN57gp29RfJTv7fokBt10ZH1rLM4MS2+jwkLp/mPaUqzqHBqyLRy1IPGGZT+AOmWbNtvdVznEOi+syP0+xFyQ==', 1570052479, 1, 3),
(228, '+ocrk97NoNDJNNE9rH5mca8lZRLcBndooUL9WITPWJFhe21LYJUhshXv2zVAccGdys42S13HXnCBjTX5KDAqgQZx3jQvzqjc7WdkijQqlHa4vjhCeaULMg==', 1570113646, 4, 2),
(234, '1npeW4Cz6AAv2qU0IacqCx2G5SP12AHkl6ZfTxEymnQwAookou+tcJQIAcp3FHEtKt0jN/3X3TtWWbs+rUZ7/BvV8BOQn9pvjpY9pf8GhZQ5Ay2Dn81rbQ==', 1570296240, 1, 3),
(246, 'qoA9nwDa6YteohpaD7ZfqkUyCHH/d4/HBSqK/jcnnGFKPoZ65zd8k9W41WrnqiJAhad6vdtII1fu5WjON1pz0j75oJPqxQELCc4S7UYX6qjE8qJ33k0aWw==', 1570466224, 4, 2),
(247, 'bcrofuD4foPklbv12JACRUhsawaNW8zrwxp4Hnr+Zt74dQRxkWkLTMEoVk30AT3si2rv7y4VcVNV5LThMtZrRtSbBGmBJ09FGPCEb1K0sBc7SrhpGylbRQ==', 1570466238, 4, 2),
(248, 'JxpB+FF3eR76g7bcSL7LYq6TUB92/wjki4PsQV/7lDHf2TS6/QqphtLlvJfbidljShtn9clPpM1LzzqIsjNuI3de60W3epb9DY4K7ee3FDcDZ50QSsXt1A==', 1570466313, 4, 2),
(249, 'MyYtkQityZJ5O/clZeQph1uITjaM+c4IkJDE0fhdpwPtc9fI4hdWrV2qAVgaLZlpZ1jt1a01gh7YtTKy/w6rTShiEicODjuyxG3u+BUmQXg95St+Dx9dcg==', 1570466403, 4, 2),
(271, 'Qogqf8rre+BQMb1CCl6ov0VP00bxIeSsVsK1m5/oc1l9ujT4Av2xsauLB3t2wSm11wuvGVD67A0LpwE7eGEJL9NvDBaVYaf28ms0mFaU08vn+5YI+i8nmA==', 1570831144, 90, 3),
(272, '3PZPLwbQQlhHxFk0Sh5KgKNOsM+twJw2lKB3ATmcvvPGbXZjj1X2T/GkizXd7qyEAu8RvYSawQtcvav/cUB0ue+F5B9K4oLXUzT1UxjHzeMUBhf5K1VT2A==', 1570885652, 1, 3),
(273, 'Ok6PBHJ9xiYoR4IMoopBiSEwXeAnxLCGHE6Rjmfzdk+pnbTVWzuNy0C6Ia5rjUqyzCjaUsuM4UYdIaTZMps+tUBCxNRY0EzmYITi8TyWBQ3gfgtn5LGbtQ==', 1570885661, 1, 3),
(274, 'Jy0+oKOmAZIk+njJtJl+Cg+qw1CsdmzaXQlvFNOOAvS6W3/ChRJJ8aA+uOwfZ1MEFryOR5quI9Ur6HKauPwTSJN5R1S2sS1y82cHphnKE40Z2uqgJ/knfg==', 1570885666, 1, 100),
(275, 'LNUkMutUGy+59+Yt3dyVigp9vYfdgqRJRQluoO5RQpg9N1Ov6O7qlQgPkMf7SKpifC5gqOUqjX6k7pVMpOBqUL8rEtarUuqiLnVot76rt3OhGDs/yMIuAg==', 1570885667, 1, 100),
(278, 'wkq/EQ3y/w80wXR1D0n3/JcggTIdZJxA1wvtZiOeAYt3VZI5abD49aQ17iqFhawv5CqtIV9wwoWAAy9ekZbM1hFmAi4Jjh/zaJQQTjVhH4I+c8lMO+O0rQ==', 1571102451, 1, 100),
(279, 's9EAMmOXs/XWVng7e2m3F8pWS5RVJantScLKROLdYl4rj38n9Z2jWOVxXo+lwzctSqiJn9FFKq6CW7L0Fh/oQiqoYKRcESspjA3BItiLpyr5+ja5T4i3OA==', 1571264959, 1, 3),
(280, 'AirpOfpCsjGTJwx0FeYEFpnZsiOzLoJQjJLKO5l5tMSQx+lQPPayM6dppAOQjhkkON+iAEZ+W8gVHRNIZn+C49Dk7TD5fzuifkETmGxkcQoDt110CqM2oA==', 1571265319, 86, 3),
(281, 'YceRtPDTUS0Rtplt+Oi93Zb8tnBK/IsV8MkW/TmePWUWyW/I9AX/cjigCOflHFCt4QgCFjgQSdPWAKrXpiPMwz7WoV+2jnm2pbsQou1L0SYzqxSNz8e4dA==', 1571265394, 1, 100),
(282, 'lf4sFbcwSMvZy8rP2Gd7ik0Tm9VjHSbcdSsuftt2xX7hjCcwzK8OJ/4zy42ERDmOwAgZXZ9DxTkaMnJYGNEImu/OeTggS0/RuhVzM8Wx74dihRN4vwW27g==', 1571265936, 87, 3),
(283, 'SbYAhQg9bt/G/JJoxX5aVXFrf4c0urKIr1hO0GRmzIZ9qp1Wqp5O/R7wnOs/6+AuhmeuHIaGnlBiun+9fEGOPREXxu0kQ3On7z/S2g76RB5EgdXIXjq4lA==', 1571266955, 1, 100),
(284, '/dOqRGbdsIC6BYeggrK6SqtHP9oT5vxXXLntaWccCTKYBHWT6yraagDqQpYJ34cQht0vuGWRkAm+BrQvIyrd2Hp6XlAvlZ30jHWV+vE3l/j3SwrDolr0bA==', 1571345040, 87, 3),
(285, 'kWOKyoiGP37ZTHMQZA4cZ7ynVSPwKUxLgJp2qGK69X7Z1KOnF7SnSdRwKhNVSpeuslkLA552rvf8omFOWYxW/v5eMzbNC6Jyt3YkXix2N+4DLiQ2ukbxkQ==', 1571345360, 4, 2),
(286, 'd1bTwoSL78DrZyvcDNCxozwFquWA4DHwfO+yL6gDWLUWYCiPOU8w2rhFF58+7dFKk4jl4TAiFSub17sUKseNtN4WDdzf4RfYrrDqr+SAEYGpXKjyX1/Fkw==', 1571345889, 90, 3),
(287, 'sYIH6u2HZNlwwPFqCIoIjXfeW093GS7yUcqIAkg+dLirbUayuaDrMjI4DzREZA9G0e6hCYjKvrQFXU7uzVn86HheaxRuSalLdWtyB3nV6Rrz0G18MDMJ5Q==', 1571345950, 90, 3),
(288, 'uqhFE7kwyTbnwNLP+v6DlOU6sa/vDhN4NfDV1+TAs/98H0bvSwjhMACiRV95ksTbsCS6wcIqq3U5+JK7NKqlPUsqezWDlqFOTaZk2YQ8LSMns8N5yjFGWQ==', 1571345997, 90, 3),
(289, 'kA8lN9dgjYMKKjmYfZCpqLn0/2Mos7U/xna0a51vjVj2SUMp3wIBhVtuMJWWmLdvB+sLximeRoyEmDHtL1mAdJwQHiSQWVE1ne45hH2FVReEZCxn+XHaMg==', 1571346071, 4, 3),
(290, 'c0pFQu3fdrO3/r+xJ0l9Mvt/O4t4Rg7Ry6i0UojjrioXVeBLcD9N5j4MlDDlEm/HdbYmjepxAIRXIZDtr/dqFZn+00dFNkdV3SQofy7zvBiJVnNGTS6VVw==', 1571346084, 4, 2),
(291, 'gGp+RH4yFX2gp7ZpwHFJddcNfqFKWendPdSbnNh/R8YWcg81KTyq5ktEXvDvTX/7/bjbjWr7Hf2WMKeO6PLalxvMIh9qD6fgTCTz40ygNhpOh77U4xGlDg==', 1571370966, 4, 2),
(292, 'aVwIKvKpt681MI5NrEe3QsksOxtrkbk/vkuvuQ+1Oht1DhcB+m6yhzC0UtlDOzZnEP2skalJc7fyMYoKTWTwNZdnv0MxMlao/rzRZt8HO0Y2acrdutMUyw==', 1571371135, 4, 2),
(293, '7AhUmpPaPAREg3m66LNlM7v3rbnD9aUcbz4Qm7uiD7mUr7I99LZNQdbbOK5qSGi8tFKKFYqlZ8VbezSdAQbAxwTyiD3NPdFTFGwPHGwnVq1Wxb8wWyC4mQ==', 1571371380, 1, 3),
(294, 'Cm6xnRiYmTWQnP7BsgQqiciD95TBaMBe87ufeWpEOX/lxBYB6TFlDd+4UOO0JjwLVqQU/oYp7yb9kxjwTv/X1bg/jgvxEXUM+zd7LWdOKP5SRVOwLi2Oag==', 1571420656, 1, 100);

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
  `quota` int(11) NOT NULL DEFAULT '10000',
  `belongs_to` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `firstname`, `lastname`, `password`, `enabled`, `quota`, `belongs_to`) VALUES
(1, 'boctulus@gmail.com', '', '0', '561d352157d4dcafc9ca9ba37773711ee4d192fd', 1, 5727, 0),
(4, 'pbozzolo@gmail.com', 'Paulinoxxx', 'Bozzoxx', '561d352157d4dcafc9ca9ba37773711ee4d192fd', 1, 3400, 0),
(5, 'pepe@gmail.com', 'Pepe', 'Gonzalez', '9ee9c0d0bc94a1b9644cee64f1d513ca2f4dafc8', 1, 9818, 0),
(9, 'dios@gmail.com', 'Paulinoxxx', 'Bozzoxx000555', '9ee9c0d0bc94a1b9644cee64f1d513ca2f4dafc8', 1, 10000, 0),
(11, 'diosdado@gmail.com', 'Sr', 'Z', '9ee9c0d0bc94a1b9644cee64f1d513ca2f4dafc8', 1, 5010, 0),
(13, 'diosdado2@gmail.com', 'Sr', 'Z', '9ee9c0d0bc94a1b9644cee64f1d513ca2f4dafc8', 1, 8369, 0),
(14, 'juancho@aaa.com', 'Juan', 'Perez', '9ee9c0d0bc94a1b9644cee64f1d513ca2f4dafc8', 1, 6815, 0),
(15, 'juancho11@aaa.com', 'Juan XI', 'Perez 10', '9ee9c0d0bc94a1b9644cee64f1d513ca2f4dafc8', 1, 8970, 0),
(16, 'mabel@aaa.com', 'Mabel', 'S', '9ee9c0d0bc94a1b9644cee64f1d513ca2f4dafc8', 1, 4405, 0),
(17, 'a@b.commmm', 'HHH', 'AAA', '9ee9c0d0bc94a1b9644cee64f1d513ca2f4dafc8', 1, 5113, 0),
(20, 'a@b.commmmmmmmmmmm', 'HHH', 'AAA', '9ee9c0d0bc94a1b9644cee64f1d513ca2f4dafc8', 1, 2353, 0),
(33, 'a@b.commmmmmmmmmmmX', '', '', '9ee9c0d0bc94a1b9644cee64f1d513ca2f4dafc8', 1, 6425, 0),
(34, 'peter@abc.com', 'Peter', 'Norton', 'xxx', 1, 5065, 0),
(36, 'ndrrxdjrtewwrxdhgxwbpeter@abc.com', 'Peter', 'Norton', 'xxx', 1, 6051, 0),
(37, 'xjzrzfiibkjvdeczoeeepeter@abc.com', 'Peter', 'Norton', 'xxx', 1, 5058, 0),
(38, 'udcsoqjyrdgnhqqtukhupeter@abc.com', 'Peter', 'Norton', 'xxx', 1, 7137, 0),
(39, 'qbosmfvwezohbutpifbopeter@abc.com', 'Peter', 'Norton', 'xxx', 1, 513, 0),
(40, 'gjappgiduiqczagnousspeter@abc.com', 'Peter', 'Norton', 'xxx', 1, 1155, 0),
(41, 'ymcshlekdzhugvmwbjpipeter@abc.com', 'Peter', 'Norton', 'xxx', 1, 4233, 0),
(42, 'peterrr@abc.com', 'Peterrr', 'Norton', 'xxx', 1, 9999, 1),
(43, 'vydqkgqszpncijwhxeiapeter@abc.com', 'Peter', 'Norton', 'xxx', 1, 5803, 0),
(44, 'itbrknzsfnawnhxgmockpeter@abc.com', 'Peter', 'Norton', 'xxx', 1, 5914, 0),
(45, 'cproifnsfxvkxtppbgdupeter@abc.com', 'Peter', 'Norton', 'xxx', 1, 2161, 0),
(46, 'sexdjjkbhmhqtpbtkhsnpeter@abc.com', 'Peter', 'Norton', 'xxx', 1, 3061, 0),
(47, 'atlsqcgxgszbpcrzydykpeter@abc.com', 'Peter', 'Norton', 'xxx', 1, 8825, 0),
(48, 'gates@outlook.com', 'Bill', 'Gates', '9ee9c0d0bc94a1b9644cee64f1d513ca2f4dafc8', 1, 0, 0),
(51, 'kkk@bbbbbb.com', 'Sr K', 'NS/NC', 'bcf22dfc6fb76b7366b1f1675baf2332a0e6a7ce', 1, 10000, 0),
(52, 'tito@gmail.com', 'Tito', 'El Grande', '561d352157d4dcafc9ca9ba37773711ee4d192fd', 1, 10000, 0),
(53, 'ooooiiii@gmail.com', 'Oooo', 'iiiiiiii', '561d352157d4dcafc9ca9ba37773711ee4d192fd', 1, 10000, 0),
(54, 'booooiiii@gmail.com', 'AAA', 'BBB', '561d352157d4dcafc9ca9ba37773711ee4d192fd', 1, 10000, 0),
(55, 'iooobooooiiii@gmail.com', 'IIoo', 'ahaha', '561d352157d4dcafc9ca9ba37773711ee4d192fd', 1, 10000, 0),
(56, 'iooobooooiiioooi@gmail.com', 'IIoo', 'ahaha', '561d352157d4dcafc9ca9ba37773711ee4d192fd', 1, 10000, 0),
(57, 'iooobooooiiioooi@gmail.commmm', 'IIoo', 'ahaha', '561d352157d4dcafc9ca9ba37773711ee4d192fd', 1, 10000, 0),
(58, 'kkk@bbbbbb.commmm', 'Sr K', 'NS/NC', 'bcf22dfc6fb76b7366b1f1675baf2332a0e6a7ce', 1, 10000, 0),
(59, 'kkkbooooiiii@gmail.com', 'Ooookkk', 'kkkk', '9ee9c0d0bc94a1b9644cee64f1d513ca2f4dafc8', 1, 10000, 0),
(60, 'aaa@bbbb.com', 'Jjjj', 'kkk', '17ba0791499db908433b80f37c5fbc89b870084b', 1, 10000, 0),
(61, 'aaa@bbbb.commmm', 'Jjjj', 'kkk', '17ba0791499db908433b80f37c5fbc89b870084b', 1, 10000, 0),
(62, 'kkk@bbbbbb.commmmmmm', 'Sr K', 'NS/NC', 'bcf22dfc6fb76b7366b1f1675baf2332a0e6a7ce', 1, 10000, 0),
(63, 'aaa@bbbb.commmmmmm', 'Jjjj', 'kkk', '17ba0791499db908433b80f37c5fbc89b870084b', 1, 10000, 0),
(64, 'booooiiiixxxx@gmail.com', 'xxx', 'xxxxxxxxxx', '561d352157d4dcafc9ca9ba37773711ee4d192fd', 1, 10000, 0),
(65, 'aaa@bbbb.commmmmmmuuuuu', 'Jjjj', 'kkk', '17ba0791499db908433b80f37c5fbc89b870084b', 1, 10000, 0),
(66, 'aaa@dgdgd.cococ', 'ajajaj', 'ajajaj', '2ab8e336dbdedd7eeca7b1513e11ec5a37956d4c', 1, 10000, 0),
(67, 'booooiiiferfr@gmail.com', 'BillY', 'GGGG', '561d352157d4dcafc9ca9ba37773711ee4d192fd', 1, 10000, 0),
(68, 'aaa@dgdgd.cococo', 'ajajaj', 'ajajaj', '17ba0791499db908433b80f37c5fbc89b870084b', 1, 10000, 0),
(69, 'test@gmail.com', 'TEST', '---', '561d352157d4dcafc9ca9ba37773711ee4d192fd', 1, 10000, 0),
(70, 'kkk@bbJJJJJJJ', 'Sr K', 'NS/NC', 'bcf22dfc6fb76b7366b1f1675baf2332a0e6a7ce', 1, 10000, 0),
(72, 'aie@b.c', 'Sr K', 'NS/NC', 'b60d121b438a380c343d5ec3c2037564b82ffef3', 1, 10000, 0),
(73, 'mabelf450@gmail.com', 'Mabel', 'F', '561d352157d4dcafc9ca9ba37773711ee4d192fd', 1, 10000, 0),
(74, 'abc@def.com', 'Sr K', 'NS/NC', 'b60d121b438a380c343d5ec3c2037564b82ffef3', 1, 10000, 0),
(75, 'abc@def.commm', 'Sr K', 'NS/NC', 'b60d121b438a380c343d5ec3c2037564b82ffef3', 1, 10000, 0),
(76, 'abc@def.net', 'Sr K', 'NS/NC', 'b60d121b438a380c343d5ec3c2037564b82ffef3', 1, 10000, 0),
(77, 'abc@def.co', 'Sr K', 'NS/NC', 'b60d121b438a380c343d5ec3c2037564b82ffef3', 1, 10000, 0),
(78, 'abc@def.cox', 'Sr K', 'NS/NC', 'b60d121b438a380c343d5ec3c2037564b82ffef3', 1, 10000, 0),
(79, 'feli@', 'Sr K', 'NS/NC', 'b60d121b438a380c343d5ec3c2037564b82ffef3', 1, 10000, 0),
(80, 'feli@casa', 'Sr K', 'NS/NC', 'b60d121b438a380c343d5ec3c2037564b82ffef3', 1, 10000, 0),
(81, 'feli@casa.com', 'Sr K', 'NS/NC', 'b60d121b438a380c343d5ec3c2037564b82ffef3', 1, 10000, 0),
(82, 'feli@casa.net', 'Sr K', 'NS/NC', 'b60d121b438a380c343d5ec3c2037564b82ffef3', 1, 10000, 0),
(83, 'feli@casa.neto', 'Sr K', 'NS/NC', 'b60d121b438a380c343d5ec3c2037564b82ffef3', 1, 10000, 0),
(84, 'pablo@', 'PPP', 'AAA', 'b60d121b438a380c343d5ec3c2037564b82ffef3', 1, 2, 0),
(85, 'feli@teamo', 'Felipe', 'Bozzolo', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 1, 10000, 0),
(86, 'nuevo@gmail.com', 'Norberto', 'Nullo', '561d352157d4dcafc9ca9ba37773711ee4d192fd', 1, 10000, 0),
(87, 'pedro@gmail.com', 'Pedro', 'Picapiedras', '561d352157d4dcafc9ca9ba37773711ee4d192fd', 1, 10000, 0),
(88, 'feli@abc', 'Felipe', 'Bozzzolo', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 1, 10000, 0),
(89, 'h@', 'Sr H', 'J', '9ee9c0d0bc94a1b9644cee64f1d513ca2f4dafc8', 1, 10000, 0),
(90, 'nano@', 'Nano', 'Perez', '9ee9c0d0bc94a1b9644cee64f1d513ca2f4dafc8', 1, 10000, NULL),
(102, 'feli@delacasita', 'Sr K', 'NS/NC', '561d352157d4dcafc9ca9ba37773711ee4d192fd', 1, 10000, 102);

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
(3, 4, 100, 1569757474, NULL),
(5, 9, 100, 1569758632, NULL),
(6, 11, 100, 1569758637, NULL),
(7, 13, 100, 1569758640, NULL),
(9, 4, 2, 1569758640, NULL),
(10, 48, 2, 0, NULL),
(11, 86, 2, 0, NULL),
(12, 86, 3, 0, NULL),
(14, 86, 100, 0, NULL),
(15, 87, 3, 0, NULL),
(16, 89, 3, 0, NULL),
(17, 4, 3, 0, NULL),
(18, 1, 3, 0, NULL),
(20, 90, 3, 0, NULL),
(21, 85, 2, 0, NULL),
(22, 48, 3, 0, NULL);

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
  ADD KEY `id_user` (`user_id`),
  ADD KEY `role` (`role`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=162;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=101;

--
-- AUTO_INCREMENT for table `sessions`
--
ALTER TABLE `sessions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=295;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=103;

--
-- AUTO_INCREMENT for table `user_role`
--
ALTER TABLE `user_role`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

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
-- Constraints for table `sessions`
--
ALTER TABLE `sessions`
  ADD CONSTRAINT `sessions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `sessions_ibfk_2` FOREIGN KEY (`role`) REFERENCES `roles` (`id`);

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

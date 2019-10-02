-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Oct 01, 2019 at 01:49 PM
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
  `cost` int(11) NOT NULL,
  `belongs_to` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `size`, `cost`, `belongs_to`) VALUES
(98, 'Vodka', 'Rusia', '1L', 550, 0),
(100, 'Vodka', 'Bangladesh', '2 1/4 L', 225, 0),
(103, 'Juice', 'Delicious juice', '1L', 75, 0),
(105, 'Agua mineral', 'De Córdoba', '1L', 53, 0),
(106, 'Vodka', 'Rusia', '1L', 290, 0),
(108, 'Vodka', 'Bangladesh', '2 1/4 L', 70, 0),
(109, 'Agua mineral', 'De Córdoba', '1L', 530, 0),
(110, 'AAA', 'aaaa aaaa bbb', '1L', 100, 0),
(111, 'AAA', 'ffff', 'grg4g', 3222, 0);

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
(1, 'guest', 0),
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
(107, '6y7wMpO7p0i3e8iQFh0yV+Kxiot7ZvRibA31UtwWmJz0Lxv+FLuCmJxtLP4/UpiQiT6r0fj5mGm2yEIoDvnrTM/HzWUiPBts9mMheXDpPq5yp2D+k+N8vQ==', 1569781245, 78, 1),
(108, 'IwG+1lZBGW4TSolhrIPNtZ/P/tcc6jPEWHk3TFX92RziwTweYDnCYiSYvHQiu5r0xzwExwtfyFshFbhvKXBa6aVYGEwZW+qipFmas/3TQM8RWNBRiRtXKw==', 1569781260, 78, 1),
(109, 'GzALVBWvCinbHGI3peettcBOa2+sMbdCx4lTcwofXCQ6JAyqnugkBScVVGNZ7/xo0HjUeUNkGwF1XRViYW0EuQKW3UbaaOJULkzsuNHXomkEJZxQZNHEtQ==', 1569781277, 78, 1),
(110, 'y0qvIrSnXjg5D9EIhJ+Q2ljCC9pfLwki0+54f1wthHPdZu8EcPj9jtbS9h1pCNFQife8psL+NAbDeONlRC9aUQiWBO9hD7bfH98ROO/mgMR+1t5nHb0uyQ==', 1569781312, 78, 1),
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
(146, 'WZMu7gVMZguTvCqmSusbId4TfvDvJEXbD3H8apVaJw8zhjXHw+XMbxeS1xUshyYGMqffawb4bRmMT2RzSWlJq1MEtbeo60p+iJleJSHRIA79YyqpPf2qsA==', 1569867378, 86, 1),
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
(188, 'BfW1Xmh9cW281C+jlgiSPmbvLGGFRQMceyefVZ+nArP+uODyWO6qHCPvo/ndSXX8FGziPTLU1CJ6f5ab/Y+0SrG0mHqlzAZ1iCOz2GB3ItwURBgJsfW5Cg==', 1569929758, 86, 3);

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
(85, 'feli@teamo', 'Felipe', 'Bozzolo', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 1, 10000),
(86, 'nuevo@gmail.com', 'Norberto', 'Nullo', '561d352157d4dcafc9ca9ba37773711ee4d192fd', 1, 10000),
(87, 'pedro@gmail.com', 'Pedro', 'Picapiedras', '561d352157d4dcafc9ca9ba37773711ee4d192fd', 1, 10000);

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
(4, 14, 1, 1569758595, NULL),
(5, 9, 100, 1569758632, NULL),
(6, 11, 100, 1569758637, NULL),
(7, 13, 100, 1569758640, NULL),
(9, 4, 2, 1569758640, NULL),
(10, 48, 2, 0, NULL),
(11, 86, 2, 0, NULL),
(12, 86, 3, 0, NULL),
(13, 86, 1, 0, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `belongs_to` (`belongs_to`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=113;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=101;

--
-- AUTO_INCREMENT for table `sessions`
--
ALTER TABLE `sessions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=189;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=88;

--
-- AUTO_INCREMENT for table `user_role`
--
ALTER TABLE `user_role`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Constraints for dumped tables
--

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

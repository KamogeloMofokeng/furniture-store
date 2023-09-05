-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 19, 2021 at 08:20 AM
-- Server version: 10.1.38-MariaDB
-- PHP Version: 7.1.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `loginsystem`
--

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `ID` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `create datetime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `password2` varchar(50) NOT NULL,
  `create_datetime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `username`, `email`, `password`, `password2`, `create_datetime`) VALUES
(1, '', '', 'kamogelo mofokeng', 'mofokengkamogelo7@gmail.com', 'd8a04be94384f0e8ccd6d282ea7ee711', '', '2020-10-04 22:47:14'),
(2, '', '', 'zanele nkosi', 'znkosi@gmail.com', '92a5818a3b85c703b23100f6827cf258', '', '2020-10-13 22:42:05'),
(3, '', '', 'edyM', 'edym@gmail.com', '741c6df522404b15da4d8c932c9d5d16', '', '2020-11-19 10:05:59'),
(7, '', '', 'KamoM', 'KamogeloM@admin.com', 'b53086d558f1127993271e8c504ded45', '', '2021-03-10 16:36:08'),
(12, '', '', 'KamoM', 'KamogeloM@admin.com', '3a029f04d76d32e79367c4b3255dda4d', 'd41d8cd98f00b204e9800998ecf8427e', '2021-03-10 16:53:23'),
(13, '', '', 'KamoM', 'KamogeloM@admin.com', 'a0a080f42e6f13b3a2df133f073095dd', 'd41d8cd98f00b204e9800998ecf8427e', '2021-03-10 16:53:47'),
(14, '', '', 'KamoM', 'KamogeloM@admin.com', 'e034fb6b66aacc1d48f445ddfb08da98', 'd41d8cd98f00b204e9800998ecf8427e', '2021-03-10 16:56:30'),
(15, '', '', 'mofokengkamogelo7@gmail.com', '', 'd8a04be94384f0e8ccd6d282ea7ee711', 'd41d8cd98f00b204e9800998ecf8427e', '2021-03-17 20:14:31'),
(16, '', '', 'kamom', 'mofokengkamogelo7@gmail.com', 'd8a04be94384f0e8ccd6d282ea7ee711', 'd41d8cd98f00b204e9800998ecf8427e', '2021-03-17 20:15:15'),
(17, '', '', 'kamom', 'mofokengkamogelo7@gmail.com', 'd41d8cd98f00b204e9800998ecf8427e', '', '2021-03-17 20:17:50'),
(18, '', '', 'kmo', 'mofokengkamogelo7@gmail.com', 'ba248c985ace94863880921d8900c53f', '', '2021-03-17 20:19:12');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

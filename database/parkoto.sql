-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 10, 2026 at 02:37 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `parkoto`
--

-- --------------------------------------------------------

--
-- Table structure for table `appointment`
--

CREATE TABLE `appointment` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `date` date NOT NULL,
  `description` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `appointment`
--

INSERT INTO `appointment` (`id`, `title`, `date`, `description`) VALUES
(2, 'Hello and Hi', '2024-10-12', 'Center Library, Handouts'),
(3, 'Documents Submission', '2024-10-22', 'Metro Library');

-- --------------------------------------------------------

--
-- Table structure for table `car`
--

CREATE TABLE `car` (
  `register` char(7) NOT NULL,
  `color` varchar(30) NOT NULL,
  `model_year` int(11) NOT NULL,
  `owner_id` char(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `car`
--

INSERT INTO `car` (`register`, `color`, `model_year`, `owner_id`) VALUES
('ABC-123', 'Black', 2023, '120691-123R'),
('DTN-285', 'White', 2018, '281182-070W'),
('FTY-875', 'Navy Blue', 2022, '110982-12J');

-- --------------------------------------------------------

--
-- Table structure for table `fine`
--

CREATE TABLE `fine` (
  `id` int(11) NOT NULL,
  `person` char(11) NOT NULL,
  `car` char(7) NOT NULL,
  `date` date NOT NULL,
  `amount` double NOT NULL,
  `reason` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `fine`
--

INSERT INTO `fine` (`id`, `person`, `car`, `date`, `amount`, `reason`) VALUES
(3, '110982-12J', 'FTY-875', '2024-10-12', 200, 'Wrong Parking'),
(4, '281182-070W', 'DTN-285', '2024-10-12', 60, 'No Seat Belt'),
(5, '110982-12J', 'ABC-123', '2024-10-12', 90, 'Wrong Parking');

-- --------------------------------------------------------

--
-- Table structure for table `person`
--

CREATE TABLE `person` (
  `user_id` int(11) NOT NULL,
  `person_name` varchar(50) NOT NULL,
  `ssn` varchar(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `person`
--

INSERT INTO `person` (`user_id`, `person_name`, `ssn`) VALUES
(4, 'Matti Miettinen', '080173-169T'),
(2, 'Mikko Kivinimi', '110982-12J'),
(10, '', '120691-123R'),
(3, 'Anne Autolilija', '281182-070W');

-- --------------------------------------------------------

--
-- Table structure for table `todo`
--

CREATE TABLE `todo` (
  `id` int(11) NOT NULL,
  `task` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `todo`
--

INSERT INTO `todo` (`id`, `task`) VALUES
(2, 'Send Post by tomorrow'),
(4, 'Project Arts Deadline By November');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `user_id` int(11) NOT NULL,
  `person_name` varchar(50) NOT NULL,
  `user_name` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `user_password` varchar(255) NOT NULL,
  `user_re-password` varchar(255) NOT NULL,
  `person_address` varchar(100) NOT NULL,
  `phone_number` varchar(20) NOT NULL,
  `rol` enum('admin',' user') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_id`, `person_name`, `user_name`, `email`, `user_password`, `user_re-password`, `person_address`, `phone_number`, `rol`) VALUES
(1, 'Admin', 'admin', 'admin@admin.com', '$2y$10$ZO62lCpBXJQGkS7A/QdjMeVuyn8ldh6QjrfIDAaH3o16iAvmT1ZeW', '', 'Earth', '041-123456789', 'admin'),
(2, 'Mikko Kivinimi', 'mikko', 'mikko@mikko.com', '$2y$10$9CAUyofybqFqTkNaEBL63.hR6XssBFki4SoDb.waI7nsfWeQw/Fa2', '', 'Helsingintie 12 A ', '041-2398871', ''),
(3, 'Anne Autolilija', 'anne', 'anne@anne.com', '$2y$10$rkGWaucvwrIzdSSwWWpkUOQ/jOpb0CEBBUAR3gxAMYEsmNn3v22BC', '', 'Kanervapolku 2', '050-16440837', ''),
(4, 'Matti Miettinen', 'matti', 'matti@matti.com', '$2y$10$inqJf78WixNGGZJJQai7GurxW4rTeIWF5JHogkSe4HlP/Mde5BHlG', '', 'Koivukuja 25', '040-1842999', ''),
(8, 'User', 'user', 'user@user.com', '$2y$10$b3itEkGWnIotyuoiWk1iueg8wzCXNswljobl62L41qUZrrOg7xDWW', '', 'Planet Earth', '049-2398765', ''),
(10, 'Hanna Nollinen', 'hanna', 'hanna@hanna.fi', '$2y$10$WoxpHyH7fBJVPYrMKMgecOB5ParSr46Joa3lDlCPG0O761s.L8o.S', '', 'Kesätie A23', '042-3987898', ''),
(12, 'Maria', 'Leppalainen', 'maria@parkoto.fi', '$2y$10$6OdwuWXFAsH8wrw4b8QHPO2vKgSBaOY7r1jVXL.MgAc285UqT4GWq', '', 'Helsinki', '012345678', ''),
(13, 'Sanna Marin', 'Sanna', 'sanna@parkoto.com', '$2y$10$PEpSKg/oxBPv1z0iInt5jOxUdH.FOKcAwviFeDv7hma/z1v23ziam', '', 'Sannatie 4 A 2', '0411234567', ''),
(14, 'Admin One', 'admin_one', 'adminone@parkoto.fi', '$2y$10$lXNsuZCpZDUWwLPdmBZnmOg1VZQNgynMZMtl7sq1lRpZdBNCRgk72', '', 'Adminkatu 21 B 5', '0411234567', 'admin');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `appointment`
--
ALTER TABLE `appointment`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `car`
--
ALTER TABLE `car`
  ADD PRIMARY KEY (`register`),
  ADD KEY `owner_id` (`owner_id`);

--
-- Indexes for table `fine`
--
ALTER TABLE `fine`
  ADD PRIMARY KEY (`id`),
  ADD KEY `person` (`person`),
  ADD KEY `car` (`car`);

--
-- Indexes for table `person`
--
ALTER TABLE `person`
  ADD PRIMARY KEY (`ssn`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `todo`
--
ALTER TABLE `todo`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `appointment`
--
ALTER TABLE `appointment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `fine`
--
ALTER TABLE `fine`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `todo`
--
ALTER TABLE `todo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `car`
--
ALTER TABLE `car`
  ADD CONSTRAINT `car_ibfk_1` FOREIGN KEY (`owner_id`) REFERENCES `person` (`ssn`);

--
-- Constraints for table `fine`
--
ALTER TABLE `fine`
  ADD CONSTRAINT `fine_ibfk_1` FOREIGN KEY (`person`) REFERENCES `person` (`ssn`),
  ADD CONSTRAINT `fine_ibfk_2` FOREIGN KEY (`car`) REFERENCES `car` (`register`);

--
-- Constraints for table `person`
--
ALTER TABLE `person`
  ADD CONSTRAINT `person_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

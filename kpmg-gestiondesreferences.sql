-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 28, 2024 at 03:45 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.1.17

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `kpmg-gestiondesreferences`
--

-- --------------------------------------------------------

--
-- Table structure for table `clients`
--

CREATE TABLE `clients` (
  `idClient` int(250) NOT NULL,
  `companyName` varchar(250) NOT NULL,
  `addedDate` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `clients`
--

INSERT INTO `clients` (`idClient`, `companyName`, `addedDate`) VALUES
(1, 'orange', '2024-06-11 15:32:00'),
(2, 'apple', '2024-06-26 14:29:55'),
(3, 'Ministry of Finance & KFW', '2024-06-28 13:55:09');

-- --------------------------------------------------------

--
-- Table structure for table `credentials`
--

CREATE TABLE `credentials` (
  `referenceId` int(250) NOT NULL,
  `client` int(250) NOT NULL,
  `country` varchar(250) DEFAULT NULL,
  `projectTitle` varchar(250) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `country_VF` varchar(250) DEFAULT NULL,
  `projectTitle_VF` varchar(250) DEFAULT NULL,
  `userId` int(50) NOT NULL,
  `description_VF` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `credentials`
--

INSERT INTO `credentials` (`referenceId`, `client`, `country`, `projectTitle`, `description`, `country_VF`, `projectTitle_VF`, `userId`, `description_VF`) VALUES
(52, 2, 'Tunisia', NULL, NULL, NULL, NULL, 100022, NULL),
(53, 2, 'alaska', 'project chappel', '<div><strong>hi<br></strong><strong><em>ffff</em></strong></div>', 'alaska', 'projet chappel', 100010, '<div><strong>Salut</strong></div>'),
(56, 3, 'Tunisia – 2023', 'MoSIE Project \"Modernization of the State\'s Information System“', '<div>1.For the successful fulfillment of the missions assigned to the digital infrastructure company, a priority intervention area concerns infrastructure mapping. However, in Togo today, documentation is often incomplete, and it is not uncommon for the start of a construction project to result in the destruction of existing installations.&nbsp;<br>2.Furthermore, there are inter-ministerial commissions held during the launch of civil engineering works (water,<br>electricity, ICT, etc.) to ensure that the project does not overlap with existing infrastructure, but these commissions<br>are not effective due to lack of viable data.</div>', NULL, NULL, 100020, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `doctrine_migration_versions`
--

CREATE TABLE `doctrine_migration_versions` (
  `version` varchar(191) NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `messenger_messages`
--

CREATE TABLE `messenger_messages` (
  `id` bigint(20) NOT NULL,
  `body` longtext NOT NULL,
  `headers` longtext NOT NULL,
  `queue_name` varchar(190) NOT NULL,
  `created_at` datetime NOT NULL,
  `available_at` datetime NOT NULL,
  `delivered_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `objectives`
--

CREATE TABLE `objectives` (
  `idObjectif` int(11) NOT NULL,
  `objectif` text DEFAULT NULL,
  `objectif_VF` text DEFAULT NULL,
  `referenceId` int(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `objectives`
--

INSERT INTO `objectives` (`idObjectif`, `objectif`, `objectif_VF`, `referenceId`) VALUES
(14, 'efficacity', 'efficacite', 53),
(17, 'The project, titled \"Modernization of the State\'s Information System\" or MoSIE project, aims to support the Ministry of Finance in creating the necessary conditions for the successful modernization of its information system, with a particular focus on digitizing the following three key areas: Accounting (budgetary, general, and analytical); Treasury Management; and Budgetary/Accounting Information Analysis and Decision Support Report Preparation.', NULL, 56);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `userId` int(50) NOT NULL,
  `firstName` varchar(250) NOT NULL,
  `lastName` varchar(250) NOT NULL,
  `email` varchar(250) NOT NULL,
  `password` varchar(250) NOT NULL,
  `profilPhoto` blob DEFAULT NULL,
  `creationDate` timestamp NOT NULL DEFAULT current_timestamp(),
  `role` varchar(25) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`userId`, `firstName`, `lastName`, `email`, `password`, `profilPhoto`, `creationDate`, `role`) VALUES
(100010, 'az', 'az', 'nairi.mohamedaziz@gmail.com', '$2y$13$HDDBCugZ217b3E0FVFDKfupnNeQTYfeh1LKfkaD1p9m9OhRNjWI3u', NULL, '2024-06-11 15:20:49', 'ROLE_USER'),
(100011, 'aziz', 'nairi', 'tayaaayachi07@gmail.com', '$2y$13$lkl7i/Ws2vyLc7T5HDUSTOUwgEjQw5tawyqeXvG4CtDwIbN4pwpJa', 0x6364, '2018-12-31 23:00:00', ''),
(100012, 'billy', 'eillish', 'gdscesprit@gmail.com', '$2y$13$BaXqZSl9cvp9zvGb4aJyGO5vnDIJ60tu68WdK7OvXpyk0UDEGxdVW', 0x6364, '2019-01-01 16:13:00', ''),
(100013, 'steve', 'stevens', 'fdx@gmail.com', '$2y$13$dO.i7bQYReiK/e/1go97POyV6czkMLfiY2ij3g5mVeRSU5EKunrfS', NULL, '2024-06-24 10:34:00', ''),
(100014, 'aziz', 'nairi', 'lasso@gmail.com', '$2y$13$WM0znW9SKE6xV.W5KPeIzuCUPfB/aYYRdUT04Ixcot6y2Oe9VACk2', NULL, '2024-06-24 11:47:00', ''),
(100015, 'aziz', 'nairi', 'aziz@aziz.com', '$2y$13$05EZCrH1NYh1SNoZrkuib.yGxM0.cSN.NE5cYZLRfVPgCaKhSzZCa', NULL, '2024-06-24 15:56:00', NULL),
(100017, 'aziz', 'nairi', 'espresso@gmail.com', '$2y$13$VzgYAUs.9Kz4hfS4ALVm3usPIYKdtnYGhqojXUxYWqT4i48CJobnm', NULL, '2024-06-24 16:12:00', 'ROLE_ADMIN'),
(100018, 'aziz', 'nairi', 'azzzzz@gmail.com', '$2y$13$D.kwyL7qlwukhSn0pA/lOei3I.OVDDgQiDDLn4JdBJutyP.lFZicq', NULL, '2024-06-25 09:13:00', 'ROLE_ADMIN'),
(100019, 'aziz', 'nairi', 'zzzzzzz@gmail.com', '$2y$13$k/ArCXxND8QMwonoPQNEY.z9fjZCOiseT88Ux0IMDTowi4x5QNn4S', NULL, '2024-06-25 09:13:00', 'ROLE_USER'),
(100020, 'aziz', 'nairi', 'eeeeeee@gmail.com', '$2y$13$M/y.et9Kspi.TxJnaidQVeGGdmyEk5iRrIZoPtXqEVXR19OBO0IEa', NULL, '2024-06-25 09:13:00', 'ROLE_ADMIN'),
(100021, 'aziz', 'nairi', 'espressoaz@gmail.com', 'az', NULL, '2024-06-21 12:45:00', 'ROLE_ADMIN'),
(100022, 'aziz', 'nairi', 'tayaaayacxhi07@gmail.com', '$2y$13$38WeobY5gYqlbF1dIi0oUOxdCH5F89ooTVrpZAxqZ5I3tdxCx7cu.', NULL, '2024-06-25 13:53:14', 'ROLE_ADMIN'),
(100023, 'liv', 'rod', 'tayaaayachi@gmail.com', '$2y$13$jR4CSOwsBicYaxLaPy4Xzu.EVrFebZlljT0nIlW0ee2F2bjyLC2iG', NULL, '2024-06-25 16:47:09', 'ROLE_USER'),
(100024, 'tylor', 'u', 'hey@gmail.com', '$2y$13$Po9brHOM/0PVTAOW5A3Ameq5rmfOWjwlJGJCDgI37QAQKaOlGcq56', NULL, '2024-06-28 12:46:22', 'ROLE_USER');

-- --------------------------------------------------------

--
-- Table structure for table `workstreams`
--

CREATE TABLE `workstreams` (
  `idWorkstream` int(11) NOT NULL,
  `workstream` text DEFAULT NULL,
  `workstream_VF` text DEFAULT NULL,
  `referenceId` int(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `workstreams`
--

INSERT INTO `workstreams` (`idWorkstream`, `workstream`, `workstream_VF`, `referenceId`) VALUES
(40, 'f', 'f', 53),
(41, 'f', 'f', 53),
(44, 'Business Stream: Focuses on institutional preparation, restructuring business processes, and designing IT procurement strategies.', NULL, 56),
(45, 'Technical Stream: Involves planning data migration, designing deployment environments, and updating hardware/software inventories.', NULL, 56),
(46, 'Governance Stream: Establishes governance at the Ministry of Finance, supports project portfolio management, and sets up a Project Management Office (PMO).', NULL, 56),
(47, 'Change Management and Capacity Building Stream: Aims to establish and implement change management strategies and build the capacity of relevant stakeholders.', NULL, 56);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `clients`
--
ALTER TABLE `clients`
  ADD PRIMARY KEY (`idClient`);

--
-- Indexes for table `credentials`
--
ALTER TABLE `credentials`
  ADD PRIMARY KEY (`referenceId`),
  ADD KEY `fk_user_cred` (`userId`),
  ADD KEY `fk_clients_cred` (`client`);

--
-- Indexes for table `doctrine_migration_versions`
--
ALTER TABLE `doctrine_migration_versions`
  ADD PRIMARY KEY (`version`);

--
-- Indexes for table `messenger_messages`
--
ALTER TABLE `messenger_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_75EA56E0FB7336F0` (`queue_name`),
  ADD KEY `IDX_75EA56E0E3BD61CE` (`available_at`),
  ADD KEY `IDX_75EA56E016BA31DB` (`delivered_at`);

--
-- Indexes for table `objectives`
--
ALTER TABLE `objectives`
  ADD PRIMARY KEY (`idObjectif`),
  ADD KEY `fk_obj_cred` (`referenceId`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`userId`);

--
-- Indexes for table `workstreams`
--
ALTER TABLE `workstreams`
  ADD PRIMARY KEY (`idWorkstream`),
  ADD KEY `fk_ws_cred` (`referenceId`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `clients`
--
ALTER TABLE `clients`
  MODIFY `idClient` int(250) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `credentials`
--
ALTER TABLE `credentials`
  MODIFY `referenceId` int(250) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT for table `messenger_messages`
--
ALTER TABLE `messenger_messages`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `objectives`
--
ALTER TABLE `objectives`
  MODIFY `idObjectif` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `userId` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=100025;

--
-- AUTO_INCREMENT for table `workstreams`
--
ALTER TABLE `workstreams`
  MODIFY `idWorkstream` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `credentials`
--
ALTER TABLE `credentials`
  ADD CONSTRAINT `fk_clients_cred` FOREIGN KEY (`client`) REFERENCES `clients` (`idClient`),
  ADD CONSTRAINT `fk_user_cred` FOREIGN KEY (`userId`) REFERENCES `users` (`userId`);

--
-- Constraints for table `objectives`
--
ALTER TABLE `objectives`
  ADD CONSTRAINT `fk_obj_cred` FOREIGN KEY (`referenceId`) REFERENCES `credentials` (`referenceId`) ON DELETE CASCADE;

--
-- Constraints for table `workstreams`
--
ALTER TABLE `workstreams`
  ADD CONSTRAINT `fk_ws_cred` FOREIGN KEY (`referenceId`) REFERENCES `credentials` (`referenceId`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

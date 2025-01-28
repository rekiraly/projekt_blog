-- phpMyAdmin SQL Dump
-- version 4.8.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Erstellungszeit: 28. Jan 2025 um 12:27
-- Server-Version: 10.1.37-MariaDB
-- PHP-Version: 7.3.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `blog_v1`
--
CREATE DATABASE IF NOT EXISTS `blog_v1` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `blog_v1`;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `blog`
--

DROP TABLE IF EXISTS `blog`;
CREATE TABLE `blog` (
  `blog_id` int(11) NOT NULL,
  `blog_headline` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `blog_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `blog_imageAlignment` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `blog_content` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `blog_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `cat_id` int(11) NOT NULL,
  `usr_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `category`
--

DROP TABLE IF EXISTS `category`;
CREATE TABLE `category` (
  `cat_id` int(11) NOT NULL,
  `cat_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `thema_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Daten für Tabelle `category`
--

INSERT INTO `category` (`cat_id`, `cat_name`, `thema_id`) VALUES
(1, 'Über mich', 1),
(2, 'Was steht hier?', 1),
(3, 'Côte d\'Azur', 2),
(4, 'Istria', 3),
(5, 'Essen', 3),
(7, 'Leben', 4),
(9, 'Impressium', 1),
(10, 'Nachrichten', 1);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `commentary`
--

DROP TABLE IF EXISTS `commentary`;
CREATE TABLE `commentary` (
  `com_id` int(11) NOT NULL,
  `com_inhalt` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cat_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `usr_id` int(11) NOT NULL,
  `cat_name` varchar(256) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Daten für Tabelle `commentary`
--

INSERT INTO `commentary` (`com_id`, `com_inhalt`, `cat_date`, `usr_id`, `cat_name`) VALUES
(33, 'My Probe Comment', '2020-07-14 07:33:29', 1, 'newTest'),
(34, 'new new new', '2020-07-14 07:33:29', 1, 'Mobile'),
(35, 'neoneo   neo test', '2020-07-14 07:33:29', 1, 'Mobile'),
(36, 'lmö-', '2020-07-14 07:33:29', 1, 'newTest'),
(37, 'pojolkjm jkh', '2020-07-14 07:33:29', 1, 'newTest'),
(39, 'probe new test', '2020-07-14 07:33:29', 1, 'newTest'),
(40, 'erste', '2020-07-14 07:33:29', 1, 'Food'),
(41, 'zweite', '2020-07-14 07:33:29', 1, 'Food'),
(42, 'new comm', '2020-07-14 07:33:29', 1, 'Mobile'),
(44, 'abibu yoho!!!!', '2020-07-14 07:37:01', 1, 'newTest'),
(45, 'testiiiiiii', '2020-07-14 07:37:24', 1, 'newTest'),
(46, 'vvvvv', '2020-07-14 07:38:00', 1, 'Mobile'),
(47, 'bbjhjhbjkb', '2020-07-14 07:38:20', 1, 'Mobile'),
(48, 'uuu', '2020-07-14 07:42:49', 1, 'Mobile'),
(49, 'hmjnkm my probe\n', '2020-07-14 07:43:44', 1, 'Mobile'),
(50, 'beeeeeeeeeee', '2020-07-14 07:50:19', 1, 'Mobile'),
(51, '', '2021-03-03 10:19:41', 1, ''),
(52, '', '2021-03-03 10:28:52', 1, ''),
(53, 'bla bla bla', '2021-04-30 13:02:56', 1, 'Leben');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `thema`
--

DROP TABLE IF EXISTS `thema`;
CREATE TABLE `thema` (
  `thema_id` int(11) NOT NULL,
  `thema_name` varchar(256) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Daten für Tabelle `thema`
--

INSERT INTO `thema` (`thema_id`, `thema_name`) VALUES
(1, 'Allgemein Fragen'),
(2, 'Frankreich'),
(3, 'Kroatien'),
(4, 'Germany'),
(5, 'Ägypten');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `usr_id` int(11) NOT NULL,
  `usr_firstname` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `usr_lastname` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `usr_email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `usr_city` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `usr_password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Daten für Tabelle `user`
--

INSERT INTO `user` (`usr_id`, `usr_firstname`, `usr_lastname`, `usr_email`, `usr_city`, `usr_password`) VALUES
(1, 'Kira', 'Lysova', 'a@b.c', 'Nuremberg', '$2y$10$tbCYcuHF/flLur6pSSpMheR5DKA2io7T9TcE/Gw3Q/2aulfoQiGD2'),
(2, 'Paul', 'Paulsen', 'paul@paulsen.net', 'Paris', '$2y$10$3vC0YKbOcGVXevncK82iFuUGP611c8Es1DxHVuDZ3652veoAFA2kO');

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`cat_id`),
  ADD KEY `blog_thema_cat` (`thema_id`);

--
-- Indizes für die Tabelle `commentary`
--
ALTER TABLE `commentary`
  ADD PRIMARY KEY (`com_id`),
  ADD KEY `com_ibfk1` (`usr_id`) USING BTREE,
  ADD KEY `com_ibfk2` (`cat_name`(191)) USING BTREE;

--
-- Indizes für die Tabelle `thema`
--
ALTER TABLE `thema`
  ADD PRIMARY KEY (`thema_id`);

--
-- Indizes für die Tabelle `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`usr_id`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `category`
--
ALTER TABLE `category`
  MODIFY `cat_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT für Tabelle `commentary`
--
ALTER TABLE `commentary`
  MODIFY `com_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT für Tabelle `thema`
--
ALTER TABLE `thema`
  MODIFY `thema_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT für Tabelle `user`
--
ALTER TABLE `user`
  MODIFY `usr_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints der exportierten Tabellen
--

--
-- Constraints der Tabelle `category`
--
ALTER TABLE `category`
  ADD CONSTRAINT `blog_thema_cat` FOREIGN KEY (`thema_id`) REFERENCES `thema` (`thema_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

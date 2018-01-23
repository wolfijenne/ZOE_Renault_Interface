-- phpMyAdmin SQL Dump
-- version 4.6.6
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Erstellungszeit: 23. Jan 2018 um 19:17
-- Server-Version: 10.1.30-MariaDB
-- PHP-Version: 5.6.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `spider_zoe`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `vehicles`
--

CREATE TABLE `vehicles` (
  `id` bigint(20) NOT NULL,
  `id_renault_user` int(11) NOT NULL,
  `ze_vin` varchar(20) NOT NULL,
  `name` varchar(128) NOT NULL,
  `ze_activation_code` varchar(20) NOT NULL,
  `ze_assoc_user` varchar(128) NOT NULL,
  `ze_phone_number` varchar(25) NOT NULL,
  `active` varchar(4) NOT NULL,
  `nom_capacity` smallint(5) UNSIGNED NOT NULL,
  `soh` tinyint(3) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `vehicle_measurements`
--

CREATE TABLE `vehicle_measurements` (
  `id` bigint(20) NOT NULL,
  `id_vehicle` bigint(20) NOT NULL,
  `ze_time_stamp` bigint(20) NOT NULL,
  `ze_plugged` varchar(4) NOT NULL,
  `ze_charging` varchar(4) NOT NULL,
  `ze_charge_level` smallint(6) NOT NULL,
  `ze_remaining_range` float NOT NULL,
  `time_inserted` int(11) NOT NULL,
  `ze_charging_point` varchar(50) NOT NULL,
  `ze_remaining_time` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `ze_users`
--

CREATE TABLE `ze_users` (
  `id` int(11) NOT NULL,
  `ze_id` varchar(128) NOT NULL,
  `ze_locale` varchar(10) NOT NULL,
  `ze_country` varchar(3) NOT NULL,
  `ze_timezone` varchar(128) NOT NULL,
  `ze_email` varchar(128) NOT NULL,
  `ze_first_name` varchar(128) NOT NULL,
  `ze_last_name` varchar(128) NOT NULL,
  `ze_phone_number` varchar(128) NOT NULL,
  `ze_username` varchar(512) NOT NULL,
  `ze_password` varchar(512) NOT NULL,
  `ze_token` varchar(512) NOT NULL,
  `ze_refresh_token` varchar(128) NOT NULL,
  `active` varchar(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `vehicles`
--
ALTER TABLE `vehicles`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `vehicle_measurements`
--
ALTER TABLE `vehicle_measurements`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `ze_users`
--
ALTER TABLE `ze_users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `vehicles`
--
ALTER TABLE `vehicles`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT für Tabelle `vehicle_measurements`
--
ALTER TABLE `vehicle_measurements`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=281;
--
-- AUTO_INCREMENT für Tabelle `ze_users`
--
ALTER TABLE `ze_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

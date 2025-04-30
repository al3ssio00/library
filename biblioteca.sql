-- phpMyAdmin SQL Dump
-- version 5.2.1deb3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Creato il: Apr 30, 2025 alle 08:19
-- Versione del server: 8.0.41-0ubuntu0.24.04.1
-- Versione PHP: 8.2.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `biblioteca`
--
CREATE DATABASE IF NOT EXISTS `biblioteca` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci;
USE `biblioteca`;

-- --------------------------------------------------------

--
-- Struttura della tabella `bibliotecaL`
--

CREATE TABLE `bibliotecaL` (
  `id` int NOT NULL,
  `filiale` varchar(100) DEFAULT NULL,
  `cap` varchar(10) DEFAULT NULL,
  `indirizzo` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dump dei dati per la tabella `bibliotecaL`
--

INSERT INTO `bibliotecaL` (`id`, `filiale`, `cap`, `indirizzo`) VALUES
(1, 'Guidonia', '00012', 'Via Palermo'),
(2, 'Tivoli', '00019', 'Via del Corso 12');

-- --------------------------------------------------------

--
-- Struttura della tabella `disponibilita`
--

CREATE TABLE `disponibilita` (
  `id` int NOT NULL,
  `ref_biblioteca` int DEFAULT NULL,
  `ref_libro` int DEFAULT NULL,
  `count` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dump dei dati per la tabella `disponibilita`
--

INSERT INTO `disponibilita` (`id`, `ref_biblioteca`, `ref_libro`, `count`) VALUES
(6, 2, 10, 1),
(8, 2, 12, 8),
(9, 1, 7, 1),
(11, 1, 11, 1),
(12, 2, 18, 5),
(13, 1, 15, 2),
(15, 1, 9, 0),
(16, 2, 6, 0),
(17, 1, 13, 4),
(18, 2, 16, 8),
(19, 1, 17, 3),
(20, 2, 14, 6),
(21, 1, 19, 4),
(22, 2, 4, 0),
(23, 1, 8, 1),
(24, 2, 20, 6),
(26, 1, 10, 0);

-- --------------------------------------------------------

--
-- Struttura della tabella `libri`
--

CREATE TABLE `libri` (
  `id` int NOT NULL,
  `titolo` varchar(255) NOT NULL,
  `autore` varchar(255) NOT NULL,
  `anno` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dump dei dati per la tabella `libri`
--

INSERT INTO `libri` (`id`, `titolo`, `autore`, `anno`) VALUES
(2, 'Linguaggio C', 'Brian Kernighan', 1978),
(3, 'Cybersecurity', 'William Stallings', 2018),
(4, 'Metasploit', 'David Kennedy', 2011),
(5, 'Nmap', 'Gordon Fyodor', 2003),
(6, 'Ciao a tutti', 'Mario Rossi', 2020),
(7, 'Introduzione alla Programmazione', 'Giuseppe Verdi', 2015),
(8, 'Il Grande Libro di PHP', 'Luca Bianchi', 2018),
(9, 'Mastering SQL', 'Alessandro Gialli', 2021),
(10, 'JavaScript: The Good Parts', 'Douglas Crockford', 2008),
(11, 'Python Crash Course', 'Eric Matthes', 2019),
(12, 'Clean Code', 'Robert C. Martin', 2008),
(13, 'The Pragmatic Programmer', 'Andrew Hunt, David Thomas', 1999),
(14, 'Database Design for Mere Mortals', 'Michael J. Hernandez', 2006),
(15, 'Learning SQL', 'Alan Beaulieu', 2009),
(16, 'Web Development with Node and Express', 'Ethan Brown', 2019),
(17, 'The Mythical Man-Month', 'Frederick P. Brooks', 1975),
(18, 'Design Patterns: Elements of Reusable Object-Oriented Software', 'Erich Gamma, Richard Helm, Ralph Johnson, John Vlissides', 1994),
(19, 'The Art of Computer Programming', 'Donald E. Knuth', 1968),
(20, 'Introduction to Algorithms', 'Thomas H. Cormen, Charles E. Leiserson, Ronald L. Rivest, Clifford Stein', 2009);

-- --------------------------------------------------------

--
-- Struttura della tabella `storico_prenotazioni`
--

CREATE TABLE `storico_prenotazioni` (
  `id` int NOT NULL,
  `id_libro` int NOT NULL,
  `data_ora` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `id_filiale` int NOT NULL DEFAULT '0',
  `restituito` tinyint NOT NULL DEFAULT '0',
  `id_utente` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dump dei dati per la tabella `storico_prenotazioni`
--

INSERT INTO `storico_prenotazioni` (`id`, `id_libro`, `data_ora`, `id_filiale`, `restituito`, `id_utente`) VALUES
(53, 10, '2025-04-24 12:47:58', 0, 0, 0),
(54, 10, '2025-04-24 12:48:02', 0, 0, 0),
(55, 10, '2025-04-24 12:48:05', 0, 0, 0),
(56, 10, '2025-04-24 12:58:36', 2, 0, 0),
(57, 10, '2025-04-24 12:58:38', 1, 0, 0),
(58, 10, '2025-04-24 12:58:41', 2, 1, 0),
(59, 10, '2025-04-24 12:58:44', 2, 1, 0),
(60, 10, '2025-04-24 12:58:46', 2, 1, 0),
(61, 10, '2025-04-24 12:58:52', 1, 1, 0),
(62, 10, '2025-04-24 13:04:21', 1, 1, 0),
(63, 10, '2025-04-24 13:04:33', 2, 1, 0),
(64, 10, '2025-04-24 13:15:17', 2, 1, 0),
(65, 10, '2025-04-24 13:15:19', 2, 1, 0),
(66, 10, '2025-04-24 13:15:26', 2, 1, 0),
(67, 10, '2025-04-24 13:15:36', 1, 1, 0),
(68, 10, '2025-04-24 13:17:51', 1, 1, 0),
(69, 10, '2025-04-24 14:30:09', 1, 0, 0),
(70, 10, '2025-04-24 14:30:11', 1, 0, 0),
(71, 10, '2025-04-24 14:30:13', 2, 0, 0),
(72, 6, '2025-04-28 07:40:20', 2, 1, 0),
(73, 6, '2025-04-28 07:40:22', 2, 0, 0),
(74, 6, '2025-04-28 07:59:08', 2, 0, 0),
(75, 9, '2025-04-28 09:48:10', 1, 0, 0),
(76, 10, '2025-04-28 13:00:35', 2, 0, 0),
(77, 9, '2025-04-28 13:00:39', 1, 0, 0),
(78, 12, '2025-04-28 13:00:44', 2, 0, 0),
(79, 18, '2025-04-28 13:00:49', 2, 1, 0),
(80, 6, '2025-04-28 13:16:25', 2, 0, 0),
(81, 6, '2025-04-28 13:22:55', 2, 0, 0),
(82, 6, '2025-04-28 13:23:24', 2, 0, 0),
(83, 6, '2025-04-28 14:04:22', 2, 0, 0),
(84, 8, '2025-04-28 14:04:26', 1, 0, 0),
(85, 7, '2025-04-28 14:04:28', 1, 0, 0),
(86, 7, '2025-04-28 14:08:08', 1, 0, 0),
(87, 8, '2025-04-28 14:08:13', 1, 0, 0),
(88, 9, '2025-04-28 14:10:07', 1, 0, 0),
(89, 9, '2025-04-28 14:10:10', 1, 0, 0),
(90, 8, '2025-04-28 15:04:47', 1, 0, 0),
(91, 6, '2025-04-28 15:05:07', 2, 0, 0),
(92, 9, '2025-04-29 07:00:23', 1, 0, 0),
(93, 8, '2025-04-29 07:00:24', 1, 0, 0),
(94, 10, '2025-04-29 07:00:25', 2, 0, 0),
(95, 10, '2025-04-29 07:00:25', 2, 0, 0),
(96, 10, '2025-04-30 07:00:53', 2, 1, 0),
(97, 10, '2025-04-30 07:00:54', 2, 1, 0),
(98, 9, '2025-04-30 07:00:54', 1, 1, 0),
(99, 10, '2025-04-30 07:08:41', 2, 1, 0),
(100, 9, '2025-04-30 08:08:25', 1, 0, 2),
(101, 10, '2025-04-30 08:08:27', 2, 0, 2),
(102, 10, '2025-04-30 08:08:27', 2, 0, 2),
(103, 10, '2025-04-30 08:14:56', 2, 0, 2),
(104, 10, '2025-04-30 08:14:57', 2, 0, 2),
(105, 9, '2025-04-30 08:14:58', 1, 0, 2),
(106, 9, '2025-04-30 08:14:58', 1, 0, 2);

-- --------------------------------------------------------

--
-- Struttura della tabella `utenti`
--

CREATE TABLE `utenti` (
  `id` int NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `permessi` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dump dei dati per la tabella `utenti`
--

INSERT INTO `utenti` (`id`, `username`, `password`, `permessi`) VALUES
(2, 'fra', '1234', 2),
(3, 'ale', '123Stella', 1),
(4, 'ciccio', '1234', 1),
(5, 'pino', '1234', 1),
(6, 'ciao', '1234', 1),
(7, 'gino', '1234', 1),
(9, 'gandalf', '1234', 1),
(10, 'lego', '1234', 1),
(11, 'ciaooo', '1234', 1),
(12, 'bufa', '1234', 1),
(13, 'ciaoo', '1234', 1);

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `bibliotecaL`
--
ALTER TABLE `bibliotecaL`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `disponibilita`
--
ALTER TABLE `disponibilita`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `libri`
--
ALTER TABLE `libri`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `storico_prenotazioni`
--
ALTER TABLE `storico_prenotazioni`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_libro` (`id_libro`);

--
-- Indici per le tabelle `utenti`
--
ALTER TABLE `utenti`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `bibliotecaL`
--
ALTER TABLE `bibliotecaL`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT per la tabella `disponibilita`
--
ALTER TABLE `disponibilita`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT per la tabella `libri`
--
ALTER TABLE `libri`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT per la tabella `storico_prenotazioni`
--
ALTER TABLE `storico_prenotazioni`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=107;

--
-- AUTO_INCREMENT per la tabella `utenti`
--
ALTER TABLE `utenti`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Limiti per le tabelle scaricate
--

--
-- Limiti per la tabella `storico_prenotazioni`
--
ALTER TABLE `storico_prenotazioni`
  ADD CONSTRAINT `storico_prenotazioni_ibfk_1` FOREIGN KEY (`id_libro`) REFERENCES `libri` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

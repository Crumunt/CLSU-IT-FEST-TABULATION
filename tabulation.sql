-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3307
-- Generation Time: Oct 07, 2025 at 09:41 AM
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
-- Database: `tabulation`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `category_id` int(11) NOT NULL,
  `category_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`category_id`, `category_name`) VALUES
(1, 'production'),
(2, 'sports_attire'),
(3, 'creative_uniform'),
(4, 'formal_attire'),
(5, 'Confidence and Stage Presence'),
(6, 'Posture, Poise, and Bearing'),
(7, 'Audience Impact and Overall Projection'),
(8, 'Clarity of Speech and Diction'),
(9, 'Content and Relevance of Self-Introduction'),
(10, 'Authenticity, Personality, and Charisma'),
(11, 'Substance and Depth of Response'),
(12, 'Critical Thinking and Relevance'),
(13, 'Confidence and Communication Skills');

-- --------------------------------------------------------

--
-- Table structure for table `finalparticipants`
--

CREATE TABLE `finalparticipants` (
  `participant_id` int(11) NOT NULL,
  `participant_num` int(11) NOT NULL,
  `participant_name` varchar(255) NOT NULL,
  `gender` varchar(50) NOT NULL,
  `college` varchar(255) NOT NULL,
  `participant_img_name` varchar(100) NOT NULL DEFAULT 'tmp_candidate.jpg'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `finalparticipants`
--

INSERT INTO `finalparticipants` (`participant_id`, `participant_num`, `participant_name`, `gender`, `college`, `participant_img_name`) VALUES
(2, 2, 'Christopher Ortiz', 'Male', 'Fire', 'tmp_candidate.jpg'),
(3, 3, 'Lucky Louise Romero', 'Male', 'Fire', 'tmp_candidate.jpg'),
(6, 6, 'Ryley Andrew Reyes', 'Male', 'Earth', 'tmp_candidate.jpg'),
(10, 10, 'Ma. CryssTeena Cutaran', 'Female', 'Fire', 'tmp_candidate.jpg'),
(11, 11, 'Mary Grace Ferrer', 'Female', 'Fire', 'tmp_candidate.jpg'),
(12, 12, 'Maycaila Felipe', 'Female', 'Fire', 'tmp_candidate.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `finals`
--

CREATE TABLE `finals` (
  `judge_id` int(11) NOT NULL,
  `participant_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `score` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `participants`
--

CREATE TABLE `participants` (
  `participant_id` int(11) NOT NULL,
  `participant_num` int(11) DEFAULT NULL,
  `participant_name` varchar(100) DEFAULT NULL,
  `college` varchar(100) DEFAULT NULL,
  `gender` enum('Male','Female') DEFAULT NULL,
  `participant_img_name` varchar(100) NOT NULL DEFAULT 'tmp_candidate.jpg'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `participants`
--

INSERT INTO `participants` (`participant_id`, `participant_num`, `participant_name`, `college`, `gender`, `participant_img_name`) VALUES
(1, 1, 'Vince Miguel Padilla', 'Fire', 'Male', 'tmp_candidate.jpg'),
(2, 2, 'Christopher Ortiz', 'Fire', 'Male', 'tmp_candidate.jpg'),
(3, 3, 'Lucky Louise Romero', 'Fire', 'Male', 'tmp_candidate.jpg'),
(4, 4, 'Joby Neil Dee', 'Air', 'Male', 'tmp_candidate.jpg'),
(5, 5, 'John Paul Naco', 'Earth', 'Male', 'tmp_candidate.jpg'),
(6, 6, 'Ryley Andrew Reyes', 'Earth', 'Male', 'tmp_candidate.jpg'),
(7, 7, 'Jazzlee Sulayao', 'Water', 'Male', 'tmp_candidate.jpg'),
(8, 8, 'John Wayne Belizar', 'Water', 'Male', 'tmp_candidate.jpg'),
(9, 9, 'Marionne Jade Pascual', 'Water', 'Male', 'tmp_candidate.jpg'),
(10, 10, 'Ma. CryssTeena Cutaran', 'Fire', 'Female', 'tmp_candidate.jpg'),
(11, 11, 'Mary Grace Ferrer', 'Fire', 'Female', 'tmp_candidate.jpg'),
(12, 12, 'Maycaila Felipe', 'Fire', 'Female', 'tmp_candidate.jpg'),
(13, 13, 'Princess Mina', 'Air', 'Female', 'tmp_candidate.jpg'),
(14, 14, 'Nisha Malani', 'Earth', 'Female', 'tmp_candidate.jpg'),
(15, 15, 'Arianney Jane Maducdoc', 'Water', 'Female', 'tmp_candidate.jpg'),
(16, 16, 'Joddeah Ferrer', 'Water', 'Female', 'tmp_candidate.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `scores`
--

CREATE TABLE `scores` (
  `judge_id` int(11) NOT NULL,
  `participant_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `score` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `scores`
--

INSERT INTO `scores` (`judge_id`, `participant_id`, `category_id`, `score`) VALUES
(1, 1, 1, 14),
(1, 1, 2, 1),
(1, 1, 3, 0),
(1, 1, 4, 0),
(1, 1, 5, 4),
(1, 1, 6, 1),
(1, 1, 7, 1),
(1, 1, 8, 1),
(1, 2, 1, 12),
(1, 2, 2, 13),
(1, 2, 3, 13),
(1, 2, 4, 0),
(1, 2, 5, 6),
(1, 2, 6, 8),
(1, 2, 7, 6),
(1, 2, 8, 8),
(1, 3, 1, 15),
(1, 3, 2, 15),
(1, 3, 3, 12),
(1, 3, 4, 0),
(1, 3, 5, 10),
(1, 3, 6, 10),
(1, 3, 7, 10),
(1, 3, 8, 10),
(1, 4, 1, 1),
(1, 4, 2, 1),
(1, 4, 3, 0),
(1, 4, 4, 0),
(1, 4, 5, 4),
(1, 4, 6, 10),
(1, 4, 7, 1),
(1, 4, 8, 1),
(1, 5, 1, 1),
(1, 5, 2, 1),
(1, 5, 3, 0),
(1, 5, 4, 0),
(1, 5, 5, 1),
(1, 5, 6, 10),
(1, 5, 7, 10),
(1, 5, 8, 1),
(1, 6, 1, 1),
(1, 6, 2, 1),
(1, 6, 3, 9),
(1, 6, 4, 0),
(1, 6, 5, 9),
(1, 6, 6, 10),
(1, 6, 7, 10),
(1, 6, 8, 10),
(1, 7, 1, 1),
(1, 7, 2, 1),
(1, 7, 3, 0),
(1, 7, 4, 0),
(1, 7, 5, 1),
(1, 7, 6, 1),
(1, 7, 7, 1),
(1, 7, 8, 1),
(1, 8, 1, 1),
(1, 8, 2, 1),
(1, 8, 3, 0),
(1, 8, 4, 0),
(1, 8, 5, 1),
(1, 8, 6, 1),
(1, 8, 7, 10),
(1, 8, 8, 1),
(1, 9, 1, 1),
(1, 9, 2, 1),
(1, 9, 3, 0),
(1, 9, 4, 0),
(1, 9, 5, 1),
(1, 9, 6, 2),
(1, 9, 7, 1),
(1, 9, 8, 1),
(1, 10, 1, 1),
(1, 10, 2, 12),
(1, 10, 3, 9),
(1, 10, 4, 0),
(1, 10, 5, 6),
(1, 10, 6, 9),
(1, 10, 7, 10),
(1, 10, 8, 9),
(1, 11, 1, 1),
(1, 11, 2, 11),
(1, 11, 3, 13),
(1, 11, 4, 0),
(1, 11, 5, 6),
(1, 11, 6, 7),
(1, 11, 7, 4),
(1, 11, 8, 4),
(1, 12, 1, 1),
(1, 12, 2, 10),
(1, 12, 3, 4),
(1, 12, 4, 0),
(1, 12, 5, 4),
(1, 12, 6, 4),
(1, 12, 7, 1),
(1, 12, 8, 1),
(1, 13, 1, 1),
(1, 13, 2, 1),
(1, 13, 3, 0),
(1, 13, 4, 0),
(1, 13, 5, 1),
(1, 13, 6, 2),
(1, 13, 7, 1),
(1, 13, 8, 1),
(1, 14, 1, 1),
(1, 14, 2, 1),
(1, 14, 3, 0),
(1, 14, 4, 0),
(1, 14, 5, 1),
(1, 14, 6, 1),
(1, 14, 7, 1),
(1, 14, 8, 1),
(1, 15, 1, 1),
(1, 15, 2, 1),
(1, 15, 3, 0),
(1, 15, 4, 0),
(1, 15, 5, 1),
(1, 15, 6, 1),
(1, 15, 7, 1),
(1, 15, 8, 1),
(1, 16, 1, 1),
(1, 16, 2, 1),
(1, 16, 3, 0),
(1, 16, 4, 0),
(1, 16, 5, 1),
(1, 16, 6, 1),
(1, 16, 7, 1),
(1, 16, 8, 1),
(2, 1, 1, 15),
(2, 1, 2, 12),
(2, 1, 3, 0),
(2, 1, 4, 0),
(2, 1, 5, 40),
(2, 1, 6, 30),
(2, 1, 7, 20),
(2, 1, 8, 10),
(2, 2, 1, 15),
(2, 2, 2, 14),
(2, 2, 3, 14),
(2, 2, 4, 15),
(2, 2, 5, 39),
(2, 2, 6, 29),
(2, 2, 7, 19),
(2, 2, 8, 10),
(2, 3, 1, 15),
(2, 3, 2, 15),
(2, 3, 3, 15),
(2, 3, 4, 15),
(2, 3, 5, 40),
(2, 3, 6, 30),
(2, 3, 7, 20),
(2, 3, 8, 10),
(2, 4, 1, 10),
(2, 4, 2, 8),
(2, 4, 3, 14),
(2, 4, 4, 0),
(2, 4, 5, 23),
(2, 4, 6, 24),
(2, 4, 7, 19),
(2, 4, 8, 5),
(2, 5, 1, 1),
(2, 5, 2, 10),
(2, 5, 3, 0),
(2, 5, 4, 0),
(2, 5, 5, 31),
(2, 5, 6, 26),
(2, 5, 7, 12),
(2, 5, 8, 8),
(2, 6, 1, 15),
(2, 6, 2, 14),
(2, 6, 3, 11),
(2, 6, 4, 15),
(2, 6, 5, 38),
(2, 6, 6, 28),
(2, 6, 7, 18),
(2, 6, 8, 10),
(2, 7, 1, 1),
(2, 7, 2, 12),
(2, 7, 3, 0),
(2, 7, 4, 0),
(2, 7, 5, 30),
(2, 7, 6, 23),
(2, 7, 7, 12),
(2, 7, 8, 8),
(2, 8, 1, 1),
(2, 8, 2, 11),
(2, 8, 3, 0),
(2, 8, 4, 0),
(2, 8, 5, 32),
(2, 8, 6, 17),
(2, 8, 7, 15),
(2, 8, 8, 9),
(2, 9, 1, 1),
(2, 9, 2, 12),
(2, 9, 3, 0),
(2, 9, 4, 0),
(2, 9, 5, 30),
(2, 9, 6, 19),
(2, 9, 7, 12),
(2, 9, 8, 9),
(2, 10, 1, 15),
(2, 10, 2, 15),
(2, 10, 3, 15),
(2, 10, 4, 15),
(2, 10, 5, 40),
(2, 10, 6, 30),
(2, 10, 7, 20),
(2, 10, 8, 10),
(2, 11, 1, 14),
(2, 11, 2, 14),
(2, 11, 3, 15),
(2, 11, 4, 15),
(2, 11, 5, 40),
(2, 11, 6, 28),
(2, 11, 7, 18),
(2, 11, 8, 10),
(2, 12, 1, 13),
(2, 12, 2, 15),
(2, 12, 3, 1),
(2, 12, 4, 15),
(2, 12, 5, 40),
(2, 12, 6, 29),
(2, 12, 7, 19),
(2, 12, 8, 10),
(2, 13, 1, 1),
(2, 13, 2, 10),
(2, 13, 3, 1),
(2, 13, 4, 0),
(2, 13, 5, 18),
(2, 13, 6, 14),
(2, 13, 7, 14),
(2, 13, 8, 6),
(2, 14, 1, 1),
(2, 14, 2, 8),
(2, 14, 3, 1),
(2, 14, 4, 0),
(2, 14, 5, 22),
(2, 14, 6, 22),
(2, 14, 7, 15),
(2, 14, 8, 8),
(2, 15, 1, 1),
(2, 15, 2, 9),
(2, 15, 3, 1),
(2, 15, 4, 0),
(2, 15, 5, 20),
(2, 15, 6, 23),
(2, 15, 7, 12),
(2, 15, 8, 4),
(2, 16, 1, 1),
(2, 16, 2, 10),
(2, 16, 3, 1),
(2, 16, 4, 0),
(2, 16, 5, 24),
(2, 16, 6, 25),
(2, 16, 7, 18),
(2, 16, 8, 5),
(3, 1, 1, 1),
(3, 1, 2, 10),
(3, 1, 3, 5),
(3, 1, 4, 5),
(3, 1, 5, 10),
(3, 1, 6, 10),
(3, 1, 7, 10),
(3, 1, 8, 10),
(3, 2, 1, 15),
(3, 2, 2, 15),
(3, 2, 3, 15),
(3, 2, 4, 15),
(3, 2, 5, 40),
(3, 2, 6, 30),
(3, 2, 7, 20),
(3, 2, 8, 10),
(3, 3, 1, 15),
(3, 3, 2, 15),
(3, 3, 3, 15),
(3, 3, 4, 15),
(3, 3, 5, 40),
(3, 3, 6, 30),
(3, 3, 7, 20),
(3, 3, 8, 10),
(3, 4, 1, 1),
(3, 4, 2, 10),
(3, 4, 3, 0),
(3, 4, 4, 5),
(3, 4, 5, 10),
(3, 4, 6, 10),
(3, 4, 7, 10),
(3, 4, 8, 10),
(3, 5, 1, 1),
(3, 5, 2, 10),
(3, 5, 3, 5),
(3, 5, 4, 10),
(3, 5, 5, 10),
(3, 5, 6, 10),
(3, 5, 7, 10),
(3, 5, 8, 10),
(3, 6, 1, 15),
(3, 6, 2, 15),
(3, 6, 3, 15),
(3, 6, 4, 15),
(3, 6, 5, 40),
(3, 6, 6, 30),
(3, 6, 7, 20),
(3, 6, 8, 10),
(3, 7, 1, 1),
(3, 7, 2, 10),
(3, 7, 3, 5),
(3, 7, 4, 5),
(3, 7, 5, 10),
(3, 7, 6, 10),
(3, 7, 7, 10),
(3, 7, 8, 10),
(3, 8, 1, 1),
(3, 8, 2, 10),
(3, 8, 3, 5),
(3, 8, 4, 5),
(3, 8, 5, 10),
(3, 8, 6, 10),
(3, 8, 7, 10),
(3, 8, 8, 10),
(3, 9, 1, 1),
(3, 9, 2, 10),
(3, 9, 3, 5),
(3, 9, 4, 5),
(3, 9, 5, 10),
(3, 9, 6, 10),
(3, 9, 7, 10),
(3, 9, 8, 10),
(3, 10, 1, 15),
(3, 10, 2, 15),
(3, 10, 3, 15),
(3, 10, 4, 15),
(3, 10, 5, 40),
(3, 10, 6, 30),
(3, 10, 7, 20),
(3, 10, 8, 10),
(3, 11, 1, 15),
(3, 11, 2, 15),
(3, 11, 3, 15),
(3, 11, 4, 15),
(3, 11, 5, 40),
(3, 11, 6, 30),
(3, 11, 7, 20),
(3, 11, 8, 10),
(3, 12, 1, 15),
(3, 12, 2, 15),
(3, 12, 3, 15),
(3, 12, 4, 15),
(3, 12, 5, 10),
(3, 12, 6, 10),
(3, 12, 7, 10),
(3, 12, 8, 10),
(3, 13, 1, 1),
(3, 13, 2, 1),
(3, 13, 3, 5),
(3, 13, 4, 5),
(3, 13, 5, 10),
(3, 13, 6, 10),
(3, 13, 7, 10),
(3, 13, 8, 10),
(3, 14, 1, 1),
(3, 14, 2, 10),
(3, 14, 3, 5),
(3, 14, 4, 5),
(3, 14, 5, 10),
(3, 14, 6, 10),
(3, 14, 7, 10),
(3, 14, 8, 10),
(3, 15, 1, 1),
(3, 15, 2, 10),
(3, 15, 3, 5),
(3, 15, 4, 5),
(3, 15, 5, 10),
(3, 15, 6, 10),
(3, 15, 7, 10),
(3, 15, 8, 10),
(3, 16, 1, 1),
(3, 16, 2, 10),
(3, 16, 3, 5),
(3, 16, 4, 5),
(3, 16, 5, 10),
(3, 16, 6, 10),
(3, 16, 7, 10),
(3, 16, 8, 10);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `finals`
--
ALTER TABLE `finals`
  ADD PRIMARY KEY (`judge_id`,`participant_id`,`category_id`),
  ADD KEY `participant_id` (`participant_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `participants`
--
ALTER TABLE `participants`
  ADD PRIMARY KEY (`participant_id`);

--
-- Indexes for table `scores`
--
ALTER TABLE `scores`
  ADD PRIMARY KEY (`judge_id`,`participant_id`,`category_id`),
  ADD KEY `participant_id` (`participant_id`),
  ADD KEY `category_id` (`category_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `participants`
--
ALTER TABLE `participants`
  MODIFY `participant_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `scores`
--
ALTER TABLE `scores`
  ADD CONSTRAINT `scores_ibfk_1` FOREIGN KEY (`participant_id`) REFERENCES `participants` (`participant_id`),
  ADD CONSTRAINT `scores_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

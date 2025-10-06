-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3307
-- Generation Time: Oct 06, 2025 at 12:17 PM
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
-- Database: `pageant1`
--

-- --------------------------------------------------------

--
-- Stand-in structure for view `final_3_rankings`
-- (See below for the actual view)
--
CREATE TABLE `final_3_rankings` (
`participant_id` int(11)
,`participant_name` varchar(100)
,`gender` enum('Male','Female')
,`college` varchar(100)
,`participant_num` int(11)
,`total_score` decimal(54,0)
,`avg_ranking` decimal(24,4)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `final_3_rankscoresview`
-- (See below for the actual view)
--
CREATE TABLE `final_3_rankscoresview` (
`participant_id` int(11)
,`participant_name` varchar(100)
,`gender` enum('Male','Female')
,`college` varchar(100)
,`participant_num` int(11)
,`judge_id` int(11)
,`total_score` decimal(32,0)
,`rank` bigint(21)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `rankedscoresview`
-- (See below for the actual view)
--
CREATE TABLE `rankedscoresview` (
`participant_id` int(11)
,`participant_num` int(11)
,`participant_name` varchar(100)
,`gender` enum('Male','Female')
,`college` varchar(100)
,`category_id` int(11)
,`avg_total_score` decimal(36,4)
,`gender_rank` bigint(21)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `top_3_participants`
-- (See below for the actual view)
--
CREATE TABLE `top_3_participants` (
`participant_id` int(11)
,`participant_num` int(11)
,`participant_name` varchar(100)
,`gender` enum('Male','Female')
,`college` varchar(100)
,`avg_total_score` decimal(36,4)
,`avg_gender_ranking` decimal(24,4)
);

-- --------------------------------------------------------

--
-- Structure for view `final_3_rankings`
--
DROP TABLE IF EXISTS `final_3_rankings`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `final_3_rankings`  AS WITH avg_rankings AS (SELECT `final_3_rankscoresview`.`participant_id` AS `participant_id`, `final_3_rankscoresview`.`participant_name` AS `participant_name`, `final_3_rankscoresview`.`gender` AS `gender`, `final_3_rankscoresview`.`college` AS `college`, `final_3_rankscoresview`.`participant_num` AS `participant_num`, sum(`final_3_rankscoresview`.`total_score`) AS `total_score`, avg(`final_3_rankscoresview`.`rank`) AS `avg_ranking` FROM `final_3_rankscoresview` GROUP BY `final_3_rankscoresview`.`participant_id`, `final_3_rankscoresview`.`gender`, `final_3_rankscoresview`.`participant_num`), ranked_participants AS (SELECT `avg_rankings`.`participant_id` AS `participant_id`, `avg_rankings`.`participant_name` AS `participant_name`, `avg_rankings`.`gender` AS `gender`, `avg_rankings`.`college` AS `college`, `avg_rankings`.`participant_num` AS `participant_num`, `avg_rankings`.`total_score` AS `total_score`, `avg_rankings`.`avg_ranking` AS `avg_ranking`, dense_rank() over ( partition by `avg_rankings`.`gender` order by `avg_rankings`.`avg_ranking`) AS `ranking_within_gender` FROM `avg_rankings`) SELECT `ranked_participants`.`participant_id` AS `participant_id`, `ranked_participants`.`participant_name` AS `participant_name`, `ranked_participants`.`gender` AS `gender`, `ranked_participants`.`college` AS `college`, `ranked_participants`.`participant_num` AS `participant_num`, `ranked_participants`.`total_score` AS `total_score`, `ranked_participants`.`avg_ranking` AS `avg_ranking` FROM `ranked_participants``ranked_participants`  ;

-- --------------------------------------------------------

--
-- Structure for view `final_3_rankscoresview`
--
DROP TABLE IF EXISTS `final_3_rankscoresview`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `final_3_rankscoresview`  AS WITH top_ranking AS (SELECT `top_3_participants`.`participant_id` AS `participant_id`, `top_3_participants`.`participant_name` AS `participant_name`, `top_3_participants`.`gender` AS `gender`, `top_3_participants`.`college` AS `college`, `top_3_participants`.`participant_num` AS `participant_num`, `finals`.`judge_id` AS `judge_id`, sum(`finals`.`score`) AS `total_score` FROM (`top_3_participants` join `finals` on(`top_3_participants`.`participant_id` = `finals`.`participant_id`)) GROUP BY `top_3_participants`.`participant_id`, `top_3_participants`.`gender`, `top_3_participants`.`participant_num`, `finals`.`judge_id`), ranked_participants AS (SELECT `top_ranking`.`participant_id` AS `participant_id`, `top_ranking`.`participant_name` AS `participant_name`, `top_ranking`.`gender` AS `gender`, `top_ranking`.`college` AS `college`, `top_ranking`.`participant_num` AS `participant_num`, `top_ranking`.`judge_id` AS `judge_id`, `top_ranking`.`total_score` AS `total_score`, dense_rank() over ( partition by `top_ranking`.`judge_id`,`top_ranking`.`gender` order by `top_ranking`.`total_score` desc) AS `rank` FROM `top_ranking`)  SELECT `ranked_participants`.`participant_id` AS `participant_id`, `ranked_participants`.`participant_name` AS `participant_name`, `ranked_participants`.`gender` AS `gender`, `ranked_participants`.`college` AS `college`, `ranked_participants`.`participant_num` AS `participant_num`, `ranked_participants`.`judge_id` AS `judge_id`, `ranked_participants`.`total_score` AS `total_score`, `ranked_participants`.`rank` AS `rank` FROM `ranked_participants` ORDER BY `ranked_participants`.`judge_id` ASC, `ranked_participants`.`gender` ASC`gender`  ;

-- --------------------------------------------------------

--
-- Structure for view `rankedscoresview`
--
DROP TABLE IF EXISTS `rankedscoresview`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `rankedscoresview`  AS WITH TotalScores AS (SELECT `s`.`participant_id` AS `participant_id`, `p`.`participant_num` AS `participant_num`, `p`.`participant_name` AS `participant_name`, `p`.`gender` AS `gender`, `p`.`college` AS `college`, `s`.`judge_id` AS `judge_id`, `s`.`category_id` AS `category_id`, sum(`s`.`score`) AS `total_score` FROM (`scores` `s` join `participants` `p` on(`s`.`participant_id` = `p`.`participant_id`)) GROUP BY `s`.`participant_id`, `p`.`participant_num`, `p`.`participant_name`, `p`.`gender`, `p`.`college`, `s`.`judge_id`, `s`.`category_id`), RankedScores AS (SELECT `ts`.`participant_id` AS `participant_id`, `ts`.`participant_num` AS `participant_num`, `ts`.`participant_name` AS `participant_name`, `ts`.`gender` AS `gender`, `ts`.`college` AS `college`, `ts`.`judge_id` AS `judge_id`, `ts`.`category_id` AS `category_id`, `ts`.`total_score` AS `total_score`, dense_rank() over ( partition by `ts`.`judge_id`,`ts`.`gender`,`ts`.`category_id` order by `ts`.`total_score` desc) AS `rank` FROM `totalscores` AS `ts`), AvgScores AS (SELECT `rs`.`participant_id` AS `participant_id`, `rs`.`participant_num` AS `participant_num`, `rs`.`participant_name` AS `participant_name`, `rs`.`gender` AS `gender`, `rs`.`college` AS `college`, `rs`.`category_id` AS `category_id`, avg(`rs`.`total_score`) AS `avg_total_score` FROM `rankedscores` AS `rs` GROUP BY `rs`.`participant_id`, `rs`.`participant_num`, `rs`.`participant_name`, `rs`.`gender`, `rs`.`college`, `rs`.`category_id`), FinalWinners AS (SELECT `av`.`participant_id` AS `participant_id`, `av`.`participant_num` AS `participant_num`, `av`.`participant_name` AS `participant_name`, `av`.`gender` AS `gender`, `av`.`college` AS `college`, `av`.`category_id` AS `category_id`, `av`.`avg_total_score` AS `avg_total_score`, dense_rank() over ( partition by `av`.`category_id`,`av`.`gender` order by `av`.`avg_total_score` desc) AS `gender_rank` FROM `avgscores` AS `av`) SELECT `fw`.`participant_id` AS `participant_id`, `fw`.`participant_num` AS `participant_num`, `fw`.`participant_name` AS `participant_name`, `fw`.`gender` AS `gender`, `fw`.`college` AS `college`, `fw`.`category_id` AS `category_id`, `fw`.`avg_total_score` AS `avg_total_score`, `fw`.`gender_rank` AS `gender_rank` FROM `finalwinners` AS `fw` WHERE `fw`.`gender_rank` <= 33  ;

-- --------------------------------------------------------

--
-- Structure for view `top_3_participants`
--
DROP TABLE IF EXISTS `top_3_participants`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `top_3_participants`  AS WITH avg_rankings AS (SELECT `rankedscoresview`.`participant_id` AS `participant_id`, `rankedscoresview`.`participant_num` AS `participant_num`, `rankedscoresview`.`participant_name` AS `participant_name`, `rankedscoresview`.`gender` AS `gender`, `rankedscoresview`.`college` AS `college`, `rankedscoresview`.`avg_total_score` AS `avg_total_score`, avg(`rankedscoresview`.`gender_rank`) AS `avg_gender_ranking` FROM `rankedscoresview` GROUP BY `rankedscoresview`.`participant_id`, `rankedscoresview`.`participant_num`, `rankedscoresview`.`gender`), ranked_participants AS (SELECT `avg_rankings`.`participant_id` AS `participant_id`, `avg_rankings`.`participant_num` AS `participant_num`, `avg_rankings`.`participant_name` AS `participant_name`, `avg_rankings`.`gender` AS `gender`, `avg_rankings`.`college` AS `college`, `avg_rankings`.`avg_total_score` AS `avg_total_score`, `avg_rankings`.`avg_gender_ranking` AS `avg_gender_ranking`, dense_rank() over ( partition by `avg_rankings`.`gender` order by `avg_rankings`.`avg_gender_ranking`) AS `ranking_within_gender` FROM `avg_rankings`) SELECT `ranked_participants`.`participant_id` AS `participant_id`, `ranked_participants`.`participant_num` AS `participant_num`, `ranked_participants`.`participant_name` AS `participant_name`, `ranked_participants`.`gender` AS `gender`, `ranked_participants`.`college` AS `college`, `ranked_participants`.`avg_total_score` AS `avg_total_score`, `ranked_participants`.`avg_gender_ranking` AS `avg_gender_ranking` FROM `ranked_participants` WHERE `ranked_participants`.`gender` = 'Male' AND `ranked_participants`.`ranking_within_gender` <= 3 OR `ranked_participants`.`gender` = 'Female' AND `ranked_participants`.`ranking_within_gender` <= 33  ;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

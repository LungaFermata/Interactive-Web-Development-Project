-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 29, 2025 at 01:58 PM
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
-- Database: `iwd_project`
--
CREATE DATABASE IF NOT EXISTS `iwd_project` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `iwd_project`;

-- --------------------------------------------------------

--
-- Table structure for table `album`
--

CREATE TABLE `album` (
  `album_id` smallint(6) NOT NULL,
  `album_name` varchar(100) NOT NULL,
  `year` year(4) NOT NULL,
  `artist` varchar(100) NOT NULL,
  `record_label` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- RELATIONSHIPS FOR TABLE `album`:
--

--
-- Dumping data for table `album`
--

INSERT INTO `album` (`album_id`, `album_name`, `year`, `artist`, `record_label`) VALUES
(10, 'Blank', '2025', 'No Values Here', 'BlackRock Records'),
(23, 'test', '2017', 'albrat', 'Belop inc'),
(24, 'I Need Test Data', '2025', 'Blorb Glorbus', 'Clod Vinyl');

-- --------------------------------------------------------

--
-- Stand-in structure for view `album_view`
-- (See below for the actual view)
--
CREATE TABLE `album_view` (
`album_id` smallint(6)
,`album_name` varchar(100)
,`year` year(4)
,`artist` varchar(100)
,`record_label` varchar(100)
,`average_score` decimal(7,4)
);

-- --------------------------------------------------------

--
-- Table structure for table `comment`
--

CREATE TABLE `comment` (
  `comment_id` mediumint(9) NOT NULL,
  `album_id` smallint(6) NOT NULL,
  `username` varchar(20) NOT NULL,
  `content` varchar(300) NOT NULL,
  `post_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- RELATIONSHIPS FOR TABLE `comment`:
--   `album_id`
--       `album` -> `album_id`
--   `username`
--       `user` -> `username`
--

--
-- Dumping data for table `comment`
--

INSERT INTO `comment` (`comment_id`, `album_id`, `username`, `content`, `post_date`) VALUES
(15, 24, 'admin', 'Shame we lost all the comments and ratings in our newest overhaul :(', '2025-10-27 22:50:03'),
(16, 24, 'dawve', 'Yeah I know right', '2025-10-29 04:58:17');

-- --------------------------------------------------------

--
-- Table structure for table `event_log`
--

CREATE TABLE `event_log` (
  `log_id` int(11) NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp(),
  `type` varchar(50) NOT NULL,
  `ip_adr` varchar(50) NOT NULL,
  `details` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- RELATIONSHIPS FOR TABLE `event_log`:
--

--
-- Dumping data for table `event_log`
--

INSERT INTO `event_log` (`log_id`, `date`, `type`, `ip_adr`, `details`) VALUES
(1, '2025-10-27 23:06:49', 'Login Attempt', '127.0.0.1', 'Failed login attempt with username of admin'),
(2, '2025-10-27 23:06:58', 'Login', '127.0.0.1', 'admin logged in'),
(3, '2025-10-27 23:09:18', 'Album Added', '127.0.0.1', 'sdwa (2012) added by admin'),
(5, '2025-10-27 23:12:59', 'Album Added', '127.0.0.1', 'name (2010) added by admin'),
(7, '2025-10-27 23:13:53', 'Album Deleted', '127.0.0.1', 'sdwa (2012) deleted by admin'),
(8, '2025-10-27 23:13:57', 'Album Deleted', '127.0.0.1', 'name (2010) deleted by admin'),
(11, '2025-10-27 23:24:59', 'Login', '127.0.0.1', 'admin logged in'),
(12, '2025-10-28 06:01:21', 'Login', '127.0.0.1', 'admin logged in'),
(13, '2025-10-28 06:17:26', 'Album Added', '127.0.0.1', 'dsads (2001) added by admin'),
(14, '2025-10-28 06:19:06', 'Album Added', '127.0.0.1', 'dsawda (2011) added by admin'),
(15, '2025-10-28 06:23:28', 'Album Added', '127.0.0.1', 'dsadsa (2000) added by admin'),
(16, '2025-10-28 06:23:36', 'Album Deleted', '127.0.0.1', 'dsadsa (2000) deleted by admin'),
(17, '2025-10-28 06:23:41', 'Album Deleted', '127.0.0.1', 'dsawda (2011) deleted by admin'),
(18, '2025-10-28 06:23:45', 'Album Deleted', '127.0.0.1', 'dsads (2001) deleted by admin'),
(19, '2025-10-28 06:37:53', 'Logout', '127.0.0.1', 'admin logged out'),
(20, '2025-10-28 09:48:39', 'Logout', '127.0.0.1', 'dsmith logged out'),
(21, '2025-10-28 09:48:57', 'Login', '127.0.0.1', 'admin logged in'),
(22, '2025-10-28 09:49:02', 'Logout', '127.0.0.1', 'admin logged out'),
(23, '2025-10-29 04:51:16', 'Login', '127.0.0.1', 'glorbfan5 logged in'),
(24, '2025-10-29 04:52:42', 'Logout', '127.0.0.1', 'glorbfan5 logged out'),
(25, '2025-10-29 04:52:55', 'Login', '127.0.0.1', 'dawve logged in'),
(26, '2025-10-29 04:59:48', 'Logout', '127.0.0.1', 'dawve logged out'),
(27, '2025-10-29 05:00:44', 'Registration', '127.0.0.1', 'harold registered as a member'),
(28, '2025-10-29 05:00:55', 'Login', '127.0.0.1', 'harold logged in'),
(29, '2025-10-29 05:07:21', 'Logout', '127.0.0.1', 'harold logged out'),
(30, '2025-10-29 05:07:27', 'Login', '127.0.0.1', 'admin logged in'),
(31, '2025-10-29 05:16:19', 'Album Added', '127.0.0.1', 'dsad (2000) added by admin'),
(32, '2025-10-29 05:16:19', 'Tracks Added', '127.0.0.1', '1 tracks added for dsad (2000)'),
(33, '2025-10-29 05:24:06', 'Album Added', '127.0.0.1', 'jhbadj,n (2000) added by admin'),
(34, '2025-10-29 05:24:26', 'Album Added', '127.0.0.1', 'dawd (2000) added by admin'),
(35, '2025-10-29 05:24:26', 'Tracks Added', '127.0.0.1', '1 tracks added for dawd (2000)'),
(36, '2025-10-29 05:24:36', 'Album Deleted', '127.0.0.1', 'dsad (2000) deleted by admin'),
(37, '2025-10-29 05:24:40', 'Album Deleted', '127.0.0.1', 'jhbadj,n (2000) deleted by admin'),
(38, '2025-10-29 05:24:45', 'Album Deleted', '127.0.0.1', 'dawd (2000) deleted by admin'),
(39, '2025-10-29 05:56:48', 'Logout', '127.0.0.1', 'admin logged out');

-- --------------------------------------------------------

--
-- Stand-in structure for view `profile_view`
-- (See below for the actual view)
--
CREATE TABLE `profile_view` (
`username` varchar(20)
,`dob` date
,`profile` varchar(300)
,`fa_name` varchar(100)
,`fa_artist` varchar(100)
,`fa_year` year(4)
,`fa_album_id` smallint(6)
,`ft_name` varchar(100)
,`ft_artist` varchar(100)
,`ft_year` year(4)
,`ft_album_id` smallint(6)
);

-- --------------------------------------------------------

--
-- Table structure for table `rating`
--

CREATE TABLE `rating` (
  `album_id` smallint(6) NOT NULL,
  `username` varchar(20) NOT NULL,
  `score` tinyint(4) NOT NULL
) ;

--
-- RELATIONSHIPS FOR TABLE `rating`:
--   `album_id`
--       `album` -> `album_id`
--   `username`
--       `user` -> `username`
--

--
-- Dumping data for table `rating`
--

INSERT INTO `rating` (`album_id`, `username`, `score`) VALUES
(24, 'admin', 5),
(24, 'dawve', 5),
(24, 'glorbfan5', 4);

-- --------------------------------------------------------

--
-- Table structure for table `track`
--

CREATE TABLE `track` (
  `track_id` smallint(6) NOT NULL,
  `album_id` smallint(6) NOT NULL,
  `track_name` varchar(100) NOT NULL,
  `duration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- RELATIONSHIPS FOR TABLE `track`:
--   `album_id`
--       `album` -> `album_id`
--

--
-- Dumping data for table `track`
--

INSERT INTO `track` (`track_id`, `album_id`, `track_name`, `duration`) VALUES
(101, 23, 'Boshhop', 247),
(102, 23, 'The Succulent Meal', 472),
(103, 23, 'Harold Harmony n72', 139),
(104, 24, 'Dave Robson', 157),
(105, 24, 'Grave Cap', 824),
(106, 24, 'Blan Flanging', 322),
(107, 24, 'CASA Cacophony', 830),
(108, 24, 'Dothew Herbert', 106),
(109, 24, 'Death Like Die', 715),
(110, 24, 'Patricia', 346),
(111, 24, 'Red Rooms', 421),
(112, 24, 'Lotus', 537),
(113, 24, 'Blorb Glorbus', 919);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `username` varchar(20) NOT NULL,
  `password` varchar(255) NOT NULL,
  `dob` date NOT NULL,
  `email` varchar(50) NOT NULL,
  `profile` varchar(300) DEFAULT NULL,
  `access_level` varchar(10) NOT NULL DEFAULT 'member',
  `fav_album` smallint(6) DEFAULT NULL,
  `fav_track` smallint(6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- RELATIONSHIPS FOR TABLE `user`:
--   `fav_album`
--       `album` -> `album_id`
--   `fav_track`
--       `track` -> `track_id`
--

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`username`, `password`, `dob`, `email`, `profile`, `access_level`, `fav_album`, `fav_track`) VALUES
('admin', '$2y$10$jyYGcs3OnNhNs9GQUPY27e2ns0uxX78bSbnmIgJDKr1e/E/nc5V0C', '2000-03-07', 'admin@admin.admin', 'My name is Ozymandias, King of Kings:\r\nLook on my works, ye Mighty, and despair!', 'admin', 24, 103),
('BigDave', '$2y$10$3QXgOrZ8xk8OG3qvdisgduwSw8maBFwddaaJhkQ7nT3H62KbBWvGy', '1987-09-20', 'bish@bash.bosh', '', 'member', NULL, NULL),
('dawve', '$2y$10$n.79nK8.z2BLD.aFGm6C4O758llWJAGCetl63P0UULabXyUj53tmC', '2000-02-02', 'aadw@dwas.asdd', '', 'member', 23, 105),
('glorbfan5', '$2y$10$6IM6GYa3SZ5Ei04RBQ5ysuqqu8F2x0LdyQF1YwAhYc.ZOi4USIaoW', '2010-11-18', 'fulllegalname@email.com', 'I LOVE GLORB', 'member', NULL, NULL),
('harold', '$2y$10$nKf8Sda6GyFJTYiFR0gSF.H4eTxkze9ni0JmGK/KW06gaL/KLGUs6', '2000-02-07', 'e@mail.co', '', 'member', 24, 108),
('Hater69', '$2y$10$XLyfiJ6AQEQh.mmyAnMpVOnlCel0PmHfywFEX/j6nhUEf.eiGVv.C', '1996-03-05', 'techno@hotmail.com', '', 'member', NULL, NULL),
('MJ2004', '$2y$10$4wTI.G5JG7QFMbW7njPWIeRGG6b03b2gRLLWzKMK96vwjY/W2FwAO', '2004-06-17', 'bobcat@charlotte.com', '', 'member', NULL, NULL),
('RealReviews', '$2y$10$zbap1OsEBQWMfSxZ3lxW/eorkQ99XmscmnAvE8BtsUeUOS9KBK.Su', '2007-07-19', 'RealReacts@gmail.com', 'CHECK ME OUT ON INSTA', 'member', NULL, NULL),
('Tiedbreaker', '$2y$10$Bsrq8rcVns33DQp8KHIbee20LdXTmr9/YkHzGCi74vC1ZXfeclNKO', '2009-02-11', 'Flower@fmail.com.au', '', 'member', NULL, NULL);

-- --------------------------------------------------------

--
-- Structure for view `album_view`
--
DROP TABLE IF EXISTS `album_view`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `album_view`  AS SELECT `album`.`album_id` AS `album_id`, `album`.`album_name` AS `album_name`, `album`.`year` AS `year`, `album`.`artist` AS `artist`, `album`.`record_label` AS `record_label`, avg(`rating`.`score`) AS `average_score` FROM (`album` left join `rating` on(`album`.`album_id` = `rating`.`album_id`)) GROUP BY `album`.`album_id` ;

-- --------------------------------------------------------

--
-- Structure for view `profile_view`
--
DROP TABLE IF EXISTS `profile_view`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `profile_view`  AS SELECT `u`.`username` AS `username`, `u`.`dob` AS `dob`, `u`.`profile` AS `profile`, `fa`.`album_name` AS `fa_name`, `fa`.`artist` AS `fa_artist`, `fa`.`year` AS `fa_year`, `fa`.`album_id` AS `fa_album_id`, `ft`.`track_name` AS `ft_name`, `fta`.`artist` AS `ft_artist`, `fta`.`year` AS `ft_year`, `fta`.`album_id` AS `ft_album_id` FROM (((`user` `u` left join `album` `fa` on(`u`.`fav_album` = `fa`.`album_id`)) left join `track` `ft` on(`u`.`fav_track` = `ft`.`track_id`)) left join `album` `fta` on(`ft`.`album_id` = `fta`.`album_id`)) ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `album`
--
ALTER TABLE `album`
  ADD PRIMARY KEY (`album_id`);

--
-- Indexes for table `comment`
--
ALTER TABLE `comment`
  ADD PRIMARY KEY (`comment_id`),
  ADD KEY `album_id` (`album_id`),
  ADD KEY `username` (`username`);

--
-- Indexes for table `event_log`
--
ALTER TABLE `event_log`
  ADD PRIMARY KEY (`log_id`);

--
-- Indexes for table `rating`
--
ALTER TABLE `rating`
  ADD PRIMARY KEY (`album_id`,`username`),
  ADD KEY `rating_ibfk_2` (`username`);

--
-- Indexes for table `track`
--
ALTER TABLE `track`
  ADD PRIMARY KEY (`track_id`),
  ADD KEY `album_id` (`album_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `user_ibfk_1` (`fav_album`),
  ADD KEY `user_ibfk_2` (`fav_track`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `album`
--
ALTER TABLE `album`
  MODIFY `album_id` smallint(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `comment`
--
ALTER TABLE `comment`
  MODIFY `comment_id` mediumint(9) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `event_log`
--
ALTER TABLE `event_log`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `track`
--
ALTER TABLE `track`
  MODIFY `track_id` smallint(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=121;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `comment`
--
ALTER TABLE `comment`
  ADD CONSTRAINT `comment_ibfk_1` FOREIGN KEY (`album_id`) REFERENCES `album` (`album_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `comment_ibfk_2` FOREIGN KEY (`username`) REFERENCES `user` (`username`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `rating`
--
ALTER TABLE `rating`
  ADD CONSTRAINT `rating_ibfk_1` FOREIGN KEY (`album_id`) REFERENCES `album` (`album_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `rating_ibfk_2` FOREIGN KEY (`username`) REFERENCES `user` (`username`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `track`
--
ALTER TABLE `track`
  ADD CONSTRAINT `track_ibfk_1` FOREIGN KEY (`album_id`) REFERENCES `album` (`album_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `user_ibfk_1` FOREIGN KEY (`fav_album`) REFERENCES `album` (`album_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `user_ibfk_2` FOREIGN KEY (`fav_track`) REFERENCES `track` (`track_id`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

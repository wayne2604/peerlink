-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: sql308.infinityfree.com
-- Generation Time: Feb 11, 2026 at 06:05 AM
-- Server version: 11.4.10-MariaDB
-- PHP Version: 7.2.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `if0_41094727_peerlink`
--

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `listener_id` int(11) NOT NULL,
  `form_name` varchar(100) DEFAULT NULL,
  `form_grade_section` varchar(150) DEFAULT NULL,
  `form_topic` text DEFAULT NULL,
  `status` enum('pending','accepted','rejected','completed','reported') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `viewed_by_student` tinyint(4) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `student_id`, `listener_id`, `form_name`, `form_grade_section`, `form_topic`, `status`, `created_at`, `viewed_by_student`) VALUES
(1, 1, 1, 'Anonymous Student', '10 - Einstein', 'I feel overwhelmed with my exams.', 'pending', '2026-02-06 11:23:48', 0),
(2, 2, 1, 'John Doe', '11 - Newton', 'Just need someone to talk to about family.', 'pending', '2026-02-06 11:23:48', 0),
(3, 3, 2, 'John Doe Kyo', '10, Einstien', 'Hi po ', 'pending', '2026-02-06 11:32:42', 0),
(4, 3, 2, 'John Doe Kyo', '10, Einstien', 'hi', 'pending', '2026-02-06 12:21:09', 0),
(5, 3, 4, 'John Doe Kyo', '10, Einstien', 'Hi', 'completed', '2026-02-06 12:28:38', 0),
(6, 3, 4, 'John Doe Kyo', '10, Einstien', 'Hi', 'reported', '2026-02-06 12:30:47', 0),
(7, 3, 4, 'John Doe Kyo', '10, Einstien', 'Hi', 'rejected', '2026-02-06 12:31:03', 0),
(8, 8, 4, 'Fryea Merabeles', '10, Einstien', 'Hi', 'rejected', '2026-02-06 12:37:08', 0),
(9, 3, 4, 'John Doe Kyo', '10, Einstien', 'Hi', 'completed', '2026-02-06 12:58:32', 0),
(10, 8, 4, 'Fryea Merabeles', '10, Einstien', 'Hello', 'reported', '2026-02-06 12:58:58', 1),
(11, 3, 4, 'John Doe Kyo', '10, Einstien', 'Hi', 'completed', '2026-02-06 13:25:40', 0),
(12, 9, 10, 'Rhett Wayne Manubag', '10, Takitaki', 'Hello', 'completed', '2026-02-06 14:06:36', 0),
(13, 9, 10, 'Rhett Wayne Manubag', '10, Takitaki', 'hi', 'reported', '2026-02-06 14:09:36', 1),
(14, 11, 10, 'K', 'Grade 10 - Takitaki', 'Academic', 'pending', '2026-02-07 00:44:53', 0),
(15, 11, 4, 'k', 'Grade 10 - Takitaki', 'Academic', 'completed', '2026-02-07 00:45:26', 0),
(16, 3, 12, 'John Doe Kyo', '10, Einstien', 'Stress', 'completed', '2026-02-07 02:02:32', 0),
(17, 8, 12, 'Fryea Merabeles', '10, Takitaki', 'Stress', 'completed', '2026-02-07 02:04:43', 0),
(18, 3, 12, 'John Doe Kyo', '10, Einstien', 'Stress', 'reported', '2026-02-07 02:08:26', 1),
(19, 3, 12, 'Kyla', 'Grade 8 - Gumamela', 'Anxiety', 'reported', '2026-02-07 03:37:15', 1),
(20, 3, 12, 'John Doe Kyo', '10, Einstien', 'Stress', 'completed', '2026-02-09 05:19:17', 0),
(21, 13, 12, 'Rap ', '10, Takitaki', 'Stress', 'completed', '2026-02-09 05:47:08', 0),
(22, 14, 4, 'Zam', 'G10 Einstein', 'Stressed', 'accepted', '2026-02-09 09:51:38', 0),
(23, 3, 12, 'John', 'Grade 10 - Takitaki', 'Academic Stress', 'completed', '2026-02-10 15:05:42', 0);

-- --------------------------------------------------------

--
-- Table structure for table `listener_profiles`
--

CREATE TABLE `listener_profiles` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `alias` varchar(50) DEFAULT 'New Listener',
  `specialty` varchar(100) DEFAULT 'General Support',
  `avatar` varchar(255) DEFAULT 'default.png',
  `personality` varchar(255) DEFAULT 'Friendly & Open',
  `approach` varchar(255) DEFAULT 'Listening Ear',
  `religion` varchar(100) DEFAULT 'N/A',
  `belief` varchar(255) DEFAULT 'Kindness first',
  `focus` varchar(255) DEFAULT 'Mental Wellness',
  `goal` varchar(255) DEFAULT 'To help others',
  `reminder` varchar(255) DEFAULT 'You are valid',
  `hobbies` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `listener_profiles`
--

INSERT INTO `listener_profiles` (`id`, `user_id`, `alias`, `specialty`, `avatar`, `personality`, `approach`, `religion`, `belief`, `focus`, `goal`, `reminder`, `hobbies`) VALUES
(1, 4, 'Penelope', 'Academic Stress', 'avatar_4_1770380069.jpg', 'Empathetic & Calm', 'Listening Ear', 'Catholic', 'Kindness first', 'Mental Wellness', 'To help others', 'You are valid', 'Reading, Gaming, Cycling'),
(2, 5, 'Atlas', 'Family Problems', 'avatar_5_1770380216.jpg', 'Empathetic & Calm', 'Listening Ear', 'Catholic', 'Kindness first', 'Mental Wellness', 'To help others', 'You are valid', 'Reading, Gaming, Cycling'),
(3, 7, 'Kraig', 'General Support', 'avatar_7_1770380407.jpg', 'Friendly', 'Listening', 'Catholic', 'Hope', 'Wellness', 'To Help', 'You matter', 'Guitar'),
(4, 10, 'Con', 'General Support', 'avatar_10_1770386566.jpg', 'Friendly', 'Listening', 'Roman Catholic', 'Hope', 'Wellness', 'To Help', 'You matter', 'Painting, Horseback Riding'),
(5, 12, 'Drake', 'General Support', 'avatar_12_1770429695.png', 'Friendly & Open', 'Listening Ear', 'Born Again Christian', 'Kindness first', 'Mental Wellness', 'To help others', 'You are valid', 'Reading'),
(6, 15, 'New Listener', 'General Support', 'default.png', 'Friendly & Open', 'Listening Ear', 'N/A', 'Kindness first', 'Mental Wellness', 'To help others', 'You are valid', NULL),
(7, 16, 'New Listener', 'General Support', 'default.png', 'Friendly & Open', 'Listening Ear', 'N/A', 'Kindness first', 'Mental Wellness', 'To help others', 'You are valid', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `booking_id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `booking_id`, `sender_id`, `message`, `created_at`) VALUES
(1, 11, 3, 'Hi', '2026-02-06 13:27:05'),
(2, 11, 4, 'hi', '2026-02-06 13:27:38'),
(3, 11, 3, 'Hello', '2026-02-06 13:28:37'),
(4, 11, 4, 'hello', '2026-02-06 13:31:49'),
(5, 11, 4, 'How are you?', '2026-02-06 13:31:59'),
(6, 11, 3, 'Hi im doing good ', '2026-02-06 13:32:11'),
(7, 11, 4, 'ilove how you communicate', '2026-02-06 13:32:39'),
(8, 11, 4, 'j', '2026-02-06 13:32:45'),
(9, 11, 4, 'j', '2026-02-06 13:32:47'),
(10, 11, 4, 'j', '2026-02-06 13:32:49'),
(11, 11, 3, 'jo', '2026-02-06 13:36:28'),
(12, 11, 3, 'hi', '2026-02-06 13:36:43'),
(13, 12, 10, 'hi', '2026-02-06 14:06:58'),
(14, 12, 10, 'hello', '2026-02-06 14:07:04'),
(15, 12, 10, 'hello', '2026-02-06 14:07:33'),
(16, 12, 9, 'hi', '2026-02-06 14:08:48'),
(17, 12, 10, 'wadup', '2026-02-06 14:08:56'),
(18, 13, 10, 'hi', '2026-02-06 14:10:52'),
(19, 13, 9, 'hikog ko', '2026-02-06 14:11:08'),
(20, 13, 9, 'hi', '2026-02-06 14:11:24'),
(21, 15, 4, 'hi', '2026-02-07 00:45:57'),
(22, 15, 11, 'tanga kaba?', '2026-02-07 00:46:13'),
(23, 15, 4, 'anong bakit tanga ka talaga boy', '2026-02-07 00:46:26'),
(24, 17, 12, 'hi', '2026-02-07 02:05:28'),
(25, 17, 8, 'hello', '2026-02-07 02:06:29'),
(26, 17, 12, 'whats up', '2026-02-07 02:06:37'),
(27, 18, 12, 'hi', '2026-02-07 02:08:53'),
(28, 18, 3, 'hello', '2026-02-07 02:08:58'),
(29, 18, 3, 'i want to die', '2026-02-07 02:09:13'),
(30, 18, 12, 'no please', '2026-02-07 02:09:20'),
(31, 19, 3, 'hi', '2026-02-09 04:13:08'),
(32, 19, 3, 'hi', '2026-02-09 04:13:19'),
(33, 19, 12, 'hi', '2026-02-09 04:17:24'),
(34, 19, 3, 'hi', '2026-02-09 04:25:35'),
(35, 19, 12, 'hi', '2026-02-09 04:27:47'),
(36, 19, 12, 'h9', '2026-02-09 04:37:45'),
(37, 19, 12, 'hi', '2026-02-09 04:38:09'),
(38, 19, 3, 'hi', '2026-02-09 04:38:46'),
(39, 19, 3, 'hi', '2026-02-09 04:38:47'),
(40, 19, 3, 'hi', '2026-02-09 04:48:52'),
(41, 19, 3, 'hi', '2026-02-09 04:50:53'),
(42, 19, 3, 'hi', '2026-02-09 04:51:47'),
(43, 19, 3, 'hello', '2026-02-09 04:55:18'),
(44, 19, 3, 'Hello', '2026-02-09 04:59:53'),
(45, 19, 3, 'Hello', '2026-02-09 04:59:54'),
(46, 19, 3, 'Chat test', '2026-02-09 05:00:06'),
(47, 20, 12, 'hi', '2026-02-09 05:19:44'),
(48, 20, 3, 'Hello', '2026-02-09 05:22:10'),
(49, 20, 3, 'I am saf', '2026-02-09 05:22:21'),
(50, 20, 3, 'Sad*', '2026-02-09 05:22:32'),
(51, 20, 12, 'dont be sad', '2026-02-09 05:22:48'),
(52, 23, 3, 'Hi', '2026-02-10 15:07:06'),
(53, 23, 12, 'hello', '2026-02-10 15:07:18'),
(54, 23, 12, 'how are you', '2026-02-10 15:07:30');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('student','listener','admin') NOT NULL,
  `real_name` varchar(100) NOT NULL,
  `birthdate` date DEFAULT NULL,
  `gender` enum('Male','Female','Non-Binary','Prefer not to say') DEFAULT NULL,
  `grade_section` varchar(100) DEFAULT NULL,
  `is_verified` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `reset_token` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `password`, `role`, `real_name`, `birthdate`, `gender`, `grade_section`, `is_verified`, `created_at`, `reset_token`) VALUES
(1, 'rmanubag308@gmail.com', 'Tyrek2604', 'admin', 'Guidance Counselor', NULL, NULL, NULL, 1, '2026-02-05 06:08:40', NULL),
(2, 'angkolderuloness@gmail.com', '308', 'listener', 'Rhett', '2004-04-26', 'Male', '', 1, '2026-02-05 20:56:59', NULL),
(3, 'johndoe@gmail.com', '12345', 'student', 'John Doe', '2004-04-26', 'Male', '10 - Einstien', 1, '2026-02-06 04:08:24', NULL),
(4, 'listener1@test.com', '123', 'listener', 'RealName Hidden1', NULL, NULL, NULL, 1, '2026-02-06 04:18:04', NULL),
(5, 'listener2@test.com', '123', 'listener', 'RealName Hidden2', NULL, NULL, NULL, 1, '2026-02-06 04:18:04', NULL),
(7, 'test_listener_new@gmail.com', '123', 'listener', 'Test Listener 1', '2010-04-01', 'Male', '', 1, '2026-02-06 11:48:45', NULL),
(8, 'frye@gmail.com', '123', 'student', 'Fryea Merabeles', '2020-01-06', 'Female', '10 - Einstien', 1, '2026-02-06 12:36:33', NULL),
(9, 'rman@gmail.com', '123', 'student', 'Rhett Wayne Manubag', '2004-04-26', 'Male', '10 - Einstien', 1, '2026-02-06 13:52:25', NULL),
(10, 'mar@gmail.com', '123', 'listener', 'Maricon Gahisan', '2012-02-06', 'Female', '', 1, '2026-02-06 13:53:44', NULL),
(11, 'Ty@gmail.com', '$2y$10$3Hk04WF1jHwDvqfbR3B.JemZ098dQ90LmI0XEV3TjurqHk5yXtCFy', 'student', 'Tyrek', NULL, NULL, 'Grade 10 - TakiTaki', 1, '2026-02-07 00:43:27', NULL),
(12, 'deruloness308@gmail.com', '$2y$10$IzgZBpVCbJpxeWkAFYucaueW/Ru4nDgmRvDxHTfGKGMSqYMwDtKYu', 'listener', 'Drake', NULL, NULL, '', 1, '2026-02-07 01:59:25', '348081'),
(13, 'rapandilab3@gmail.com', '$2y$10$zqDRZROYxlaQuBorqzCIL.PNZAD74f94JijjIimnnYkN4hZF.jKJ6', 'student', 'Ralph Wyndril O. Andilab', NULL, NULL, '10 - Einstien', 1, '2026-02-09 05:43:26', NULL),
(14, 'zdysas@gmail.com', '$2y$10$YDmBYyhAzdmmo9I1RLG5R.Kboq.rGU1eMTECrk.7AV0czU2EAjplu', 'student', 'Alyssa Ysabelle T. Saile', NULL, NULL, 'Grade 10 Einstein', 1, '2026-02-09 09:49:43', NULL),
(15, 'ysabellesaile@gmail.com', '$2y$10$h1qgifrkUi/ClHE.QNuXR.Lujof6hFCupTM3esR9bgg9d56jVblG6', 'listener', 'Zam Saile', NULL, NULL, 'G10 Einstein', 1, '2026-02-09 09:55:10', NULL),
(16, 'penelopesantos811@gmail.com', '$2y$10$wa59o6.dN7yE5N2N06cZjueF6Q9rbHtuKisJLb3q/G1n570DOmggW', 'listener', 'Penelope', NULL, NULL, 'Grade 10 - Einstein', 1, '2026-02-09 12:54:43', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `listener_profiles`
--
ALTER TABLE `listener_profiles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `listener_profiles`
--
ALTER TABLE `listener_profiles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `listener_profiles`
--
ALTER TABLE `listener_profiles`
  ADD CONSTRAINT `listener_profiles_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

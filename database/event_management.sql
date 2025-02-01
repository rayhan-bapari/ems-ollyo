-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 01, 2025 at 04:30 PM
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
-- Database: `event_management`
--

-- --------------------------------------------------------

--
-- Table structure for table `attendees`
--

CREATE TABLE `attendees` (
  `id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `registration_date` datetime DEFAULT current_timestamp(),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `attendees`
--

INSERT INTO `attendees` (`id`, `event_id`, `name`, `email`, `phone`, `registration_date`, `created_at`, `updated_at`) VALUES
(1, 1, 'Md.Rayhan Bapari', 'mdrayhanbapari02@gmail.com', '+8801626317700', '2025-02-01 21:09:24', '2025-02-01 15:09:24', NULL),
(2, 2, 'Graiden Stephenson', 'quhexof@mailinator.com', '+1 (405) 828-2551', '2025-02-01 21:09:44', '2025-02-01 15:09:44', NULL),
(3, 3, 'Madeson Manning', 'ceza@mailinator.com', '+1 (112) 587-9863', '2025-02-01 21:09:58', '2025-02-01 15:09:58', NULL),
(4, 1, 'Aaron Christian', 'deguxe@mailinator.com', '+1 (216) 643-2062', '2025-02-01 21:10:05', '2025-02-01 15:10:05', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `date` datetime NOT NULL,
  `location` varchar(255) DEFAULT NULL,
  `max_capacity` int(11) DEFAULT NULL,
  `status` enum('active','cancelled','completed') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id`, `user_id`, `title`, `description`, `date`, `location`, `max_capacity`, `status`, `created_at`) VALUES
(1, 1, 'Midnight Masquerade', 'Join us for a night of mystery and enchantment at the Midnight Masquerade. With a live orchestra, candlelit ballroom, and masked guests, it’s sure to be an unforgettable evening. Come dressed to impress, and prepare for a night of intrigue.', '2025-02-15 18:00:00', 'The Velvet Ballroom, 50 Nightfall Road, Dream City', 100, 'active', '2025-01-31 23:59:41'),
(2, 1, 'Galactic Tech Expo 2025', 'Discover the latest innovations in technology, from artificial intelligence to virtual reality. Top tech companies will be showcasing their groundbreaking products, and expert speakers will give talks on the future of tech. Don’t miss out on the chance to see tomorrow’s technology today.', '2025-02-20 14:00:00', 'echX Convention Center, 100 Innovation Avenue, Silicon Valley', 100, 'active', '2025-02-01 00:00:38'),
(3, 1, 'Enchanted Forest Yoga Retreat', 'Escape to the tranquility of the Enchanted Forest for a weekend of rejuvenation and relaxation. Enjoy daily yoga, guided meditation, and nature walks while connecting with like-minded individuals. Unplug from the world and reconnect with yourself.', '2025-02-25 14:00:00', 'Enchanted Pines Resort, 123 Forest Path, Serenity Valley', 100, 'active', '2025-02-01 01:46:47');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `created_at`) VALUES
(1, 'admin', 'admin@gmail.com', '$2y$10$6ZqPpFvDrpte.TCaRFFwZeuiNG8BXPaqBIF6GlIDLr20PSXK3Zjda', '2025-01-31 17:16:53');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `attendees`
--
ALTER TABLE `attendees`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_attendee` (`event_id`,`email`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `attendees`
--
ALTER TABLE `attendees`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `attendees`
--
ALTER TABLE `attendees`
  ADD CONSTRAINT `attendees_ibfk_1` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`);

--
-- Constraints for table `events`
--
ALTER TABLE `events`
  ADD CONSTRAINT `events_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

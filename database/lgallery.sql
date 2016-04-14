-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Apr 15, 2016 at 02:51 AM
-- Server version: 5.6.28-0ubuntu0.14.04.1
-- PHP Version: 5.5.9-1ubuntu4.14

SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `lgallery`
--

-- --------------------------------------------------------

--
-- Table structure for table `albums`
--

CREATE TABLE IF NOT EXISTS `albums` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `category_id` int(10) unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `shareable` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `albums_user_id_foreign` (`user_id`),
  KEY `albums_category_id_foreign` (`category_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=7 ;

--
-- Dumping data for table `albums`
--

INSERT INTO `albums` (`id`, `user_id`, `category_id`, `name`, `description`, `shareable`, `created_at`, `updated_at`) VALUES
(3, 1, 2, 'Holiday Getaway', 'Fist', 0, '2016-04-05 18:11:00', '2016-04-14 01:57:48'),
(4, 1, 1, 'Properties', 'Properties image here', 0, '2016-04-12 00:43:01', '2016-04-12 01:16:22'),
(6, 2, 1, 'Auckland City', 'Auckland City Pics here!!', 0, '2016-04-14 02:16:14', '2016-04-14 02:16:14');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE IF NOT EXISTS `categories` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `sort_order` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, 'General', 1, '2016-04-04 23:32:40', '2016-04-04 23:32:40'),
(2, 'Seaside', 2, '2016-04-05 18:58:38', '2016-04-05 18:58:38');

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE IF NOT EXISTS `comments` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `photo_id` int(10) unsigned NOT NULL,
  `content` text COLLATE utf8_unicode_ci NOT NULL,
  `status` int(10) unsigned NOT NULL DEFAULT '2',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `comments_user_id_foreign` (`user_id`),
  KEY `comments_photo_id_foreign` (`photo_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=10 ;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`id`, `user_id`, `photo_id`, `content`, `status`, `created_at`, `updated_at`) VALUES
(6, 1, 3, 'rocky!!', 2, '2016-04-11 04:54:18', '2016-04-11 04:54:18'),
(7, 1, 4, 'Nice watch shot\r\n', 2, '2016-04-13 23:28:21', '2016-04-13 23:28:21'),
(8, 2, 4, 'Beautiful!', 2, '2016-04-14 02:11:58', '2016-04-14 02:11:58'),
(9, 1, 40, 'look at this colour!!', 2, '2016-04-14 02:22:09', '2016-04-14 02:22:09');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE IF NOT EXISTS `migrations` (
  `migration` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`migration`, `batch`) VALUES
('2014_10_12_000000_create_users_table', 1),
('2014_10_12_100000_create_password_resets_table', 1),
('2016_04_04_150000_create_table_categories', 1),
('2016_04_04_150750_create_table_albums', 1),
('2016_04_04_152322_create_table_tags', 1),
('2016_04_04_164933_create_table_photos', 1),
('2016_04_04_164942_create_table_comments', 1),
('2016_04_08_070253_change_status_default_on_comments_table', 2),
('2016_04_11_151022_remove_tags_for_albums', 3),
('2016_04_11_151555_create_table_tags_for_photos', 4);

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE IF NOT EXISTS `password_resets` (
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  KEY `password_resets_email_index` (`email`),
  KEY `password_resets_token_index` (`token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `photos`
--

CREATE TABLE IF NOT EXISTS `photos` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `album_id` int(10) unsigned NOT NULL,
  `filename` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `alt_text` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `caption` text COLLATE utf8_unicode_ci NOT NULL,
  `sort_order` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `photos_album_id_foreign` (`album_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=43 ;

--
-- Dumping data for table `photos`
--

INSERT INTO `photos` (`id`, `album_id`, `filename`, `alt_text`, `caption`, `sort_order`, `created_at`, `updated_at`) VALUES
(2, 3, '3_570655d81d1db6.29640769.jpg', '', '', 0, '2016-04-07 00:43:04', '2016-04-07 00:43:04'),
(3, 3, '3_570655d81d6161.83750025.jpg', '', 'rocks', 10, '2016-04-07 00:43:04', '2016-04-10 17:10:42'),
(4, 3, '3_570655d83a7124.59259235.jpg', '', '', 0, '2016-04-07 00:43:04', '2016-04-07 00:43:04'),
(5, 4, '2_570ceb1c87fa50.73432965.jpg', '', '', 0, '2016-04-12 00:33:32', '2016-04-13 22:55:22'),
(6, 3, '2_570ceb1c885187.27416007.jpg', '', '', 0, '2016-04-12 00:33:32', '2016-04-14 02:01:17'),
(7, 3, '2_570ceb257347b9.42902014.jpg', '', '', 0, '2016-04-12 00:33:41', '2016-04-14 02:01:17'),
(8, 3, '2_570ceb2572c2d1.65512243.jpg', '', '', 0, '2016-04-12 00:33:41', '2016-04-14 02:01:17'),
(9, 4, '4_570cfa55555c94.14681352.jpg', '', '', 0, '2016-04-12 01:38:29', '2016-04-12 01:38:29'),
(10, 4, '4_570cfa555697a7.61965160.jpg', '', '', 20, '2016-04-12 01:38:29', '2016-04-12 04:21:03'),
(11, 4, '4_570cfa5556ffa7.92079158.jpg', '', '', 0, '2016-04-12 01:38:29', '2016-04-12 01:38:29'),
(12, 4, '4_570cfa555e7cd3.46679044.jpg', '', '', 0, '2016-04-12 01:38:29', '2016-04-12 01:38:29'),
(13, 4, '4_570cfa56d68592.10507738.jpg', '', '', 0, '2016-04-12 01:38:30', '2016-04-12 01:38:30'),
(14, 4, '4_570cfa56d64400.32993243.jpg', '', '', 0, '2016-04-12 01:38:30', '2016-04-12 01:38:30'),
(15, 4, '4_570cfa56d74424.87700113.jpg', '', '', 0, '2016-04-12 01:38:30', '2016-04-12 01:38:30'),
(16, 4, '4_570cfa56d71ab0.73958618.jpg', '', '', 0, '2016-04-12 01:38:30', '2016-04-12 01:38:30'),
(17, 4, '4_570cfa5ce76252.48617471.jpg', '', '', 0, '2016-04-12 01:38:36', '2016-04-12 01:38:36'),
(18, 4, '4_570cfa5ce7c521.88010837.jpg', '', '', 0, '2016-04-12 01:38:36', '2016-04-12 01:38:36'),
(19, 4, '4_570cfa5ce95366.48680736.jpg', '', '', 0, '2016-04-12 01:38:36', '2016-04-12 01:38:36'),
(40, 6, '6_570fa69e05b384.24794224.jpg', '', '', 0, '2016-04-14 02:18:06', '2016-04-14 02:18:06'),
(41, 6, '6_570fa69e05ec46.96178713.jpg', '', '', 0, '2016-04-14 02:18:06', '2016-04-14 02:18:06'),
(42, 6, '6_570fa69e05d7e6.46515567.jpg', '', '', 0, '2016-04-14 02:18:06', '2016-04-14 02:18:06');

-- --------------------------------------------------------

--
-- Table structure for table `photo_tag`
--

CREATE TABLE IF NOT EXISTS `photo_tag` (
  `photo_id` int(10) unsigned NOT NULL,
  `tag_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  KEY `photo_tag_photo_id_index` (`photo_id`),
  KEY `photo_tag_tag_id_index` (`tag_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `photo_tag`
--

INSERT INTO `photo_tag` (`photo_id`, `tag_id`, `created_at`, `updated_at`) VALUES
(3, 1, '2016-04-11 03:41:49', '2016-04-11 03:41:49'),
(3, 2, '2016-04-11 03:49:15', '2016-04-11 03:49:15'),
(12, 3, '2016-04-12 18:51:45', '2016-04-12 18:51:45'),
(11, 3, '2016-04-12 18:52:09', '2016-04-12 18:52:09'),
(9, 3, '2016-04-12 18:52:45', '2016-04-12 18:52:45'),
(9, 4, '2016-04-12 18:53:02', '2016-04-12 18:53:02'),
(10, 3, '2016-04-12 18:55:39', '2016-04-12 18:55:39'),
(13, 4, '2016-04-12 18:55:46', '2016-04-12 18:55:46'),
(5, 3, '2016-04-12 23:24:52', '2016-04-12 23:24:52'),
(7, 6, '2016-04-12 23:25:35', '2016-04-12 23:25:35'),
(8, 7, '2016-04-12 23:25:51', '2016-04-12 23:25:51'),
(6, 5, '2016-04-12 23:26:22', '2016-04-12 23:26:22'),
(7, 2, '2016-04-12 23:55:32', '2016-04-12 23:55:32'),
(40, 6, '2016-04-14 02:19:10', '2016-04-14 02:19:10'),
(40, 11, '2016-04-14 02:19:10', '2016-04-14 02:19:10'),
(41, 8, '2016-04-14 02:19:17', '2016-04-14 02:19:17'),
(42, 6, '2016-04-14 02:19:19', '2016-04-14 02:19:19'),
(42, 8, '2016-04-14 02:19:21', '2016-04-14 02:19:21'),
(8, 8, '2016-04-14 02:26:20', '2016-04-14 02:26:20'),
(8, 2, '2016-04-14 02:26:32', '2016-04-14 02:26:32'),
(8, 1, '2016-04-14 02:26:36', '2016-04-14 02:26:36');

-- --------------------------------------------------------

--
-- Table structure for table `tags`
--

CREATE TABLE IF NOT EXISTS `tags` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=12 ;

--
-- Dumping data for table `tags`
--

INSERT INTO `tags` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'rocks', '2016-04-11 03:41:49', '2016-04-11 03:41:49'),
(2, 'beach', '2016-04-11 03:49:15', '2016-04-11 03:49:15'),
(3, 'house', '2016-04-12 18:51:45', '2016-04-12 18:51:45'),
(4, 'snow', '2016-04-12 18:53:02', '2016-04-12 18:53:02'),
(5, 'shopping', '2016-04-12 23:25:26', '2016-04-12 23:25:26'),
(6, 'sky', '2016-04-12 23:25:35', '2016-04-12 23:25:35'),
(7, 'boat', '2016-04-12 23:25:51', '2016-04-12 23:25:51'),
(8, 'water', '2016-04-12 23:26:02', '2016-04-12 23:26:02'),
(11, 'city', '2016-04-14 02:19:10', '2016-04-14 02:19:10');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Eddy', 'edwin@abc.com', '$2y$10$xx2Ql5DXmi3iwV18pElpd.N110A5wNpjGVUK9qSTd11hMKeSkxFQ.', 'rnzySaP6NB5AUuG4AdmbS90yBkxbR42jLWipAIulr0u7eT4E9N2hvf13b2ie', '2016-04-05 02:58:13', '2016-04-14 02:10:34'),
(2, 'Admin', 'admin@abc.com', '$2y$10$nWLOstDHy0o4JsOu9TTDquDLLqNLmSTUxGXXdezoxnoPtuCjBiKPO', 'nyQ3BA22qzjKUiWEXdMMP5BSX5pata2XGwfXxcKJtQxeKS1zWnQRbvLYXWBC', '2016-04-14 02:08:38', '2016-04-14 02:08:54');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `albums`
--
ALTER TABLE `albums`
  ADD CONSTRAINT `albums_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`),
  ADD CONSTRAINT `albums_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_photo_id_foreign` FOREIGN KEY (`photo_id`) REFERENCES `photos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `comments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `photos`
--
ALTER TABLE `photos`
  ADD CONSTRAINT `photos_album_id_foreign` FOREIGN KEY (`album_id`) REFERENCES `albums` (`id`);

--
-- Constraints for table `photo_tag`
--
ALTER TABLE `photo_tag`
  ADD CONSTRAINT `photo_tag_photo_id_foreign` FOREIGN KEY (`photo_id`) REFERENCES `photos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `photo_tag_tag_id_foreign` FOREIGN KEY (`tag_id`) REFERENCES `tags` (`id`) ON DELETE CASCADE;
SET FOREIGN_KEY_CHECKS=1;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

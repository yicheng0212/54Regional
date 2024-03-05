-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- 主機： 127.0.0.1
-- 產生時間： 2024-03-05 10:16:02
-- 伺服器版本： 10.4.32-MariaDB
-- PHP 版本： 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 資料庫： `54regional`
--

-- --------------------------------------------------------

--
-- 資料表結構 `bookings`
--

CREATE TABLE `bookings` (
  `id` int(11) NOT NULL,
  `roomNumber` varchar(100) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `checkInDate` date DEFAULT NULL,
  `checkOutDate` date DEFAULT NULL,
  `totalPrice` int(10) DEFAULT NULL,
  `deposit` int(10) DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `bookingNumber` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- 傾印資料表的資料 `bookings`
--

INSERT INTO `bookings` (`id`, `roomNumber`, `name`, `email`, `phone`, `checkInDate`, `checkOutDate`, `totalPrice`, `deposit`, `remarks`, `bookingNumber`) VALUES
(58, '5', 'ˊ˙ㄚ', '˙ˊㄚ', '˙嗄', '2024-02-24', '2024-02-29', 25000, 7500, '˙ˊㄚ', '20242240001'),
(59, '2', '２１３', '１２３', '１３２１３２', '2024-03-01', '2024-03-15', 70000, 21000, '１２３', '2024310001'),
(60, '2', '', '', '', '2024-03-01', '2024-03-08', 35000, 10500, '', '202403010002'),
(62, '4', '', '', '', '2024-03-01', '2024-03-02', 5000, 1500, '', '202403010004'),
(63, '1', '6', '687', '678', '2024-03-01', '2024-03-02', 5000, 1500, '678', '202403010005'),
(64, '2', '6', '687', '678', '2024-03-01', '2024-03-02', 5000, 1500, '678', '202403010005'),
(65, '3', '6', '687', '678', '2024-03-01', '2024-03-02', 5000, 1500, '678', '202403010005'),
(66, '4', '6', '687', '678', '2024-03-01', '2024-03-02', 5000, 1500, '678', '202403010005'),
(67, '5', '6', '687', '678', '2024-03-01', '2024-03-02', 5000, 1500, '678', '202403010005'),
(68, '6', '6', '687', '678', '2024-03-01', '2024-03-02', 5000, 1500, '678', '202403010005'),
(69, '7', '6', '687', '678', '2024-03-01', '2024-03-02', 5000, 1500, '678', '202403010005'),
(70, '8', '6', '687', '678', '2024-03-01', '2024-03-02', 5000, 1500, '678', '202403010005'),
(73, 'Room03', 'ㄢ', 'ㄢ', 'ㄢ', '2024-03-08', '2024-03-09', 5000, 1500, 'ㄢ', '202403080001');

-- --------------------------------------------------------

--
-- 資料表結構 `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `messageNumber` char(4) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `content` text NOT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `displayEmail` tinyint(1) DEFAULT 1,
  `displayPhone` tinyint(1) DEFAULT 1,
  `deleted_at` datetime DEFAULT NULL,
  `is_top` tinyint(1) DEFAULT 0,
  `admin_response` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- 傾印資料表的資料 `messages`
--

INSERT INTO `messages` (`id`, `messageNumber`, `name`, `email`, `phone`, `content`, `image_path`, `created_at`, `updated_at`, `displayEmail`, `displayPhone`, `deleted_at`, `is_top`, `admin_response`) VALUES
(41, '1234', '11111111231313', '11111@1111.111', '1111', '此留言已被删除', './image/1.jpeg', '2024-02-02 11:08:48', '2024-02-24 23:36:49', 0, 0, '2024-02-02 12:47:47', 0, '132233223'),
(42, '1234', '1111', '11111@1111.111', '1111', '此留言已被删除', '', '2024-02-02 12:44:08', '2024-02-23 13:30:22', 1, 1, '2024-02-23 12:52:48', 0, NULL),
(43, '1231', '123', '321@123.213', '321', '321\r\n', '', '2024-02-21 17:26:10', '2024-02-21 17:26:10', 1, 1, NULL, 0, NULL),
(44, '1231', '123', '321@123.213', '321', '321\r\n', '', '2024-02-21 17:26:12', '2024-02-21 17:26:12', 1, 1, NULL, 0, NULL),
(46, '1231', '123', '321@123.213', '321', '321\r\n', '', '2024-02-21 17:26:14', '2024-02-21 17:26:14', 1, 1, NULL, 0, NULL),
(47, '1231', '123', '321@123.213', '321', '321\r\n', '', '2024-02-21 17:26:14', '2024-02-21 17:26:14', 1, 1, NULL, 0, NULL),
(48, '1231', '123', '321@123.213', '321', '321\r\n', '', '2024-02-21 17:26:14', '2024-02-21 17:26:14', 1, 1, NULL, 0, NULL),
(49, '1231', '123', '321@123.213', '321', '321\r\n', '', '2024-02-21 17:26:14', '2024-02-21 17:26:14', 1, 1, NULL, 0, NULL),
(50, '1231', '123', '321@123.213', '321', '１２３１２３', '', '2024-02-21 17:26:14', '2024-02-23 14:11:04', 1, 1, NULL, 0, NULL),
(51, '1231', '123', '321@123.213', '321', '321\r\n', '', '2024-02-21 17:26:16', '2024-02-21 17:26:16', 1, 1, NULL, 0, NULL),
(52, '1231', '123', '321@123.213', '321', '321\r\n', '', '2024-02-21 17:26:16', '2024-02-21 17:26:16', 1, 1, NULL, 0, NULL),
(53, '1231', '123', '321@123.213', '321', '321\r\n', '', '2024-02-21 17:26:16', '2024-02-21 17:26:16', 1, 1, NULL, 0, NULL),
(54, '1231', '123', '321@123.213', '321', '321\r\n', '', '2024-02-21 17:26:16', '2024-02-21 17:26:16', 1, 1, NULL, 0, NULL),
(55, '1231', '123', '321@123.213', '321', '321\r\n', '', '2024-02-21 17:26:17', '2024-02-21 17:26:17', 1, 1, NULL, 0, NULL),
(56, '1234', '231', '123@1231223.321312', '213', '312', '', '2024-02-21 18:08:19', '2024-02-23 17:55:38', 1, 1, NULL, 0, NULL),
(57, '1234', '12', '123@1231223.321312', '213', '1111111', './image/', '2024-02-21 18:08:20', '2024-03-04 12:44:57', 0, 0, NULL, 1, 'null'),
(58, '1234', '１', 'yiyiyi@bfgdjk.fsgjkf', '111', '１', '', '2024-02-21 19:27:43', '2024-02-24 10:33:05', 1, 1, NULL, 0, 'null'),
(68, '1234', 'l;lk', '432@hil.ih', '889', '此留言已被删除', '', '2024-02-24 20:53:04', '2024-02-24 20:53:12', 1, 1, '2024-02-24 20:53:12', 0, NULL),
(69, '1234', '908098', '432@rg.sdv', '345', '453', '', '2024-02-24 22:12:16', '2024-02-24 22:12:16', 1, 1, NULL, 0, NULL),
(70, '1234', '87879', '0990908@rge.reg.reg', '309834908', 'isgrohjdfghjksdfgbkj', '', '2024-02-24 22:24:54', '2024-02-24 22:24:54', 1, 1, NULL, 0, NULL),
(71, '1234', '89980980', '12323@wdf.qerwer', '980890980980', '980980890980', './image/1.jpeg', '2024-02-24 22:27:00', '2024-02-24 23:36:57', 1, 1, NULL, 0, NULL),
(73, '1234', '09098', '23@y.huohuohukl', '798', '079', '', '2024-02-25 00:06:39', '2024-02-25 00:08:22', 0, 0, NULL, 0, NULL),
(74, '1234', '8998', 'gfio@ffffd.35sdf', '454598', '32', '', '2024-02-25 00:07:58', '2024-02-25 00:08:31', 0, 0, NULL, 0, NULL),
(75, '1234', '08990980', '21334@terwrg.gdfhg', '4458', '49056', '', '2024-02-25 00:33:10', '2024-02-25 00:33:10', 1, 1, NULL, 0, NULL),
(76, '1234', '08990980', '21334@terwrg.gdfhg', '4458', '49056', '', '2024-02-25 00:33:15', '2024-02-25 00:33:15', 1, 1, NULL, 0, NULL),
(77, '1234', 'ㄢ', 'rrsg@grereg.ee', '588', 'egrijprtgdjkfdhg ', '', '2024-02-25 00:35:17', '2024-02-25 00:35:17', 1, 1, NULL, 0, NULL),
(78, '1234', '123', '123234@gg.33', '959599595', '394949', '', '2024-02-25 09:27:36', '2024-02-25 09:27:36', 1, 1, NULL, 0, NULL),
(79, '1234', '1234', '2132@gdffg.33', '204040', '4040450', './image/2.jpeg', '2024-02-25 09:42:05', '2024-02-25 09:57:46', 0, 0, NULL, 0, ''),
(80, '1234', '112', '112@dss.dfd', '505606', '30506056', './image/', '2024-03-01 14:24:03', '2024-03-01 14:24:03', 1, 1, NULL, 0, NULL);

-- --------------------------------------------------------

--
-- 資料表結構 `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- 傾印資料表的資料 `users`
--

INSERT INTO `users` (`user_id`, `username`, `password`) VALUES
(1, 'admin', '1234');

--
-- 已傾印資料表的索引
--

--
-- 資料表索引 `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`);

--
-- 資料表索引 `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- 資料表索引 `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- 在傾印的資料表使用自動遞增(AUTO_INCREMENT)
--

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=74;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=81;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

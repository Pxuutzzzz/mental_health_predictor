-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 11, 2026 at 12:24 PM
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
-- Database: `mental_health_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `assessments`
--

CREATE TABLE `assessments` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `age` int(11) NOT NULL,
  `stress_level` int(11) NOT NULL,
  `anxiety_level` int(11) NOT NULL,
  `depression_level` int(11) NOT NULL,
  `mental_history` enum('Yes','No') DEFAULT 'No',
  `sleep_hours` decimal(3,1) NOT NULL,
  `exercise_level` enum('Low','Medium','High') DEFAULT 'Medium',
  `social_support` enum('Yes','No') DEFAULT 'Yes',
  `prediction` varchar(50) NOT NULL,
  `confidence` decimal(5,4) NOT NULL,
  `probabilities` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`probabilities`)),
  `recommendations` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`recommendations`)),
  `clinical_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`clinical_data`)),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `assessments`
--

INSERT INTO `assessments` (`id`, `user_id`, `age`, `stress_level`, `anxiety_level`, `depression_level`, `mental_history`, `sleep_hours`, `exercise_level`, `social_support`, `prediction`, `confidence`, `probabilities`, `recommendations`, `clinical_data`, `created_at`) VALUES
(20, 4, 23, 8, 8, 9, 'Yes', 10.0, 'Low', 'Yes', 'High Risk', 0.8200, '{\"Low Risk\":0.08849557522123894,\"Moderate Risk\":0.18584070796460178,\"High Risk\":0.7256637168141593}', '[\"\\u26a0\\ufe0f Sangat disarankan konsultasi profesional\",\"Hubungi profesional kesehatan mental segera\",\"Hubungi hotline krisis: 119 ext.8 atau 1500-567\",\"Jangan menyendiri - hubungi orang yang dipercaya\",\"Hindari keputusan besar sampai berkonsultasi dengan profesional\"]', NULL, '2026-01-05 07:39:46'),
(21, 4, 28, 5, 5, 5, 'Yes', 7.0, 'Medium', 'Yes', 'Moderate Risk', 0.8000, '{\"Low Risk\":0.25,\"Moderate Risk\":0.560344827586207,\"High Risk\":0.18965517241379312}', '[\"Pertimbangkan untuk berbicara dengan konselor\",\"Latih teknik manajemen stres\",\"Tingkatkan aktivitas fisik minimal 30 menit\\/hari\",\"Pertahankan rutinitas tidur yang konsisten\",\"Hubungi jaringan dukungan Anda\"]', NULL, '2026-01-05 09:27:30'),
(22, 4, 23, 6, 3, 7, 'No', 3.0, 'Low', 'Yes', 'High Risk', 0.8200, '{\"Low Risk\":0.125,\"Moderate Risk\":0.17857142857142858,\"High Risk\":0.6964285714285714}', '[\"\\u26a0\\ufe0f Sangat disarankan konsultasi profesional\",\"Hubungi profesional kesehatan mental segera\",\"Hubungi hotline krisis: 119 ext.8 atau 1500-567\",\"Jangan menyendiri - hubungi orang yang dipercaya\",\"Hindari keputusan besar sampai berkonsultasi dengan profesional\"]', NULL, '2026-01-05 09:48:49'),
(28, 5, 22, 5, 2, 2, '', 7.5, 'High', '', 'Low Risk', 0.8100, '{\"Low Risk\":0.7222222222222222,\"Moderate Risk\":0.20370370370370372,\"High Risk\":0.07407407407407408}', '[\"Lanjutkan kebiasaan hidup sehat Anda\",\"Pertahankan jadwal tidur teratur (7-9 jam)\",\"Tetap aktif secara fisik\",\"Jaga hubungan sosial yang kuat\"]', NULL, '2026-01-10 13:23:07'),
(31, 3, 22, 5, 2, 2, '', 7.5, 'High', '', 'Low Risk', 0.8000, '{\"Low Risk\":0.7567567567567567,\"Moderate Risk\":0.1891891891891892,\"High Risk\":0.05405405405405405}', '[\"Lanjutkan kebiasaan hidup sehat Anda\",\"Pertahankan jadwal tidur teratur (7-9 jam)\",\"Tetap aktif secara fisik\",\"Jaga hubungan sosial yang kuat\"]', NULL, '2026-01-10 15:02:32'),
(32, 3, 20, 9, 20, 25, '', 4.0, 'Low', '', 'High Risk', 0.9000, '{\"Low Risk\":0.13131313131313133,\"Moderate Risk\":0.21212121212121215,\"High Risk\":0.6565656565656566}', '[\"\\u26a0\\ufe0f Sangat disarankan konsultasi profesional\",\"Hubungi profesional kesehatan mental segera\",\"Hubungi hotline krisis: 119 ext.8 atau 1500-567\",\"Jangan menyendiri - hubungi orang yang dipercaya\",\"Hindari keputusan besar sampai berkonsultasi dengan profesional\"]', NULL, '2026-01-10 15:11:27'),
(33, 3, 19, 2, 7, 7, '', 7.5, 'High', '', 'Low Risk', 0.8200, '{\"Low Risk\":0.6752136752136751,\"Moderate Risk\":0.23931623931623935,\"High Risk\":0.08547008547008549}', '[\"Lanjutkan kebiasaan hidup sehat Anda\",\"Pertahankan jadwal tidur teratur (7-9 jam)\",\"Tetap aktif secara fisik\",\"Jaga hubungan sosial yang kuat\"]', NULL, '2026-01-10 15:20:14'),
(34, 3, 25, 5, 2, 2, '', 7.5, 'High', '', 'Low Risk', 0.8000, '{\"Low Risk\":0.7297297297297296,\"Moderate Risk\":0.18018018018018017,\"High Risk\":0.09009009009009009}', '[\"Lanjutkan kebiasaan hidup sehat Anda\",\"Pertahankan jadwal tidur teratur (7-9 jam)\",\"Tetap aktif secara fisik\",\"Jaga hubungan sosial yang kuat\"]', NULL, '2026-01-10 15:23:11');

-- --------------------------------------------------------

--
-- Table structure for table `audit_logs`
--

CREATE TABLE `audit_logs` (
  `id` int(11) NOT NULL,
  `event_type` varchar(50) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `action` varchar(255) NOT NULL,
  `details` text DEFAULT NULL,
  `status` varchar(20) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `user_agent` text DEFAULT NULL,
  `session_id` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `audit_logs`
--

INSERT INTO `audit_logs` (`id`, `event_type`, `user_id`, `action`, `details`, `status`, `ip_address`, `user_agent`, `session_id`, `created_at`) VALUES
(1, 'LOGIN', 2, 'User logged in successfully', '{\"email\":\"putriindah2343@gmail.com\"}', 'SUCCESS', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'ti1gmoip721lra3q3si8emnsu5', '2025-12-21 07:40:59'),
(2, 'SYSTEM_INIT', NULL, 'Security tables initialized', NULL, 'SUCCESS', 'SYSTEM', NULL, NULL, '2025-12-21 13:50:07'),
(3, 'DATA_ACCESS', 2, 'Password reset requested', '{\"email\":\"putriindah2343@gmail.com\"}', 'SUCCESS', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'vmi7ca0ruq8qps67cu8vr0jven', '2025-12-21 10:29:27'),
(4, 'LOGIN', 2, 'User logged in successfully', '{\"email\":\"putriindah2343@gmail.com\"}', 'SUCCESS', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '9v7uh41hlk7jh9onmans12kcqu', '2025-12-21 10:34:16'),
(5, 'LOGOUT', 2, 'User logged out', '[]', 'SUCCESS', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '9v7uh41hlk7jh9onmans12kcqu', '2025-12-21 10:39:34'),
(6, 'DATA_ACCESS', 2, 'Password reset requested', '{\"email\":\"putriindah2343@gmail.com\"}', 'SUCCESS', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'h79c2j1l26uhmd7cdhosau0gm0', '2025-12-21 10:39:54'),
(7, 'LOGIN', 2, 'User logged in successfully', '{\"email\":\"putriindah2343@gmail.com\"}', 'SUCCESS', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'h79c2j1l26uhmd7cdhosau0gm0', '2025-12-21 10:40:12'),
(8, 'LOGOUT', 2, 'User logged out', '[]', 'SUCCESS', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'h79c2j1l26uhmd7cdhosau0gm0', '2025-12-21 10:41:02'),
(9, 'LOGIN', 2, 'User logged in successfully', '{\"email\":\"putriindah2343@gmail.com\"}', 'SUCCESS', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'pn79mm3fola0rcbqolet0jh0d4', '2025-12-21 10:42:52'),
(10, 'LOGOUT', 2, 'User logged out', '[]', 'SUCCESS', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'pn79mm3fola0rcbqolet0jh0d4', '2025-12-21 10:44:58'),
(11, 'LOGIN', 2, 'User logged in successfully', '{\"email\":\"putriindah2343@gmail.com\"}', 'SUCCESS', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'aul2jfrsek4n7mmcofr4365t9b', '2025-12-21 10:45:00'),
(12, 'LOGOUT', 2, 'User logged out', '[]', 'SUCCESS', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'aul2jfrsek4n7mmcofr4365t9b', '2025-12-21 10:48:08'),
(13, 'LOGIN', 2, 'User logged in successfully', '{\"email\":\"putriindah2343@gmail.com\"}', 'SUCCESS', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '5qjabujpj7jo65nql4s61knu7o', '2025-12-21 10:52:36'),
(14, 'LOGOUT', 2, 'User logged out', '[]', 'SUCCESS', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '5qjabujpj7jo65nql4s61knu7o', '2025-12-21 10:52:51'),
(15, 'LOGIN', 2, 'User logged in successfully', '{\"email\":\"putriindah2343@gmail.com\"}', 'SUCCESS', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2t220muqvifj4pc085rvau96mm', '2025-12-21 11:00:56'),
(16, 'LOGOUT', 2, 'User logged out', '[]', 'SUCCESS', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2t220muqvifj4pc085rvau96mm', '2025-12-21 11:01:43'),
(17, 'LOGIN', 2, 'User logged in successfully', '{\"email\":\"putriindah2343@gmail.com\"}', 'SUCCESS', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '5iff57emsmbjlqg6c3hkku93c6', '2025-12-21 11:02:49'),
(18, 'LOGOUT', 2, 'User logged out', '[]', 'SUCCESS', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '5iff57emsmbjlqg6c3hkku93c6', '2025-12-21 11:05:03'),
(19, 'LOGIN', 2, 'User logged in successfully', '{\"email\":\"putriindah2343@gmail.com\"}', 'SUCCESS', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'f94ikfr8nsqf0kk5ft6k9m0ug7', '2025-12-21 11:05:53'),
(20, 'LOGOUT', 2, 'User logged out', '[]', 'SUCCESS', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'f94ikfr8nsqf0kk5ft6k9m0ug7', '2025-12-21 11:09:47'),
(21, 'LOGIN', 2, 'User logged in successfully', '{\"email\":\"putriindah2343@gmail.com\"}', 'SUCCESS', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'enjtfpog9ub1c0c7emc0cpft1f', '2025-12-21 11:11:35'),
(22, 'LOGOUT', 2, 'User logged out', '[]', 'SUCCESS', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'q6svjuei2lrr0lhngbuth9359t', '2026-01-05 07:53:34'),
(23, 'LOGIN_FAILED', NULL, 'Failed login attempt', '{\"email\":\"putriindah2343@gmail.com\",\"reason\":\"Invalid credentials\"}', 'FAILURE', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'a6pb22slm2ttj0bima5urjdqbh', '2026-01-05 07:53:59'),
(24, 'LOGIN_FAILED', NULL, 'Failed login attempt', '{\"email\":\"putriindah2343@gmail.com\",\"reason\":\"Invalid credentials\"}', 'FAILURE', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'a6pb22slm2ttj0bima5urjdqbh', '2026-01-05 07:54:55'),
(25, 'REGISTER', 4, 'New user registered', '{\"name\":\"Inces Puput\",\"email\":\"putriindahcahyani1419@gmail.com\"}', 'SUCCESS', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'a6pb22slm2ttj0bima5urjdqbh', '2026-01-05 07:55:32'),
(26, 'PREDICTION', 4, 'Mental health prediction performed', '{\"prediction\":\"High Risk\",\"confidence\":0.82}', 'SUCCESS', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'a6pb22slm2ttj0bima5urjdqbh', '2026-01-05 08:39:46'),
(27, 'HOSPITAL_SYNC', 4, 'Sinkronisasi hasil assessment ke rumah sakit', '{\"hospital_id\":\"rs_hermina\",\"hospital_name\":\"RS Hermina Depok\",\"status\":\"FAILED\"}', 'FAILURE', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'a6pb22slm2ttj0bima5urjdqbh', '2026-01-05 08:39:46'),
(28, 'PREDICTION', 4, 'Mental health prediction performed', '{\"prediction\":\"Moderate Risk\",\"confidence\":0.7999999999999999}', 'SUCCESS', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'a6pb22slm2ttj0bima5urjdqbh', '2026-01-05 10:27:30'),
(29, 'PREDICTION', 4, 'Mental health prediction performed', '{\"prediction\":\"High Risk\",\"confidence\":0.82}', 'SUCCESS', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'a6pb22slm2ttj0bima5urjdqbh', '2026-01-05 10:48:50'),
(30, 'REGISTER', 5, 'New user registered', '{\"name\":\"Putri Indah Cahyani\",\"email\":\"mahasiswa@unisayogya.ac.id\"}', 'SUCCESS', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'kob01jlk34ge1o50nrs7q03kda', '2026-01-10 13:53:39'),
(31, 'PREDICTION', 5, 'Mental health prediction performed', '{\"prediction\":\"Moderate Risk\",\"confidence\":0.74}', 'SUCCESS', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'kob01jlk34ge1o50nrs7q03kda', '2026-01-10 13:55:00'),
(32, 'LOGOUT', 5, 'User logged out', '[]', 'SUCCESS', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'kob01jlk34ge1o50nrs7q03kda', '2026-01-10 13:59:11'),
(33, 'LOGIN_FAILED', NULL, 'Failed login attempt', '{\"email\":\"admin@unisayogya.ac.id\",\"reason\":\"Invalid credentials\"}', 'FAILURE', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '57l2sm6vkol2dc8djqjoctn5tc', '2026-01-10 13:59:28'),
(34, 'LOGIN_FAILED', NULL, 'Failed login attempt', '{\"email\":\"Admin@unisayogya.ac.id\",\"reason\":\"Invalid credentials\"}', 'FAILURE', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '57l2sm6vkol2dc8djqjoctn5tc', '2026-01-10 13:59:35'),
(35, 'LOGIN_FAILED', NULL, 'Failed login attempt', '{\"email\":\"Admin@unisayogya.ac.id\",\"reason\":\"Invalid credentials\"}', 'FAILURE', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '57l2sm6vkol2dc8djqjoctn5tc', '2026-01-10 13:59:37'),
(36, 'LOGIN_FAILED', NULL, 'Failed login attempt', '{\"email\":\"Admin@unisayogya.ac.id\",\"reason\":\"Invalid credentials\"}', 'FAILURE', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '57l2sm6vkol2dc8djqjoctn5tc', '2026-01-10 13:59:38'),
(37, 'LOGIN_FAILED', NULL, 'Failed login attempt', '{\"email\":\"Admin@unisayogya.ac.id\",\"reason\":\"Invalid credentials\"}', 'FAILURE', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '57l2sm6vkol2dc8djqjoctn5tc', '2026-01-10 13:59:43'),
(38, 'LOGIN_FAILED', NULL, 'Failed login attempt', '{\"email\":\"Admin@unisayogya.ac.id\",\"reason\":\"Invalid credentials\"}', 'FAILURE', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '57l2sm6vkol2dc8djqjoctn5tc', '2026-01-10 13:59:54'),
(39, 'LOGIN_FAILED', NULL, 'Failed login attempt', '{\"email\":\"Admin@unisayogya.ac.id\",\"reason\":\"Invalid credentials\"}', 'FAILURE', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '57l2sm6vkol2dc8djqjoctn5tc', '2026-01-10 14:00:02'),
(40, 'LOGIN', 5, 'User logged in successfully', '{\"email\":\"mahasiswa@unisayogya.ac.id\"}', 'SUCCESS', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '57l2sm6vkol2dc8djqjoctn5tc', '2026-01-10 14:00:11'),
(41, 'PREDICTION', 5, 'Mental health prediction performed', '{\"prediction\":\"Moderate Risk\",\"confidence\":0.75}', 'SUCCESS', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '57l2sm6vkol2dc8djqjoctn5tc', '2026-01-10 14:01:29'),
(42, 'PREDICTION', 5, 'Mental health prediction performed', '{\"prediction\":\"Moderate Risk\",\"confidence\":0.76}', 'SUCCESS', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '57l2sm6vkol2dc8djqjoctn5tc', '2026-01-10 14:09:29'),
(43, 'PREDICTION', 5, 'Mental health prediction performed', '{\"prediction\":\"Low Risk\",\"confidence\":0.8200000000000001}', 'SUCCESS', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '57l2sm6vkol2dc8djqjoctn5tc', '2026-01-10 14:12:42'),
(44, 'PREDICTION', 5, 'Mental health prediction performed', '{\"prediction\":\"Low Risk\",\"confidence\":0.88}', 'SUCCESS', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '57l2sm6vkol2dc8djqjoctn5tc', '2026-01-10 14:18:21'),
(45, 'PREDICTION', 5, 'Mental health prediction performed', '{\"prediction\":\"Low Risk\",\"confidence\":0.81}', 'SUCCESS', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '57l2sm6vkol2dc8djqjoctn5tc', '2026-01-10 14:23:07'),
(46, 'LOGOUT', 5, 'User logged out', '[]', 'SUCCESS', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '57l2sm6vkol2dc8djqjoctn5tc', '2026-01-10 14:40:53'),
(47, 'LOGIN', 3, 'User logged in via Google', '{\"email\":\"2211501021@student.unisayogya.ac.id\"}', 'SUCCESS', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '71p15ubj925mrcihnou5op9mtg', '2026-01-10 15:05:34'),
(48, 'LOGIN', 2, 'User logged in via Google', '{\"email\":\"putriindah2343@gmail.com\"}', 'SUCCESS', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'u9unof60d61526230eg3m4f35h', '2026-01-10 15:06:26'),
(49, 'PREDICTION', 3, 'Mental health prediction performed', '{\"prediction\":\"Low Risk\",\"confidence\":0.81}', 'SUCCESS', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '71p15ubj925mrcihnou5op9mtg', '2026-01-10 15:16:25'),
(50, 'PREDICTION', 3, 'Mental health prediction performed', '{\"prediction\":\"Low Risk\",\"confidence\":0.88}', 'SUCCESS', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '71p15ubj925mrcihnou5op9mtg', '2026-01-10 15:58:25'),
(51, 'PREDICTION', 3, 'Mental health prediction performed', '{\"prediction\":\"Low Risk\",\"confidence\":0.8}', 'SUCCESS', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '71p15ubj925mrcihnou5op9mtg', '2026-01-10 16:02:32'),
(52, 'LOGOUT', 3, 'User logged out', '[]', 'SUCCESS', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '71p15ubj925mrcihnou5op9mtg', '2026-01-10 16:04:28'),
(53, 'LOGIN', 3, 'User logged in via Google', '{\"email\":\"2211501021@student.unisayogya.ac.id\"}', 'SUCCESS', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '9lsuv1qcp4ssaqron2ibmks675', '2026-01-10 16:05:23'),
(54, 'PREDICTION', 3, 'Mental health prediction performed', '{\"prediction\":\"High Risk\",\"confidence\":0.8999999999999999}', 'SUCCESS', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '9lsuv1qcp4ssaqron2ibmks675', '2026-01-10 16:11:27'),
(55, 'PREDICTION', 3, 'Mental health prediction performed', '{\"prediction\":\"Low Risk\",\"confidence\":0.8200000000000001}', 'SUCCESS', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '9lsuv1qcp4ssaqron2ibmks675', '2026-01-10 16:20:14'),
(56, 'PREDICTION', 3, 'Mental health prediction performed', '{\"prediction\":\"Low Risk\",\"confidence\":0.8}', 'SUCCESS', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '9lsuv1qcp4ssaqron2ibmks675', '2026-01-10 16:23:11'),
(57, 'LOGOUT', 3, 'User logged out', '[]', 'SUCCESS', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '9lsuv1qcp4ssaqron2ibmks675', '2026-01-10 16:38:12'),
(58, 'LOGIN', 3, 'User logged in via Google', '{\"email\":\"2211501021@student.unisayogya.ac.id\"}', 'SUCCESS', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '8d4nvldtnt3768rklh8d554mqi', '2026-01-10 16:40:08'),
(59, 'LOGOUT', 3, 'User logged out', '[]', 'SUCCESS', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '8d4nvldtnt3768rklh8d554mqi', '2026-01-10 16:45:53'),
(60, 'LOGIN', 3, 'User logged in via Google', '{\"email\":\"2211501021@student.unisayogya.ac.id\"}', 'SUCCESS', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '08ma2cii2463tilijlibrt0ls2', '2026-01-10 16:46:04'),
(61, 'LOGIN', 3, 'User logged in via Google', '{\"email\":\"2211501021@student.unisayogya.ac.id\"}', 'SUCCESS', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'kdq4omst5etkc3h1u7tf5spr7n', '2026-01-11 09:47:15');

-- --------------------------------------------------------

--
-- Table structure for table `data_access_log`
--

CREATE TABLE `data_access_log` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL COMMENT 'User who accessed the data',
  `accessed_user_id` int(11) DEFAULT NULL COMMENT 'User whose data was accessed',
  `resource_type` varchar(50) NOT NULL COMMENT 'Type of resource (prediction, profile, etc.)',
  `resource_id` int(11) DEFAULT NULL COMMENT 'ID of accessed resource',
  `access_type` varchar(20) NOT NULL COMMENT 'READ, UPDATE, DELETE, EXPORT',
  `ip_address` varchar(45) NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `data_retention`
--

CREATE TABLE `data_retention` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL COMMENT 'User whose data is scheduled for deletion',
  `scheduled_deletion_date` date NOT NULL COMMENT 'Date when data should be deleted',
  `reason` varchar(100) NOT NULL COMMENT 'Reason for deletion (inactive, user_request, etc.)',
  `status` varchar(20) NOT NULL DEFAULT 'PENDING' COMMENT 'PENDING, COMPLETED, CANCELLED',
  `created_at` datetime NOT NULL,
  `completed_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `encrypted_data`
--

CREATE TABLE `encrypted_data` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL COMMENT 'Owner of the data',
  `data_type` varchar(50) NOT NULL COMMENT 'Type of encrypted data',
  `encrypted_value` text NOT NULL COMMENT 'AES-256 encrypted data',
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hospital_integrations`
--

CREATE TABLE `hospital_integrations` (
  `id` int(11) NOT NULL,
  `assessment_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `hospital_id` varchar(50) NOT NULL,
  `hospital_name` varchar(150) NOT NULL,
  `patient_reference` varchar(100) DEFAULT NULL,
  `status` enum('PENDING','SUCCESS','FAILED','QUEUED') DEFAULT 'PENDING',
  `request_payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`request_payload`)),
  `response_payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`response_payload`)),
  `response_code` varchar(20) DEFAULT NULL,
  `error_message` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hospital_integrations`
--

INSERT INTO `hospital_integrations` (`id`, `assessment_id`, `user_id`, `hospital_id`, `hospital_name`, `patient_reference`, `status`, `request_payload`, `response_payload`, `response_code`, `error_message`, `created_at`, `updated_at`) VALUES
(1, 20, 4, 'rs_hermina', 'RS Hermina Depok', 'RS-46F97B4512', 'FAILED', '{\"facility_id\":\"rs_hermina\",\"facility_name\":\"RS Hermina Depok\",\"assessment_id\":\"20\",\"patient_reference\":\"RS-46F97B4512\",\"submitted_at\":\"2026-01-05T08:39:46+01:00\",\"risk_category\":\"High Risk\",\"risk_score\":1,\"confidence\":0.82,\"probabilities\":{\"Low Risk\":0.08849557522123894,\"Moderate Risk\":0.18584070796460178,\"High Risk\":0.7256637168141593},\"recommendations\":[\"\\u26a0\\ufe0f Sangat disarankan konsultasi profesional\",\"Hubungi profesional kesehatan mental segera\",\"Hubungi hotline krisis: 119 ext.8 atau 1500-567\",\"Jangan menyendiri - hubungi orang yang dipercaya\",\"Hindari keputusan besar sampai berkonsultasi dengan profesional\"],\"notes\":\"gila\",\"features\":{\"age\":23,\"stress\":8,\"anxiety\":8,\"depression\":9,\"sleep\":10,\"exercise\":\"Low\",\"social_support\":\"Yes\",\"mental_history\":\"Yes\"},\"meta\":{\"source\":\"mental_health_predictor_laravel\",\"mode\":\"sandbox\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/143.0.0.0 Safari\\/537.36\"}}', NULL, NULL, 'Could not resolve host: sandbox.hermina.id', '2026-01-05 07:39:46', '2026-01-05 07:39:46');

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `expires_at` timestamp NULL DEFAULT NULL,
  `used_at` timestamp NULL DEFAULT NULL,
  `ip_address` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `password_reset_tokens`
--

INSERT INTO `password_reset_tokens` (`id`, `email`, `token`, `created_at`, `expires_at`, `used_at`, `ip_address`) VALUES
(1, 'putriindah2343@gmail.com', 'ac479afe4b71fb5fcadfbae1d57e890e15f765163f550ae5ae8533dae7d294c5', '2025-12-21 09:29:27', '2025-12-21 04:29:27', NULL, '::1'),
(2, 'putriindah2343@gmail.com', 'c010a5f7c9f7f64665cc03135cb9d8e299fc9e3a1a46666cc910c5e11ce30ee6', '2025-12-21 09:39:54', '2025-12-21 04:39:54', NULL, '::1');

-- --------------------------------------------------------

--
-- Table structure for table `security_incidents`
--

CREATE TABLE `security_incidents` (
  `id` int(11) NOT NULL,
  `incident_type` varchar(50) NOT NULL COMMENT 'Type of security incident',
  `severity` varchar(20) NOT NULL COMMENT 'LOW, MEDIUM, HIGH, CRITICAL',
  `description` text NOT NULL COMMENT 'Description of incident',
  `affected_users` text DEFAULT NULL COMMENT 'JSON array of affected user IDs',
  `status` varchar(20) NOT NULL DEFAULT 'OPEN' COMMENT 'OPEN, INVESTIGATING, RESOLVED, CLOSED',
  `detected_at` datetime NOT NULL COMMENT 'When incident was detected',
  `resolved_at` datetime DEFAULT NULL COMMENT 'When incident was resolved',
  `resolution_notes` text DEFAULT NULL COMMENT 'How incident was resolved',
  `reported_by` int(11) DEFAULT NULL COMMENT 'User or admin who reported'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `google_id` varchar(255) DEFAULT NULL,
  `google_picture` varchar(500) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `google_id`, `google_picture`, `password`, `created_at`, `updated_at`) VALUES
(2, 'Putri Indah Cahyani', 'putriindah2343@gmail.com', '116572548905030669342', 'https://lh3.googleusercontent.com/a/ACg8ocL5bcV8qwTszLx00q9PDYN9mBxO7nO2VT1KFKbi2k7yVg1NObAx=s96-c', '$2y$10$K1BN5ZIJa2GDgzjpZugE2uGZy/A0TPkPtoURhv3trGIVzH9Ft87eG', '2025-12-04 04:15:05', '2026-01-10 14:06:26'),
(3, 'Incess', '2211501021@student.unisayogya.ac.id', '112752040889613194061', 'https://lh3.googleusercontent.com/a/ACg8ocKm2gl1QO_Z7QJQlAZykIM8cigriKOoWHzC7D8VSzjw-17K42I=s96-c', '$2y$10$joge93AnZRfMDhWKR1g0qemonF7hbkmmlUeKAnq9vgoMYlQRYRCV6', '2025-12-04 06:14:02', '2026-01-10 14:05:34'),
(4, 'Inces Puput', 'putriindahcahyani1419@gmail.com', NULL, NULL, '$2y$10$qKER/cvdh688nCTP9shvdOV1.I2b.zwY4jJS3b7EpMWADdE8Dcj36', '2026-01-05 06:55:32', '2026-01-05 06:55:32'),
(5, 'Putri Indah Cahyani', 'mahasiswa@unisayogya.ac.id', NULL, NULL, '$2y$10$5TntZdlq35A8bbSPYQPoQuzYwIPR3NVmp5H8eCB15aGjWBme2fkwO', '2026-01-10 12:53:39', '2026-01-10 12:53:39');

-- --------------------------------------------------------

--
-- Table structure for table `user_consents`
--

CREATE TABLE `user_consents` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL COMMENT 'User who gave consent',
  `consent_data` text NOT NULL COMMENT 'JSON of consent checkboxes',
  `consent_version` varchar(10) NOT NULL COMMENT 'Version of consent form',
  `ip_address` varchar(45) NOT NULL COMMENT 'IP when consent was given',
  `user_agent` text DEFAULT NULL COMMENT 'Browser when consent was given',
  `created_at` datetime NOT NULL COMMENT 'When consent was given',
  `withdrawn_at` datetime DEFAULT NULL COMMENT 'When consent was withdrawn (if applicable)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `assessments`
--
ALTER TABLE `assessments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_user_id` (`user_id`),
  ADD KEY `idx_created_at` (`created_at`);

--
-- Indexes for table `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_user_id` (`user_id`),
  ADD KEY `idx_event_type` (`event_type`),
  ADD KEY `idx_created_at` (`created_at`);

--
-- Indexes for table `data_access_log`
--
ALTER TABLE `data_access_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_user_id` (`user_id`),
  ADD KEY `idx_accessed_user_id` (`accessed_user_id`),
  ADD KEY `idx_created_at` (`created_at`);

--
-- Indexes for table `data_retention`
--
ALTER TABLE `data_retention`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_user_id` (`user_id`),
  ADD KEY `idx_scheduled_date` (`scheduled_deletion_date`),
  ADD KEY `idx_status` (`status`);

--
-- Indexes for table `encrypted_data`
--
ALTER TABLE `encrypted_data`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_user_id` (`user_id`),
  ADD KEY `idx_data_type` (`data_type`);

--
-- Indexes for table `hospital_integrations`
--
ALTER TABLE `hospital_integrations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `idx_hospital_id` (`hospital_id`),
  ADD KEY `idx_assessment` (`assessment_id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_email` (`email`),
  ADD KEY `idx_token` (`token`),
  ADD KEY `idx_expires_at` (`expires_at`);

--
-- Indexes for table `security_incidents`
--
ALTER TABLE `security_incidents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_detected_at` (`detected_at`),
  ADD KEY `idx_severity` (`severity`),
  ADD KEY `idx_status` (`status`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `google_id` (`google_id`),
  ADD KEY `idx_email` (`email`);

--
-- Indexes for table `user_consents`
--
ALTER TABLE `user_consents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_user_id` (`user_id`),
  ADD KEY `idx_created_at` (`created_at`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `assessments`
--
ALTER TABLE `assessments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `audit_logs`
--
ALTER TABLE `audit_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- AUTO_INCREMENT for table `data_access_log`
--
ALTER TABLE `data_access_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `data_retention`
--
ALTER TABLE `data_retention`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `encrypted_data`
--
ALTER TABLE `encrypted_data`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hospital_integrations`
--
ALTER TABLE `hospital_integrations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `security_incidents`
--
ALTER TABLE `security_incidents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `user_consents`
--
ALTER TABLE `user_consents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `assessments`
--
ALTER TABLE `assessments`
  ADD CONSTRAINT `assessments_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `data_access_log`
--
ALTER TABLE `data_access_log`
  ADD CONSTRAINT `data_access_log_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `data_access_log_ibfk_2` FOREIGN KEY (`accessed_user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `data_retention`
--
ALTER TABLE `data_retention`
  ADD CONSTRAINT `data_retention_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `encrypted_data`
--
ALTER TABLE `encrypted_data`
  ADD CONSTRAINT `encrypted_data_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `hospital_integrations`
--
ALTER TABLE `hospital_integrations`
  ADD CONSTRAINT `hospital_integrations_ibfk_1` FOREIGN KEY (`assessment_id`) REFERENCES `assessments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `hospital_integrations_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `user_consents`
--
ALTER TABLE `user_consents`
  ADD CONSTRAINT `user_consents_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

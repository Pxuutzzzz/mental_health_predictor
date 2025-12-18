-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 18, 2025 at 05:31 AM
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
  `user_id` int(11) NOT NULL,
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
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `assessments`
--

INSERT INTO `assessments` (`id`, `user_id`, `age`, `stress_level`, `anxiety_level`, `depression_level`, `mental_history`, `sleep_hours`, `exercise_level`, `social_support`, `prediction`, `confidence`, `probabilities`, `recommendations`, `created_at`) VALUES
(2, 2, 25, 5, 5, 5, 'No', 7.0, 'Medium', 'Yes', 'Moderate Risk', 0.8000, '{\"Low Risk\":0.2542372881355932,\"Moderate Risk\":0.5677966101694916,\"High Risk\":0.17796610169491525}', '[\"Pertimbangkan untuk berbicara dengan konselor\",\"Latih teknik manajemen stres\",\"Tingkatkan aktivitas fisik minimal 30 menit\\/hari\",\"Pertahankan rutinitas tidur yang konsisten\",\"Hubungi jaringan dukungan Anda\"]', '2025-12-04 04:51:47'),
(3, 2, 22, 9, 9, 9, 'Yes', 3.0, 'Low', 'No', 'High Risk', 0.9200, '{\"Low Risk\":0.10091743119266057,\"Moderate Risk\":0.23853211009174316,\"High Risk\":0.6605504587155964}', '[\"\\u26a0\\ufe0f Sangat disarankan konsultasi profesional\",\"Hubungi profesional kesehatan mental segera\",\"Hubungi hotline krisis: 119 ext.8 atau 1500-567\",\"Jangan menyendiri - hubungi orang yang dipercaya\",\"Hindari keputusan besar sampai berkonsultasi dengan profesional\"]', '2025-12-04 05:59:08'),
(4, 2, 22, 9, 9, 9, 'Yes', 3.0, 'Low', 'No', 'High Risk', 0.8800, '{\"Low Risk\":0.09734513274336284,\"Moderate Risk\":0.23008849557522126,\"High Risk\":0.672566371681416}', '[\"\\u26a0\\ufe0f Sangat disarankan konsultasi profesional\",\"Hubungi profesional kesehatan mental segera\",\"Hubungi hotline krisis: 119 ext.8 atau 1500-567\",\"Jangan menyendiri - hubungi orang yang dipercaya\",\"Hindari keputusan besar sampai berkonsultasi dengan profesional\"]', '2025-12-04 05:59:13'),
(5, 2, 22, 9, 9, 9, 'Yes', 3.0, 'Low', 'No', 'High Risk', 0.8200, '{\"Low Risk\":0.12173913043478263,\"Moderate Risk\":0.2173913043478261,\"High Risk\":0.6608695652173914}', '[\"\\u26a0\\ufe0f Sangat disarankan konsultasi profesional\",\"Hubungi profesional kesehatan mental segera\",\"Hubungi hotline krisis: 119 ext.8 atau 1500-567\",\"Jangan menyendiri - hubungi orang yang dipercaya\",\"Hindari keputusan besar sampai berkonsultasi dengan profesional\"]', '2025-12-04 06:00:45'),
(6, 2, 22, 9, 9, 9, 'Yes', 3.0, 'Low', 'No', 'High Risk', 0.8600, '{\"Low Risk\":0.10891089108910891,\"Moderate Risk\":0.19801980198019803,\"High Risk\":0.6930693069306931}', '[\"\\u26a0\\ufe0f Sangat disarankan konsultasi profesional\",\"Hubungi profesional kesehatan mental segera\",\"Hubungi hotline krisis: 119 ext.8 atau 1500-567\",\"Jangan menyendiri - hubungi orang yang dipercaya\",\"Hindari keputusan besar sampai berkonsultasi dengan profesional\"]', '2025-12-04 06:01:58'),
(7, 2, 22, 9, 9, 9, 'Yes', 4.0, 'Low', 'No', 'High Risk', 0.9200, '{\"Low Risk\":0.09999999999999999,\"Moderate Risk\":0.2545454545454546,\"High Risk\":0.6454545454545454}', '[\"\\u26a0\\ufe0f Sangat disarankan konsultasi profesional\",\"Hubungi profesional kesehatan mental segera\",\"Hubungi hotline krisis: 119 ext.8 atau 1500-567\",\"Jangan menyendiri - hubungi orang yang dipercaya\",\"Hindari keputusan besar sampai berkonsultasi dengan profesional\"]', '2025-12-04 06:04:35'),
(8, 2, 25, 5, 5, 5, 'No', 7.0, 'Low', 'Yes', 'Moderate Risk', 0.7200, '{\"Low Risk\":0.27678571428571425,\"Moderate Risk\":0.5535714285714286,\"High Risk\":0.16964285714285712}', '[\"Pertimbangkan untuk berbicara dengan konselor\",\"Latih teknik manajemen stres\",\"Tingkatkan aktivitas fisik minimal 30 menit\\/hari\",\"Pertahankan rutinitas tidur yang konsisten\",\"Hubungi jaringan dukungan Anda\"]', '2025-12-04 06:05:03'),
(9, 2, 25, 2, 0, 0, 'No', 10.5, 'High', 'Yes', 'Low Risk', 0.8800, '{\"Low Risk\":0.7118644067796609,\"Moderate Risk\":0.23728813559322032,\"High Risk\":0.05084745762711864}', '[\"Lanjutkan kebiasaan hidup sehat Anda\",\"Pertahankan jadwal tidur teratur (7-9 jam)\",\"Tetap aktif secara fisik\",\"Jaga hubungan sosial yang kuat\"]', '2025-12-04 06:08:22'),
(10, 2, 21, 0, 0, 0, 'No', 9.5, 'High', 'Yes', 'Low Risk', 0.8600, '{\"Low Risk\":0.6806722689075628,\"Moderate Risk\":0.24369747899159663,\"High Risk\":0.07563025210084032}', '[\"Lanjutkan kebiasaan hidup sehat Anda\",\"Pertahankan jadwal tidur teratur (7-9 jam)\",\"Tetap aktif secara fisik\",\"Jaga hubungan sosial yang kuat\"]', '2025-12-04 06:10:15'),
(11, 3, 25, 5, 5, 5, 'No', 7.0, 'Low', 'Yes', 'Moderate Risk', 0.7500, '{\"Low Risk\":0.2627118644067797,\"Moderate Risk\":0.5508474576271187,\"High Risk\":0.1864406779661017}', '[\"Pertimbangkan untuk berbicara dengan konselor\",\"Latih teknik manajemen stres\",\"Tingkatkan aktivitas fisik minimal 30 menit\\/hari\",\"Pertahankan rutinitas tidur yang konsisten\",\"Hubungi jaringan dukungan Anda\"]', '2025-12-04 06:17:14'),
(12, 2, 23, 9, 9, 6, 'No', 8.0, 'Low', 'No', 'High Risk', 0.9100, '{\"Low Risk\":0.11570247933884299,\"Moderate Risk\":0.19834710743801656,\"High Risk\":0.6859504132231405}', '[\"\\u26a0\\ufe0f Sangat disarankan konsultasi profesional\",\"Hubungi profesional kesehatan mental segera\",\"Hubungi hotline krisis: 119 ext.8 atau 1500-567\",\"Jangan menyendiri - hubungi orang yang dipercaya\",\"Hindari keputusan besar sampai berkonsultasi dengan profesional\"]', '2025-12-05 06:03:10'),
(13, 2, 76, 10, 10, 0, 'Yes', 0.0, 'High', 'No', 'High Risk', 0.8600, '{\"Low Risk\":0.08333333333333333,\"Moderate Risk\":0.22499999999999998,\"High Risk\":0.6916666666666667}', '[\"\\u26a0\\ufe0f Sangat disarankan konsultasi profesional\",\"Hubungi profesional kesehatan mental segera\",\"Hubungi hotline krisis: 119 ext.8 atau 1500-567\",\"Jangan menyendiri - hubungi orang yang dipercaya\",\"Hindari keputusan besar sampai berkonsultasi dengan profesional\"]', '2025-12-09 08:01:28'),
(14, 2, 25, 5, 5, 5, 'No', 7.0, 'Low', 'Yes', 'Moderate Risk', 0.7400, '{\"Low Risk\":0.27777777777777773,\"Moderate Risk\":0.5370370370370371,\"High Risk\":0.18518518518518517}', '[\"Pertimbangkan untuk berbicara dengan konselor\",\"Latih teknik manajemen stres\",\"Tingkatkan aktivitas fisik minimal 30 menit\\/hari\",\"Pertahankan rutinitas tidur yang konsisten\",\"Hubungi jaringan dukungan Anda\"]', '2025-12-09 08:09:07'),
(15, 2, 25, 5, 6, 5, 'No', 7.0, 'High', 'Yes', 'Moderate Risk', 0.7900, '{\"Low Risk\":0.27586206896551724,\"Moderate Risk\":0.5344827586206897,\"High Risk\":0.1896551724137931}', '[\"Pertimbangkan untuk berbicara dengan konselor\",\"Latih teknik manajemen stres\",\"Tingkatkan aktivitas fisik minimal 30 menit\\/hari\",\"Pertahankan rutinitas tidur yang konsisten\",\"Hubungi jaringan dukungan Anda\"]', '2025-12-09 08:12:06');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `created_at`, `updated_at`) VALUES
(2, 'Putri Indah Cahyani', 'putriindah2343@gmail.com', '$2y$10$K1BN5ZIJa2GDgzjpZugE2uGZy/A0TPkPtoURhv3trGIVzH9Ft87eG', '2025-12-04 04:15:05', '2025-12-04 04:15:05'),
(3, 'Incess', '2211501021@student.unisayogya.ac.id', '$2y$10$joge93AnZRfMDhWKR1g0qemonF7hbkmmlUeKAnq9vgoMYlQRYRCV6', '2025-12-04 06:14:02', '2025-12-04 06:14:02');

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
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `assessments`
--
ALTER TABLE `assessments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `assessments`
--
ALTER TABLE `assessments`
  ADD CONSTRAINT `assessments_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

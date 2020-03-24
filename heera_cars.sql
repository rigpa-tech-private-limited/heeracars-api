-- phpMyAdmin SQL Dump
-- version 4.9.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Mar 24, 2020 at 04:29 PM
-- Server version: 5.7.26
-- PHP Version: 7.4.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `heera_cars`
--

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(5) NOT NULL,
  `name` varchar(60) DEFAULT NULL,
  `email` varchar(60) DEFAULT NULL,
  `mobile` bigint(10) NOT NULL,
  `password` varchar(60) NOT NULL,
  `token` varchar(100) NOT NULL,
  `otp` bigint(6) NOT NULL,
  `is_expired` int(1) NOT NULL DEFAULT '0',
  `role` varchar(50) NOT NULL,
  `active` int(1) NOT NULL DEFAULT '1',
  `created_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `mobile`, `password`, `token`, `otp`, `is_expired`, `role`, `active`, `created_on`, `updated_on`) VALUES
(1, 'Admin', 'admin@heeracars.in', 7448666351, 'password', '$2y$05$G4KgkD62XdcqfPj8Qjq8Pe5NheIbLhuYgm/zLH4rddyfRNxWnATP6', 678059, 0, 'admin', 1, '2020-03-20 16:31:02', '2020-03-24 21:56:07'),
(2, 'Karthik', 'karthik@rigpa.in', 8807882577, 'password', '$2y$05$AOPXCe.8KUHoEKo1pdotLu.sW8Iq63t0nSJ/sTZFbAQTi4x9jJKBu', 476166, 1, 'agent', 1, '2020-03-20 20:22:54', '2020-03-24 21:56:07');

--
-- Indexes for dumped tables
--

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
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

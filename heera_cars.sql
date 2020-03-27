-- phpMyAdmin SQL Dump
-- version 4.9.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Mar 27, 2020 at 08:07 AM
-- Server version: 5.7.26
-- PHP Version: 7.4.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `heera_cars`
--

-- --------------------------------------------------------

--
-- Table structure for table `car_fuel_variant`
--

CREATE TABLE `car_fuel_variant` (
  `variant_id` int(50) NOT NULL,
  `model_id` int(50) DEFAULT NULL,
  `year_id` int(50) NOT NULL,
  `variant_name` varchar(500) DEFAULT NULL,
  `variant_name_short` varchar(200) DEFAULT NULL,
  `variant_display_name` varchar(500) DEFAULT NULL,
  `variant_type` varchar(100) DEFAULT NULL,
  `display_order` int(11) NOT NULL DEFAULT '0',
  `status` varchar(100) DEFAULT 'Active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `car_fuel_variant`
--

INSERT INTO `car_fuel_variant` (`variant_id`, `model_id`, `year_id`, `variant_name`, `variant_name_short`, `variant_display_name`, `variant_type`, `display_order`, `status`) VALUES
(1, 1, 1, 'LE 7 SEATER', 'LE 7 SEATER', 'LE 7 Seater', 'Diesel', 0, 'Active'),
(2, 1, 1, 'LE 8 SEATER', 'LE 8 SEATER', 'LE 8 Seater', 'Diesel', 0, 'Active'),
(3, 1, 1, 'LS 7 SEATER', 'LS 7 SEATER', 'LS 7 Seater', 'Diesel', 0, 'Active'),
(4, 1, 1, 'LS 8 SEATER', 'LS 8 SEATER', 'LS 8 Seater', 'Diesel', 0, 'Active'),
(5, 1, 1, 'LX 7 SEATER', 'LX 7 SEATER', 'LX 7 Seater', 'Diesel', 0, 'Active'),
(6, 1, 1, 'LX 8 SEATER', 'LX 8 SEATER', 'LX 8 Seater', 'Diesel', 0, 'Active'),
(7, 1, 2, 'LE 7 SEATER', 'LE 7 SEATER', 'LE 7 Seater', 'Diesel', 0, 'Active'),
(8, 1, 2, 'LE 8 SEATER', 'LE 8 SEATER', 'LE 8 Seater', 'Diesel', 0, 'Active'),
(9, 1, 2, 'LS 7 SEATER', 'LS 7 SEATER', 'LS 7 Seater', 'Diesel', 0, 'Active'),
(10, 1, 2, 'LS 8 SEATER', 'LS 8 SEATER', 'LS 8 Seater', 'Diesel', 0, 'Active'),
(11, 1, 2, 'LX 7 SEATER', 'LX 7 SEATER', 'LX 7 Seater', 'Diesel', 0, 'Active'),
(12, 1, 2, 'LX 8 SEATER', 'LX 8 SEATER', 'LX 8 Seater', 'Diesel', 0, 'Active'),
(13, 1, 3, 'LE 7 SEATER', 'LE 7 SEATER', 'LE 7 Seater', 'Diesel', 0, 'Active'),
(14, 1, 3, 'LE 8 SEATER', 'LE 8 SEATER', 'LE 8 Seater', 'Diesel', 0, 'Active'),
(15, 1, 3, 'LS 7 SEATER', 'LS 7 SEATER', 'LS 7 Seater', 'Diesel', 0, 'Active'),
(16, 1, 3, 'LS 8 SEATER', 'LS 8 SEATER', 'LS 8 Seater', 'Diesel', 0, 'Active'),
(17, 1, 3, 'LX 7 SEATER', 'LX 7 SEATER', 'LX 7 Seater', 'Diesel', 0, 'Active'),
(18, 1, 3, 'LX 8 SEATER', 'LX 8 SEATER', 'LX 8 Seater', 'Diesel', 0, 'Active');

-- --------------------------------------------------------

--
-- Table structure for table `car_make`
--

CREATE TABLE `car_make` (
  `make_id` int(10) NOT NULL,
  `make_name` varchar(200) NOT NULL,
  `display_order` int(5) NOT NULL DEFAULT '0',
  `make_display` varchar(200) NOT NULL,
  `is_popular` varchar(10) NOT NULL DEFAULT 'no',
  `logo` varchar(500) DEFAULT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'Active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `car_make`
--

INSERT INTO `car_make` (`make_id`, `make_name`, `display_order`, `make_display`, `is_popular`, `logo`, `status`) VALUES
(1, 'ASHOK LEYLAND', 0, 'Ashok Leyland', 'no', NULL, 'Active'),
(2, 'ASTON MARTIN', 0, 'Aston Martin', 'no', NULL, 'Active'),
(3, 'AUDI', 1, 'Audi', 'yes', 'https://static.cars24.com/cars24/make-logo/audi.png', 'Active'),
(4, 'BENTLEY', 0, 'Bentley', 'no', NULL, 'Active'),
(5, 'BMW', 2, 'BMW', 'yes', 'https://static.cars24.com/cars24/make-logo/bmw.png', 'Active'),
(6, 'BUGATTI', 0, 'Bugatti', 'no', NULL, 'Active'),
(7, 'CHEVROLET', 11, 'Chevrolet', 'yes', 'https://static.cars24.com/cars24/make-logo/chevrolet.png', 'Active'),
(8, 'DATSUN', 0, 'Datsun', 'no', NULL, 'Active'),
(9, 'FERRARI', 0, 'Ferrari', 'no', NULL, 'Active'),
(10, 'FIAT', 4, 'FIAT', 'yes', 'https://static.cars24.com/cars24/make-logo/fiat.png', 'Active');

-- --------------------------------------------------------

--
-- Table structure for table `car_model`
--

CREATE TABLE `car_model` (
  `model_id` int(50) NOT NULL,
  `make_id` int(50) NOT NULL,
  `model_name` varchar(500) DEFAULT NULL,
  `model_display` varchar(500) DEFAULT NULL,
  `is_popular` varchar(5) DEFAULT 'no',
  `body_type` varchar(100) DEFAULT NULL,
  `display_order` int(11) NOT NULL DEFAULT '0',
  `status` varchar(50) NOT NULL DEFAULT 'Active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `car_model`
--

INSERT INTO `car_model` (`model_id`, `make_id`, `model_name`, `model_display`, `is_popular`, `body_type`, `display_order`, `status`) VALUES
(1, 1, 'STILE', 'Stile', 'yes', 'SUV', 0, 'Active'),
(2, 2, 'DB9', 'DB9', 'no', 'Luxury Sedan', 0, 'Active'),
(3, 2, 'DBS', 'DBS', 'no', 'Luxury Sedan', 0, 'Active'),
(4, 2, 'RAPIDE', 'Rapide', 'no', 'Luxury Sedan', 0, 'Active'),
(5, 2, 'V12 VANTAGE', 'V12 Vantage', 'no', 'Luxury Sedan', 0, 'Active'),
(6, 2, 'V8 VANTAGE', 'V8 Vantage', 'no', 'Luxury Sedan', 0, 'Active'),
(7, 2, 'VANQUISH', 'Vanquish', 'no', 'Luxury Sedan', 0, 'Active'),
(8, 2, 'VIRAGE', 'VIRAGE', 'no', 'Luxury Sedan', 0, 'Active'),
(9, 2, 'ZAGATO', 'Zagato', 'no', 'Luxury Sedan', 0, 'Active'),
(10, 3, 'A3', 'A3', 'no', 'Luxury Sedan', 0, 'Active'),
(11, 3, 'A4', 'A4', 'yes', 'Luxury Sedan', 0, 'Active'),
(12, 3, 'A5', 'A5', 'no', 'Luxury Sedan', 0, 'Active'),
(13, 3, 'A6', 'A6', 'no', 'Luxury Sedan', 0, 'Active'),
(14, 3, 'A7', 'A7', 'no', 'Luxury Sedan', 0, 'Active'),
(15, 3, 'A8', 'A8', 'no', 'Luxury Sedan', 0, 'Active'),
(16, 3, 'A8L', 'A8L', 'no', 'Luxury Sedan', 0, 'Active'),
(17, 3, 'Q3', 'Q3', 'no', 'Luxury SUV', 0, 'Active'),
(18, 3, 'Q5', 'Q5', 'no', 'Luxury SUV', 0, 'Active'),
(19, 3, 'Q7', 'Q7', 'no', 'Luxury SUV', 0, 'Active'),
(20, 3, 'R8', 'R8', 'no', 'Luxury Sedan', 0, 'Active'),
(21, 3, 'RS 5', 'RS 5', 'no', 'Luxury Sedan', 0, 'Active'),
(22, 3, 'RS 7', 'RS 7', 'no', 'Luxury Sedan', 0, 'Active'),
(23, 3, 'S4', 'S4', 'no', 'Luxury Sedan', 0, 'Active'),
(24, 3, 'S5', 'S5', 'no', 'Luxury Sedan', 0, 'Active'),
(25, 3, 'S6', 'S6', 'no', 'Luxury Sedan', 0, 'Active'),
(26, 3, 'TT', 'TT', 'no', 'Luxury Sedan', 0, 'Active');

-- --------------------------------------------------------

--
-- Table structure for table `car_variant_year`
--

CREATE TABLE `car_variant_year` (
  `year_id` int(50) NOT NULL,
  `model_id` int(50) NOT NULL,
  `year` varchar(10) DEFAULT NULL,
  `display_order` int(11) NOT NULL DEFAULT '0',
  `status` varchar(50) NOT NULL DEFAULT 'Active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `car_variant_year`
--

INSERT INTO `car_variant_year` (`year_id`, `model_id`, `year`, `display_order`, `status`) VALUES
(1, 1, '2013', 0, 'Active'),
(2, 1, '2014', 0, 'Active'),
(3, 1, '2015', 0, 'Active'),
(4, 2, '2013', 0, 'Active'),
(5, 2, '2014', 0, 'Active'),
(6, 2, '2015', 0, 'Active'),
(7, 2, '2016', 0, 'Active'),
(8, 2, '2017', 0, 'Active'),
(9, 2, '2018', 0, 'Active'),
(10, 3, '2013', 0, 'Active'),
(11, 3, '2014', 0, 'Active'),
(12, 3, '2015', 0, 'Active'),
(13, 3, '2016', 0, 'Active'),
(14, 3, '2017', 0, 'Active'),
(15, 3, '2018', 0, 'Active'),
(16, 4, '2013', 0, 'Active'),
(17, 4, '2014', 0, 'Active'),
(18, 4, '2015', 0, 'Active'),
(19, 4, '2016', 0, 'Active'),
(20, 4, '2017', 0, 'Active'),
(21, 4, '2018', 0, 'Active'),
(22, 5, '2013', 0, 'Active'),
(23, 5, '2014', 0, 'Active'),
(24, 5, '2015', 0, 'Active'),
(25, 5, '2016', 0, 'Active'),
(26, 5, '2017', 0, 'Active'),
(27, 5, '2018', 0, 'Active');

-- --------------------------------------------------------

--
-- Table structure for table `quotations`
--

CREATE TABLE `quotations` (
  `id` int(50) NOT NULL,
  `user_id` int(50) NOT NULL,
  `make_id` int(50) NOT NULL,
  `make_display` varchar(200) DEFAULT NULL,
  `model_id` int(50) NOT NULL,
  `model_display` varchar(200) DEFAULT NULL,
  `year_id` int(50) NOT NULL,
  `year` varchar(50) DEFAULT NULL,
  `variant_id` int(50) NOT NULL,
  `variant_display` varchar(200) DEFAULT NULL,
  `car_color` varchar(100) DEFAULT NULL,
  `fuel_type` varchar(20) DEFAULT NULL,
  `car_kms` varchar(50) DEFAULT NULL,
  `car_owner` varchar(50) DEFAULT NULL,
  `is_replacement` varchar(10) DEFAULT NULL,
  `insurance_date` varchar(100) DEFAULT NULL,
  `refurbishment_cost` varchar(50) DEFAULT NULL,
  `requested_price` varchar(50) DEFAULT NULL,
  `approved_price` varchar(50) DEFAULT NULL,
  `approved_by` int(50) NOT NULL DEFAULT '0',
  `dropped_by` int(50) NOT NULL DEFAULT '0',
  `status` int(1) NOT NULL DEFAULT '0',
  `created_on` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_on` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `quotations`
--

INSERT INTO `quotations` (`id`, `user_id`, `make_id`, `make_display`, `model_id`, `model_display`, `year_id`, `year`, `variant_id`, `variant_display`, `car_color`, `fuel_type`, `car_kms`, `car_owner`, `is_replacement`, `insurance_date`, `refurbishment_cost`, `requested_price`, `approved_price`, `approved_by`, `dropped_by`, `status`, `created_on`, `updated_on`) VALUES
(1, 1, 1, 'Ashok Leyland', 1, 'Stile', 1, '2013', 1, 'LE 7 Seater', 'white', 'deisel', '1000', 'Single', 'Yes', '2020-08-01', '20000', '500000', NULL, 0, 0, 0, '2020-03-26 21:32:00', '2020-03-26 21:32:00'),
(2, 1, 1, 'Ashok Leyland', 2, 'DB9', 4, '2013', 7, 'LE 7 Seater', 'blue', 'Petrol', '6000', 'Second', 'no', '2028-08-01', '40000', '1000000', NULL, 0, 0, 0, '2020-03-26 21:34:37', '2020-03-26 21:34:37');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(5) NOT NULL,
  `name` varchar(60) DEFAULT NULL,
  `email` varchar(60) DEFAULT NULL,
  `mobile` bigint(10) DEFAULT NULL,
  `company` varchar(200) DEFAULT NULL,
  `location` varchar(100) DEFAULT NULL,
  `password` varchar(60) DEFAULT NULL,
  `token` varchar(100) DEFAULT NULL,
  `otp` bigint(6) DEFAULT NULL,
  `is_expired` int(1) NOT NULL DEFAULT '0',
  `role` varchar(50) DEFAULT NULL,
  `active` int(1) NOT NULL DEFAULT '1',
  `created_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `mobile`, `company`, `location`, `password`, `token`, `otp`, `is_expired`, `role`, `active`, `created_on`, `updated_on`) VALUES
(1, 'Vinoth', 'vinoth@rigpa.in', 8190075138, 'Heera Cars', 'Chennai', 'heera@123', '$2y$05$aZNoneScrGqLdcNBwYIA0uIOplf0PkU56CMoU3aqNpuygQiOfcg0i', 909797, 1, 'admin', 1, '2020-03-26 13:05:31', '2020-03-26 13:05:31'),
(2, 'Karthik', 'karthik@rigpa.io', 7448666351, 'Heera Cars', 'Chennai', 'heera@123', NULL, NULL, 0, 'admin', 1, '2020-03-26 13:05:31', '2020-03-26 13:05:31'),
(3, 'Maha', 'maha@rigpa.in', 9677030281, 'Heera Cars', 'Chennai', 'heera@123', NULL, NULL, 0, 'admin', 1, '2020-03-26 13:05:31', '2020-03-26 13:05:31');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `car_fuel_variant`
--
ALTER TABLE `car_fuel_variant`
  ADD PRIMARY KEY (`variant_id`);

--
-- Indexes for table `car_make`
--
ALTER TABLE `car_make`
  ADD PRIMARY KEY (`make_id`);

--
-- Indexes for table `car_model`
--
ALTER TABLE `car_model`
  ADD PRIMARY KEY (`model_id`);

--
-- Indexes for table `car_variant_year`
--
ALTER TABLE `car_variant_year`
  ADD PRIMARY KEY (`year_id`);

--
-- Indexes for table `quotations`
--
ALTER TABLE `quotations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `mobile` (`mobile`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `car_fuel_variant`
--
ALTER TABLE `car_fuel_variant`
  MODIFY `variant_id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `car_make`
--
ALTER TABLE `car_make`
  MODIFY `make_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `car_model`
--
ALTER TABLE `car_model`
  MODIFY `model_id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `car_variant_year`
--
ALTER TABLE `car_variant_year`
  MODIFY `year_id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `quotations`
--
ALTER TABLE `quotations`
  MODIFY `id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

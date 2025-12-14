-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Apr 01, 2024 at 10:22 AM
-- Server version: 10.4.14-MariaDB
-- PHP Version: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `digital_wallet_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `accounts`
--

CREATE TABLE `accounts` (
  `id` int(30) NOT NULL,
  `account_number` varchar(50) NOT NULL,
  `pin` text NOT NULL,
  `firstname` varchar(250) NOT NULL,
  `lastname` varchar(250) NOT NULL,
  `middlename` varchar(250) NOT NULL,
  `msisdn` varchar(20) NOT NULL,
  `password` text NOT NULL,
  `branch_id` int(30) NOT NULL,
  `type` enum('individual','cooperative') NOT NULL DEFAULT 'individual',
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `physical_id_type` enum('NID','Driving Licence') NOT NULL,
  `physical_id_number` varchar(50) NOT NULL,
  `physical_id_issue_date` date NOT NULL,
  `physical_id_expiry_date` date NOT NULL,
  `gender` enum('male','female','other') NOT NULL,
  `next_of_kin_fullname` varchar(500) NOT NULL,
  `next_of_kin_relationship` varchar(100) NOT NULL,
  `next_of_kin_msisdn` varchar(20) NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `date_updated` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `accounts`
--

INSERT INTO `accounts` (`id`, `account_number`, `pin`, `firstname`, `lastname`, `middlename`, `msisdn`, `password`, `branch_id`, `type`, `status`, `physical_id_type`, `physical_id_number`, `physical_id_issue_date`, `physical_id_expiry_date`, `gender`, `next_of_kin_fullname`, `next_of_kin_relationship`, `next_of_kin_msisdn`, `date_created`, `date_updated`) VALUES
(8, '100001', '', 'Stanislaus', 'Sakwiya', '', '0999160640', '', 1, 'individual', 'active', 'NID', 'RTT868GH', '2024-03-11', '2024-03-11', 'male', 'Stanislaus Sakwiya', 'Brother', '09999160640', '2024-03-11 16:08:03', '2024-03-11 16:43:28'),
(9, '100002', '', 'Orama', 'Chautsi', '', '0990092704', '', 1, 'individual', 'active', 'NID', 'RTT868G7', '2024-03-11', '2024-03-11', 'female', 'Stanislaus Sakwiya', 'Brother', '0999160640', '2024-03-11 16:36:29', NULL),
(10, '100003', '', 'Chitsitsumutso', 'Club', '', '0888800101', '', 1, 'cooperative', 'active', 'NID', '', '2024-03-12', '2024-04-06', 'other', 'Stanislaus Sakwiya', 'Brother', '0999900101', '2024-03-11 17:00:52', NULL),
(11, '100004', '', 'Sample', 'Account', '', '0999160647', '', 3, 'cooperative', 'active', 'NID', 'RTT868GH', '2024-03-20', '2024-03-20', 'other', 'Stanislaus Sakwiya', 'Brother', '0999160640', '2024-03-20 14:17:07', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `agents`
--

CREATE TABLE `agents` (
  `id` int(30) NOT NULL,
  `account_number` varchar(50) NOT NULL,
  `pin` text NOT NULL,
  `firstname` varchar(250) NOT NULL,
  `lastname` varchar(250) NOT NULL,
  `msisdn` varchar(20) NOT NULL,
  `branch_id` int(30) NOT NULL,
  `balance` float NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `agents`
--

INSERT INTO `agents` (`id`, `account_number`, `pin`, `firstname`, `lastname`, `msisdn`, `branch_id`, `balance`) VALUES
(0, '400001', '', 'Stanislaus', 'Sakwiya', '0999160640', 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `agent_balance_offloading`
--

CREATE TABLE `agent_balance_offloading` (
  `id` int(30) NOT NULL,
  `agent_id` int(30) NOT NULL,
  `amount` float NOT NULL,
  `date_offloaded` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `branches`
--

CREATE TABLE `branches` (
  `id` int(30) NOT NULL,
  `branch_name` varchar(100) NOT NULL,
  `location` varchar(255) NOT NULL,
  `district_id` int(30) NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `date_updated` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `branches`
--

INSERT INTO `branches` (`id`, `branch_name`, `location`, `district_id`, `date_created`, `date_updated`) VALUES
(1, 'Tanga', 'Chitsime EPA', 1, '2024-03-11 14:17:55', NULL),
(2, 'Mkangamira ', 'Chitsime EPA', 11, '2024-03-12 08:41:37', NULL),
(3, 'Maunda', 'Chitsime EPA', 11, '2024-03-12 20:00:27', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `charge_fees`
--

CREATE TABLE `charge_fees` (
  `id` int(30) NOT NULL,
  `scheme_id` int(30) NOT NULL,
  `name` varchar(100) NOT NULL,
  `amount` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `charge_fees`
--

INSERT INTO `charge_fees` (`id`, `scheme_id`, `name`, `amount`) VALUES
(2, 1, 'Monthly Fee', 1000),
(3, 2, 'Monthly Fee', 1000);

-- --------------------------------------------------------

--
-- Table structure for table `cooperative_accounts`
--

CREATE TABLE `cooperative_accounts` (
  `id` int(30) NOT NULL,
  `account_id` int(30) NOT NULL,
  `cooperative_name` varchar(100) NOT NULL,
  `club_purpose` text NOT NULL,
  `president` varchar(250) DEFAULT NULL,
  `vice_president` varchar(250) DEFAULT NULL,
  `secretary` varchar(250) DEFAULT NULL,
  `treasurer` varchar(250) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `districts`
--

CREATE TABLE `districts` (
  `id` int(30) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `districts`
--

INSERT INTO `districts` (`id`, `name`) VALUES
(1, 'Balaka'),
(2, 'Blantyre'),
(3, 'Chikwawa'),
(4, 'Chiradzulu'),
(5, 'Chitipa'),
(6, 'Dedza'),
(7, 'Dowa'),
(8, 'Karonga'),
(9, 'Kasungu'),
(10, 'Likoma'),
(11, 'Lilongwe'),
(12, 'Machinga'),
(13, 'Mangochi'),
(14, 'Mchinji'),
(15, 'Mulanje'),
(16, 'Mwanza'),
(17, 'Mzimba'),
(18, 'Nkhata Bay'),
(19, 'Nkhotakota'),
(20, 'Nsanje'),
(21, 'Ntcheu'),
(22, 'Ntchisi'),
(23, 'Phalombe'),
(24, 'Rumphi'),
(25, 'Salima'),
(26, 'Thyolo'),
(27, 'Zomba');

-- --------------------------------------------------------

--
-- Table structure for table `savings`
--

CREATE TABLE `savings` (
  `id` int(30) NOT NULL,
  `account_id` int(30) NOT NULL,
  `scheme_id` int(30) NOT NULL,
  `balance` float NOT NULL,
  `status` enum('active','closed','suspended','completed') NOT NULL DEFAULT 'active',
  `start_date` date NOT NULL,
  `end_date` date DEFAULT NULL,
  `product_given` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `saving_schemes`
--

CREATE TABLE `saving_schemes` (
  `id` int(30) NOT NULL,
  `scheme_name` varchar(100) NOT NULL,
  `target_savings_amount` float NOT NULL,
  `monthly_deductible` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `saving_schemes`
--

INSERT INTO `saving_schemes` (`id`, `scheme_name`, `target_savings_amount`, `monthly_deductible`) VALUES
(1, 'Fertilizer Savings (1001)', 180000, 1),
(2, 'Fertilizer Savings (1002)', 90000, 1);

-- --------------------------------------------------------

--
-- Table structure for table `system_settings`
--

CREATE TABLE `system_settings` (
  `id` int(30) NOT NULL,
  `name` text NOT NULL,
  `email` varchar(200) NOT NULL,
  `contact` varchar(20) NOT NULL,
  `address` text NOT NULL,
  `banner_img` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `system_settings`
--

INSERT INTO `system_settings` (`id`, `name`, `email`, `contact`, `address`, `banner_img`) VALUES
(1, 'Mlimi Digital Wallet', '', '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` int(30) NOT NULL,
  `savings_id` int(30) NOT NULL,
  `type` tinyint(4) NOT NULL COMMENT '1=Cash in, 2=Withdraw, 3=Transfer, 4=Fees',
  `amount` float NOT NULL,
  `remarks` text DEFAULT NULL,
  `agent_id` int(30) DEFAULT NULL,
  `transaction_method` enum('agent','mobile_money') NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(30) NOT NULL,
  `firstname` varchar(200) NOT NULL,
  `lastname` varchar(200) NOT NULL,
  `username` varchar(200) NOT NULL,
  `email` varchar(200) NOT NULL,
  `password` text NOT NULL,
  `type` int(1) NOT NULL DEFAULT 3 COMMENT '1=admin,2=data entry,3=accountant',
  `avatar` text NOT NULL DEFAULT 'no-image-available.png',
  `date_created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `firstname`, `lastname`, `username`, `email`, `password`, `type`, `avatar`, `date_created`) VALUES
(1, 'System', 'Administrator', 'admin', 'admin@admin.com', '0192023a7bbd73250516f069df18b500', 1, '', '2020-11-26 10:57:04');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `agents`
--
ALTER TABLE `agents`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `agent_balance_offloading`
--
ALTER TABLE `agent_balance_offloading`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `branches`
--
ALTER TABLE `branches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `charge_fees`
--
ALTER TABLE `charge_fees`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cooperative_accounts`
--
ALTER TABLE `cooperative_accounts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `districts`
--
ALTER TABLE `districts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `savings`
--
ALTER TABLE `savings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `saving_schemes`
--
ALTER TABLE `saving_schemes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `system_settings`
--
ALTER TABLE `system_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accounts`
--
ALTER TABLE `accounts`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `branches`
--
ALTER TABLE `branches`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `charge_fees`
--
ALTER TABLE `charge_fees`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `districts`
--
ALTER TABLE `districts`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `saving_schemes`
--
ALTER TABLE `saving_schemes`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `system_settings`
--
ALTER TABLE `system_settings`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

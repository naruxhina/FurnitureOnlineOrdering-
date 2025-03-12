-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 09, 2025 at 07:57 PM
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
-- Database: `japan_surplus`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `username`, `email`, `password`, `created_at`) VALUES
(1, 'admin', 'admin', '$2y$10$oNVzThjAwzt9ZK/gmVt0T.wt7p.YVLBMvodGiYbs23XL3MwCYMZO.', '2025-02-05 10:43:06'),
(4, 'wasd', 'wasd@example.com', '$2y$10$0dsk7Saz8ybTfKaIsl7BgeIYuqbDx47Ra3Me2dENmyQT5N1R0v6A2', '2025-02-08 17:37:09');

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `cart_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL CHECK (`quantity` > 0),
  `total_price` decimal(10,2) NOT NULL,
  `date_added` timestamp NOT NULL DEFAULT current_timestamp(),
  `payment_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `category_id` int(11) NOT NULL,
  `category_name` varchar(255) NOT NULL,
  `category_date` date DEFAULT curdate()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`category_id`, `category_name`, `category_date`) VALUES
(2, 'chairs', '2025-02-17'),
(3, 'tables', '2025-02-17'),
(4, 'mesa', '2025-03-09'),
(5, 'kabinet', '2025-03-09'),
(6, 'chairs', '2025-03-09');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `product_name` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL,
  `order_date` date DEFAULT curdate(),
  `status` enum('pending','ready to ship','delivered','cancelled') NOT NULL DEFAULT 'pending',
  `mode_of_payment` varchar(50) NOT NULL,
  `amount_pay` decimal(10,2) NOT NULL,
  `gref` varchar(100) DEFAULT NULL,
  `gnumber` varchar(100) DEFAULT NULL,
  `product_image` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `user_id`, `product_id`, `product_name`, `quantity`, `order_date`, `status`, `mode_of_payment`, `amount_pay`, `gref`, `gnumber`, `product_image`, `address`) VALUES
(1, 3, 24, '', 0, '2025-03-10', 'ready to ship', 'GCash', 234.00, '123123', '123123123', '', ''),
(2, 3, 24, '', 0, '2025-03-10', 'delivered', 'GCash', 123793.00, '123', '123', '', ''),
(3, 3, 24, '', 0, '2025-03-10', 'delivered', 'GCash', 123793.00, '123', '123', '', ''),
(4, 3, 23, '', 0, '2025-03-10', 'cancelled', 'GCash', 123793.00, '123', '123', '', ''),
(5, 3, 22, '', 0, '2025-03-10', '', 'GCash', 123793.00, '123', '123', '', ''),
(6, 3, 21, '', 0, '2025-03-10', '', 'GCash', 123793.00, '123', '123', '', ''),
(7, 3, 24, '234s', 1, '2025-03-10', 'pending', 'GCash', 234.00, '123123', '123123', '../uploads/1741537359_bitcoin.png', ''),
(8, 3, 24, '234s', 1, '2025-03-10', 'pending', 'GCash', 234.00, '123123', '123123', '../uploads/1741537359_bitcoin.png', ''),
(9, 3, 24, '234s', 1, '2025-03-10', 'pending', 'GCash', 234.00, 'asdasd', 'asdasd', '../uploads/1741537359_bitcoin.png', ''),
(10, 3, 24, '234s', 1, '2025-03-10', 'pending', 'GCash', 234.00, 'asdasd', 'asdasd', '../uploads/1741537359_bitcoin.png', ''),
(11, 3, 21, 'sofa2', 1, '2025-03-10', 'pending', 'GCash', 100.00, '123123', '123123', '', 'asd'),
(12, 3, 24, '234s', 1, '2025-03-10', 'pending', 'GCash', 234.00, '123', '123', '../uploads/1741537359_bitcoin.png', 'asdasd');

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

CREATE TABLE `payment` (
  `payment_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `order_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `amount_paid` decimal(10,2) NOT NULL,
  `payment_method` enum('walk-in','online') NOT NULL,
  `payment_status` enum('paid','unpaid') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `product_id` int(11) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `product_name` varchar(255) NOT NULL,
  `product_date` date DEFAULT NULL,
  `price` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `status` enum('available','out of stock') NOT NULL,
  `description` text NOT NULL,
  `product_image` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`product_id`, `category_id`, `product_name`, `product_date`, `price`, `quantity`, `status`, `description`, `product_image`) VALUES
(21, 2, 'sofa2', '2025-03-09', 100, 8, 'available', '123', 'upload/SF 4.2.jpg'),
(22, 2, 'upuan', '2025-03-24', 12, 11, '', 'asdasd', 'uploads/1741531437_bitcoin.png'),
(23, 4, 'asd', '2025-03-09', 123213, 123, 'available', 'asd', ''),
(24, 2, '234s', '2025-03-09', 234, 227, 'available', 'sdf', '../uploads/1741537359_bitcoin.png'),
(25, 3, 'asdasdasd', '2025-03-09', 123, 312, 'available', 'asdasd', '../uploads/1741542676_bh.jpg'),
(26, 2, 'asdadw', '2025-03-09', 123, 123, 'available', 'asdasd', '');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `user_name` varchar(255) NOT NULL,
  `user_email` varchar(255) NOT NULL,
  `user_password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `full_address` varchar(255) NOT NULL,
  `contact_number` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `user_name`, `user_email`, `user_password`, `created_at`, `full_address`, `contact_number`) VALUES
(1, 'jayson', 'jaysonman790@gmail.com', '$2y$10$DtM8R8ZfLOwH53AbI73NCewAtwRZTn9jO0ovxbcEfyRL0CCLa6bPy', '2025-02-04 10:51:20', '', ''),
(2, 'jaysonsasd', 'gagi123123@gmail.com', '$2y$10$hf7t1B1fKja.x4IwaZtzJu/LWTbDBJ4FyvoDsuHBShgU6WY5Kezy.', '2025-02-04 12:46:16', '', ''),
(3, 'jayson123', 'gagi123@gmail.com', '$2y$10$yfqg8nDeMAS5XL/Z8xpZM.z2Mv4W49GCIzn4CeHAgzOdee0IJb51K', '2025-02-05 07:31:15', '', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`cart_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `fk_payment` (`payment_id`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `payment`
--
ALTER TABLE `payment`
  ADD PRIMARY KEY (`payment_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`product_id`),
  ADD UNIQUE KEY `unique_product_name` (`product_name`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `user_email` (`user_email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `cart_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `payment`
--
ALTER TABLE `payment`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `product` (`product_id`),
  ADD CONSTRAINT `fk_payment` FOREIGN KEY (`payment_id`) REFERENCES `payment` (`payment_id`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `product` (`product_id`);

--
-- Constraints for table `payment`
--
ALTER TABLE `payment`
  ADD CONSTRAINT `payment_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `payment_ibfk_2` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`),
  ADD CONSTRAINT `payment_ibfk_3` FOREIGN KEY (`product_id`) REFERENCES `product` (`product_id`);

--
-- Constraints for table `product`
--
ALTER TABLE `product`
  ADD CONSTRAINT `product_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `category` (`category_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

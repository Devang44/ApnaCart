-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 13, 2026 at 05:51 PM
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
-- Database: `apnacart`
--

-- --------------------------------------------------------

--
-- Table structure for table `brands`
--

CREATE TABLE `brands` (
  `brandID` int(11) NOT NULL,
  `brandName` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `cartID` int(11) NOT NULL,
  `pID` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `userID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`cartID`, `pID`, `quantity`, `createdAt`, `userID`) VALUES
(2, 2, 1, '2026-09-13 09:41:57', 2),
(3, 1, 1, '2026-09-13 09:42:04', 2);

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `id` int(11) NOT NULL,
  `category_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`id`, `category_name`) VALUES
(1, 'Accessories'),
(2, 'tech'),
(3, 'grooming');

-- --------------------------------------------------------

--
-- Table structure for table `offers`
--

CREATE TABLE `offers` (
  `offerID` int(11) NOT NULL,
  `title` varchar(150) NOT NULL,
  `description` text NOT NULL,
  `couponCode` varchar(50) NOT NULL,
  `discountType` varchar(20) NOT NULL,
  `discountValue` decimal(10,2) NOT NULL,
  `minCartValue` decimal(10,2) DEFAULT 0.00,
  `targetAudience` varchar(50) DEFAULT 'ALL',
  `badgeText` varchar(50) DEFAULT 'Special Offer',
  `status` varchar(20) DEFAULT 'active',
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `orderID` int(11) NOT NULL,
  `userID` int(11) NOT NULL,
  `fullName` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `address` text NOT NULL,
  `city` varchar(550) NOT NULL,
  `state` varchar(50) NOT NULL,
  `pincode` varchar(20) NOT NULL,
  `totalAmount` decimal(10,2) NOT NULL,
  `paymentMethod` varchar(30) NOT NULL,
  `paymentStatus` varchar(30) DEFAULT 'Pending',
  `orderStatus` varchar(30) DEFAULT 'Placed',
  `orderDate` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`orderID`, `userID`, `fullName`, `phone`, `address`, `city`, `state`, `pincode`, `totalAmount`, `paymentMethod`, `paymentStatus`, `orderStatus`, `orderDate`) VALUES
(1, 1, 'kilji devang', '09727420263', 'rajkot', 'Jasdan', 'Gujarat', '360050', 159500.00, 'COD', 'Pending (COD)', 'Placed', '2026-09-13 14:54:26'),
(2, 1, 'kilji devang', '09727420263', 'rajkot', 'Jasdan', 'Gujarat', '360050', 500.00, 'Razorpay', 'Paid (Razorpay)', 'Placed', '2026-09-13 15:02:48');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `orderID` int(11) NOT NULL,
  `pID` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `orderID`, `pID`, `price`, `quantity`) VALUES
(1, 1, 2, 500.00, 3),
(2, 1, 1, 158000.00, 1),
(3, 2, 2, 500.00, 1);

-- --------------------------------------------------------

--
-- Table structure for table `pincode`
--

CREATE TABLE `pincode` (
  `pinID` int(11) NOT NULL,
  `pincode` varchar(6) NOT NULL,
  `city` varchar(100) NOT NULL,
  `state` varchar(100) NOT NULL,
  `Dstatus` enum('Available','Not_Available') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pincode`
--

INSERT INTO `pincode` (`pinID`, `pincode`, `city`, `state`, `Dstatus`) VALUES
(1, '360050', 'jasdan', 'gujrat', 'Available');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `pID` int(11) NOT NULL,
  `pTitle` varchar(255) NOT NULL,
  `Pdescription` text NOT NULL,
  `Pkeywords` varchar(255) NOT NULL,
  `id` int(11) NOT NULL,
  `catID` int(11) NOT NULL,
  `Pimage1` varchar(255) NOT NULL,
  `Pimage2` varchar(255) NOT NULL,
  `Pimage3` varchar(255) NOT NULL,
  `Pprice` decimal(10,2) NOT NULL,
  `status` enum('active','inactive') NOT NULL,
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `Prating` decimal(2,1) NOT NULL,
  `brand` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`pID`, `pTitle`, `Pdescription`, `Pkeywords`, `id`, `catID`, `Pimage1`, `Pimage2`, `Pimage3`, `Pprice`, `status`, `createdAt`, `Prating`, `brand`) VALUES
(1, 'Apple iPhone 16 Pro Max 256GB Desert Titanium', 'Apple iPhone 16 Pro Max with 6.9-inch Super Retina XDR OLED display, A18 Pro chip, 48MP Fusion Camera System, 5x Telephoto Lens, 4K Dolby Vision recording, Face ID, USB-C, MagSafe charging, IP68 water resistance, and iOS. Available in Desert Titanium finish with 256GB storage.', 'iPhone 16 Pro Max, Apple iPhone, iPhone 16, 256GB, Desert Titanium, A18 Pro, 48MP Camera, 5G Smartphone, iOS, Apple Mobile', 2, 2, 'm-front.png', 'm-back.png', 'm-side.png', 158000.00, 'active', '2026-09-13 15:17:46', 4.5, 'Iphone'),
(2, 'hk', 'vjghjg', 'hgujhfhjf', 1, 1, 'back1.png', 'back1.png', 'back1.png', 500.00, 'active', '2026-08-03 04:07:07', 4.3, '');

-- --------------------------------------------------------

--
-- Table structure for table `subcategory`
--

CREATE TABLE `subcategory` (
  `catID` int(11) NOT NULL,
  `id` int(11) NOT NULL,
  `categoryTitle` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subcategory`
--

INSERT INTO `subcategory` (`catID`, `id`, `categoryTitle`) VALUES
(1, 1, 'Backpacks'),
(2, 2, 'Mobile'),
(3, 3, 'Perfumes');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `userID` int(11) NOT NULL,
  `fullName` varchar(100) NOT NULL,
  `email` varchar(30) NOT NULL,
  `mobile` varchar(10) NOT NULL,
  `password` varchar(255) NOT NULL,
  `gender` enum('Male','Female','Other') NOT NULL DEFAULT 'Male',
  `profileImage` varchar(255) NOT NULL DEFAULT '''default.png',
  `status` enum('Active','Blocked') NOT NULL DEFAULT 'Active',
  `emailVerified` tinyint(1) NOT NULL DEFAULT 0,
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `updatedAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `Ppic` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`userID`, `fullName`, `email`, `mobile`, `password`, `gender`, `profileImage`, `status`, `emailVerified`, `CreatedAt`, `updatedAt`, `Ppic`) VALUES
(1, 'kilji devang', 'mrdevang06@gmail.com', '9727420263', '$2y$10$b1RY/yJX5ygEZyDyb25Qi.A7z1CVAj96rk4rAD4UUWFp62CO5amAK', 'Male', '\'default.png', 'Active', 0, '2026-08-03 15:57:07', '2026-08-03 15:57:07', 'dk.JPG'),
(2, 'sumeet gohel', 'sumeet@gmail.com', '9624732555', '$2y$10$b33ly.rbDEMSi42nJI.vPeKY.3uzMz1G90QJH/LW5AR5yzFMw.w.q', 'Male', '\'default.png', 'Active', 0, '2026-08-04 03:37:01', '2026-08-04 03:37:01', ''),
(3, 'dk kilji', 'mrdevang6@gmail.com', '9727420264', '$2y$10$Ncpegh588edw/4OrTeWdyuujU9clhPz0VsCpwpErniIuFcewl0GXi', 'Male', '\'default.png', 'Active', 0, '2026-08-04 13:26:05', '2026-08-04 13:26:05', '');

-- --------------------------------------------------------

--
-- Table structure for table `wishlist`
--

CREATE TABLE `wishlist` (
  `wishlistID` int(11) NOT NULL,
  `userID` int(11) NOT NULL,
  `pID` int(11) NOT NULL,
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `wishlist`
--

INSERT INTO `wishlist` (`wishlistID`, `userID`, `pID`, `createdAt`) VALUES
(2, 1, 2, '2026-09-13 15:04:37');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `brands`
--
ALTER TABLE `brands`
  ADD PRIMARY KEY (`brandID`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`cartID`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `offers`
--
ALTER TABLE `offers`
  ADD PRIMARY KEY (`offerID`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`orderID`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pincode`
--
ALTER TABLE `pincode`
  ADD PRIMARY KEY (`pinID`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`pID`);

--
-- Indexes for table `subcategory`
--
ALTER TABLE `subcategory`
  ADD PRIMARY KEY (`catID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`userID`),
  ADD UNIQUE KEY `userID` (`userID`),
  ADD UNIQUE KEY `mobile` (`mobile`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `wishlist`
--
ALTER TABLE `wishlist`
  ADD PRIMARY KEY (`wishlistID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `brands`
--
ALTER TABLE `brands`
  MODIFY `brandID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `cartID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `offers`
--
ALTER TABLE `offers`
  MODIFY `offerID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `orderID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `pincode`
--
ALTER TABLE `pincode`
  MODIFY `pinID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `pID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `subcategory`
--
ALTER TABLE `subcategory`
  MODIFY `catID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `userID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `wishlist`
--
ALTER TABLE `wishlist`
  MODIFY `wishlistID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

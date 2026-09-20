-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 20, 2026 at 11:03 AM
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

--
-- Dumping data for table `brands`
--

INSERT INTO `brands` (`brandID`, `brandName`) VALUES
(1, 'lenovo'),
(3, 'samsung'),
(4, 'Sony'),
(5, 'Anker'),
(6, 'Apple'),
(7, 'Dell'),
(8, 'Philips'),
(9, 'Braun');

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
(1, 1, 'kilji devang', '09727420263', 'rajkot', 'Jasdan', 'Gujarat', '360050', 159500.00, 'COD', 'Pending (COD)', 'Cancelled', '2026-09-13 14:54:26'),
(2, 1, 'kilji devang', '09727420263', 'rajkot', 'Jasdan', 'Gujarat', '360050', 500.00, 'Razorpay', 'Paid (Razorpay)', 'Delivered', '2026-09-13 15:02:48'),
(3, 1, 'kilji devang', '09727420263', 'rajkot', 'Jasdan', 'Gujarat', '360050', 500.00, 'Razorpay', 'Paid (Razorpay)', 'Shipped', '2026-09-15 04:56:11'),
(4, 1, 'kilji devang', '09727420263', 'rajkot', 'Jasdan', 'Gujarat', '360050', 500.00, 'Razorpay', 'Paid (Razorpay)', 'Shipped', '2026-09-18 05:11:24'),
(5, 1, 'kilji devang', '09727420263', 'rajkot\r\njasdan', 'Jasdan', 'Gujarat', '360050', 119999.00, 'Razorpay', 'Paid (Razorpay)', 'Placed', '2026-09-19 15:08:47'),
(6, 1, 'kilji devang', '09727420263', 'rajkot\r\njasdan', 'Jasdan', 'Gujarat', '360050', 119999.00, 'COD', 'Pending (COD)', 'Placed', '2026-09-19 15:16:47'),
(7, 1, 'kilji devang', '09727420263', 'rajkot\r\njasdan', 'Jasdan', 'Gujarat', '360050', 119999.00, 'Razorpay', 'Paid (Razorpay)', 'Placed', '2026-09-19 15:21:50'),
(8, 1, 'kilji devang', '09727420263', 'rajkot\r\njasdan', 'Jasdan', 'Gujarat', '360050', 119999.00, 'COD', 'Pending (COD)', 'Placed', '2026-09-19 15:31:57'),
(9, 1, 'kilji devang', '09727420263', 'rajkot\r\njasdan', 'Jasdan', 'Gujarat', '360050', 119999.00, 'Razorpay', 'Paid (Razorpay)', 'Placed', '2026-09-19 15:34:17'),
(10, 1, 'kilji devang', '09727420263', 'rajkot\r\njasdan', 'Jasdan', 'Gujarat', '360050', 500.00, 'COD', 'Pending (COD)', 'Placed', '2026-09-19 15:35:34');

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
(3, 2, 2, 500.00, 1),
(4, 3, 2, 500.00, 1),
(5, 4, 2, 500.00, 1),
(6, 5, 3, 119999.00, 1),
(7, 6, 3, 119999.00, 1),
(8, 7, 3, 119999.00, 1),
(9, 8, 3, 119999.00, 1),
(10, 9, 3, 119999.00, 1),
(11, 10, 2, 500.00, 1);

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
  `costPrice` decimal(10,2) NOT NULL DEFAULT 0.00,
  `stock` int(11) NOT NULL DEFAULT 0,
  `status` enum('active','inactive') NOT NULL,
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `Prating` decimal(2,1) NOT NULL,
  `brand` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`pID`, `pTitle`, `Pdescription`, `Pkeywords`, `id`, `catID`, `Pimage1`, `Pimage2`, `Pimage3`, `Pprice`, `costPrice`, `stock`, `status`, `createdAt`, `Prating`, `brand`) VALUES
(1, 'Apple iPhone 16 Pro Max 256GB Desert Titanium', 'Apple iPhone 16 Pro Max with 6.9-inch Super Retina XDR OLED display, A18 Pro chip, 48MP Fusion Camera System, 5x Telephoto Lens, 4K Dolby Vision recording, Face ID, USB-C, MagSafe charging, IP68 water resistance, and iOS. Available in Desert Titanium finish with 256GB storage.', 'iPhone 16 Pro Max, Apple iPhone, iPhone 16, 256GB, Desert Titanium, A18 Pro, 48MP Camera, 5G Smartphone, iOS, Apple Mobile', 2, 2, 'm-front.png', 'm-back.png', 'm-side.png', 158000.00, 0.00, 0, 'active', '2026-09-20 06:13:19', 4.5, 'Apple'),
(3, 'Samsung Galaxy S25 Ultra 5G (12GB RAM, 256GB Storage, Titanium Gray)', 'Experience next-level mobile innovation with the Samsung Galaxy S25 Ultra 5G. Powered by the cutting-edge Snapdragon 8 Elite octa-core processor and 12GB of RAM, this flagship device delivers lightning-fast performance for multitasking and gaming. Immerse yourself in the stunning 6.9-inch QHD+ Dynamic AMOLED 2X display, protected by Corning Gorilla Armor with a smooth 120Hz refresh rate. Capture professional-grade photos and videos using the advanced 200MP quad-camera setup, complete with space zoom and enhanced low-light capabilities. Built with a sleek and durable titanium frame, it also features a built-in S Pen, a long-lasting 5000mAh battery with 45W fast charging, and Galaxy AI capabilities to elevate your everyday productivity.', 'Samsung Galaxy S25 Ultra, S25 Ultra 5G, Samsung smartphone, Snapdragon 8 Elite phone, 200MP camera mobile, flagship Android phone, 12GB RAM mobile, titanium phone with S Pen, 5000mAh battery smartphone', 1, 2, 'Samsung_S25_Ultra_product_shot_2K_20260918182410.jpeg', 'Samsung_S25_Ultra_product_shot_2K_20260918182402.jpeg', 'Samsung_S25_Ultra_product_shot_2K_20260918182350.jpeg', 119999.00, 0.00, 495, 'active', '2026-09-19 15:34:17', 4.5, 'samsung'),
(4, 'Apple AirPods Pro (2nd Generation) with MagSafe Case', 'Experience rich audio immersion with active noise cancellation, adaptive transparency, and personalized spatial audio. Features up to 30 hours of total listening time with the MagSafe charging case and sweat/water resistance.', 'Apple AirPods Pro, wireless earbuds, noise cancelling earphones, MagSafe charging, Bluetooth audio', 1, 1, 'AirPods_Pro_case_open_2K_20260920113337.jpeg', 'AirPods_Pro_charging_case_standing_2K_20260920113343.jpeg', 'Apple_AirPods_Pro_charging_case_2K_20260920113346.jpeg', 24999.00, 0.00, 700, 'active', '2026-09-20 06:13:33', 4.5, 'Apple'),
(5, 'Sony WH-1000XM5 Wireless Industry Leading Noise Canceling Headphones', 'Industry-leading noise cancellation optimized with two processors and 8 microphones. Enjoy crystal-clear hands-free calling, up to 30 hours of battery life with quick charging, and ultra-comfortable lightweight design.', 'Sony WH-1000XM5, wireless headphones, over-ear headphones, noise canceling, Bluetooth headphones', 1, 1, 'Sony_WH-1000XM5_wireless_headphones_2K_20260920113657.jpeg', 'Sony_wireless_headphones_folded_…_2K_20260920113701.jpeg', 'Sony_headphones_side_profile_view_2K_20260920113707.jpeg', 29990.00, 1000.00, 599, 'active', '2026-09-20 06:08:54', 4.5, 'Sony'),
(6, 'Apple AirTag (4 Pack)', 'Keep track of your keys, wallet, luggage, and more in the Find My app with ultra-wideband precision tracking.', 'Apple AirTag, item tracker, smart locator, Find My tracker', 1, 1, 'Apple_AirTag_retail_packaging_view_2K_20260920114251.jpeg', 'Four_AirTags_stacked_side_profile_2K_20260920114254.jpeg', 'Apple_AirTags_fanned_out_2K_20260920114259.jpeg', 11900.00, 1500.00, 499, 'active', '2026-09-20 06:14:50', 4.5, 'Apple'),
(7, 'Apple MagSafe Wireless Charger Pad (Fast Magnetic Charging for iPhone Series)', 'Charging becomes completely effortless with the Apple MagSafe Wireless Charger. Its perfectly aligned magnets instantly snap onto your iPhone 16, 15, or 14 series devices, delivering up to 15W of fast wireless charging. The compact and lightweight design also makes it exceptionally convenient for travel.', 'Apple MagSafe charger, wireless charging pad, magnetic fast charger, iPhone charging accessory, Qi wireless pad', 1, 1, 'Apple_MagSafe_Wireless_Charger_Pad_2K_20260920115508.jpeg', 'Apple_MagSafe_wireless_charger_pad_2K_20260920115518.jpeg', 'Wireless_charger_slim_profile_view_2K_20260920115527.jpeg', 4500.00, 700.00, 51, 'active', '2026-09-20 06:25:40', 4.5, 'Apple'),
(8, 'Apple 20W USB-C Power Adapter Fast Wall Charger Plug', 'Power up your iPhone, iPad, or any USB-C compatible device quickly and safely. The original Apple 20W USB-C power adapter features a compact wall design that rapidly charges your devices at home, in the office, or on the go while protecting them from overheating.', 'Apple 20W adapter, USB-C wall charger, fast charger block, iPhone plug adapter, original apple charger', 1, 1, 'Apple_power_adapter_wall_charger_2K_20260920115726.jpeg', 'Apple_USB-C_power_adapter_2K_20260920115731.jpeg', 'Apple_USB-C_power_adapter_view_2K_20260920115736.jpeg', 1900.00, 500.00, 56, 'active', '2026-09-20 06:32:51', 5.0, 'Apple'),
(9, 'Apple FineWoven MagSafe Cardholder Wallet (Midnight Black)', 'Crafted from durable microtwill fabric, this Apple FineWoven Wallet delivers a premium look with a soft, suede-like touch. It features strong built-in magnets that securely attach to the back of your iPhone or MagSafe-compatible case, safely holding your credit and debit cards with built-in Find My support', 'Apple FineWoven wallet, MagSafe cardholder, iPhone leather magnetic wallet, card pouch accessory', 1, 1, 'Apple_FineWoven_MagSafe_Cardholder_2K_20260920120513.jpeg', 'Apple_FineWoven_MagSafe_Cardhold…_2K_20260920120520.jpeg', 'Apple_FineWoven_MagSafe_Cardhold…_2K_20260920120523.jpeg', 5900.00, 1199.00, 80, 'active', '2026-09-20 06:37:00', 4.5, 'Apple'),
(10, 'Sony WF-1000XM5 Premium Truly Wireless Hi-Res Earbuds with Built-in Mic', 'Big performance in a small form factor! The Sony WF-1000XM5 wireless earbuds deliver high-resolution audio quality and phenomenal noise isolation. Their ergonomically redesigned comfortable fit and crystal-clear call quality ensure you can enjoy your favorite music and calls without any disturbance.', 'Sony WF-1000XM5, wireless earbuds, hi-res audio earphones, bluetooth buds with mic, noise isolating pods', 1, 1, 'Sony_earbuds_in_charging_case_2K_20260920120926.jpeg', 'Sony_WF-1000XM5_charging_case_st…_2K_20260920120932.jpeg', 'Sony_earbuds_near_charging_case_2K_20260920120936.jpeg', 21990.00, 2150.00, 95, 'active', '2026-09-20 06:39:45', 4.5, 'Sony'),
(11, 'Sony SRS-XB100 Ultra-Portable Waterproof Wireless Bluetooth Speaker', 'Massive bass sound packed in a compact build! The Sony SRS-XB100 portable speaker features an IP67 waterproof and dustproof design, allowing you to enjoy music outdoors, at the beach, or by the pool without worry. It includes a multi-way strap and a long-lasting battery backup.', 'Sony SRS-XB100, portable bluetooth speaker, waterproof mini speaker, wireless outdoor audio box', 1, 1, 'Sony_wireless_Bluetooth_speaker_2K_20260920121126.jpeg', 'Sony_wireless_Bluetooth_speaker_2K_20260920121130.jpeg', 'Sony_wireless_speaker_side_profile_2K_20260920121135.jpeg', 4990.00, 750.00, 200, 'active', '2026-09-20 06:41:50', 4.5, 'Sony'),
(12, 'Sony MDR-ZX110 Lightweight Foldable On-Ear Wired Stereo Headphones', 'Simple, reliable, and budget-friendly, the Sony MDR-ZX110 wired headphones feature 30mm dynamic drivers that deliver balanced sound and deep bass. Their foldable design and soft cushioned ear pads ensure pure comfort even during long hours of use.', 'Sony wired headphones, on-ear stereo headset, lightweight foldable earphones, budget music headset', 1, 1, 'Sony_wired_stereo_headphones_2K_20260920121409.jpeg', 'Sony_MDR-ZX110_Foldable_Wired_He…_2K_20260920121412.jpeg', 'Sony_MDR-ZX110_headphones_side_p…_2K_20260920121416.jpeg', 1290.00, 350.00, 700, 'active', '2026-09-20 06:44:28', 4.5, 'Sony'),
(13, 'Sony GP-VPT2BT Wireless Bluetooth Shooting Grip and Tripod Stand', 'An essential gadget for content creators and vloggers! The Sony GP-VPT2BT wireless shooting grip features built-in Bluetooth remote control buttons that let you snap photos and start video recording without touching your camera. It easily converts into a sturdy mini tripod stand.', 'Sony shooting grip, wireless tripod stand, vlogging accessory for camera, bluetooth remote handle', 1, 1, 'Sony_wireless_shooting_grip_tripod_2K_20260920121612.jpeg', 'Sony_shooting_grip_and_tripod_2K_20260920121616.jpeg', 'Sony_wireless_shooting_grip_depl…_2K_20260920121621.jpeg', 11990.00, 820.00, 740, 'active', '2026-09-20 06:46:35', 4.5, 'Sony'),
(14, 'Anker PowerCore 24,000mAh 140W High-Capacity Portable Laptop Power Bank', 'Never worry about running out of battery again. The Anker PowerCore 24,000mAh power bank features 140W Power Delivery 3.1 technology, capable of quickly charging laptops, MacBooks, tablets, and smartphones simultaneously. It includes a smart digital display to monitor battery status in real-time.', 'Anker power bank, 140W portable laptop charger, fast charging battery pack, 24000mAh power bank, USB-C bank', 1, 1, 'Anker_Power_Bank_Product_View_2K_20260920121826.jpeg', 'Anker_PowerCore_portable_power_bank_2K_20260920121828.jpeg', 'Anker_Power_Bank_side_profile_2K_20260920121833.jpeg', 9999.00, 0.00, 320, 'active', '2026-09-20 06:48:39', 4.5, 'Anker'),
(15, 'Anker Nano 3-Port 65W GaN Fast Wall Charger Adapter', 'Powered by GaN (Gallium Nitride) technology, this ultra-compact wall charger comes with 3 separate ports, allowing you to charge your laptop, phone, and tablet simultaneously at high speeds. It features foldable pins and advanced safety protection systems, making it ideal for travel.', 'Anker 65W charger, GaN fast charger, multi-port USB adapter, compact travel wall plug, fast power brick', 1, 1, 'Anker_wall_charger_adapter_2K_20260920122025.jpeg', 'Anker_wall_charger_adapter_2K_20260920122029.jpeg', 'Anker_65W_wall_charger_adapter_2K_20260920122036.jpeg', 3999.00, 0.00, 666, 'active', '2026-09-20 06:50:44', 4.5, 'Anker'),
(16, 'Anker Soundcore Life Q30 Hybrid Active Noise Cancelling Wireless Headphones', 'Experience an immersive soundstage with multi-mode active noise cancellation and Hi-Res audio drivers on the Anker Soundcore Life Q30. It delivers up to 40 hours of massive battery backup with fast charging support, while soft protein leather earcups ensure an ultra-comfortable fit.', 'Anker Soundcore Q30, wireless ANC headphones, bluetooth headset, long battery headphones, over-ear music gear', 1, 1, 'Anker_wireless_headphones_produc…_2K_20260920122215.jpeg', 'Anker_Soundcore_Life_Q30_headphones_2K_20260920122220.jpeg', 'Anker_headphones_side_profile_view_2K_20260920122224.jpeg', 5999.00, 0.00, 700, 'active', '2026-09-20 06:52:32', 4.5, 'Anker'),
(17, 'Anker PowerLine III USB-C to USB-C Braided Fast Charging Cable (6ft, 100W)', 'Tired of frayed cables? The Anker PowerLine III is an ultra-durable braided cable designed to withstand over 25,000 bends. It fully supports 100W Power Delivery, enabling you to safely charge laptops and smartphones at high speeds while facilitating fast data transfer.', 'Anker USB-C cable, fast charging cord, braided type-C cable, 100W data transfer wire, durable charging cable', 1, 1, 'Anker_USB-C_charging_cable_2K_20260920122405.jpeg', 'Anker_USB-C_cable_coiled_2K_20260920122413.jpeg', 'USB-C_charging_cable_side_view_2K_20260920122415.jpeg', 999.00, 0.00, 300, 'active', '2026-09-20 06:54:24', 4.5, 'Anker'),
(18, 'Anker MagGo Qi2 Certified Magnetic Wireless Power Bank (10,000mAh with Stand)', 'A Qi2-certified wireless power bank featuring a strong magnetic attachment that securely snaps onto your iPhone. It includes a built-in foldable kickstand so you can prop your phone up at vertical or horizontal angles to watch movies or handle video calls while charging.', 'Anker MagGo, magnetic power bank, wireless portable charger, MagSafe battery pack with stand, 10000mAh power bank', 1, 1, 'Anker_MagGo_wireless_power_bank_2K_20260920122610.jpeg', 'Wireless_power_bank_with_stand_2K_20260920122745.jpeg', 'Wireless_power_bank_with_stand_2K_20260920122655.jpeg', 4999.00, 0.00, 300, 'active', '2026-09-20 06:57:51', 4.5, 'Anker'),
(19, 'Samsung Galaxy Tab S9 Ultra 14.6-inch Dynamic AMOLED 2X Display Tablet (Wi-Fi, 12GB RAM, 256GB)', 'Laptop-level power in a tablet form factor! The Samsung Galaxy Tab S9 Ultra features a massive 14.6-inch Dynamic AMOLED 2X screen. It includes a water-resistant S Pen inside the box, quad speakers tuned by AKG, and powerful multi-window processing capabilities tailored for professional design and heavy entertainment.', 'Samsung Galaxy Tab S9 Ultra, Android tablet, large screen tab, S Pen included, premium entertainment tablet', 2, 2, 'Samsung_Galaxy_Tab_S9_Ultra_2K_20260920125710.jpeg', 'Samsung_tablet_back_view_2K_20260920125712.jpeg', 'Samsung_Galaxy_Tab_S9_profile_2K_20260920125719.jpeg', 108999.00, 0.00, 200, 'active', '2026-09-20 07:27:54', 4.5, 'samsung'),
(20, 'Samsung Galaxy Watch 7 Bluetooth Smartwatch (44mm Fitness & Health Tracker)', 'Keep your health goals on track around the clock. The Samsung Galaxy Watch 7 comes equipped with an advanced BioActive sensor, dual-frequency GPS, and energy score tracking insights. Its durable sapphire crystal glass and sleek design make it suitable for both intense workouts and daily office wear.', 'Samsung Galaxy Watch 7, fitness smartwatch, Android smart watch, health tracking band, GPS wearable', 2, 2, 'Samsung_Galaxy_Watch_7_displayed_2K_20260920130119.jpeg', 'Samsung_Galaxy_Watch_7_back_2K_20260920125935.jpeg', 'Samsung_Galaxy_Watch_7_side_2K_20260920125938.jpeg', 29999.00, 0.00, 500, 'active', '2026-09-20 07:31:36', 4.5, 'samsung'),
(21, 'Samsung Odyssey G9 49-inch Curved Ultra-Wide Gaming Monitor (240Hz, 1ms, Mini-LED)', 'The undisputed king of gaming monitors! The Samsung Odyssey G9 is a massive 49-inch dual QHD curved monitor boasting a 240Hz refresh rate and a 1ms response time. It features Quantum Mini-LED technology and an immersive 1000R curvature to deliver hyper-realistic visual depths.\r\nSamsung Odyssey G9, ultra-wide monitor, 49 inch curved screen, 240Hz gaming display, Mini-LED PC monitor', 'Samsung Odyssey G9, ultra-wide monitor, 49 inch curved screen, 240Hz gaming display, Mini-LED PC monitor', 2, 2, 'Samsung_Odyssey_G9_Monitor_Display_2K_20260920130434.jpeg', 'Samsung_Odyssey_G9_Monitor_Back_2K_20260920130436.jpeg', 'Samsung_Odyssey_G9_gaming_monitor_2K_20260920130438.jpeg', 129999.00, 0.00, 300, 'active', '2026-09-20 07:34:54', 4.5, 'samsung'),
(22, 'Samsung T7 Shield 1TB Rugged Portable External Solid State Drive (SSD)', 'Secure your heavy data, 4K video projects, and high-resolution photos with the Samsung T7 Shield SSD. This portable solid-state drive delivers lightning-fast transfer speeds of up to 1050 MB/s. Its rugged rubber exterior offers IP65 water and dust resistance along with drop protection up to 3 meters.\r\n Samsung T7 Shield, portable SSD, external hard drive, rugged storage drive, 1TB fast SSD storage', 'Samsung T7 Shield, portable SSD, external hard drive, rugged storage drive, 1TB fast SSD storage', 2, 2, 'Samsung_T7_Shield_SSD_displayed_2K_20260920130640.jpeg', 'Samsung_T7_Shield_portable_SSD_2K_20260920130644.jpeg', 'Samsung_T7_Shield_SSD_displayed_2K_20260920130646.jpeg', 8999.00, 0.00, 450, 'active', '2026-09-20 07:36:58', 4.5, 'samsung'),
(23, 'Apple Studio Display 27-inch 5K Retina Desktop Monitor with Camera & Speakers', 'The perfect companion screen for any Mac desktop setup. The Apple Studio Display is a gorgeous 27-inch 5K Retina screen offering 600 nits of brightness and P3 wide color. It integrates a 12MP Ultra Wide camera with Center Stage, a studio-quality 3-mic array, and a robust 6-speaker sound system.', 'Apple Studio Display, 5K monitor, Mac desktop screen, Apple computer display, high resolution monitor', 2, 2, 'Apple_Studio_Display_27-inch_mon…_2K_20260920130907.jpeg', 'Apple_Studio_Display_back_view_2K_20260920130911.jpeg', 'Apple_Studio_Display_side_profile_2K_20260920130913.jpeg', 159900.00, 0.00, 200, 'active', '2026-09-20 07:39:25', 4.5, 'Apple'),
(24, 'Apple Mac mini Desktop Computer (M4 Chip, 16GB Unified Memory, 256GB SSD)', 'Incredible power packed inside a remarkably small footprint. The Apple Mac mini has been completely redesigned with the M4 chip, offering extraordinary processing speeds, extensive connectivity ports, and a silent thermal architecture ideal for office or home setups.', 'Apple Mac mini, M4 desktop computer, compact PC, small form factor Mac, mini workstation CPU', 2, 2, 'Apple_Mac_mini_desktop_computer_2K_20260920131219.jpeg', 'Apple_Mac_mini_back_view_2K_20260920131222.jpeg', 'Apple_Mac_mini_desktop_computer_2K_20260920131227.jpeg', 59900.00, 0.00, 400, 'active', '2026-09-20 07:43:37', 4.5, 'Apple'),
(25, 'Apple Magic Keyboard with Touch ID and Numeric Keypad (Wireless Mac Accessory)', 'Enhance your typing experience on Mac computers. The Apple Magic Keyboard features an extended layout with a numeric keypad and full-size arrow keys. It includes integrated Touch ID, enabling secure user logins and fast online checkouts with a single touch.', 'Apple Magic Keyboard, wireless keyboard with Touch ID, Mac keyboard, numeric keypad, bluetooth typing accessory', 2, 2, 'Apple_Magic_Keyboard_shown_2K_20260920131522.jpeg', 'Apple_Magic_Keyboard_layout_2K_20260920131526.jpeg', 'Apple_Magic_Keyboard_side_profile_2K_20260920131532.jpeg', 14900.00, 0.00, 600, 'active', '2026-09-20 07:46:20', 4.5, 'Apple'),
(26, 'Dell XPS 14 OLED Premium Windows Laptop (Intel Core Ultra 7, 16GB RAM, 1TB SSD)', 'A seamless blend of elegance and elite performance. The Dell XPS 14 is a premium ultrabook featuring a 3.2K OLED touch display, a CNC-machined aluminum chassis, and dedicated AI acceleration via Intel Core Ultra processors. It serves as a top-tier choice for creators and business executives.', 'Dell XPS 14, OLED laptop, Intel Core Ultra 7, Windows ultrabook, premium business laptop, slim notebook', 2, 2, 'Dell_XPS_14_laptop_view_2K_20260920132237.jpeg', 'Dell_laptop_closed_lid_2K_20260920132240.jpeg', 'Dell_XPS_14_laptop_profile_2K_20260920132247.jpeg', 164990.00, 0.00, 420, 'active', '2026-09-20 07:53:45', 4.5, 'Dell'),
(27, 'Dell Alienware m16 R2 Gaming Laptop (Intel Core Ultra 7, NVIDIA RTX 4070, 16GB RAM)', 'A high-octane machine built for hardcore gamers. The Dell Alienware m16 R2 packs a 240Hz QHD+ high-refresh-rate display, robust NVIDIA RTX 4070 graphics, and Element 31 thermal interface material to keep temperatures low during heavy gaming marathons.', 'Dell Alienware m16, RTX 4070 gaming laptop, high-end gaming PC, Windows gaming notebook, high refresh rate laptop', 2, 2, 'Dell_Alienware_m16_gaming_laptop_2K_20260920132626.jpeg', 'Dell_Alienware_m16_laptop_closed_2K_20260920132629.jpeg', 'Dell_Alienware_m16_R2_Laptop_2K_20260920132634.jpeg', 179990.00, 0.00, 400, 'active', '2026-09-20 07:56:49', 4.5, 'Dell'),
(28, 'Dell UltraSharp 27-inch 4K USB-C Hub Professional Monitor (U2724DE)', 'The ultimate monitor for office productivity, coding, and design. The Dell UltraSharp 27 4K monitor utilizes IPS Black technology to deliver a 2000:1 contrast ratio and deeper blacks. It includes a 120Hz refresh rate and up to 90W power delivery through a single USB-C cable connection.', 'Dell UltraSharp monitor, 4K USB-C hub screen, professional office monitor, IPS Black display, computer screen', 2, 2, 'Dell_UltraSharp_4K_Monitor_2K_20260920132904.jpeg', 'Dell_UltraSharp_monitor_back_view_2K_20260920132906.jpeg', 'Dell_monitor_side_profile_view_2K_20260920132908.jpeg', 49990.00, 0.00, 400, 'active', '2026-09-20 07:59:29', 4.5, 'Dell'),
(29, 'Dell Inspiron 15 Everyday Productivity Laptop (Intel Core i5, 16GB RAM, 512GB SSD)', 'A reliable and budget-friendly notebook designed for students and working professionals. The Dell Inspiron 15 features a 15.6-inch FHD display, a comfortable lift-hinge design that elevates the keyboard for better typing posture, and fast-charging battery technology for all-day use.', 'Dell Inspiron 15, student laptop, budget Windows notebook, everyday laptop computer, office computer', 2, 2, 'Dell_Inspiron_laptop_product_pho…_2K_20260920133359.jpeg', 'Dell_laptop_closed_lid_2K_20260920133403.jpeg', 'Dell_Inspiron_15_side_profile_2K_20260920133408.jpeg', 48990.00, 0.00, 320, 'active', '2026-09-20 08:04:40', 4.5, 'Dell'),
(30, 'Dell Pro Wireless Keyboard and Mouse Combo (KM5221W Silent Desktop Set)', 'Clean up your workspace and eliminate cable clutter. The Dell Pro wireless keyboard and mouse combo features quiet keys and a sculpted ergonomic mouse with secure 2.4GHz wireless connectivity. It offers an impressive battery life of up to 36 months for low-maintenance reliability.', 'Dell wireless keyboard and mouse, office desktop accessory, silent computer keyboard, ergonomic mouse combo', 2, 2, 'Dell_wireless_keyboard_and_mouse_2K_20260920134156.jpeg', 'Dell_keyboard_and_mouse_combo_2K_20260920134202.jpeg', 'Dell_wireless_keyboard_and_mouse_2K_20260920134205.jpeg', 2499.00, 0.00, 350, 'active', '2026-09-20 08:12:22', 4.5, 'Dell'),
(31, 'Philips BT3231/15 Cordless Beard Trimmer with Lift & Trim System and Fast Charging', 'Keep your beard looking clean and sharp. The Philips BT3231 trimmer features skin-friendly, self-sharpening stainless steel blades and a Lift & Trim system that lifts hair up for an even, one-stroke trim. It comes with 20 lock-in length settings and provides 60 minutes of cordless runtime.', 'Philips beard trimmer, cordless hair trimmer, men grooming kit, facial hair trimmer, rechargeable clipper', 3, 3, 'Philips_beard_trimmer_product_view_2K_20260920134450.jpeg', 'Beard_trimmer_with_accessories_2K_20260920134454.jpeg', 'Philips_beard_trimmer_side_profile_2K_20260920134456.jpeg', 1995.00, 0.00, 50, 'active', '2026-09-20 08:15:08', 4.5, 'Philips'),
(32, 'Philips Multigroom Series 7000 14-in-1 Waterproof Face, Hair and Body Grooming Kit', 'Handle all your head-to-toe grooming needs with a single kit. The Philips Multigroom 7000 features DualCut technology and steel blades that never dull. It includes 14 versatile attachments for face, hair, nose, and body grooming, backed by a 100% waterproof design.', 'Philips Multigroom 7000, body groomer, all-in-one trimmer, facial hair clipper, waterproof grooming set', 3, 3, 'Philips_grooming_kit_displayed_2K_20260920134635.jpeg', 'Philips_grooming_kit_with_attach…_2K_20260920134642.jpeg', 'Philips_Multigroom_trimming_hand…_2K_20260920134642.jpeg', 4495.00, 0.00, 200, 'active', '2026-09-20 08:16:56', 4.5, 'Philips'),
(33, 'Philips Advanced Men’s Daily Face Wash and Hydrating Cleanser (100ml)', 'Deep-cleansing daily face wash formulated to remove excess oil, dirt, and pollution from pores while keeping natural skin moisture locked in for a fresh look.', 'Philips face wash, men daily cleanser, oil control face wash, skin purifier, facial wash', 3, 3, 'Philips_face_wash_tube_displayed_2K_20260920135002.jpeg', 'Philips_face_wash_tube_on_2K_20260920135003.jpeg', 'Philips_face_wash_tube_displayed_2K_20260920135008.jpeg', 399.00, 0.00, 500, 'active', '2026-09-20 08:20:21', 4.5, 'Philips'),
(34, 'Philips Refreshing Vitamin C Brightening Face Serum for Men (30ml)', 'Energizing Vitamin C face serum designed to reduce dullness, fight dark spots, and boost natural collagen synthesis for a brighter, healthier complexion.', 'Philips Vitamin C serum, face brightening serum, men skincare, antioxidant serum, dark spot corrector', 3, 3, 'Philips_Vitamin_C_face_serum_2K_20260920135412.jpeg', 'Philips_Vitamin_C_Face_Serum_2K_20260920135415.jpeg', 'Philips_face_serum_bottle_shown_2K_20260920135420.jpeg', 799.00, 0.00, 700, 'active', '2026-09-20 08:24:32', 4.5, 'Philips'),
(35, 'Braun Series 9 Pro+ Premium Electric Shaver for Men with Cleaning Station', 'High-end electric shaver featuring 5 specialized shaving elements, a surgical-grade ProTrimmer, and AutoSense technology to cut dense hair effortlessly with an automated cleaning dock.\r\nBraun Series 9 Pro+, electric shaver, foil razor for men, luxury grooming device, wet dry shaver', 'Braun Series 9 Pro+, electric shaver, foil razor for men, luxury grooming device, wet dry shaver', 3, 3, 'Electric_shaver_product_display_2K_20260920135652.jpeg', 'Electric_shaver_in_cleaning_station_2K_20260920135653.jpeg', 'Braun_electric_shaver_side_profile_2K_20260920135700.jpeg', 27999.00, 0.00, 50, 'active', '2026-09-20 08:27:13', 4.5, 'Braun'),
(36, 'Braun Beard Trimmer Series 7 Precision Facial Hair Styler', 'Sculpt your facial hair with professional precision. Features a ProBlade, AutoSense motor, 40 precision length settings, and a lithium-ion battery delivering 100 minutes of runtime.', 'Braun beard trimmer Series 7, precise hair clipper, professional beard styler, cordless trimmer', 3, 3, 'Braun_beard_trimmer_product_view_2K_20260920135834.jpeg', 'Braun_beard_trimmer_with_attachm…_2K_20260920135837.jpeg', 'Braun_beard_trimmer_product_view_2K_20260920135840.jpeg', 5999.00, 0.00, 30, 'active', '2026-09-20 08:28:49', 4.5, 'Braun'),
(37, 'Braun Daily Energizing Face Scrub and Exfoliator for Men (150ml)', 'Deep exfoliating scrub formulated to clear out dead skin cells, unclog pores, and lift ingrown hairs before shaving for a smoother, cleaner razor glide.', 'Braun face scrub, exfoliating cleanser, ingrown hair treatment, men skin care, deep clean wash', 3, 3, 'Braun_face_scrub_tube_packaging_2K_20260920140101.jpeg', 'Braun_Face_Scrub_Tube_2K_20260920140105.jpeg', 'Braun_Daily_Energizing_Face_Scrub_2K_20260920140108.jpeg', 499.00, 0.00, 1000, 'active', '2026-09-20 08:31:22', 4.5, 'Braun'),
(38, 'Braun Anti-Aging Hyaluronic Acid and Vitamin C Face Serum (50ml)', ': Advanced face serum infused with Vitamin C and hyaluronic acid to intensely hydrate dry skin, smooth out fine lines, and revitalize tired facial features.', 'Braun face serum, hyaluronic acid, Vitamin C serum for men, anti-aging skin treatment, facial moisturizer', 3, 3, 'Face_serum_in_dropper_bottle_2K_20260920140352.jpeg', 'Face_serum_bottle_on_marble_2K_20260920140355.jpeg', 'Face_serum_in_glass_bottle_2K_20260920140358.jpeg', 899.00, 0.00, 600, 'active', '2026-09-20 08:34:10', 4.5, 'Braun'),
(39, 'Braun Intensive Beard Softening and Conditioning Serum (40ml)', 'Lightweight conditioning serum designed to tame unruly beard hairs, relieve dry skin underneath, and provide a soft, healthy sheen.', 'Braun beard serum, beard softener, facial hair conditioner, grooming oil treatment, non-greasy beard care', 3, 3, 'Beard_softening_serum_bottle_2K_20260920140532.jpeg', 'Beard_softening_and_conditioning…_2K_20260920140542.jpeg', 'Beard_serum_bottle_on_wood_2K_20260920140544.jpeg', 699.00, 0.00, 200, 'active', '2026-09-20 08:35:54', 4.5, 'Braun'),
(40, 'Philips Invigorating Fresh Musk Body Spray and Deodorant for Men (150ml)', 'A long-lasting body spray featuring a crisp musk fragrance that neutralizes body odor and keeps you feeling confident and fresh throughout the day.', 'Philips body spray, deodorant for men, musk fragrance, long lasting freshness, body mist', 3, 3, 'Philips_body_spray_aerosol_can_2K_20260920141252.jpeg', 'Philips_Body_Spray_Product_2K_20260920141257.jpeg', 'Philips_body_spray_aerosol_can_2K_20260920141259.jpeg', 350.00, 0.00, 100, 'active', '2026-09-20 08:43:07', 4.5, 'Philips'),
(41, 'Philips Daily Hydrating Face and Body Moisturizing Lotion (200ml)', 'A lightweight, non-greasy moisturizing lotion suitable for both face and body, designed to soothe dry skin and lock in all-day hydration.', 'Philips body lotion, face moisturizer, hydrating skin cream, daily body care, non-sticky lotion', 3, 3, 'Philips_moisturizing_lotion_bottle_2K_20260920141435.jpeg', 'Philips_moisturizing_lotion_bott…_2K_20260920141437.jpeg', 'Philips_moisturizing_lotion_pump…_2K_20260920141444.jpeg', 450.00, 0.00, 300, 'active', '2026-09-20 08:44:51', 4.5, 'Philips'),
(42, 'Philips Purifying Charcoal Peel-Off Face Mask (100g)', 'A detoxifying peel-off mask infused with activated charcoal to effectively draw out stubborn blackheads, impurities, and excess oil from deep within pores.', 'Philips charcoal mask, peel-off face mask, blackhead remover, skin detox, men face care', 3, 3, 'Philips_Purifying_Charcoal_Face_…_2K_20260920142520.jpeg', 'Philips_charcoal_face_mask_tube_2K_20260920142523.jpeg', 'Philips_charcoal_face_mask_tube_2K_20260920142528.jpeg', 499.00, 0.00, 250, 'active', '2026-09-20 08:55:39', 4.5, 'Philips'),
(43, 'Philips Luxury Woody Men\'s Body Mist and Cologne (100ml)', 'A refined daily body mist featuring deep woody and amber undertones that provide an instant burst of long-lasting fragrance after a shower.', 'Philips body mist, men cologne, woody fragrance, luxury body spray, daily scent', 3, 3, 'Philips_men_body_mist_bottle_2K_20260920143034.jpeg', 'Philips_Woody_Body_Mist_Bottle_2K_20260920143038.jpeg', 'Philips_Men_Body_Mist_Cologne_2K_20260920143039.jpeg', 699.00, 0.00, 250, 'active', '2026-09-20 09:00:50', 4.5, 'Philips');

-- --------------------------------------------------------

--
-- Table structure for table `product_reviews`
--

CREATE TABLE `product_reviews` (
  `reviewID` int(11) NOT NULL,
  `productID` int(11) NOT NULL,
  `userID` int(11) NOT NULL,
  `rating` decimal(2,1) NOT NULL,
  `review` text NOT NULL,
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_reviews`
--

INSERT INTO `product_reviews` (`reviewID`, `productID`, `userID`, `rating`, `review`, `createdAt`) VALUES
(1, 8, 1, 5.0, 'this product are to help full', '2026-09-20 06:32:51');

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
-- Indexes for table `product_reviews`
--
ALTER TABLE `product_reviews`
  ADD PRIMARY KEY (`reviewID`),
  ADD KEY `idx_product` (`productID`),
  ADD KEY `idx_user` (`userID`);

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
  MODIFY `brandID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `cartID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `offers`
--
ALTER TABLE `offers`
  MODIFY `offerID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `orderID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `pincode`
--
ALTER TABLE `pincode`
  MODIFY `pinID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `pID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT for table `product_reviews`
--
ALTER TABLE `product_reviews`
  MODIFY `reviewID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

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
  MODIFY `wishlistID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

CREATE TABLE IF NOT EXISTS `admins` (
  `adminID` int(11) NOT NULL AUTO_INCREMENT,
  `adminName` varchar(100) NOT NULL,
  `email` varchar(190) NOT NULL,
  `password` varchar(255) NOT NULL,
  `status` enum('Active','Blocked') NOT NULL DEFAULT 'Active',
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`adminID`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT IGNORE INTO `admins` (`adminName`, `email`, `password`, `status`) VALUES
('ApnaCart Admin', 'admin@apnacart.com', '$2y$10$.QvYyM2v/c21NvMHBzvbFOsMQXMaHqtRfBwbzfakRamNM0Qetf736', 'Active');
-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Nov 03, 2023 at 08:20 PM
-- Server version: 8.0.31
-- PHP Version: 8.0.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `website_auction`
--

-- --------------------------------------------------------

--
-- Table structure for table `auctions`
--

DROP TABLE IF EXISTS `auctions`;
CREATE TABLE IF NOT EXISTS `auctions` (
  `auctionID` int NOT NULL AUTO_INCREMENT,
  `auctionStartDate` datetime NOT NULL,
  `auctionEndDate` datetime NOT NULL,
  `auctionStartingPrice` double(10,2) NOT NULL,
  `auctionCurrentHighestBid` double(10,2) DEFAULT NULL,
  `auctionReservePrice` double(10,2) NOT NULL,
  `auctionName` varchar(100) NOT NULL,
  `auctionDescription` varchar(500) DEFAULT NULL,
  `auctionBidCount` int DEFAULT NULL,
  `auctionStatus` varchar(100) NOT NULL,
  `auctionPicture` varchar(100) DEFAULT NULL,
  `sellerID` int DEFAULT NULL,
  `categoryType` varchar(100) NOT NULL,
  `categoryColor` varchar(100) NOT NULL,
  `categoryGender` varchar(100) NOT NULL,
  `categorySize` varchar(100) NOT NULL,
  PRIMARY KEY (`auctionID`),
  KEY `sellerID` (`sellerID`),
  KEY `categoryType` (`categoryType`),
  KEY `categoryColor` (`categoryColor`),
  KEY `categoryGender` (`categoryGender`),
  KEY `categorySize` (`categorySize`)
) ;

--
-- Dumping data for table `auctions`
--

INSERT INTO `auctions` (`auctionID`, `auctionStartDate`, `auctionEndDate`, `auctionStartingPrice`, `auctionCurrentHighestBid`, `auctionReservePrice`, `auctionName`, `auctionDescription`, `auctionBidCount`, `auctionStatus`, `auctionPicture`, `sellerID`, `categoryType`, `categoryColor`, `categoryGender`, `categorySize`) VALUES
(1, '2023-11-02 08:30:00', '2023-11-04 20:00:00', 20.50, NULL, 45.00, 'Amazing VIVAIA flats', 'Green flats for females, size 38 European, brand new worn once', NULL, 'Running', '/auction/images/Green female shoes.png', 2, 'Shoes', 'Green', 'Female', 'N/A'),
(2, '2023-11-01 08:30:00', '2023-11-04 20:00:00', 5.50, NULL, 10.00, 'Stylish black T-Shitrt for men', 'Black T-shirt with red rose decoration, brand new never worn', NULL, 'Running', '/auction/images/plainblackTshirt_male.png', 4, 'T-shirts', 'Black', 'Male', 'L'),
(3, '2023-11-01 08:30:00', '2023-11-07 20:00:00', 50.00, NULL, 70.00, 'Pier One jeans', 'Male light denim jeans, in good condition', NULL, 'Running', '/auction/images/jeans_male.png', 4, 'Trousers/Jeans', 'Blue', 'Male', 'L'),
(4, '2023-11-01 08:30:00', '2023-11-08 20:00:00', 100.00, NULL, 150.00, 'PERFECT KAREN MILLEN DRESS', 'very stylish designer dress, worn only on one occasion and has been to dry cleaning', NULL, 'Running', '/auction/images/bluedress.png', 2, 'Dresses/Skirts', 'Blue', 'Female', 'M'),
(5, '2023-11-01 08:30:00', '2023-11-06 20:00:00', 50.00, NULL, 70.00, 'Summer colorful skirt', 'skirt perfect for the summer, never worn', NULL, 'Running', '/auction/images/colorfulldress.png', 2, 'Dresses/Skirts', 'Multi-coloured', 'Female', 'M'),
(6, '2023-11-02 08:30:00', '2023-11-04 20:00:00', 20.50, NULL, 45.00, 'Ultimate deal for a VALENTINO BAG', 'Amazing Valentino bag in very good condition', NULL, 'Running', '/auction/images/accessories_valentino_bag.png', 2, 'Accessories', 'Black', 'Female', 'N/A'),
(7, '2023-11-03 08:30:00', '2023-11-05 20:00:00', 10.00, NULL, 25.00, 'Elegant Unisex Scarf', 'Stylish unisex scarf in cheerful colors, perfect for any season', NULL, 'Running', '/auction/images/accessories_unisex_scarf.jpg', 3, 'Accessories', 'Multi-coloured', 'Unisex', 'N/A'),
(8, '2023-11-04 08:30:00', '2023-11-06 20:00:00', 15.00, NULL, 30.00, 'Elegant White Blouse for Women', 'White blouse for females, size Medium, elegant design, never worn', NULL, 'Closed', '/auction/images/whiteblousefemale.jpg', 3, 'Blouses/Shirts', 'White', 'Female', 'M'),
(9, '2023-11-05 08:30:00', '2023-11-07 20:00:00', 18.50, NULL, 35.00, 'Stylish Blue Blouse for Women', 'Blue blouse for females, size Small, elegant design, like new', NULL, 'Closed', '/auction/images/blueblousefemale.jpg', 3, 'Blouses/Shirts', 'Blue', 'Female', 'S'),
(10, '2023-11-06 08:30:00', '2023-11-08 20:00:00', 12.75, NULL, 25.00, 'Elegant Black Skirt for Women', 'Black skirt for females, size Large, perfect for formal occasions', NULL, 'Closed', '/auction/images/blackskirtfemale.jpg', 3, 'Dresses/Skirts', 'Black', 'Female', 'L'),
(11, '2023-11-07 08:30:00', '2023-11-09 20:00:00', 22.99, NULL, 40.00, 'Classic Black Female Shoes', 'Black shoes for females, size 38 European, comfortable and stylish', NULL, 'Closed', '/auction/images/blackshoesfemale.jpg', 2, 'Shoes', 'Black', 'Female', 'N/A'),
(12, '2023-11-08 08:30:00', '2023-11-10 20:00:00', 30.00, NULL, 60.00, 'Elegant Black Dress for Women', 'Black dress for females, size Medium, perfect for special occasions', NULL, 'Closed', '/auction/images/blackdressfemale.jpg', 2, 'Dresses/Skirts', 'Black', 'Female', 'M'),
(13, '2023-11-09 08:30:00', '2023-11-11 20:00:00', 40.00, NULL, 80.00, 'Stylish Red Coat for Women', 'Red coat for females, size Large, excellent condition, perfect for winter', NULL, 'Closed', '/auction/images/redcoatfemale.jpg', 3, 'Jackets/Coats', 'Red', 'Female', 'L');

-- --------------------------------------------------------

--
-- Table structure for table `bids`
--

DROP TABLE IF EXISTS `bids`;
CREATE TABLE IF NOT EXISTS `bids` (
  `bidID` int NOT NULL AUTO_INCREMENT,
  `dateBid` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `bidValue` decimal(10,2) NOT NULL,
  `bidStatus` varchar(100) NOT NULL DEFAULT 'Running',
  `buyerID` int NOT NULL,
  `auctionID` int NOT NULL,
  PRIMARY KEY (`bidID`),
  KEY `buyerID` (`buyerID`),
  KEY `auctionID` (`auctionID`)
) ;

--
-- Dumping data for table `bids`
--

INSERT INTO `bids` (`bidID`, `dateBid`, `bidValue`, `bidStatus`, `buyerID`, `auctionID`) VALUES
(1, '2023-11-03 20:19:39', '5.50', 'Running', 6, 2),
(2, '2023-11-03 20:19:39', '7.00', 'Running', 7, 2),
(3, '2023-11-03 20:19:39', '9.00', 'Running', 6, 2),
(4, '2023-11-03 20:19:39', '10.50', 'Running', 7, 2),
(5, '2023-11-03 20:19:39', '50.00', 'Running', 7, 3),
(6, '2023-11-03 20:19:39', '55.00', 'Running', 6, 2),
(7, '2023-11-03 20:19:39', '65.00', 'Running', 7, 2),
(8, '2023-11-03 20:19:39', '69.00', 'Running', 6, 2),
(9, '2023-11-03 20:19:39', '100.00', 'Running', 5, 4),
(10, '2023-11-03 20:19:39', '50.00', 'Running', 8, 5);

-- --------------------------------------------------------

--
-- Table structure for table `categoryclothstype`
--

DROP TABLE IF EXISTS `categoryclothstype`;
CREATE TABLE IF NOT EXISTS `categoryclothstype` (
  `categoryType` varchar(100) NOT NULL,
  PRIMARY KEY (`categoryType`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `categoryclothstype`
--

INSERT INTO `categoryclothstype` (`categoryType`) VALUES
('Accessories'),
('Bags'),
('Blouses/Shirts'),
('Dresses/Skirts'),
('Jackets/Coats'),
('Shoes'),
('Skirts'),
('T-shirts'),
('Trousers/Jeans');

-- --------------------------------------------------------

--
-- Table structure for table `categorycolortype`
--

DROP TABLE IF EXISTS `categorycolortype`;
CREATE TABLE IF NOT EXISTS `categorycolortype` (
  `categoryColor` varchar(100) NOT NULL,
  PRIMARY KEY (`categoryColor`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `categorycolortype`
--

INSERT INTO `categorycolortype` (`categoryColor`) VALUES
('Black'),
('Blue'),
('Brown'),
('Green'),
('Grey'),
('Multi-coloured'),
('Orange'),
('Pink'),
('Red'),
('White'),
('Yellow');

-- --------------------------------------------------------

--
-- Table structure for table `categorygendertype`
--

DROP TABLE IF EXISTS `categorygendertype`;
CREATE TABLE IF NOT EXISTS `categorygendertype` (
  `categoryGender` varchar(100) NOT NULL,
  PRIMARY KEY (`categoryGender`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `categorygendertype`
--

INSERT INTO `categorygendertype` (`categoryGender`) VALUES
('Female'),
('Male'),
('Unisex');

-- --------------------------------------------------------

--
-- Table structure for table `categorysizetype`
--

DROP TABLE IF EXISTS `categorysizetype`;
CREATE TABLE IF NOT EXISTS `categorysizetype` (
  `categorySize` varchar(100) NOT NULL,
  PRIMARY KEY (`categorySize`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `categorysizetype`
--

INSERT INTO `categorysizetype` (`categorySize`) VALUES
('L'),
('M'),
('N/A'),
('S'),
('XL'),
('XS'),
('XXL');

-- --------------------------------------------------------

--
-- Table structure for table `ratings`
--

DROP TABLE IF EXISTS `ratings`;
CREATE TABLE IF NOT EXISTS `ratings` (
  `auctionID` int NOT NULL,
  `ratingValue` int NOT NULL,
  `ratingText` varchar(500) DEFAULT NULL,
  `buyerID` int DEFAULT NULL,
  PRIMARY KEY (`auctionID`),
  KEY `buyerID` (`buyerID`)
) ;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `userID` int NOT NULL AUTO_INCREMENT,
  `userEmail` varchar(100) NOT NULL,
  `username` varchar(100) NOT NULL,
  `userPassword` varchar(42) NOT NULL,
  `userFirstName` varchar(100) DEFAULT NULL,
  `userLastName` varchar(100) DEFAULT NULL,
  `userAddress` varchar(100) DEFAULT NULL,
  `userTel` varchar(100) DEFAULT NULL,
  `userGender` varchar(100) DEFAULT NULL,
  `userAdminRights` tinyint(1) NOT NULL DEFAULT '0',
  `userBuyerRights` tinyint(1) NOT NULL DEFAULT '0',
  `userSellerRights` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`userID`),
  UNIQUE KEY `userEmail` (`userEmail`),
  UNIQUE KEY `username` (`username`)
) ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`userID`, `userEmail`, `username`, `userPassword`, `userFirstName`, `userLastName`, `userAddress`, `userTel`, `userGender`, `userAdminRights`, `userBuyerRights`, `userSellerRights`) VALUES
(1, 'gbakova@yahoo.com', 'admin', '6216f8a75fd5bb3d5f22b6f9958cdede3fc086c2', 'Gabriela', 'Bakova', 'Discovery Dock East, London E14 9RZ UK', '790000000', 'Female', 1, 0, 0),
(2, 'gabriela.bkva@gmail.com', 'user2', '6216f8a75fd5bb3d5f22b6f9958cdede3fc086c2', 'Gabi', 'Bakova', 'Imaginary Land, London E14 9RZ UK', '792200000', 'Female', 0, 0, 0),
(3, 'annecat.xhonneux@gmail.com', 'user3', '6216f8a75fd5bb3d5f22b6f9958cdede3fc086c2', 'Cath', 'Xhonneux', 'Imaginary Land, London E14 9RZ UK', '792200000', 'Female', 0, 0, 0),
(4, 'gianpremr@gmail.com', 'user4', '6216f8a75fd5bb3d5f22b6f9958cdede3fc086c2', 'Gian', 'Prem', 'Imaginary Land, London E14 9RZ UK', '792200000', 'Male', 0, 0, 0),
(5, 'mnamyslowska1@gmail.com', 'user5', '6216f8a75fd5bb3d5f22b6f9958cdede3fc086c2', 'Maja', 'Namyslowska', 'Imaginary Land, London E14 9RZ UK', '792200000', 'Female', 0, 0, 0),
(6, 'sample@user.com', 'user6', '6216f8a75fd5bb3d5f22b6f9958cdede3fc086c2', 'Max', 'Mustermann', 'Imaginary Land, London E14 9RZ UK', '790000000', 'Male', 0, 0, 0),
(7, 'sample@user6.com', 'user7', '6216f8a75fd5bb3d5f22b6f9958cdede3fc086c2', 'Sam', 'Samplemann', 'Imaginary Land, London E14 9RZ UK', '792200000', 'Other', 0, 0, 0),
(8, 'sample@user7.com', 'user8', '6216f8a75fd5bb3d5f22b6f9958cdede3fc086c2', 'Sophie', 'Samplemann', 'Imaginary Land, London E14 9RZ UK', '792200000', 'Female', 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `watchlists`
--

DROP TABLE IF EXISTS `watchlists`;
CREATE TABLE IF NOT EXISTS `watchlists` (
  `buyerID` int NOT NULL,
  `auctionID` int NOT NULL,
  PRIMARY KEY (`buyerID`,`auctionID`),
  KEY `auctionID` (`auctionID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `watchlists`
--

INSERT INTO `watchlists` (`buyerID`, `auctionID`) VALUES
(6, 2),
(7, 2),
(6, 3),
(7, 3),
(5, 4),
(8, 4),
(5, 5),
(8, 5);

-- --------------------------------------------------------

--
-- Table structure for table `wishilists`
--

DROP TABLE IF EXISTS `wishilists`;
CREATE TABLE IF NOT EXISTS `wishilists` (
  `wishlistitemID` int NOT NULL AUTO_INCREMENT,
  `categoryType` varchar(100) NOT NULL,
  `categoryColor` varchar(100) NOT NULL,
  `categoryGender` varchar(100) NOT NULL,
  `categorySize` varchar(100) NOT NULL,
  `buyerID` int NOT NULL,
  PRIMARY KEY (`wishlistitemID`),
  KEY `buyerID` (`buyerID`),
  KEY `categoryType` (`categoryType`),
  KEY `categoryColor` (`categoryColor`),
  KEY `categoryGender` (`categoryGender`),
  KEY `categorySize` (`categorySize`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `auctions`
--
ALTER TABLE `auctions`
  ADD CONSTRAINT `auctions_ibfk_1` FOREIGN KEY (`sellerID`) REFERENCES `users` (`userID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `auctions_ibfk_2` FOREIGN KEY (`categoryType`) REFERENCES `categoryclothstype` (`categoryType`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `auctions_ibfk_3` FOREIGN KEY (`categoryColor`) REFERENCES `categorycolortype` (`categoryColor`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `auctions_ibfk_4` FOREIGN KEY (`categoryGender`) REFERENCES `categorygendertype` (`categoryGender`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `auctions_ibfk_5` FOREIGN KEY (`categorySize`) REFERENCES `categorysizetype` (`categorySize`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Constraints for table `bids`
--
ALTER TABLE `bids`
  ADD CONSTRAINT `bids_ibfk_1` FOREIGN KEY (`buyerID`) REFERENCES `users` (`userID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `bids_ibfk_2` FOREIGN KEY (`auctionID`) REFERENCES `auctions` (`auctionID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `ratings`
--
ALTER TABLE `ratings`
  ADD CONSTRAINT `ratings_ibfk_1` FOREIGN KEY (`auctionID`) REFERENCES `auctions` (`auctionID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `ratings_ibfk_2` FOREIGN KEY (`buyerID`) REFERENCES `users` (`userID`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `watchlists`
--
ALTER TABLE `watchlists`
  ADD CONSTRAINT `watchlists_ibfk_1` FOREIGN KEY (`buyerID`) REFERENCES `users` (`userID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `watchlists_ibfk_2` FOREIGN KEY (`auctionID`) REFERENCES `auctions` (`auctionID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `wishilists`
--
ALTER TABLE `wishilists`
  ADD CONSTRAINT `wishilists_ibfk_1` FOREIGN KEY (`buyerID`) REFERENCES `users` (`userID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `wishilists_ibfk_2` FOREIGN KEY (`categoryType`) REFERENCES `categoryclothstype` (`categoryType`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `wishilists_ibfk_3` FOREIGN KEY (`categoryColor`) REFERENCES `categorycolortype` (`categoryColor`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `wishilists_ibfk_4` FOREIGN KEY (`categoryGender`) REFERENCES `categorygendertype` (`categoryGender`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `wishilists_ibfk_5` FOREIGN KEY (`categorySize`) REFERENCES `categorysizetype` (`categorySize`) ON DELETE RESTRICT ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

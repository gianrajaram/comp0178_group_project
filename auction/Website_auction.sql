/* SQL Database for project Database fundamentals */

/* When running this code for the first time, mute the next line with drop database  */

/* Setting up strict SQL mode to prevent setting an attribute not given a value to its default value */

SET global sql_mode='STRICT_TRANS_TABLES';

DROP DATABASE Website_auction;

CREATE DATABASE Website_auction;

USE Website_auction;

CREATE TABLE Users (
    userID INT(10) AUTO_INCREMENT, 
	userEmail VARCHAR(100) NOT NULL UNIQUE CHECK (userEmail LIKE "%_@__%.__%"), 
	username VARCHAR(100) NOT NULL UNIQUE, 
	userPassword VARCHAR(20) NOT NULL, 
	userFirstName VARCHAR(100),
	userLastName VARCHAR(100), 
	userAddress VARCHAR(100), 
	userTel VARCHAR(100), 
	userGender VARCHAR(100) CHECK (userGender in ("Male", "Female", "Other", "Prefer not to say")), 
	userAdminRights BOOLEAN NOT NULL DEFAULT 0, 
	userBuyerRights BOOLEAN NOT NULL DEFAULT 0,
	userSellerRights BOOLEAN NOT NULL DEFAULT 0,
	PRIMARY KEY(userID)
	)
	ENGINE=INNODB;


/* Insert sample users into database - attention - no input needed for userID (auto incrementing), userAdminRights, userBuyerRights, userSellerRights */

/* Admin registration */
INSERT INTO Users (userEmail, username, userPassword, userFirstName, userLastName, userAddress, userTel, userGender, userAdminRights) VALUES
("gbakova@yahoo.com", "admin", "111", "Gabriela", "Bakova", "Discovery Dock East, London E14 9RZ UK", 0790000000, "Female", 1);

/* Sample user registration */
INSERT INTO Users (userEmail, username, userPassword, userFirstName, userLastName, userAddress, userTel, userGender) VALUES
("gabriela.bkva@gmail.com", "user2", "111", "Gabi", "Bakova", "Imaginary Land, London E14 9RZ UK", 0792200000, "Female"),
("annecat.xhonneux@gmail.com", "user3", "111", "Cath", "Xhonneux", "Imaginary Land, London E14 9RZ UK", 0792200000, "Female"),
("gianpremr@gmail.com", "user4", "111", "Gian", "Prem", "Imaginary Land, London E14 9RZ UK", 0792200000, "Male"),
("mnamyslowska1@gmail.com", "user5", "111", "Maja", "Namyslowska", "Imaginary Land, London E14 9RZ UK", 0792200000, "Female"),
("sample@user.com", "user6", "111", "Max", "Mustermann", "Imaginary Land, London E14 9RZ UK", 0790000000, "Male"),
("sample@user6.com", "user7", "111", "Sam", "Samplemann", "Imaginary Land, London E14 9RZ UK", 0792200000, "Other"),
("sample@user7.com", "user8", "111", "Sophie", "Samplemann", "Imaginary Land, London E14 9RZ UK", 0792200000, "Female");


CREATE TABLE CategoryClothsType (
  categoryType VARCHAR(100) NOT NULL,
  PRIMARY KEY (`categoryType`)
)
ENGINE=INNODB;

/* Clothing category options LOCKED - no more entries */
INSERT INTO CategoryClothsType (categoryType) VALUES
    ("Dresses/Skirts"),
    ("Bags"),
    ("Trousers/Jeans"),
    ("Blouses/Shirts"),
    ("T-shirts"),
    ("Shoes"),
    ("Skirts"),
    ("Jackets/Coats"),
    ("Accessories")
;

CREATE TABLE CategoryColorType ( 
  categoryColor VARCHAR(100) NOT NULL,
  PRIMARY KEY (categoryColor)
)
ENGINE=INNODB;

/* Color category options LOCKED - no more entries */
INSERT INTO CategoryColorType (categoryColor) VALUES
    ("Black"),
    ("Brown"),
    ("White"),
    ("Blue"),
    ("Green"),
    ("Yellow"),
    ("Orange"),
    ("Red"),
    ("Pink"),
    ("Grey"),
    ("Multi-coloured")
;


CREATE TABLE CategoryGenderType (
  categoryGender VARCHAR(100) NOT NULL,
  PRIMARY KEY (categoryGender)
)
ENGINE=INNODB;

/* Gender category options LOCKED - no more entries  */
INSERT INTO CategoryGenderType (categoryGender) VALUES
    ("Female"),
    ("Male"),
    ("Unisex")
;

CREATE TABLE CategorySizeType (
  categorySize varchar(100) NOT NULL,
  PRIMARY KEY (categorySize)
)
ENGINE=INNODB;

/* Sizes category options LOCKED - no more entries */
INSERT INTO CategorySizeType (categorySize) VALUES
    ("XS"),
    ("S"),
    ("M"),
    ("L"),
    ("XL"),
    ("XXL"),
    ("N/A")
;


CREATE TABLE Auctions (
    auctionID INT(10) AUTO_INCREMENT,
    auctionStartDate DATETIME NOT NULL,
    auctionEndDate DATETIME NOT NULL,
    auctionStartingPrice DOUBLE(10,2) NOT NULL, 
    auctionCurrentHighestBid DOUBLE(10,2),
    auctionReservePrice DOUBLE(10,2) NOT NULL,
    auctionName VARCHAR(100) NOT NULL,
    auctionDescription VARCHAR(500),
    auctionBidCount INT(10),
    auctionStatus VARCHAR(100) NOT NULL CHECK (auctionStatus in ("Running", "Closed")),
    auctionPicture VARCHAR(100),
    sellerID INT(10),
    categoryType VARCHAR(100) NOT NULL,
    categoryColor VARCHAR(100) NOT NULL,
    categoryGender VARCHAR(100) NOT NULL,
    categorySize VARCHAR(100) NOT NULL,
    PRIMARY KEY(auctionID),
    FOREIGN KEY (sellerID) REFERENCES Users (userID) ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (categoryType) REFERENCES CategoryClothsType (categoryType) ON UPDATE CASCADE ON DELETE RESTRICT,
    FOREIGN KEY (categoryColor) REFERENCES CategoryColorType (categoryColor) ON UPDATE CASCADE ON DELETE RESTRICT,
    FOREIGN KEY (categoryGender) REFERENCES categoryGenderType (categoryGender) ON UPDATE CASCADE ON DELETE RESTRICT,
    FOREIGN KEY (categorySize) REFERENCES categorySizeType (categorySize) ON UPDATE CASCADE ON DELETE RESTRICT
)
ENGINE=INNODB;


/* Insert sample auctions */
INSERT INTO Auctions (auctionStartDate, auctionEndDate, auctionStartingPrice, auctionReservePrice, auctionName, auctionDescription, auctionStatus, auctionPicture, sellerID, categoryType, categoryColor, categoryGender, categorySize) VALUES
("2023-11-02 8:30:00", "2023-11-04 20:00:00", 20.50, 45, "Amazing VIVAIA flats", "Green flats for females, size 38 European, brand new worn once", (CASE WHEN NOW() > auctionStartDate AND NOW() < auctionEndDate THEN "Running" ELSE "Closed" END), "/auction/images/Green female shoes.png", 2, "Shoes", "Green", "Female", "N/A"),
("2023-11-01 8:30:00", "2023-11-04 20:00:00", 5.50, 10, "Stylish black T-Shitrt for men", "Black T-shirt with red rose decoration, brand new never worn", (CASE WHEN NOW() > auctionStartDate AND NOW() < auctionEndDate THEN "Running" ELSE "Closed" END), "/auction/images/plainblackTshirt_male.png", 4, "T-shirts", "Black", "Male", "L"),
("2023-11-01 8:30:00", "2023-11-07 20:00:00", 50, 70, "Pier One jeans", "Male light denim jeans, in good condition", (CASE WHEN NOW() > auctionStartDate AND NOW() < auctionEndDate THEN "Running" ELSE "Closed" END), "/auction/images/jeans_male.png", 4, "Trousers/Jeans", "Blue", "Male", "L"),
("2023-11-01 8:30:00", "2023-11-08 20:00:00", 100, 150, "PERFECT KAREN MILLEN DRESS", "very stylish designer dress, worn only on one occasion and has been to dry cleaning", (CASE WHEN NOW() > auctionStartDate AND NOW() < auctionEndDate THEN "Running" ELSE "Closed" END), "/auction/images/bluedress.png", 2, "Dresses/Skirts", "Blue", "Female", "M"),
("2023-11-01 8:30:00", "2023-11-06 20:00:00", 50, 70, "Summer colorful skirt", "skirt perfect for the summer, never worn", (CASE WHEN NOW() > auctionStartDate AND NOW() < auctionEndDate THEN "Running" ELSE "Closed" END), "/auction/images/colorfulldress.png", 2, "Dresses/Skirts", "Multi-coloured", "Female", "M"),
("2023-11-02 08:30:00", "2023-11-04 20:00:00", 20.50, 45, "Ultimate deal for a VALENTINO BAG", "Amazing Valentino bag in very good condition", (CASE WHEN NOW() > auctionStartDate AND NOW() < auctionEndDate THEN 'Running' ELSE 'Closed' END), "/auction/images/accessories_valentino_bag.png", 2, "Accessories", "Black", "Female", "N/A"),
("2023-11-03 08:30:00", "2023-11-05 20:00:00", 10.00, 25, "Elegant Unisex Scarf", "Stylish unisex scarf in cheerful colors, perfect for any season", (CASE WHEN NOW() > auctionStartDate AND NOW() < auctionEndDate THEN "Running" ELSE "Closed" END), "/auction/images/accessories_unisex_scarf.jpg", 3, "Accessories", "Multi-coloured", "Unisex", "N/A"),
("2023-11-04 08:30:00", "2023-11-06 20:00:00", 15.00, 30, "Elegant White Blouse for Women", "White blouse for females, size Medium, elegant design, never worn", (CASE WHEN NOW() > auctionStartDate AND NOW() < auctionEndDate THEN "Running" ELSE "Closed" END), "/auction/images/whiteblousefemale.jpg", 3, "Blouses/Shirts", "White", "Female", "M"),
("2023-11-05 08:30:00", "2023-11-07 20:00:00", 18.50, 35, "Stylish Blue Blouse for Women", "Blue blouse for females, size Small, elegant design, like new", (CASE WHEN NOW() > auctionStartDate AND NOW() < auctionEndDate THEN "Running" ELSE "Closed" END), "/auction/images/blueblousefemale.jpg", 3, "Blouses/Shirts", "Blue", "Female", "S"),
("2023-11-06 08:30:00", "2023-11-08 20:00:00", 12.75, 25, "Elegant Black Skirt for Women", "Black skirt for females, size Large, perfect for formal occasions", (CASE WHEN NOW() > '2023-11-06 08:30:00' AND NOW() < '2023-11-08 20:00:00' THEN 'Running' ELSE 'Closed' END), "/auction/images/blackskirtfemale.jpg", 3, "Dresses/Skirts", "Black", "Female", "L"),
("2023-11-07 08:30:00", "2023-11-09 20:00:00", 22.99, 40, "Classic Black Female Shoes", "Black shoes for females, size 38 European, comfortable and stylish", (CASE WHEN NOW() > auctionStartDate AND NOW() < auctionEndDate THEN "Running" ELSE "Closed" END), "/auction/images/blackshoesfemale.jpg", 2, "Shoes", "Black", "Female", "N/A"),
("2023-11-08 08:30:00", "2023-11-10 20:00:00", 30.00, 60, "Elegant Black Dress for Women", "Black dress for females, size Medium, perfect for special occasions", (CASE WHEN NOW() > auctionStartDate AND NOW() < auctionEndDate THEN "Running" ELSE "Closed" END), "/auction/images/blackdressfemale.jpg", 2, "Dresses/Skirts", "Black", "Female", "M"),
("2023-11-09 08:30:00", "2023-11-11 20:00:00", 40.00, 80, "Stylish Red Coat for Women", "Red coat for females, size Large, excellent condition, perfect for winter", (CASE WHEN NOW() > '2023-11-09 08:30:00' AND NOW() < '2023-11-11 20:00:00' THEN 'Running' ELSE 'Closed' END), "/auction/images/redcoatfemale.jpg", 3, "Jackets/Coats", "Red", "Female", "L")
;


CREATE TABLE Bids (
    bidID INT(10) AUTO_INCREMENT,
    dateBid DATETIME NOT NULL DEFAULT NOW(),
    bidValue DECIMAL(10,2) NOT NULL,
    bidStatus VARCHAR(100) NOT NULL CHECK (bidStatus in ("Running", "Lost", "Won")) DEFAULT "Running",
    buyerID INT(10) NOT NULL,
    auctionID INT(10) NOT NULL,
    PRIMARY KEY (bidID),
    FOREIGN KEY (buyerID) REFERENCES Users (userID) ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (auctionID) REFERENCES Auctions (auctionID) ON UPDATE CASCADE ON DELETE CASCADE
)
ENGINE=INNODB;

/* Insert sample bids */
INSERT INTO Bids (bidValue, buyerID, auctionID) VALUES
(5.5,6,2),
(7,7,2),
(9,6,2),
(10.5,7,2),
(50,7,3),
(55,6,2),
(65,7,2),
(69,6,2),
(100,5,4),
(50,8,5)
;

CREATE TABLE Watchlists (
    buyerID INT(10) NOT NULL,
    auctionID INT(10) NOT NULL,
    PRIMARY KEY (buyerID, auctionID),
    FOREIGN KEY (buyerID) REFERENCES Users (userID) ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (auctionID) REFERENCES Auctions (auctionID) ON UPDATE CASCADE ON DELETE CASCADE
)
ENGINE=INNODB;

INSERT INTO Watchlists (buyerID, auctionID) VALUES
(6,2),
(7,2),
(7,3),
(6,3),
(5,4),
(8,5),
(5,5),
(8,4)
;


CREATE TABLE Ratings (
    auctionID INT(10) NOT NULL,
    ratingValue INT(1) NOT NULL CHECK (ratingValue >= 1 AND ratingValue <= 5),
    ratingText VARCHAR(500),
    buyerID INT(10),
    PRIMARY KEY (auctionID),
    FOREIGN KEY (auctionID) REFERENCES Auctions (auctionID) ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (buyerID) REFERENCES Users (userID) ON UPDATE CASCADE ON DELETE SET NULL
)
ENGINE=INNODB;

CREATE TABLE Wishilists (
    wishlistitemID INT(10) NOT NULL AUTO_INCREMENT,
    categoryType VARCHAR(100) NOT NULL,
    categoryColor VARCHAR(100) NOT NULL,
    categoryGender VARCHAR(100) NOT NULL,
    categorySize VARCHAR(100) NOT NULL,
    buyerID INT(10) NOT NULL,
    PRIMARY KEY (WishlistitemID),
    FOREIGN KEY (buyerID) REFERENCES Users (userID) ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (categoryType) REFERENCES CategoryClothsType (categoryType) ON UPDATE CASCADE ON DELETE RESTRICT,
    FOREIGN KEY (categoryColor) REFERENCES CategoryColorType (categoryColor) ON UPDATE CASCADE ON DELETE RESTRICT,
    FOREIGN KEY (categoryGender) REFERENCES categoryGenderType (categoryGender) ON UPDATE CASCADE ON DELETE RESTRICT,
    FOREIGN KEY (categorySize) REFERENCES categorySizeType (categorySize) ON UPDATE CASCADE ON DELETE RESTRICT
)
ENGINE=INNODB;

<?php include_once("header.php")?>
<?php require_once("database_connection.php");
require_once("utilities.php");
?>




<?php

$connection = connectMAC();

// get info passed from listing.php
if (isset($_POST['bid_price']) && $_POST['bid_price']!='') {
    $auctionID = $_POST['auctionID'];
    $auctionMaxCP = $_POST['auctionMaxCP'];
    $bidValue = $_POST['bid_price']; 
    $userID = $_SESSION['userID']; 

    // check if big is high enough
    $dateBid = date('Y-m-d H:i:s');
    if ($bidValue > $auctionMaxCP) {
        
        $query = "INSERT INTO Bids (dateBid, bidValue, buyerID, auctionID) VALUES ('$dateBid', '$bidValue', '$userID', '$auctionID')";
        
        
        send_queryMAC($query);
        echo "<script>alert('Your bid was successful.');</script>";
        echo "<script>window.location.href='mybids.php';</script>";
    } else {
        echo "<script>alert('Your bid was too low.');</script>";
        echo "<script>window.location.href='listing.php';</script>";
    }

    
} else {

    echo "<script>alert('User ID is not set. Please log in.');</script>";

}
?>
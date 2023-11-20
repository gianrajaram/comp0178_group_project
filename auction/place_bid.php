
<?php include_once("header.php")?>
<?php require_once("database_connection.php");
require_once("utilities.php");
?>




<?php
// Establish connection with database

$connection = connectMAC();

// TODO: Extract $_POST variables, check they're OK, and attempt to make a bid.
// Notify user of success/failure and redirect/give navigation options.
if (isset($_POST['bid_price']) && $_POST['bid_price']!='') {
    $auctionID = $_POST['auctionID'];
    $auctionMaxCP = $_POST['auctionMaxCP'];
    $bidValue = $_POST['bid_price']; // Assuming the bid value is passed via POST
    $userID = $_SESSION['userID']; // Assuming the user ID is stored in the session

    // Check if user is active; adjust this part according to your application logi
    $dateBid = date('Y-m-d H:i:s');
    if ($bidValue > $auctionMaxCP) {
        // Prepare the query
        $query = "INSERT INTO Bids (dateBid, bidValue, buyerID, auctionID) VALUES ('$dateBid', '$bidValue', '$userID', '$auctionID')";
        
        // Execute the query
        send_queryMAC($query);
        echo "<script>alert('Your bid was successful.');</script>";
        echo "<script>window.location.href='mybids.php';</script>";
    } else {
        echo "<script>alert('Your bid was too low.');</script>";
        echo "<script>window.location.href='listing.php';</script>";
    }

    
} else {
        // Handle the case where userID is not set or is empty
        // For example, redirect to login page or show an error message
    echo "<script>alert('User ID is not set. Please log in.');</script>";
        // Redirect or other actions
        // Stop further execution if userID is not available
}
?>
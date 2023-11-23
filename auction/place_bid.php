
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

    // Further variables for emailing
    // bidder email and name
    $query = "SELECT * FROM Users WHERE userID = '$userID'";
    $result = send_query($query);
    $row = mysqli_fetch_assoc($result);
    $emailBidder = $row['userEmail'];
    $firstName = $row['userFirstName'];

    // auction titel
    $query = "SELECT * FROM Auctions WHERE auctionID = '$auctionID'";
    $result = send_query($query);
    $row = mysqli_fetch_assoc($result);
    $auctionTitle = $row['auctionName'];

    // previous highest bidder email
    $query = "SELECT B.*, U.userEmail, U.userFirstName
                FROM Bids B
                JOIN Users U ON B.buyerID = U.userID
                WHERE B.auctionID = '$auctionID' AND B.bidValue = '$auctionMaxCP'";
    $result = send_query($query);
    $row = mysqli_fetch_assoc($result);
    $emailPreviousBidder = $row['userEmail'];
    $firstNamePreviousBidder = $row['userFirstName'];
    
    // emails of all buyers that have put the auction in watchlist

    // Check if user is active; adjust this part according to your application logi
    $dateBid = date('Y-m-d H:i:s');
    if ($bidValue > $auctionMaxCP) {
        // Prepare the query
        $query = "INSERT INTO Bids (dateBid, bidValue, buyerID, auctionID) VALUES ('$dateBid', '$bidValue', '$userID', '$auctionID')";
        
        // Execute the query
        send_queryMAC($query);
        echo "<script>alert('Your bid was successful.');</script>";
        echo "<script>window.location.href='mybids.php';</script>";

        // send successful bid submission email
        $name = "ReWear Auctions"; //sender’s name
        $email = "UCL2023DatabasesAuctionReWear@gmail.com"; //sender’s e-mail address
        $recipient = $emailBidder; //recipient
        $mail_body= "$firstName, you successfully bidded £$bidValue on auction $auctionTitle!"; //mail body
        $subject = "ReWear Auctions - Successful bid submission!"; //subject
        $header = "From: ". $name . " <" . $email . ">\r\n";
        mail($recipient, $subject, $mail_body, $header); 

        // send email informing buyer with highest bid that they were outbidded
        $name = "ReWear Auctions"; //sender’s name
        $email = "UCL2023DatabasesAuctionReWear@gmail.com"; //sender’s e-mail address
        $recipient = $emailPreviousBidder; //recipient
        $mail_body= "$firstNamePreviousBidder, you were outbidded on auction $auctionTitle!"; //mail body
        $subject = "ReWear Auctions - You were outbidded!"; //subject
        $header = "From: ". $name . " <" . $email . ">\r\n";
        mail($recipient, $subject, $mail_body, $header);

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

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
    $isWinner = $_POST['isWinner'];
    $userID = $_SESSION['userID']; 

    // Further variables for emailing
    // bidder email and name
    $queryBidder = "SELECT * FROM Users WHERE userID = '$userID'";
    $resultBidder = send_query($queryBidder);
    $rowBidder = mysqli_fetch_assoc($resultBidder);
    $emailBidder = $rowBidder['userEmail'];
    $firstNameBidder = $rowBidder['userFirstName'];

    // auction title
    $queryAuctionTitle = "SELECT * FROM Auctions WHERE auctionID = '$auctionID'";
    $resultAuctionTitle = send_query($queryAuctionTitle);
    $rowAuctionTitle = mysqli_fetch_assoc($resultAuctionTitle);
    $auctionTitle = $rowAuctionTitle['auctionName'];

    // previous highest bidder email
    $queryPreviousHighestBidder = "SELECT B.*, U.userEmail, U.userFirstName
                FROM Bids B
                JOIN Users U ON B.buyerID = U.userID
                WHERE B.auctionID = '$auctionID' AND B.bidValue = '$auctionMaxCP'";
    $resultPreviousBidder = send_query($queryPreviousHighestBidder);
    $rowPreviousBidder = mysqli_fetch_assoc($resultPreviousBidder);
    $emailPreviousBidder = $rowPreviousBidder['userEmail'];
    $firstNamePreviousBidder = $rowPreviousBidder['userFirstName'];
    
    // emails and first names of all buyers that have put the auction in watchlist
    $queryWatchlist = "SELECT U.userEmail, U.userFirstName
                FROM Watchlists W
                JOIN Users U ON W.buyerID = U.userID
                WHERE W.auctionID = '$auctionID'";
    $resultWatchlistBidders = send_query($queryWatchlist);
 


  
    // check if the bid is high enough, if it is a numerical value and if the user is the winner
    $dateBid = date('Y-m-d H:i:s');
    if (!is_numeric($bidValue)) {
        echo "<script>alert('Invalid bid. Please enter a numerical value.');</script>";
        echo "<script>window.location.href='listing.php?item_id=" . htmlspecialchars($auctionID) . "';</script>";
    } else if ($isWinner == 1) {

        echo "<script>alert('Your bid is currently the highest bid. There is no need to place another one.');</script>";
        echo "<script>window.location.href='listing.php?item_id=" . htmlspecialchars($auctionID) . "';</script>";
    } else if ($bidValue > $auctionMaxCP) {
        $query = "INSERT INTO Bids (dateBid, bidValue, buyerID, auctionID) VALUES ('$dateBid', '$bidValue', '$userID', '$auctionID')";
        
        
        send_queryMAC($query);
        echo "<script>alert('Your bid was successful.');</script>";
        echo "<script>window.location.href='mybids.php';</script>";

        // send successful bid submission email
        $name = "ReWear Auctions"; //sender’s name
        $email = "UCL2023DatabasesAuctionReWear@gmail.com"; //sender’s e-mail address
        $recipient = $emailBidder; //recipient
        $mail_body= "$firstNameBidder, you successfully bidded £$bidValue on auction $auctionTitle!"; //mail body
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

        // send email informing buyers who have put the auction in their watchlist that someone has bidded on the auction    

        if (mysqli_num_rows($resultWatchlistBidders) > 0) {
            while ($rowWatchlistBidders = mysqli_fetch_assoc($resultWatchlistBidders)) {
                $emailWatchlistBidder = $rowWatchlistBidders['userEmail'];
                $firstNameWatchlistBidder = $rowWatchlistBidders['userFirstName'];
                $name = "ReWear Auctions"; //sender’s name
                $email = "UCL2023DatabasesAuctionReWear@gmail.com"; //sender’s e-mail address
                $recipient = $emailWatchlistBidder; //recipient
                $mail_body= "$firstNameWatchlistBidder, there was a bid submission on auction $auctionTitle that is in your watchlist!"; //mail body
                $subject = "ReWear Auctions - Someone bid on a watchlisted auction"; //subject
                $header = "From: ". $name . " <" . $email . ">\r\n";
                mail($recipient, $subject, $mail_body, $header);      
                }
            }
    }  else {
        echo "<script>alert('Your bid was too low.');</script>";
        echo "<script>window.location.href='listing.php?item_id=" . htmlspecialchars($auctionID) . "';</script>";
    }

    
} else {

    echo "<script>alert('User ID is not set. Please log in.');</script>";

}
?>
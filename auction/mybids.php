<?php include_once("header.php")?>
<?php require_once("database_connection.php")?>
<?php require("utilities.php")?>

<div class="container">

<h2 class="my-3">My bids</h2>

<?php

// WATCHLIST NOT WISHLIST

//session_start();

$connection = connectMAC();

$buyerID = $_SESSION['userID'];
$auctionID = isset($_GET['item_id']) ? $_GET['item_id'] : 0;


// SQL query to fetch auction names from the Watchlists for a specific buyer
//$query = "SELECT a.auctionID, a.auctionName, MAX(b.bidValue) AS maxBidValue
//          FROM Bids b
//          JOIN Auctions a ON b.auctionID = a.auctionID
//          WHERE b.buyerID = '$buyerID'
//         GROUP BY a.auctionID, a.auctionName";

$query = "SELECT a.auctionID, a.auctionName, 
                 MAX(b1.bidValue) AS maxBuyerBidValue, 
                 MAX(b2.bidValue) AS maxBidValue
          FROM Auctions a
          LEFT JOIN Bids b1 ON a.auctionID = b1.auctionID AND b1.buyerID = '$buyerID'
          LEFT JOIN Bids b2 ON a.auctionID = b2.auctionID
          WHERE b1.buyerID = '$buyerID'
          GROUP BY a.auctionID, a.auctionName";

$result = send_queryMAC($query);
?>

<div class="container">
    <table class="table">
        <thead>
            <tr>
                <th>Auction Name</th>
                <th>Your Highest Bid</th>
                <th>Current Highest Bid</th>
            </tr>
        </thead>
        <tbody>
            <?php
                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                      echo "<tr>";
                      echo "<td><a href='listing.php?item_id=" . htmlspecialchars($row['auctionID']) . "'>" . htmlspecialchars($row['auctionName']) . "</a></td>";
                      echo "<td>" . htmlspecialchars($row['maxBuyerBidValue']) . "</td>";
                      echo "<td>" . htmlspecialchars($row['maxBidValue']) . "</td>";
                      echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='2'>No auctions found in your watchlist.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<?php include_once("footer.php"); ?>

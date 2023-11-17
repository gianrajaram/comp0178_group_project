<?php include_once("header.php")?>
<?php require_once("database_connection.php")?>
<?php require("utilities.php")?>

<div class="container">

<h2 class="my-3">My watchlist</h2>

<?php

// WATCHLIST NOT WISHLIST

//session_start();

$connection = connectMAC();

$buyerID = $_SESSION['userID'];

// SQL query to fetch auction names from the Watchlists for a specific buyer
$query = "SELECT a.auctionID, a.auctionName, MAX(b.bidValue) AS maxBidValue
          FROM Watchlists w
          JOIN Auctions a ON w.auctionID = a.auctionID
          LEFT JOIN Bids b ON a.auctionID = b.auctionID
          WHERE w.buyerID = '$buyerID'
          GROUP BY a.auctionID, a.auctionName";


$result = send_queryMAC($query);
?>

<div class="container">
    <table class="table">
        <thead>
            <tr>
                <th>Auction Name</th>
                <th>Max Bid Value</th>
            </tr>
        </thead>
        <tbody>
            <?php
                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        // Make auction names clickable links
                        echo "<td><a href='listing.php?auctionID=" . htmlspecialchars($row['auctionID']) . "'>" . htmlspecialchars($row['auctionName']) . "</a></td>";
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

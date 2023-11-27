<?php include_once("header.php")?>
<?php require_once("database_connection.php")?>
<?php require("utilities.php")?>

<div class="container">
<h2 class="my-3">My watchlist</h2>

<?php

// WATCHLIST NOT WISHLIST, wrong file name

$connection = connectMAC();

$buyerID = $_SESSION['userID'];

// get auction names for the specific buyer
$query = "SELECT a.auctionID, a.auctionName, a.auctionStartingPrice, MAX(b.bidValue) as auctionMaxCP
          FROM Watchlists w
          JOIN Auctions a ON w.auctionID = a.auctionID
          LEFT JOIN Bids b ON a.auctionID = b.auctionID
          WHERE w.buyerID = '$buyerID'
          GROUP BY a.auctionID, a.auctionName, a.auctionStartingPrice";

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
                        $auctionMaxCP = $row['auctionMaxCP'];
                        $auctionStartingPrice = $row['auctionStartingPrice'];
                        if ($auctionMaxCP == 0 ) {
                            $auctionMaxCP = $row['auctionStartingPrice'];
                        }

                        echo "<tr>";
                        // turn auction names into clickable links
                        echo "<td><a href='listing.php?item_id=" . htmlspecialchars($row['auctionID']) . "'>" . htmlspecialchars($row['auctionName']) . "</a></td>";
                        echo "<td>" . htmlspecialchars($auctionMaxCP) . "</td>";
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

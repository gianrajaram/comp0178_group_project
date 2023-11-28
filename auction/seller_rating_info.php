<?php include_once("header.php")?>
<?php require_once("database_connection.php")?>
<?php require("utilities.php")?>

<div class="container">


<?php
// establish connection with the database
$connection = connectMAC();

// get the username, should be passed on from the previous page
if (isset($_GET['seller'])) {
    $sellerUsername = urldecode($_GET['seller']);
}

$query = "SELECT userID FROM Users WHERE username = '$sellerUsername'";
$result = send_queryMAC($query);

if ($row = mysqli_fetch_assoc($result)) {
    $sellerID = $row['userID'];
}

// query for user's auctions
$auctionsQuery = "SELECT auctionID, auctionName, ratingValue, ratingText FROM Auctions WHERE sellerID = '$sellerID' AND a.ratingValue IS NOT NULL";
$auctionsResult = send_queryMAC($auctionsQuery);

$averageRatingQuery = "SELECT AVG(a.ratingValue) as overallAvgRating
                       FROM Auctions a
                       WHERE a.sellerID = '$sellerID' AND a.ratingValue IS NOT NULL";

$avgResult = send_queryMAC($averageRatingQuery);
if ($avgRow = mysqli_fetch_assoc($avgResult)) {
    $overallAvgRating = number_format($avgRow['overallAvgRating'], 2);
} else {
    $overallAvgRating = "No ratings";
}
?>

<div style="margin-top: 20px;"> </div>



<!-- user's ratings table -->
<div class="container">
    <h3><?php echo $sellerUsername; ?>'s Ratings</h3>
    <h4> Overall rating: <?php echo $overallAvgRating; ?> </h4>
    <table class="table">
        <thead>
            <tr>
                <th>Auction Name</th>
                <th>Rating</th>
                <th>Comment</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (mysqli_num_rows($ratingsResult) > 0) {
                while ($row = mysqli_fetch_assoc($ratingsResult)) {
                    echo "<tr>";
                    
                    echo "<td>" . htmlspecialchars($row['auctionName']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['ratingValue']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['ratingText']) . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='3'>No ratings found.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<!-- user's auctions table -->

<div class="container">
    <h3><?php echo $sellerUsername; ?>'s Auctions</h3>
    <table class="table">
        <thead>
            <tr>
                <th>Auction Name</th>
                
            </tr>
        </thead>
        <tbody>
            <?php
                if (mysqli_num_rows($auctionsResult) > 0) {
                    while ($row = mysqli_fetch_assoc($auctionsResult)) {

                        echo "<tr>";
                        // turn auction names into clickable links
                        echo "<td><a href='listing.php?item_id=" . htmlspecialchars($row['auctionID']) . "'>" . htmlspecialchars($row['auctionName']) . "</a></td>";
                        echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='2'>No auctions found.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<?php include_once("footer.php"); ?>
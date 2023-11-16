<?php 
include_once("header.php");
require("utilities.php");
require("database_connection.php");


// Fetch the users from the database
$query_auctions = "SELECT auctionID, auctionStartDate, auctionEndDate, auctionName, auctionDescription, sellerID FROM Auctions ";
$auctions = send_query($query_auctions);
// Get auction status 
$currentTime = date('Y-m-d H:i:s');


?>

<!-- Formating of table adapted from ChatGPT -->
<div class="container">
    <h2 class="my-3">Overview of auctions</h2>

    <style>
        .user-info-table th, .user-info-table td {
            padding: 10px;
            border-bottom: 1px solid #ccc; 
        }
    </style>


    <table class="user-info-table">
        <thead>
            <tr>
                <th>Auction ID</th>
                <th>Name</th>
                <th>Start date</th>
                <th>End date</th>
                <th>Description</th>
                <th>Auction status</th>
                <th>Seller ID</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($auctions as $auction): ?>
                <tr>
                    <td><?php echo $auction['auctionID']; ?></td>
                    <td><?php echo $auction['auctionName']; ?></td>
                    <td><?php echo $auction['auctionStartDate']; ?></td>
                    <td><?php echo $auction['auctionEndDate']; ?></td>
                    <td><?php echo $auction['auctionDescription']; ?></td>
                    <?php if ($currentTime >= $auction['auctionStartDate'] && $currentTime <= $auction['auctionEndDate']){
                        $auction_status = "Active";
                    } else {
                        $auction_status = "Closed";
                    } ?>
                    <td><?php echo $auction_status; ?></td>
                    <td><?php echo $auction['sellerID']; ?></td>
                    <td> <!-- End of adaptation from ChatGPT -->
                        <form method="POST" action="admin_delete_auction.php">
                        <input type="hidden" name="auctionID" value="<?php echo $auction['auctionID']; ?>">
                        <button type="submit" class="btn btn-danger form-control">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include_once("footer.php")?>
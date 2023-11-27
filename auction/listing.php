
<?php include_once("header.php")?>
<?php require_once("database_connection.php")?>

<?php require("utilities.php")?>


<?php
// connection with database
$connection = connectMAC();




//$auctionID dynamic
$auctionID = isset($_GET['item_id']) ? $_GET['item_id'] : 0;
//echo $auctionID;

if(isset($_SESSION['userID'])) {
    $userID= $_SESSION['userID'];


    $sql_auction = "SELECT a.auctionID, a.sellerID, a.auctionName, a.auctionReservePrice, a.categoryType, a.categoryColor, a.categoryGender, a.categorySize, u.username, a.auctionDescription, a.auctionStartDate, a.auctionEndDate, a.auctionPicture, a.auctionStartingPrice, MAX(b.bidValue) as auctionMaxCP, MAX(b1.bidValue) AS maxBuyerBidValue, COUNT(b.auctionID) as auctionBidCount
                    FROM Auctions a
                    JOIN Users u ON a.sellerID = u.userID
                    LEFT JOIN Bids b ON a.auctionID = b.auctionID
                    LEFT JOIN Bids b1 ON a.auctionID = b1.auctionID AND b1.buyerID = '{$userID}'
                    WHERE a.auctionID = '{$auctionID}'
                    GROUP BY a.auctionID, a.sellerID, a.auctionName, a.auctionReservePrice, a.categoryType, a.categoryColor, a.categoryGender, a.categorySize, u.username, a.auctionDescription, a.auctionStartDate, a.auctionEndDate, a.auctionStartingPrice";
} else {
    $sql_auction = "SELECT a.auctionID, a.sellerID, a.auctionName, a.auctionReservePrice, a.categoryType, a.categoryColor, a.categoryGender, a.categorySize, u.username, a.auctionDescription, a.auctionStartDate, a.auctionEndDate, a.auctionPicture, a.auctionStartingPrice, MAX(b.bidValue) as auctionMaxCP, MAX(b1.bidValue) AS maxBuyerBidValue, COUNT(b.auctionID) as auctionBidCount
                    FROM Auctions a
                    JOIN Users u ON a.sellerID = u.userID
                    LEFT JOIN Bids b ON a.auctionID = b.auctionID
                    LEFT JOIN Bids b1 ON a.auctionID = b1.auctionID
                    WHERE a.auctionID = '{$auctionID}'
                    GROUP BY a.auctionID, a.sellerID, a.auctionName, a.auctionReservePrice, a.categoryType, a.categoryColor, a.categoryGender, a.categorySize, u.username, a.auctionDescription, a.auctionStartDate, a.auctionEndDate, a.auctionStartingPrice";
}

$result = send_queryMAC($sql_auction);
$rows = mysqli_fetch_array($result);
//print_r($rows);
$sellerID = $rows['sellerID']; 
$auctionName = $rows["auctionName"];
$auctionDescription = $rows['auctionDescription'];
$auctionReservePrice = $rows['auctionReservePrice'];
$sellerUsername = $rows['username'];
$auctionMaxCP = $rows['auctionMaxCP'];
$auctionStartingPrice = $rows['auctionStartingPrice'];
$auctionBidCount = $rows['auctionBidCount'];

$categoryType = $rows['categoryType'];
$categoryColor = $rows['categoryColor'];
$categoryGender = $rows['categoryGender'];

$auctionPicture = $rows['auctionPicture'];
$auctionImageSrc = str_replace('/auction/', '', $auctionPicture);

$maxBuyerBidValue = $rows['maxBuyerBidValue'];

$auctionStartDate = $rows['auctionStartDate'];
$auctionEndDate = $rows['auctionEndDate'];
$startDate = new DateTime($auctionStartDate);
$endDate = new DateTime($auctionEndDate);

$now = new DateTime();
if ($endDate > $now) {
  $auctionStatus = 'Active';
} else {
  $auctionStatus = 'Closed';
}
if ($startDate > $now) {
  $auctionStatus = 'Pending';
}


if ($auctionMaxCP == 0 ) {
    $auctionMaxCP = $rows['auctionStartingPrice'];
}

$sql_bids = "SELECT * FROM Bids WHERE auctionID = '{$auctionID}' ORDER BY dateBid DESC";
$bids_result = mysqli_query($connection, $sql_bids);

$isWinner = 0;
if ($maxBuyerBidValue == $auctionMaxCP && $maxBuyerBidValue >= $auctionReservePrice) {
    $isWinner = 1;
}
//echo $isWinner;


$has_session = false;
$watching = false;






if(isset($_SESSION['userID'])) {
    $has_session = true;
    //check if this item is in watchlist
    $sql_check_watchlist = "SELECT * FROM `Watchlists` WHERE buyerID = '{$userID}' AND auctionID = '{$auctionID}'";
    $res1 = send_queryMAC($sql_check_watchlist);
    $res2 = mysqli_fetch_array($res1);
    if ($res2) {
        $watching = true;
    }
}

// Check if auction has been rated if auction is closed
$checkQueryIsRated = "SELECT COUNT(*) as count FROM Ratings WHERE auctionID = '$auctionID'";
$checkResultIsRated = send_query($checkQueryIsRated);
$rowIsRated = mysqli_fetch_assoc($checkResultIsRated);


?>

<div class="container">
    <div style="margin-top: 30px;">
        <div class="auction-status-info" style="font-size: 20px; font-weight: bold;">
            <p> This auction is  <?php echo strtolower($auctionStatus) ?> !</p>
        </div>
    </div>
    <div class="row">
        <style>
            .item-img {
                width: 50%;
                height: auto;
            }
        </style>            
        <div class="col-sm-6">

            <div style="margin-top: 20px;">
                <img src="<?php echo htmlspecialchars($auctionImageSrc); ?>" class='img-rounded img-responsive item-img'>
            </div>
        </div>



    <div class="col-sm-6">
        <div style="margin-top: 10px;">
        <div class="item-details">
            <h2 class="item-title" style="font-size: 30px; font-weight: bold;"><?php echo $auctionName ?></h2>
            <p class="item-description" style="font-size: 14px;"><?php echo $auctionDescription ?></p>

            <table class="table table-sm">
                <tbody>
                    <tr>
                        <td>Type: <?php echo $categoryType ?></td>
                        <td>Colour: <?php echo $categoryColor ?></td>
                        <td>Gender: <?php echo $categoryGender ?></td>
                    </tr>
                </tbody>
            </table>
            

            <div class="auction-info">
                <p>Seller: <a href="seller_rating_info.php?seller=<?php echo urlencode($sellerUsername); ?>"><?php echo htmlspecialchars($sellerUsername); ?></a></p>
                <p>Bid count: <?php echo $auctionBidCount ?></p>
                <p>Current highest bid: <?php echo $auctionMaxCP ?></p>
                <p>Status: <?php echo $auctionStatus ?></p>
            </div>
        </div> 

        <div style="margin-top: 20px;"> </div>
        
        <!-- add to the watchlist -->
        <div style="margin-top: 20px;">
            <?php if ($auctionStatus == 'Active' && $watching == false && $has_session == true && $userID != $sellerID): ?>
            <form action="watchlist_funcs.php" method="POST">
                <input type="hidden" name="auctionID" value="<?php echo $auctionID; ?>">
                <input type="hidden" name="userID" value="<?php echo $userID; ?>">
                <button type="submit" class="btn btn-primary form-control" name="watchlistsubmit" style="background-color: blue; color: white"> Add to the watchlist </button>
            </form>
            <?php endif; ?>
        </div>

        <!-- remove from the watchlist -->
        <div style="margin-top: 20px;">
            <?php if ($auctionStatus == 'Active' && $watching == true && $has_session == true && $userID != $sellerID): ?>
            <?php echo "This auction is already on your watchlist."?>
            <form action="watchlist_funcsr.php" method="POST">
                <input type="hidden" name="auctionID" value="<?php echo $auctionID; ?>">
                <input type="hidden" name="userID" value="<?php echo $userID; ?>">
                <button type="submit" class="btn btn-primary form-control" name="watchlistremove" style="background-color: blue; color: white"> Remove from the watchlist </button>
            </form>
            <?php endif; ?>
        </div>

        <!-- place a bid -->

        <div style="margin-top: 20px;"> </div>
            <?php if ($auctionStatus == 'Active' && $has_session == true && $userID != $sellerID): ?>
            <form action="place_bid.php" method="POST">
                <div class="row form-group">
                <div class="col-sm-6">
                    <input type="text" id="bid_price" name="bid_price" placeholder="Enter Your Bid" class="form-control input-frame" style="background-color: white; color: grey">
                    <input type="hidden" name="auctionID" value="<?php echo $auctionID; ?>">
                    <input type="hidden" name="auctionMaxCP" value="<?php echo $auctionMaxCP; ?>">
                    <input type="hidden" name="isWinner" value="<?php echo $isWinner; ?>">
                    
                </div>
    
                <input type="submit" class="btn btn-default item-button" value="Bid Now" name="submit" id="submit" style="background-color: blue; color: white"/>
                </div>
            </form>
            <?php endif; ?>

        
        <!-- rate the auction -->
        <div style="margin-top: 20px;">
            <?php if ($auctionStatus == 'Closed' && $isWinner == 1 && $has_session == true && $userID != $sellerID && $rowIsRated['count'] == 0): ?> <!-- change to 'Closed' -->
            <form action="ratings_form.php" method="POST">
                <div class="star-rating">
                    <input type="radio" id="5-stars" name="ratingValue" value="5" /><label for="5-stars" class="star">&#9733;</label>
                    <input type="radio" id="4-stars" name="ratingValue" value="4" /><label for="4-stars" class="star">&#9733;</label>
                    <input type="radio" id="3-stars" name="ratingValue" value="3" /><label for="3-stars" class="star">&#9733;</label>
                    <input type="radio" id="2-stars" name="ratingValue" value="2" /><label for="2-stars" class="star">&#9733;</label>
                    <input type="radio" id="1-star" name="ratingValue" value="1" /><label for="1-star" class="star">&#9733;</label>
                </div>
                <div class="row form-group">
                    <div class="col-sm-6">
                        <textarea id="ratingText" name="ratingText" placeholder="Leave your comment here..." class="form-control input-frame" rows="1" style="background-color: white; color: grey"></textarea>
                        <input type="hidden" name="auctionID" value="<?php echo $auctionID; ?>">
                        <input type="hidden" name="userID" value="<?php echo $userID; ?>"> <!-- Assuming userID is stored in session -->
                    </div>

                    <input type="submit" class="btn btn-default item-button" value="Submit Rating" name="submitRating" id="submitRating" style="background-color: blue; color: white"/>
                </div>
            </form>

            <?php endif; ?>
        </div>

        <!-- view bid history -->
        <div class="bid-history">
            <!-- Bid History Button -->
            <button id="bidHistoryBtn" class="btn btn-primary" style="background-color: blue; color: white">Bid History</button>
        </div>

        <!-- bid history cont. -->
        <div id="bidHistoryModal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <h3>Bid history</h3>
                <div id="bidHistoryTable">
                    <?php if ($bids_result && mysqli_num_rows($bids_result) > 0): ?>
                        <table>
                            <tr>
                                
                                <th>Bid Value</th>
                                <th>Date of Bid</th>
                            </tr>
                            <?php while ($bid = mysqli_fetch_assoc($bids_result)): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($bid['bidValue']); ?></td>
                                    <td><?php echo htmlspecialchars($bid['dateBid']); ?></td>
                                </tr>
                            <?php endwhile; ?>
                        </table>
                    <?php else: ?>
                        <p>No bids found.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- style settings; adopted from ChatGPT -->
        <style>
            .modal {
                display: none;
                position: fixed;
                z-index: 1;
                left: 0;
                top: 0;
                width: 100%;
                height: 100%;
                overflow: auto;
                background-color: rgb(0,0,0);
                background-color: rgba(0,0,0,0.4);
            }
            .modal-content {
                background-color: #fefefe;
                margin: 15% auto;
                padding: 20px;
                border: 1px solid #888;
                width: 80%;
            }
            .modal-content table th,
            .modal-content table td {
                padding: 10px; 
                text-align: left;
                border-bottom: 1px solid #ddd;
            }

            .modal-content table {
                border-collapse: separate;
                border-spacing: 10px 0; 
            }
            .close {
                color: #aaa;
                float: right;
                font-size: 28px;
                font-weight: bold;
            }
            .close:hover,
            .close:focus {
                color: black;
                text-decoration: none;
                cursor: pointer;
            }

            .star-rating {
                direction: rtl; 
                font-size: 0;
            }
            .star-rating input {
                display: none;
            }
            .star-rating label {
                font-size: 30px;
                padding: 0 5px;
                cursor: pointer;
            }
            .star-rating label:hover,
            .star-rating label:hover ~ label,
            .star-rating input:checked ~ label {
                color: blue;
            }
        </style>

        <script>
            var modal = document.getElementById("bidHistoryModal");
            var btn = document.getElementById("bidHistoryBtn");
            var span = document.getElementsByClassName("close")[0];

            btn.onclick = function() {
                modal.style.display = "block";
            }

            span.onclick = function() {
                modal.style.display = "none";
            }

            window.onclick = function(event) {
                if (event.target == modal) {
                    modal.style.display = "none";
                }
            }
        </script>

    </div>
</div>



<?php include_once("footer.php")?>


<?php include_once("header.php")?>
<?php require_once("database_connection.php")?>

<?php require("utilities.php")?>


<?php
// Establish connection with database
$connection = connectMAC();

$auctionID = 2; // to be dynamically adjusted 2 active 7 closed

$sql_auction = "SELECT a.auctionName, a.categoryType, a.categoryColor, a.categoryGender, a.categorySize, u.username, a.auctionDescription, a.auctionStartDate, a.auctionEndDate, a.auctionStartingPrice, MAX(b.bidValue) as auctionMaxCP, COUNT(b.auctionID) as auctionBidCount 
FROM Auctions a
JOIN Users u ON a.sellerID = u.userID
LEFT JOIN Bids b ON a.auctionID = b.auctionID
WHERE a.auctionID = '{$auctionID}'
GROUP BY a.auctionID, a.auctionName, a.categoryType, a.categoryColor, a.categoryGender, a.categorySize, u.username, a.auctionDescription, a.auctionStartDate, a.auctionEndDate, a.auctionStartingPrice";


$result = send_queryMAC($sql_auction);
$rows = mysqli_fetch_array($result);
//print_r($rows);

$auctionName = $rows["auctionName"];
$auctionDescription = $rows['auctionDescription'];
$sellerUsername = $rows['username'];
$auctionMaxCP = $rows['auctionMaxCP'];
$auctionStartingPrice = $rows['auctionStartingPrice'];
$auctionBidCount = $rows['auctionBidCount'];

$categoryType = $rows['categoryType'];
$categoryColor = $rows['categoryColor'];
$categoryGender = $rows['categoryGender'];

$auctionEndDate = $rows['auctionEndDate'];
$endDate = new DateTime($auctionEndDate);
$now = new DateTime();
if ($endDate > $now) {
  $auctionStatus = 'Active';
} else {
  $auctionStatus = 'Closed';
}

//if ($auctionBidCount === NULL || $auctionBidCount === 0 ) {
//  $auctionHighestCP = 0;
//}

$sql_bids = "SELECT * FROM Bids WHERE auctionID = '{$auctionID}' ORDER BY dateBid DESC";
$bids_result = mysqli_query($connection, $sql_bids);


$has_session = false;
$watching = false;

@$userID= $_SESSION['userID'];
if (@$_SESSION['logged_in']) {
    $has_session = true;
    //check if this item is in watchlist
    $sql_check_watchlist = "SELECT * FROM `Watchlists` WHERE buyerID = '{$userID}' AND auctionID = '{$auctionID}'";
    $res1 = send_queryMAC($sql_check_watchlist);
    $res2 = mysqli_fetch_array($res1);
    if ($res2) {
        $watching = true;
    }
}

?>

<div class="container">
        <div class="row">
            <div class="col-sm-6">
            <img src='images/item_images/the auctions/<?php echo $auctionID . '.png' ?>' class='img-rounded img-responsive item-img'>
            </div>



            <div class="col-sm-6">
            <div style="margin-top: 30px;">
                <div class="item-title" style="font-size: larger; font-weight: bold;"><?php echo $auctionName ?></div>


                <div class="item-seller">
                    <?php echo "Seller: " . $sellerUsername ?>
                </div>
                <div class="item-type">
                    <?php echo "Type: " . $categoryType ?>
                </div>
                <div class="item-color">
                    <?php echo "Colour: " . $categoryColor ?>
                </div>
                <div class="item-gender">
                    <?php echo "Gender: " . $categoryGender ?>
                </div>
                <div class="item-description">
                    <?php echo "Description: " . $auctionDescription ?>
                </div>
                <div class="item-count-bids">
                    <?php echo "Bid count: " . $auctionBidCount ?>
                </div>
                <div class="item-current-price">
                    <?php echo "Current highest bid: " . $auctionMaxCP ?>
                </div>

                <div class="item-status">
                    <?php echo "Status: " . $auctionStatus ?>
                </div>

                <div style="margin-top: 20px;"> </div>

                <?php if ($auctionStatus == 'Active'): ?>
                  <div id="watch_nowatch" <?php if ($has_session && $watching) echo ('style="display: none"'); ?>>
                      <button type="button" class="btn btn-outline-secondary item-button" onclick="addToWatchlist()" <?php if (!isset($_SESSION['logged_in'])) echo ('disabled'); ?>>+ Add to watchlist</button>
                  </div>
                  <div id="watch_watching" <?php if (!$has_session || !$watching) echo ('style="display: none"'); ?>>
                      <button type="button" class="btn btn-success item-button" disabled>Watching</button>
                      <button type="button" class="btn btn-danger item-button" onclick="removeFromWatchlist()">Remove watch</button>
                  </div>
                <?php endif; ?>


                <div style="margin-top: 20px;"> </div>
                  <?php if ($auctionStatus == 'Active'): ?>
                    <form action="place_bid.php" method="POST">
                      <div class="row form-group">
                        <div class="col-sm-6">
                          <input type="text" id="bid_price" name="bid_price" placeholder="Enter Your Bid" class="form-control input-frame">
                          <input type="hidden" name="auctionID" value="<?php echo $auctionID; ?>">
                          <input type="hidden" name="auctionMaxCP" value="<?php echo $auctionMaxCP; ?>">

                          
                        </div>
            
                        <input type="submit" class="btn btn-default item-button" value="Bid Now" name="submit" id="submit" style="background-color: blue; color: white;"/>
                      </div>
                    </form>
                  <?php endif; ?>

                <div style="margin-top: 20px;"> </div>

                <div class="bid-history">
                    <!-- Bid History Button -->
                    <button id="bidHistoryBtn" class="btn btn-primary" style="background-color: gray; color: white;">Bid History</button>
                </div>

                <!-- Bid History Modal -->
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
                        padding: 10px; /* Increase this value for more space */
                        text-align: left;
                        border-bottom: 1px solid #ddd;
                    }

                    .modal-content table {
                        border-collapse: separate;
                        border-spacing: 10px 0; /* Adjust horizontal and vertical spacing */
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


<script> 

function addToWatchlist(button) {
  console.log("These print statements are helpful for debugging btw");

  $.ajax('watchlist_funcs.php', {
    type: "POST",
    data: {functionname: 'add_to_watchlist', arguments: { auctionID: <?php echo $auctionID; ?> }},

    success: 
      function (obj, textstatus) {
        console.log("Success");
        var objT = obj.trim();
 
        if (objT == "success") {
          $("#watch_nowatch").hide();
          $("#watch_watching").show();
        }
        else {
          var mydiv = document.getElementById("watch_nowatch");
          mydiv.appendChild(document.createElement("br"));
          mydiv.appendChild(document.createTextNode("Add to watch failed. Try again later."));
        }
      },

    error:
      function (obj, textstatus) {
        console.log("Error");
      }
  });

} 

function removeFromWatchlist(button) {
  // This performs an asynchronous call to a PHP function using POST method.
  // Sends item ID as an argument to that function.
  $.ajax('watchlist_funcs.php', {
    type: "POST",
    data: {functionname: 'remove_from_watchlist', arguments: { auctionID: <?php echo $auctionID; ?> }},

    success: 
      function (obj, textstatus) {
        // Callback function for when call is successful and returns obj
        console.log("Success");
        var objT = obj.trim();
 
        if (objT == "success") {
          $("#watch_watching").hide();
          $("#watch_nowatch").show();
        }
        else {
          var mydiv = document.getElementById("watch_watching");
          mydiv.appendChild(document.createElement("br"));
          mydiv.appendChild(document.createTextNode("Watch removal failed. Try again later."));
        }
      },

    error:
      function (obj, textstatus) {
        console.log("Error");
      }
  }); 

}
</script>

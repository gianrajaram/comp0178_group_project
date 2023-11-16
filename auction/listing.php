
<?php include_once("header.php")?>
<?php include_once("database_connection.php")?>
<?php require("utilities.php")?>

<?php
  function connectm()
  {
    $servernamem = "127.0.0.1";
    $usernamem = "AuctionProject";
    $passwordm = "london2023";
    $dbnamem = "Website_auction";
    $port = 3306;

    $connection = mysqli_connect($servernamem, $usernamem, $passwordm, $dbnamem, $port);
    if ($connection->connect_error) {
      die("Connection failed: " . $connection->connect_error);
    }
    //echo "Connected successfully";
    return $connection;
  }
  function send_querym($query)
{   
    $connection = connectm();
    
    // send query
    $result = mysqli_query($connection, $query);
    if (!$result)
    {
        die('Error making select users query'.mysqli_error($connection));
    }  

    //close connection and return result query
    mysqli_close($connection);
    return $result; // mysqli object
}

function query_database($connection, $sql)
{
    $result = mysqli_query($connection, $sql);
    if (!$result) {
        throw new Exception("Preparation failed: " . mysqli_error($connection));
        die('Error making query' . mysqli_error($connection)); #preserve original behavior even if exception is handled.
    }
    mysqli_close($connection);
    return $result;
}
function get_bids_by_items($auctionID)
{
    $sql_bid_by_items = "SELECT DISTINCT bidValue, a.username, dateBid 
                FROM Users a, Bids b 
                where b.buyerID = a.userID
                and b.auctionID = '{$auctionID}'";
    $connection = connectm();
    $resultsm = query_database($connection, $sql_bid_by_items);
    print_r($resultsm);
    return $resultsm;
}



  $auctionID = 2;
  

  $connectionm = connectm();

  $sql_auction = "SELECT auctionName, categoryType, categoryColor,categoryGender,categorySize, username, auctionDescription, auctionStatus, auctionStartDate, auctionEndDate, auctionStartingPrice,auctionCurrentHighestBid 
  FROM Auctions a, Users u
  where a.sellerID = u.userID
  and a.auctionID = '{$auctionID}'";

  $result = mysqli_query($connectionm, $sql_auction);
  $rows = $result->fetch_assoc();
  //print_r($rows);
  $auctionName = $rows["auctionName"];
  $auctionDescription = $rows['auctionDescription'];
  $sellerUsername = $rows['username'];
  $auctionCurrentPrice = $rows['auctionStartingPrice'];
  $bid_price =  $rows['auctionStartingPrice']; //isset($rows['max_price']) ? $rows['max_price'] : "  No Bid Now";
  $categoryType = $rows['categoryType'];
  $categoryColor = $rows['categoryColor'];
  $categoryGender = $rows['categoryGender'];
  $auctionEndDate = $rows['auctionEndDate'];
  $time = strtotime($auctionEndDate);
  $nowtime = time();

  // Calculate time to auction end:
  $now = new DateTime();

  $auctionEndDate = new DateTime($auctionEndDate);
  
  if ($now > $auctionEndDate) {
    $time_to_end = date_diff($now, $auctionEndDate);
    $time_remaining = ' (in ' . display_time_remaining($time_to_end) . ')';
  }

  $has_session = false;
  $watching = false;

  @$userID= $_SESSION['userID'];
  if (@$_SESSION['logged_in']) {
      $has_session = true;
      //check if this item is in watchlist
      $sql_check_watchlist = "SELECT * FROM `Watchlists` WHERE buyerID = '{$userID}' AND auctionID = '{$auctionID}'";
      $res = send_querym($sql_check_watchlist)->fetch_assoc();
      if ($res) {
          $watching = true;
      }
  }


?>


<!--- good up to here -->



<div class="container">
        <div class="row">
            <div class="col-sm-6">

                <img src='images/item_images/the auctions/<?php echo $auctionID . '.png' ?>' class='img-rounded img-responsive item-img'>
                <div id="watch_nowatch" <?php if ($has_session && $watching) echo ('style="display: none"'); ?>>
                    <button type="button" class="btn btn-outline-secondary item-button" onclick="addToWatchlist()" <?php if (!isset($_SESSION['logged_in'])) echo ('disabled'); ?>>+ Add to watchlist</button>
                </div>
                <div id="watch_watching" <?php if (!$has_session || !$watching) echo ('style="display: none"'); ?>>
                    <button type="button" class="btn btn-success item-button" disabled>Watching</button>
                    <button type="button" class="btn btn-danger item-button" onclick="removeFromWatchlist()">Remove watch</button>
                </div>


                <button class="btn btn-primary btn-lg" data-toggle="modal" data-target="#myModal" style="margin-top : 20px;">
                    Bid History
                </button>

                <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                                    &times;
                                </button>
                                <h4 class="modal-title" id="myModalLabel">
                                    Bid History
                                </h4>
                            </div>
                            <div class="modal-body">
                                <?php
                                $bid_items = get_bids_by_items($auctionID);
                                //display past bids
                                ?>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

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

                <div class="item-current-price">
                    <?php echo "Current highest bid: " . $auctionCurrentPrice ?>
                </div>

                <div style="margin-top: 20px;">

                    <div class="col-sm-6">
                        <form action="place_bid.php" method="POST">

                            <div class="row form-group">
                                <label for="auctionID" class="col-sm-4 col-form-label text-right item-id-label">ID:</label>
                                <input type="text" class="form-control col-sm-8 item-id-input" id="auctionID" name="auctionID" value="<?php echo $auctionID ?>" placeholder="<?php echo $auctionID ?>" readonly="readonly">
                            </div>

                            <div class="row form-group">
                                <div class="col-sm-6">
                                    <input type="text" id="bid_price" name="bid_price" placeholder="Enter Your Bid" class="form-control input-frame" <?php if (!isset($_SESSION['logged_in'])) echo ('disabled'); ?>>
                                </div>
                                <input type="submit" class="btn btn-default item-button" value="Bid Now" name="submit" id="submit" <?php if (!isset($_SESSION['logged_in'])) echo ('disabled'); ?> />
                            </div>

                        </form>

                    </div>

                </div>
            </div>
        </div>




<?php include_once("footer.php")?>


<script> 

function addToWatchlist(button) {
  console.log("These print statements are helpful for debugging btw");

  $.ajax('watchlist_funcs.php', {
    type: "POST",
    data: {functionname: 'add_to_watchlist', arguments: [<?php echo($auctionID);?>]},

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
    data: {functionname: 'remove_from_watchlist', arguments: [<?php echo($auctionID);?>]},

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
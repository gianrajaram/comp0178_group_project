<?php include_once("header.php")?>
<?php require_once("database_connection.php")?>
<?php require("utilities.php")?>

<div class="container">

<h2 class="my-3">My listings</h2>

<?php
//session_start();

$connection = connect();

if(isset($_SESSION['userID'])) {
  // Get the seller ID
  $userID = $_SESSION['userID'];
  //$userID = 2;
  $query = "SELECT * FROM auctions WHERE sellerID = $userID";
  $result = send_query($query);

} else {
  header("Location: login.php");
  exit();
}
  
?>

<div class="container my-5">


  <ul class="list-group">


    <?php
    // Check if there are auctions
    if (mysqli_num_rows($result) > 0) {
      while($row = mysqli_fetch_assoc($result)) {  

        //var_dump($row);     
        $auctionID = $row["auctionID"];
        $query_bid = "SELECT a.auctionName, a.categoryType, a.categoryColor, 
                            a.categoryGender, a.categorySize, u.username, 
                            a.auctionDescription, a.auctionStartDate, a.auctionEndDate, 
                            a.auctionStartingPrice, MAX(b.bidValue) as auctionMaxCP 
        FROM Auctions a
        JOIN Users u ON a.sellerID = u.userID
        LEFT JOIN Bids b ON a.auctionID = b.auctionID
        WHERE a.auctionID = '{$auctionID}'
        GROUP BY a.auctionID, a.auctionName, a.categoryType, a.categoryColor, 
                  a.categoryGender, a.categorySize, u.username, 
                  a.auctionDescription, a.auctionStartDate, a.auctionEndDate, a.auctionStartingPrice";
        $result_bid = send_query($query_bid);
        $row_bid = mysqli_fetch_assoc($result_bid);
        $auctionMaxCP = $row_bid['auctionMaxCP'];
        if ($auctionMaxCP == 0) {
          $auctionMaxCP = $row["auctionStartingPrice"];
        }

        $query_num = "SELECT COUNT(*) AS numberOfBids FROM `bids` WHERE auctionID = $auctionID";
        $result_num = send_query($query_num);
        $row_num = mysqli_fetch_assoc($result_num);
        $num_bids = $row_num['numberOfBids'];
        // Display auction information
        print_mylisting_li(
          $row["auctionID"],
          $row["auctionName"],
          $row["auctionDescription"],
          $auctionMaxCP, // Need to use Highest bid submitted if any otherwise starting price - create variable $highest_bid with sql query fetching the highest bid for the auction 
          $num_bids, // Need to create a $num_bids variable, make a query to count bids and use it here
          new DateTime($row["auctionEndDate"]),
          $row["auctionPicture"],
        );
      }
    } else {
      echo '<p>No auctions found.</p>';
    }
      ?>
    </ul>
  </div>



<?php include_once("footer.php")?>
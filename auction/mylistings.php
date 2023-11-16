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





  // This page is for showing a user the auction listings they've made.
  // It will be pretty similar to browse.php, except there is no search bar.
  // This can be started after browse.php is working with a database.
  // Feel free to extract out useful functions from browse.php and put them in
  // the shared "utilities.php" where they can be shared by multiple files.
  
  
  // TODO: Check user's credentials (cookie/session).
  
  // TODO: Perform a query to pull up their auctions.
  
  // TODO: Loop through results and print them out as list items.
  
?>


<div class="container my-5">

  <h2 class="my-3">Your Auctions</h2>

  <ul class="list-group">


    <?php
    // Check if there are auctions
    if (mysqli_num_rows($result) > 0) {
      while($row = mysqli_fetch_assoc($result)) {
        // Display auction information
        print_listing_li(
          $row["item_id"],
          $row["auctionName"],
          $row["auctionDescription"],
          $row["auctionStartingPrice"],
          $row["auctionBidCount"],
          new DateTime($row["auctionEndDate"])
        );
      }
    } else {
      echo '<p>No auctions found.</p>';
    }
      ?>
    </ul>
  </div>



<?php include_once("footer.php")?>
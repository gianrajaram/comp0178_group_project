<?php include_once("header.php")?>
<?php require_once("database_connection.php");
require_once("utilities.php");
?>

<?php

// establish connection with the database
$connection = connectMAC();

// function for removing an item from the watchlist
if (isset($_POST['watchlistremove'])) {
  $auctionID = $_POST['auctionID'];
  $userID = $_SESSION['userID'];

  $query = "DELETE FROM Watchlists WHERE buyerID = '$userID' AND auctionID = '$auctionID'";
  send_queryMAC($query);
  echo "<script>alert('Successfully removed from the watchlist.');</script>";
  echo "<script>window.location.href='my_wishlist.php';</script>";
}
?>


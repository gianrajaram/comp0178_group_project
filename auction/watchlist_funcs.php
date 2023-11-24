<?php include_once("header.php")?>
<?php require_once("database_connection.php");
require_once("utilities.php");
?>

<?php

//  connection w database
$connection = connectMAC();

if (isset($_POST['watchlistsubmit'])) {
  $auctionID = $_POST['auctionID'];
  $userID = $_SESSION['userID'];

  $query = "INSERT INTO Watchlists (buyerID, auctionID) VALUES ('$userID','$auctionID')";
  send_queryMAC($query);
  echo "<script>alert('Successfully added to the watchlist.');</script>";
  echo "<script>window.location.href='my_wishlist.php';</script>";
}
?>


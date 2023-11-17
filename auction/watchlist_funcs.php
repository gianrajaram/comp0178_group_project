<?php
//session_start();
//include_once("header.php");
require_once("database_connection.php");
//require_once("utilities.php");

$connection = connectMAC();

//if (!isset($_POST['functionname']) || !isset($_POST['auction'])) {
//  echo 'Invalid request';
//  return;
//}

// Extract arguments from the POST variables:
//$item_id = $_POST['arguments'];

$auctionID = $_POST['auction'];
$userID = $_SESSION['userID'];

$res = 'fail';
if ($_POST['functionname'] == "add_to_watchlist") {
  //  Update database and return success/failure.
  $query = "INSERT INTO Watchlists (buyerID, auctionID) VALUES ('$userID','$auctionID')";
    try {
      send_queryMAC($query);
      $res = "success";
    } catch (Exception $e) {
      $res = 'Dupulicate data.';
    }

  //$res = "success";
}
else if ($_POST['functionname'] == "remove_from_watchlist") {
  // Update database and return success/failure.
  $query2 = "DELETE FROM Watchlists WHERE buyerID = '$userID' AND auctionID = '$auctionID'";
    try {
      send_queryMAC($query2);
      $res = "success";
    } catch (Exception $e) {
      $res = 'Error removing from watchlist.';
    }
    //$res = "success";
}

// Note: Echoing from this PHP function will return the value as a string.
// If multiple echo's in this file exist, they will concatenate together,
// so be careful. You can also return JSON objects (in string form) using
// echo json_encode($res).
echo $res;

?>

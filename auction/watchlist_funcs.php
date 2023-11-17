<?php
session_start();
include_once("header.php");
require_once("database_connection.php");
require_once("utilities.php");

$connection = connectMAC();

if (!isset($_POST['functionname']) || !isset($_POST['arguments']['auctionID'])) {
    echo 'Invalid request';
    exit;
}

$auctionID = $_POST['arguments']['auctionID'];
$userID = isset($_SESSION['buyerID']) ? $_SESSION['buyerID'] : null;

if (is_null($userID)) {
    echo 'User ID not set in session.';
    exit;
}

$res = 'fail';
$query = '';
if ($_POST['functionname'] == "add_to_watchlist") {
    $query = "INSERT INTO Watchlists (buyerID, auctionID) VALUES ('$userID','$auctionID')";
} else if ($_POST['functionname'] == "remove_from_watchlist") {
    $query = "DELETE FROM Watchlists WHERE buyerID = '$userID' AND auctionID = '$auctionID'";
}

if (!empty($query)) {
    $result = send_queryMAC($query);
    if ($result === FALSE) {
        $res = 'Query failed: ' . mysqli_error($connection);
    } else {
        $res = "success";
    }
}

echo $res;
?>

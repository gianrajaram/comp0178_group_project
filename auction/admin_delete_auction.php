<?php require_once("database_connection.php");
require_once("utilities.php");
require_once("header.php")?>


<?php


// Extract $_POST variable from admin delete user form
if (isset($_POST['auctionID'])) {
    $auctionID = $_POST['auctionID'];
    $query= "DELETE FROM Auctions WHERE auctionID = '$auctionID'";
    send_query($query);
    echo "<script>alert('Auction was deleted.');</script>";
    echo "<script>window.location.href='admin_user_auction.php';</script>";
}
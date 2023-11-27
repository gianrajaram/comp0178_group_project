

<?php include_once("header.php")?>
<?php require_once("database_connection.php");
require_once("utilities.php");
?>


<?php
// establish connection with the database
$connection = connectMAC();

if (isset($_POST['submitRating'])) {
    
    $auctionID = $_POST['auctionID'];
    $userID = $_POST['userID'];

    $auctionID = $_POST['auctionID'];
    $userID = $_POST['userID']; 
    $ratingValue = $_POST['ratingValue'];
    // mysqli_real_escape_string() adapted from ChatGPT
    $ratingText = mysqli_real_escape_string($connection, $_POST['ratingText']);

    // if the rating text box is empty, set it to "No comment."
    $ratingTextTrim = trim($ratingText);
    if (empty($ratingTextTrim)) {
        $ratingText = "No comment.";
    }
        
    $query = "INSERT INTO Ratings (auctionID, ratingValue, ratingText, buyerID) VALUES ('$auctionID', '$ratingValue', '$ratingText', '$userID')";

    send_queryMAC($query);
    echo "<script>alert('Successfully submitted the rating.');</script>";
    echo "<script>window.location.href='listing.php?item_id=" . htmlspecialchars($auctionID) . "';</script>";
    }
?>
<?php include_once("header.php");
require_once("database_connection.php"); 
require_once("utilities.php")?>

<div class="container my-5">


<?php
$connection = connect();
// have the current date saved
$currentDateTime = date('Y-m-d\TH:i');
// save the folder wherre the images are stored
$imageFolder = 'auction/images/';


// check if each variable is filled out:

// Auction Title
if(empty($_POST["auctionTitle"])){
    alert_message_auction($message = 'Please provide a title for the item');
    exit;
} else {
    $auctionName = mysqli_real_escape_string($connection, $_POST['auctionTitle']);
}

// Auction Description
if (!empty($_POST["auctionDetails"])) {
    $auctionDescription = mysqli_real_escape_string($connection, $_POST['auctionDetails']);
} else {
    $auctionDescription = null;
    /*alert_message_auction($message = 'Please provide a description for the item');
    exit;*/
}

// Clothing category
if(empty($_POST["auctionClothCategory"]) || $_POST["auctionClothCategory"] === 'Choose...'){
    alert_message_auction($message = 'Please provide a valid category for the item');
    exit;
} else {
    $categoryType = mysqli_real_escape_string($connection, $_POST['auctionClothCategory']);
}

// Color category
if(empty($_POST["auctionColorCategory"]) || $_POST["auctionColorCategory"] === 'Choose...'){
    alert_message_auction($message = 'Please provide a valid color for the item');
    exit;
} else {
    $categoryColor = mysqli_real_escape_string($connection, $_POST['auctionColorCategory']);
}

// Gender category
if(empty($_POST["auctionGenderCategory"]) || $_POST["auctionGenderCategory"] === 'Choose...'){
    alert_message_auction($message = 'Please provide which gender this item is appropriate for');
    exit;
} else {
    $categoryGender = mysqli_real_escape_string($connection, $_POST['auctionGenderCategory']);
}

// Size category
if(empty($_POST["auctionSizeCategory"]) || $_POST["auctionSizeCategory"] === 'Choose...'){
    alert_message_auction($message = 'Please provide a valid size for the item');
    exit;
} else {
    $categorySize = mysqli_real_escape_string($connection, $_POST['auctionSizeCategory']);
}

// Starting Price
if (empty($_POST["auctionStartPrice"])) {
    alert_message_auction($message = 'Please provide a starting price for the auction');
    exit;
} else {
    $auctionStartingPrice = floatval($_POST['auctionStartPrice']);
}

// Reserve Price
if (!empty($_POST["auctionReservePrice"])) {
    $auctionReservePrice = floatval($_POST['auctionReservePrice']);
} else {
    $auctionReservePrice = null;
    /*alert_message_auction($message = 'Please provide a reserve price for the auction');
    exit;*/
}

// Start Date
if (empty($_POST["auctionStartDate"])) {
    alert_message_auction($message = 'Please provide a start date for the auction');
    exit;
} else {
    $auctionStartDate = new DateTime($_POST['auctionStartDate']);

    $auctionStatus = ($auctionStartDate > $currentDateTime) ? 'Closed' : 'Running';
}

// End Date
if (empty($_POST["auctionEndDate"])) {
    alert_message_auction($message = 'Please provide an end date for the auction');
    exit;
} else {
    $auctionEndDate = new DateTime($_POST['auctionEndDate']);
    if ($auctionEndDate <= $currentDateTime) {
    alert_message_auction($message = "End date must be in the future");
    exit; 
    }
} 

// Image upload

$dir = 'C:\wamp64\www\comp0178_group_project';

if (!empty($_FILES['auctionImage']['name'])) {
    $originalFilename = $_FILES['auctionImage']['name'];
    $tempFilePath = $_FILES['auctionImage']['tmp_name'];
    $fileExtension = pathinfo($originalFilename, PATHINFO_EXTENSION);

    //generate the unique filename
    $newFilename = generateUniqueFilename($imageFolder);

    move_uploaded_file($tempFilePath, $dir . $newFilename);

    //Save the pathway in the database
    $auctionPicture = mysqli_real_escape_string($connection, $newFilename);
} else {
    $auctionPicture = null;
    /*alert_message_auction($message = 'Please provide an image of the item');
    exit;*/
}

// Get the seller ID
$sellerID = $_SESSION['userID'];


$query = "INSERT INTO auctions 
          (auctionName, auctionDescription, categoryType, categoryColor, categoryGender, categorySize, 
          auctionStartingPrice, auctionReservePrice, auctionStartDate, auctionEndDate, auctionPicture, sellerID)
          VALUES 
          ('$auctionName', '$auctionDescription', '$categoryType', '$categoryColor', '$categoryGender', 
          '$categorySize', '$auctionStartingPrice', '$auctionReservePrice', '".$auctionStartDate->format('Y-m-d H:i:s')."',
          '".$auctionEndDate->format('Y-m-d H:i:s')."', '$auctionPicture', '$sellerID')";

send_query($query);




// If all is successful, let user know.
echo('<div class="text-center">Auction successfully created! <a href="mylistings.php">View your new listing.</a></div>');


?>

</div>


<?php include_once("footer.php")?>
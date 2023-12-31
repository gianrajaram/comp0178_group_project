<?php include_once("header.php");
require_once("database_connection.php"); 
require_once("utilities.php")?>

<div class="container my-5">


<?php
$connection = connect();
// have the current date saved
$currentDateTime = new DateTime();
// save the folder wherre the images are stored
$imageFolder = 'images';


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
    $auctionReservePrice = $auctionStartingPrice;
    /*alert_message_auction($message = 'Please provide a reserve price for the auction');
    exit;*/
}

// Start Date
if (empty($_POST["auctionStartDate"])) {
    alert_message_auction($message = 'Please provide a start date for the auction');
    exit;
} else {
    $auctionStartDate = new DateTime($_POST['auctionStartDate']);
    if (($auctionStartDate->format('Y-m-d')) < ($currentDateTime->format('Y-m-d'))) {
        alert_message_auction($message = "Start date must be in the future");
        exit; 
    }
    $auctionStatus = ($auctionStartDate > $currentDateTime) ? 'Closed' : 'Running';
}

// End Date
if (empty($_POST["auctionEndDate"])) {
    alert_message_auction($message = 'Please provide an end date for the auction');
    exit;
} else {
    $auctionEndDate = new DateTime($_POST['auctionEndDate']);
    if ($auctionEndDate <= $auctionStartDate) {
    alert_message_auction($message = "End date must be after start date");
    exit; 
    }
} 

// Image upload

$dir = 'C:/wamp64/www/comp0178_group_project/auction/';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!empty($_FILES['auctionImage']['name'])) {
        $originalFilename = $_FILES['auctionImage']['name'];
        $tempFilePath = $_FILES['auctionImage']['tmp_name'];
        $fileExtension = strtolower(pathinfo($originalFilename, PATHINFO_EXTENSION));


        //generate the unique filename
        $newFilename = generateUniqueFilename($imageFolder, $fileExtension);
        

        move_uploaded_file($tempFilePath, $dir . $newFilename );

        //Save the pathway in the database
        $auctionPicture = mysqli_real_escape_string($connection, $newFilename);
    } else {
        echo 'File upload error: ' . $_FILES['auctionImage']['error'];
        //$auctionPicture = null;
        alert_message_auction($message = 'Please provide an image of the item');
        exit;
    }
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
echo "<script>alert('Auction successfully created!');</script>";
echo "<script>window.location.href='mylistings.php';</script>";

// send successful auction creation email
$querySeller = "SELECT * FROM Users WHERE userID = '$sellerID'";
$resultSeller = send_query($querySeller);
$rowSeller = mysqli_fetch_assoc($resultSeller);
$emailSeller = $rowSeller['userEmail'];
$firstNameSeller = $rowSeller['userFirstName'];
$name = "ReWear Auctions"; //sender’s name
$email = "UCL2023DatabasesAuctionReWear@gmail.com"; //sender’s e-mail address
$recipient = $emailSeller; //recipient
$mail_body= "$firstNameSeller, you successfully created and auction with the Title $auctionName and following description: $auctionDescription."; //mail body
$subject = "ReWear Auctions - Auction submission successful!"; //subject
$header = "From: ". $name . " <" . $email . ">\r\n";
mail($recipient, $subject, $mail_body, $header);

?>

</div>


<?php include_once("footer.php")?>
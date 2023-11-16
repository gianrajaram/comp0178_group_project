<?php require_once("database_connection.php");
require_once("utilities.php");
require_once("header.php")?>


<?php
// Establish connection with database
$connection = connect();


// Extract $_POST variable from admin delete user form
if (isset($_POST['userID'])) {
    $userID = $_POST['userID'];
    $userIsActive = $_POST['userIsActive'];
    if ($userIsActive == "Activated") {
    $query= "UPDATE Users SET userIsActive = 'Deactivated' WHERE userID = '$userID'";
    echo "<script>alert('Profile is deactivated.');</script>";
}
    elseif ($userIsActive == "Deactivated"){
        $query= "UPDATE Users SET userIsActive = 'Activated' WHERE userID = '$userID'";
        echo "<script>alert('Profile is activated.');</script>";
    }
    send_query($query);
    echo "<script>window.location.href='admin_user_overview.php';</script>";
}
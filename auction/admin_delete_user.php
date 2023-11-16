<?php require_once("database_connection.php");
require_once("utilities.php");
require_once("header.php")?>


<?php
// Establish connection with database
$connection = connect();


// Extract $_POST variable from admin delete user form
if (isset($_POST['userID'])) {
    $userID = $_POST['userID'];
    $query= "DELETE FROM Users WHERE userID = '$userID'";
    send_query($query);
    echo "<script>alert('Profile was deleted.');</script>";
    echo "<script>window.location.href='admin_user_overview.php';</script>";
}
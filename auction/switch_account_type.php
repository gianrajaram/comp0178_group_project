<?php require_once("database_connection.php");
require_once("utilities.php")?>

<?php

session_start();
// Note current type of account and the one to switch to
$current_account_type = $_SESSION['account_type'];
$current_username = $_SESSION['username'];
if ($current_account_type == "Buyer") {
    $new_account_type = "Seller";    
} 
else {
    $new_account_type = "Buyer";
}
// Take new account type in global session variable and update the database with the new account type the user has used last
$_SESSION['account_type'] = $new_account_type;
$query_update_account_type = "UPDATE Users SET userAccountType = '$new_account_type' WHERE username ='$current_username'";
$result_update_account_type = send_query($query_update_account_type);


// Redirect to index
echo "<script>alert('You switched to $new_account_type view');</script>";
echo "<script>window.location.href='index.php';</script>";

?>
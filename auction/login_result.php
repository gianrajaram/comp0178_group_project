<?php require_once("database_connection.php");
require_once("utilities.php")?>

<?php
// Establish connection with database
$connection = connect();

session_start();


// Extract $_POST variables, check they're OK
// Username
if(empty($_POST["usernameLogin"])){
    alert_message_login($message = 'Please provide a username');
    exit;
}
else{
    $username = mysqli_real_escape_string($connection,$_POST["usernameLogin"]);
}

// Password
if(empty($_POST["passwordLogin"])){
    alert_message_login($message = 'Please provide a password.');
    exit;
}
else{
    $password = mysqli_real_escape_string($connection,$_POST["passwordLogin"]);
}
// Acount type
if(empty($_POST["accountType"]) && ($_POST["usernameLogin"] == "admin")){
    $accountType = "Admin";
}
else if (empty($_POST["accountType"]) && (!($_POST["usernameLogin"] == "admin"))){
    alert_message_login($message = 'Please choose an account type.');
    exit;
}
else{
    $accountType = $_POST["accountType"];
}
// Check email and password against database if they exist there
// Notify user of success/failure and redirect/give navigation options.
$query_check_login = "SELECT * FROM Users WHERE username = '$username' AND userpassword = SHA('$password')";
$result_check_login = send_query($query_check_login);
if (mysqli_num_rows($result_check_login) == 0){
    alert_message_login($message = 'No record of login details. Try again!');
    exit;
}
else if (mysqli_num_rows($result_check_login) == 1){
    $query_update_account_type = "UPDATE Users SET userAccountType = '$accountType' WHERE username ='$username'";
    $result_update_account_type = send_query($query_update_account_type);
    $query_updated_user_details = "SELECT * FROM Users WHERE username = '$username'";
    $result_updated_user_details = send_query($query_updated_user_details);
    $row = mysqli_fetch_array($result_updated_user_details);
    $_SESSION['logged_in'] = true;
    $_SESSION['username'] = $username;
    $_SESSION['account_type'] = $row['userAccountType'];
    $_SESSION['userID'] = $row['userID'];
    echo "<script>alert('Login is successful!');</script>";
    echo "<script>window.location.href='browse.php';</script>";
}
else{
    $_SESSION['logged_in'] = false;
    alert_message_login($message = 'Error in checking your credentials! '); // this error should not appear as we should not have same usernames in the database  
    exit;
}
?>
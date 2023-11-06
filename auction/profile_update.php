<?php require_once("database_connection.php");
require_once("utilities.php");
require_once("header.php")?>


<?php
// Establish connection with database
$connection = connect();

// Extract current profile attributes
$profile_row = user_profile();
$profile_username = user_profile_username();


// Extract $_POST variables from profile change form
if (isset($_POST['user_profile_change'])) {
    // First name - check if different and update
    if (!($_POST["firstNameProfile"] == $profile_row['userFirstName'])){
        $new_firstName = mysqli_real_escape_string($connection,$_POST["firstNameProfile"]);
        $query_update = "UPDATE Users SET userFirstName = '$new_firstName' WHERE username = '$profile_username'";
        send_query($query_update);
    }
    // Last name - check if different and update
    if (!($_POST["lastNameProfile"] == $profile_row['userLastName'])){
        $new_lastName = mysqli_real_escape_string($connection,$_POST["lastNameProfile"]);
        $query_update = "UPDATE Users SET userlastName = '$new_lastName' WHERE username = '$profile_username'";
        send_query($query_update);
    }
    // Address - check if different and update
    if (!($_POST["AddressProfile"] == $profile_row['userAddress'])){
        $new_Address = mysqli_real_escape_string($connection,$_POST["AddressProfile"]);
        $query_update = "UPDATE Users SET userAddress = '$new_Address' WHERE username = '$profile_username'";
        send_query($query_update);
    }    
    // Gender - check if different and update
    if (!($_POST["GenderProfile"] == $profile_row['userGender'])){
        $new_Gender = mysqli_real_escape_string($connection,$_POST["GenderProfile"]);
        $query_update = "UPDATE Users SET userGender = '$new_Gender' WHERE username = '$profile_username'";
        send_query($query_update);
    }    
    echo "<script>alert('Profile was updated successfully.');</script>";
    echo "<script>window.location.href='user_profile.php';</script>";
}
   
?>
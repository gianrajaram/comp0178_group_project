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
    // Password - check if set, correct and update
    if ($_POST['NewPassword'] != "" && $_POST['ConfirmNewPassword'] != "" ) {
        $new_password = mysqli_real_escape_string($connection,$_POST["NewPassword"]);
        $confirm_new_password = mysqli_real_escape_string($connection,$_POST["ConfirmNewPassword"]);
        $list_common_passwords =["password", "password1", "admin", "123456789", "123abc", "password123", "123456", "guest"];
        $min_length_password = 8; // set min length of password
        
        #check new password validity
        if (!($new_password == $confirm_new_password)) {
            alert_message_registration($message = 'Password does not match password confirmation.');   
            exit;
        }
        else{
            if (in_array($new_password, $list_common_passwords) || (strlen($new_password) < $min_length_password)){
                alert_message_registration($message = 'Choose a stronger password (min. 8 characters)'); 
                exit;   
            }
            else{
                $password = mysqli_real_escape_string($connection,$_POST["NewPassword"]);
                $query_update = "UPDATE Users SET userPassword = SHA('$new_password') WHERE username = '$profile_username'";
                send_query($query_update);
            }
        }
    }
    elseif (($_POST['NewPassword']) != "" && $_POST['ConfirmNewPassword'] == ""){
            echo "<script>alert('Please confirm password.');</script>";
            echo "<script>window.location.href='user_profile.php';</script>";
        }         
    echo "<script>alert('Profile was updated successfully.');</script>";
    echo "<script>window.location.href='user_profile.php';</script>";
}
   
?>
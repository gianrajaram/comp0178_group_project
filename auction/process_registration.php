<?php require_once("database_connection.php");
require_once("utilities.php")?>


<?php
// Establish connection with database
$connection = connect();

// Extract $_POST variables - carry out checks in between for correct and secure input 
if (isset($_POST['register'])) {
    // Email - check if in correct format
    if (filter_var($_POST["emailReg"], FILTER_VALIDATE_EMAIL)){
        $emailReg = mysqli_real_escape_string($connection,$_POST["emailReg"]);
    }
    else{
        alert_message_registration($message = 'Email is NOT a valid email address');
        exit;
    }

    // Username
    if(empty($_POST["usernameReg"])){
        alert_message_registration($message = 'Username is not a valid entry.');
        exit;
    }
    else{
        $username = mysqli_real_escape_string($connection,$_POST["usernameReg"]);
    }

    // Password
    $submitted_password =  mysqli_real_escape_string($connection,$_POST["passwordReg"]);
    $submitted_passwordConfirmation =  mysqli_real_escape_string($connection,$_POST["passwordConfirmation"]);
    $list_common_passwords =["password", "password1", "admin", "123456789", "123abc", "password123", "123456", "guest"];
    $min_length_password = 8; // set min length of password

    if (!($submitted_password == $submitted_passwordConfirmation)) {
        alert_message_registration($message = 'Password does not match password confirmation.');   
        exit;
    }
    else{
        if (in_array($submitted_password, $list_common_passwords) || (strlen($submitted_password) < $min_length_password))
        {
            alert_message_registration($message = 'Choose a stronger password (min. 8 characters)'); 
            exit;   
        }
        else
        {
            $password = mysqli_real_escape_string($connection,$_POST["passwordReg"]);
        }
    }

    //Other less important attributes - no rules and not mandatory to be filled in
    $firstName = mysqli_real_escape_string($connection,$_POST["firstName"]);
    $lastName = mysqli_real_escape_string($connection,$_POST["lastName"]);
    $address = mysqli_real_escape_string($connection,$_POST["Address"]);
    $telephone = mysqli_real_escape_string($connection,$_POST["Telephone"]);
    $selectedGender = $_POST["Gender"]; // Gender is default prefer not to say  
}
else{
    alert_message_registration($message = 'Unsuccessful registration form submission. Try again!'); 
    exit;  
}

// Check email and username against database if they already exists
    $query_check_registration = "SELECT * FROM Users WHERE username = '$username' OR userEmail = '$email'";
    $result_check_registration = send_query($query_check_registration);
    if (mysqli_num_rows($result_check_registration) == 0){
        $query_register = "INSERT INTO Users (userEmail, username, userPassword, userFirstName, userLastName, userAddress, userTel, userGender)".
        "VALUES ('$emailReg', '$username', SHA('$password'), '$firstName', '$lastName', '$address', '$telephone', '$selectedGender')";
        send_query($query_register);
        echo "<script>alert('Registration is successful! You can now log in.');</script>";
        echo "<script>window.location.href='login.php';</script>";

        // send successful registration email
        $name = "ReWear Auctions"; //sender’s name
        $email = "UCL2023DatabasesAuctionReWear@gmail.com"; //sender’s e-mail address
        $recipient = $emailReg; //recipient
        $mail_body= "$firstName, you successfully registered at ReWear Auctions! \n Your username is $username. Next step is to log in."; //mail body
        $subject = "ReWear Auctions - Registration successful!"; //subject
        $header = "From: ". $name . " <" . $email . ">\r\n";
        mail($recipient, $subject, $mail_body, $header);
    }
    else{
       alert_message_registration($message = 'Email/Username is already in the database.');
       exit;
}
    
?>
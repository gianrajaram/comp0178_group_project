<?php require_once("database_connection.php")?>

<?php
// Establish connection with database
$connection = connect();

// Extract $_POST variables - carry out checks in between for correct and secure input 
// Email
if (filter_var($_POST["email"], FILTER_SANITIZE_EMAIL))
{
    $email = mysqli_real_escape_string($connection,$_POST["email"]);
}
else
{
    echo "email is NOT a valid email address";
}

// Username
$username = mysqli_real_escape_string($connection,$_POST["username"]);

// Password
$submitted_password =  mysqli_real_escape_string($connection,$_POST["password"]);
$submitted_passwordConfirmation =  mysqli_real_escape_string($connection,$_POST["passwordConfirmation"]);
$list_common_passwords =["password", "password1", "admin", "123456789", "123abc", "password123", "123456", "guest"];
$min_length_password = 8; // set min length of password

if (!($submitted_password == $submitted_passwordConfirmation)) 
{
    echo "Password does not match password confirmation.";
    header("refresh:2;url=registration.php");
}
else
{
    if (in_array($submitted_password, $list_common_passwords) || (strlen($submitted_password) < $min_length_password))
    {
        echo "Choose a stronger password (min. 8 characters)";
    }
    else
    {
        $password = mysqli_real_escape_string($connection,$_POST["password"]);
    }
}

//Other less important attributes
$firstName = mysqli_real_escape_string($connection,$_POST["firstName"]);
$lastName = mysqli_real_escape_string($connection,$_POST["lastName"]);
$address = mysqli_real_escape_string($connection,$_POST["Address"]);
$telephone = mysqli_real_escape_string($connection,$_POST["Telephone"]);
$selectedGender = $_POST["Gender"];


// TODO: Extract $_POST variables, check they're OK, and attempt to create
// an account. Notify user of success/failure and redirect/give navigation 
// options.

?>
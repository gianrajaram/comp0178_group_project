<?php

session_start();

unset($_SESSION['logged_in']);
unset($_SESSION['account_type']);
unset($_SESSION['userID']);
unset($_SESSION['username']);
session_destroy();


// Redirect to index



//sadad
header("Location: index.php");

?>
<?php
// File with re-usable function to establish connection with the database 

// Open connection to database; if connection to database fails, try to re-connect again. If it does not work again for the second time - > redirect user to error message page to increase user experience

function open_connection()
{
    $connection = mysqli_connect("localhost", "AuctionProject", "london2023", "website_auction");

    if (!$connection) 
    { 
        $connection = mysqli_connect("localhost", "AuctionProject", "london2023", "website_auction");
        if ($connection)
        {
            return $connection;
        } 
        else 
        {
            header("Location: failed_conncetion.php");
        }
    }
}

?>
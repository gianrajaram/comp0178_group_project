<html>
<body>
<?php
// File with re-usable function to establish connection with the database 

// Define function to open connection to database; If connection to database fails, re-connect. IF still unsuccesful, redirect user to error message page
function open_connection()
{
    $connection = mysqli_connect("localhost", "AuctionProject", "london2023", "website_auction");
    if (!$connection) 
    { 
        $connection = mysqli_connect("localhost", "AuctionProject", "london2023", "website_auction");
        if (!$connection)
        {
            header("Location: failed_conncetion.php");
        }
        else
        {
            return $connection;
        }
    }
    return $connection;
}


// Define function for making a sql query - adapted from Tutorial 3 slide 7  

function sql_query($connection, $query)
{
    $result = mysqli_query($connection, $query);
    
    if (!$result)
    {
        die('Error making select users query'.mysqli_error($connection));
    }  
    return $result;   
}
$connection = open_connection();
$query = "SELECT userfirstname, userlastname FROM Users";
$result = mysqli_query($connection,$query); //returns mysql object
echo '<table border="1">';
while ($row = mysqli_fetch_array($result)) {
    echo '<tr>';
    echo '<td>' . $row['userfirstname'] . '</td>';
    echo '<td>' . $row['userlastname'] . '</td>';
    echo '</tr>';
}
echo '</table>';
?>
</html>
</body>
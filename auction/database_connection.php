
<?php
// File with re-usable function to establish connection with the database 

//Define function for establishing connection between database and php
function connect(){
    mysqli_report(MYSQLI_REPORT_OFF); // let errors be handled by error statements; without this throwing fatal exceptions despite error handling code - seems to be a problem in new php language update
    //read database host details securely
    $configFile = 'Database_host.txt';
    $configData = file($configFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $dbConfig = [];
    foreach ($configData as $line) {
       list($key, $value) = explode('=', $line);
       $dbConfig[$key] = $value;
    }
    //open connection to database; If connection to database fails, redirect user to error message page
    $connection = mysqli_connect($dbConfig['host'], 
                                $dbConfig['username'],
                                $dbConfig['password'], 
                                $dbConfig['DB_name']);
    if (!$connection) { 
        header("Location: failed_connection.php");
        }
    return $connection;
}

// define function for making a sql query - adapted from Tutorial 3 slide 7 
function send_query($query){   
    $connection = connect();
    // get query
    $result = mysqli_query($connection, $query);
    if (!$result) {
        die('Error making select users query'.mysqli_error($connection));
    } 
    //close connection and return result query
    mysqli_close($connection);
    return $result; // mysqli object
}

// Version for MAC users 
function connectMAC()
{
    mysqli_report(MYSQLI_REPORT_OFF); // let errors be handled by error statements; without this throwing fatal exceptions despite error handling code - seems to be a problem in new php language update
    //read database host details securely
    //beginning of adaptation from ChatGPT
    // Change the current working directory to the specified directory
    $configFile = 'Database_host_MAC.txt';
    $configData = file($configFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $dbConfig = [];
    
    foreach ($configData as $line) {
       list($key, $value) = explode('=', $line);
       $dbConfig[$key] = $value;
    }
    //end of adaptation from ChatGPT

    //open connection to database; If connection to database fails, re-connect. If still unsuccesful, redirect user to error message page
    $connection = mysqli_connect($dbConfig['host'], $dbConfig['username'], $dbConfig['password'], $dbConfig['DB_name']);
    if (!$connection) 
    { 
        $connection = mysqli_connect($dbConfig['host'], $dbConfig['username'], $dbConfig['password'], $dbConfig['DB_name']);
        if (!$connection)
        {
            header("Location: failed_connection.php");
        }
    }
    return $connection;
}

// define function for making a sql query - adapted from Tutorial 3 slide 7 
function send_queryMAC($query)
{   
    $connection = connectMAC();
    
    // send query
    $result = mysqli_query($connection, $query);
    if (!$result)
    {
        die('Error making select users query'.mysqli_error($connection));
    }  

    //close connection and return result query
    mysqli_close($connection);
    return $result; // mysqli object
}



// Test with SELECT query

 //$query = "SELECT userfirstname, userlastname FROM Users";
 //$output = send_query($query); //returns mysql object
 //echo '<table border="1">';
 //while ($row = mysqli_fetch_array($output)) {
 //   echo '<tr>';
 //  echo '<td>' . $row['userfirstname'] . '</td>';
 //   echo '<td>' . $row['userlastname'] . '</td>';
//    echo '</tr>';
//}
//echo '</table>';

//Test with INSERT query

//$query = "INSERT INTO Users (userEmail, username, userPassword, userFirstName, userLastName, userAddress, userTel, userGender)".
//"VALUES ('majajajajsa4@gmsdaf.com', 'majajajajajaj', '111', 'O', 'A', 'Imaginary Land, London E14 9RZ UK', 0792200000, 'Female')";
//send_queryMAC($query);

// Test with Delete query

//$query = "DELETE FROM Users Where username = 'user8'";
//send_queryMAC($query) 

?>

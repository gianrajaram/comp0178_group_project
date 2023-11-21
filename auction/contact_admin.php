<?php require_once("database_connection.php");
require_once("utilities.php");
require_once("header.php")?>

<head>
    <!--Adapted from AI tool -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Admin</title>
    <style>
        .conversation {
            border: 1px solid #ccc;
            padding: 10px;
            margin-top: 20px;
        }

        .admin-message {
            color: blue;
        }

        .user-message {
            color: green;
        }
    </style>
    <!-- End of adataption -->
</head>
<body>

<?php 
// establish connection
$connection = connect();

if(isset($_SESSION['userID'])) {
    // Get the seller ID
    $userID = $_SESSION['userID'];
    //$userID = 2;
    // check if there are any previous messages
    $query_hasMessages = "SELECT * FROM `messagesadmin` WHERE userID = $userID";
    $result_messages = send_query($query_hasMessages);
} else {
  header("Location: login.php");
  exit();
}

if (mysqli_num_rows($result_messages)>0) {
    while($row_messages = mysqli_fetch_assoc($result_messages)) {
        $messages = $row_messages["messageText"];

        echo '<div class="conversation">';
        echo '<p class="admin-message">Admin: Hello, how can I help you</p>';
        echo '<p class="user-message">User ' . $userID . ': ' . $messages . '</p>';

        //display the messages from the user
        /*foreach ($messages as $message) {
           echo '<p class="user-message">User ' . $userID . ': ' . $message['messageText'] . '</p>';
        }*/
    }
    echo '</div>';
} else {
    // Display the form
    ?>
    <div class="container">
    <!-- send message form -->
        <div style="max-width: 800px; margin: 10px auto">
            <h2 class="my-3">Send a message!</h2>
            <div class="card">
                <div class="card-body">
                    <form method="post" action="">

                        <div class="form-group row">
                            <label for="message" >Your Message: </label>
                            <div class="col-sm-10">
                                <textarea class="message" id="textMessage" name = 'textMessage' cols="50" rows="10" required></textarea><br>
                                <small id="detailsHelp" class="form-text text-muted">Fill in and please be as detailed as possible. We will get back to you as soon as possible.</small>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-10">
                                <button type="submit" class="btn btn-primary" onclick="sendMessage()">Submit</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php
}

$currentDateTime = date('Y-m-d\TH:i');


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Assuming you're using POST method to submit the form
    $messageText = $_POST['textMessage'];

    $query_insert = "INSERT INTO messagesadmin
                    (messageText, userID, messageTime)
                    VALUES
                    ('$messageText', '$userID', '$currentDateTime')";
    send_query($query_insert);

    echo '<script>alert("Message sent!");</script>';
   // echo '<script>window.location.reload();</script>';
}






?>

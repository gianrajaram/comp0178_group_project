<?php require_once("database_connection.php");
require_once("utilities.php");
require_once("header.php")?>

<head>
    <!--Adapted from AI tool -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Admin</title>
    <style>

        .message-container {
            margin-top: 20px;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }

        .message-box {
            max-width: 70%; 
            margin-bottom: 10px; 
            padding: 0;
            box-sizing: border-box;
            border-radius: 5px;
            border: 5px solid #fff;
            

            margin: 10px 0;
            word-wrap: break-word;
            clear: both;
        }

        .admin-message {
            color: #fff;
            background-color: #007bff;
        }

        .user-message {
            color: #000;
            background-color: #e5e5e5;
        }

        .message-box::after {
            content: "";
            display: table;
            clear: both;
        }

        .message-box.left {
            float: left;
        }

        .message-box.right {
            float: right;
        }

        .message-box p {
            margin: 0;
        }

        .message-box .admin-message p {
            color: #fff;
            margin-bottom: 5px;
        }

        .message-box .user-message p {
            color: #000;
            margin-bottom: 5px;
        }

        .message-box textarea {
            width: 100%;
        }

        .btn-primary {
            float: right;
            margin-top: 10px;
        }

        .message-form {
            max-width: 600px;
            margin: 10px auto;
        }

        .message-textarea {
            width: 100%; 
            padding: 10px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            margin-bottom: 10px;

        }

        .message-button {
            float: right;
            bottom: 0;
            right: 0;
        }

        .message-button button {
            background-color: #4CAF50; 
            color: white;
            border: none;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            border-radius: 5px;
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
    $senderID = $_SESSION['userID'];
    $userID = 1; // the admin
    // check if there are any previous messages
    $query_messages = "SELECT * FROM `messagesadmin` WHERE (userID = $userID AND senderID = $senderID) OR (userID = $senderID AND senderID = $userID) ORDER BY messageTime";
    $result_messages = send_query($query_messages);
} else {
  header("Location: login.php");
  exit();
}

if (mysqli_num_rows($result_messages)>0) {
    echo '<div class="message-container">';
    while($row_messages = mysqli_fetch_assoc($result_messages)) {
        $messageText = $row_messages["messageText"];
        $messageSenderID = $row_messages["senderID"];

        $query_update_read = "UPDATE messagesadmin SET isRead = 0 WHERE  senderID = $userID AND userID = $senderID";
        send_query($query_update_read);
        
        echo '<div class="message-box ' . ($messageSenderID == $senderID ? 'right' : 'left') . '" style="border-color: ' . ($messageSenderID == $userID ?  '#e5e5e5':'#007bff') . ';">';
        echo '<p class="' . ($messageSenderID == $userID ? 'user-message' : 'admin-message') . '">' . ($messageSenderID == $senderID ? 'You: ' : 'Admin: ') . $messageText . '</p>';
        echo '</div>';
    

        //display the messages from the user
        /*
        echo '<div class="conversation">';
        echo '<p class="user-message">User ' . $senderID . ': ' . $sentMessages . '</p>';
        foreach ($messages as $message) {
           echo '<p class="user-message">User ' . $userID . ': ' . $message['messageText'] . '</p>';
        }*/
    }
    //echo '</div>';
    
    echo '<div class="message-box right">';
    echo '<form method="post" action="">';
    echo '<textarea id="textMessage" name="textMessage" class="message-textarea" rows="4" placeholder="Type a message..."></textarea>';
    echo '<div class ="message-button">';
    echo '<button type="submit">Send Message</button>';
    echo '</div>';
    echo '</form>';
    echo '</div>';
    
} else {
    // Display the form
    ?>
    <div class="container">
    <!-- send message form -->
        <div style="max-width: 600px; margin: 10px auto">
            <h2 class="my-3">Send a message!</h2>
            <div class="card">
                <div class="card-body">
                    <form method="post" action="">

                        <div class="form-group row">
                            <label for="message" >Your Message: </label>
                            <div class="col-sm-12">
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
                    (messageText, userID, senderID, messageTime, isRead)
                    VALUES
                    ('$messageText', '$userID', $senderID, '$currentDateTime', 1)";
    send_query($query_insert);

    echo '<script>alert("Message sent!");</script>';
    
}

echo '<script>window.location.reload();</script>';




?>

<?php require_once("database_connection.php");
require_once("utilities.php");
require_once("header.php");

$query_adminMessage = "SELECT * FROM messagesadmin GROUP BY userID";
$result_adminMessage = send_query($query_adminMessage);

$connection = connect();

if(isset($_SESSION['userID'])) {
    // Get the seller ID
    $senderID = $_SESSION['userID'];
    //$userID = 2;
    // check if there are any previous messages
    $query_received_messages = "SELECT senderID, userID, messageText, COUNT(*) as messageCount FROM messagesadmin WHERE userID = $senderID GROUP BY senderID";
    $result_received_messages = send_query($query_received_messages);

} else {
    header("Location: login.php");
    exit();
}


?>


<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Overview of Messages</title>
    <style>
        .user-info-table th, .user-info-table td {
            padding: 10px;
            border-bottom: 1px solid #ccc;
        }

        
        .message-container {
            margin-top: 20px;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }

        .admin-message {
            color: #fff;
            background-color: #007bff;
        }

        .user-message {
            color: #000;
            background-color: #e5e5e5;
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

        .message-button {
            float: right;
            bottom: 0;
            right: 0;
        }

        .container {
            text-align: center;
        }

        .message-info-table {
            width: 70%;
            margin: 0 auto;
            border-collapse: collapse;
        }

        .message-info-table th,
        .message-info-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        .message-info-table th {
            background-color: #f2f2f2;
        }
        .unread-row {
            background-color: yellow; /* or any other color you want */
        }
    </style>
</head>
<body>


    <?php
    if (isset($_GET['userID'])) {
        $targetUserID = $_GET['userID'];
        $query_admin_messages = "SELECT * FROM messagesadmin WHERE (userID = $senderID AND senderID = $targetUserID) OR (userID = $targetUserID AND senderID = $senderID) ORDER BY messageTime";
        $result_admin_messages = send_query($query_admin_messages);
        ?>


        <div class="container">
            <h2>Messages with User ID: <?php echo $targetUserID; ?></h2>

            <?php
            if (mysqli_num_rows($result_admin_messages) > 0) {
                echo '<div class="message-container">';
                while ($row_messages = mysqli_fetch_assoc($result_admin_messages)) {
                    $messageText = $row_messages["messageText"];
                    $messageSenderID = $row_messages["senderID"];

                    $query_update_read = "UPDATE messagesadmin SET isRead = 0 WHERE senderID = $targetUserID AND userID = $senderID";
                    send_query($query_update_read);

                    echo '<div class="message-box ' . ($messageSenderID == $senderID ? 'right' : 'left') . '" style="border-color: ' . ($messageSenderID == $targetUserID ?  '#e5e5e5':'#007bff') . ';">';
                    echo '<p class="' . ($messageSenderID == $targetUserID ? 'user-message' : 'admin-message') . '">' . ($messageSenderID == $targetUserID ? 'User ' . $targetUserID . ': ' : 'Admin: ') . $messageText . '</p>';
                    echo '</div>';
                    

/*
                    echo '<div class="conversation">';
                    echo '<p class="admin-message">Admin: Hello, how can I help you</p>';

                    echo '<p class="user-message">User ' . $userID . ': ' . $messages . '</p>';
                    

                    echo '</div>';
                    */
                }
                
                echo '<div class="message-box right">';
                echo '<form method="post" action="">';
                echo '<textarea id="newMessage" name="newMessage" class="message-textarea" rows="4" placeholder="Type a message..."></textarea>';
                echo '<div class ="message-button">';
                echo '<button type="submit">Send Message</button>';
                echo '</div>';
                echo '</form>';
                echo '</div>';
            } else {
                echo '<p>No messages found.</p>';
            }
        
            ?>

        </div>

    <?php
    
    } else {
        ?>
        <div class="container">
        <h2>All Messages</h2>

        <?php
        if (mysqli_num_rows($result_received_messages) > 0) {
            echo '<table class="message-info-table">';
            echo '<tr><th>Nb of Messages</th><th>User ID</th><th>Last Message</th><th>Time of Latest Message</th></tr></thead><tbody>';
            $row_nb = mysqli_fetch_assoc($result_received_messages);
            $count = $row_nb['messageCount'];
            $query_lastMessage = "SELECT senderID, isRead, messageTime AS latestMessageTime, messageText AS latestMessageText, COUNT(*) as messageCount FROM messagesadmin WHERE userID = 1 AND (senderID, messageTime) IN ( SELECT senderID, MAX(messageTime) AS latestMessageTime FROM messagesadmin WHERE userID = 1 GROUP BY senderID ) GROUP BY senderID, latestMessageTime, latestMessageText ORDER BY latestMessageTime DESC;";
            $result_lastMessage = send_query($query_lastMessage);
            if ($result_lastMessage) {
                while ($row = mysqli_fetch_assoc($result_lastMessage)) {
                    $latestMessageTime = $row['latestMessageTime'];
                    $latestMessageText = $row['latestMessageText'];
                    $isRead = $row['isRead'];
                    $rowClass = ($isRead ?  'unread-row':'');
                
                echo '<tr class="'. $rowClass . '">';
                echo '<td>'. $count .'</td>';
                echo '<td><a href="admin_messages.php?userID=' . $row['senderID'] . '">' . $row['senderID'] . '</a></td>';
                echo '<td>' . $latestMessageText . '</td>';
                echo '<td>'. $latestMessageTime . '</td>';
                echo '</tr>';
                }
            }

            echo '</tbody></table>';
        } else {
            echo '<p>No messages yet.</p>';
        }
        

    }

    $connection = connect();




    $currentDateTime = date('Y-m-d\TH:i');


    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Assuming you're using POST method to submit the form
        $messageText = $_POST['newMessage'];
    
        $query_insert = "INSERT INTO messagesadmin
                        (messageText, userID, senderID, messageTime, isRead)
                        VALUES
                        ('$messageText', '$targetUserID', $senderID, '$currentDateTime', 1)";
        send_query($query_insert);
    
        echo '<script>alert("Message sent!");</script>';
        echo "<script>window.location.href='admin_messages.php';</script>";
    }
    ?>




</body>
</html>  


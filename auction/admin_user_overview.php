<?php 
include_once("header.php");
require("utilities.php");
require("database_connection.php");

// Establish connection with database
$connection = connect();

// Fetch the users from the database
$query = "SELECT userID, username, userEmail, userFirstName, userLastName, userIsActive, userAccountType FROM Users WHERE username != 'admin'";
$users = send_query($query);

?>

<!-- Formating of table adapted from ChatGPT -->
<div class="container">
    <h2 class="my-3">Overview of users</h2>

    <style>
        .user-info-table th, .user-info-table td {
            padding: 10px;
            border-bottom: 1px solid #ccc; 
        }
    </style>


    <table class="user-info-table">
        <thead>
            <tr>
                <th>User ID</th>
                <th>Username</th>
                <th>Email</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Account status</th>
                <th>User Account Type</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?php echo $user['userID']; ?></td>
                    <td><?php echo $user['username']; ?></td>
                    <td><?php echo $user['userEmail']; ?></td>
                    <td><?php echo $user['userFirstName']; ?></td>
                    <td><?php echo $user['userLastName']; ?></td>
                    <td><?php echo $user['userIsActive']; ?></td>
                    <td><?php echo $user['userAccountType']; ?></td>
                    <td> <!-- End of adaptation from ChatGPT -->
                        <form method="POST" action="admin_delete_user.php">
                        <input type="hidden" name="userID" value="<?php echo $user['userID']; ?>">
                        <button type="submit" class="btn btn-danger form-control">Delete</button>
                        </form>
                        <form method="POST" action="admin_ActivateDeactivate_user.php">
                        <input type="hidden" name="userID" value="<?php echo $user['userID']; ?>">
                        <input type="hidden" name="userIsActive" value="<?php echo $user['userIsActive']; ?>">
                        <?php if ($user['userIsActive'] == "Activated") {
                            $admin_action = "Deactivate";}
                            elseif ($user['userIsActive'] == "Deactivated"){
                                $admin_action = "Activate";
                                }?>
                        <button type="submit" class="btn btn-primary form-control"><?php echo $admin_action; ?></button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include_once("footer.php")?>
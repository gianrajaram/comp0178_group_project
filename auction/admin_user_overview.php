<?php include_once("header.php")?>
<?php require("utilities.php")?>

<div class="container">
<style>
    .user-info-table {
        width: 100%; /* Set the width of the entire table */
    }

    .title {
        width: (100%/7); /* Adjust the width as needed */
        font-weight: bold;
        display: left;
    }

    .user-info {
        width: (100%/7); /* Adjust the width as needed */
        padding: 10px;
    }

    .spacer {
        width: 5%; /* Adjust the width as needed */
    }

    .user-item {
        display: left;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
    }

    .button-container {
        display: flex;
        align-items: center;
    }
</style>

<h2 class="my-3">Overview of users</h2>


<?php
  // should be similar to browse overview of auctions but with user profiles
  print_user_li_titles();
  print_user_li($userID = "342", $username = "swqe", $userEmail = "2were2", $userFirstName = "ASJDajksd", $userLastName = "dsfsafsf", $userAccountType = "dsafad");
  
?>

<?php include_once("footer.php")?>
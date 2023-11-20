<?php
  session_start();
?>


<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  
  <!-- Bootstrap and FontAwesome CSS -->
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

  <!-- Custom CSS file -->
  <link rel="stylesheet" href="css/custom.css">

  <title>[ReWear Auctions]</title>

</head>


<body>

<!-- Navbars -->
<nav class="navbar navbar-expand-lg navbar-light bg-light mx-2">
  <a class="navbar-brand" href="browse.php">ReWear Auctions</a>
  <ul class="navbar-nav ml-auto">
    <li class="nav-item">
    
<?php
  // Displays either login or logout on the right, depending on user's current status (session).
  if (isset($_SESSION['account_type']) 
      && ($_SESSION['account_type'] == "Buyer" 
      OR $_SESSION['account_type'] == "Seller")){
        echo('<li class="nav-item"><a class="nav-link" href="switch_account_type.php">Switch account type</a>
  </li>');
}
  if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true) {
    echo '<a class="nav-link" href="logout.php">Logout</a>';
  }
  else {
    echo '<a href="login.php" class="btn nav-link">Login</a>';
  }
?>

    </li>
  </ul>
</nav>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <ul class="navbar-nav align-middle">
<?php
// Define different privilages - what are users allowed to see according to their account type
  if (isset($_SESSION['account_type']) && $_SESSION['account_type'] == 'Buyer') {
  echo('
  <li class="nav-item mx-1"><a class="nav-link" href="user_profile.php">My profile</a>
  </li>
  <li class="nav-item mx-1"><a class="nav-link" href="browse.php">Browse</a>
  </li>
  <li class="nav-item mx-1"><a class="nav-link" href="mybids.php">My bids</a>
    </li>
	<li class="nav-item mx-1"><a class="nav-link" href="recommendations.php">My recommendations</a>
    </li>  
  <li class="nav-item mx-1"><a class="nav-link" href="my_wishlist.php">My watchlist</a>
    </li>');
  }
  else if (isset($_SESSION['account_type']) && $_SESSION['account_type'] == 'Seller') {
    echo('
    <li class="nav-item mx-1"><a class="nav-link" href="user_profile.php">My profile</a>
    </li>
    <li class="nav-item mx-1"><a class="nav-link" href="browse.php">Browse</a>
    </li>
    <li class="nav-item mx-1"><a class="nav-link" href="mylistings.php">My listings</a>
      </li>
    <li class="nav-item ml-3"><a class="nav-link btn border-light" href="create_auction.php">+ Create auction</a>
      </li>');
  }
  else if (isset($_SESSION['account_type']) && $_SESSION['account_type'] == 'Admin') { 
    echo('<li class="nav-item mx-1"><a class="nav-link" href="user_profile.php">My profile</a>
    </li>
    <li class="nav-item mx-1"><a class="nav-link" href="browse.php">Browse</a>
    </li>
    <li class="nav-item mx-1"><a class="nav-link" href="admin_user_overview.php">User overview</a>
    </li>
    <li class="nav-item mx-1"><a class="nav-link" href="admin_auction_overview.php">Auction overview</a>
    </li> 
    ');
  }
  else {
    echo('
    <li class="nav-item mx-1"><a class="nav-link" href="browse.php">Browse</a>
    </li>');
  }
?>
  </ul>
</nav>



<!-- Login modal - not used - instead login.php-->
 <div class="modal fade" id="loginModal">
  <div class="modal-dialog">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Login</h4>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
        <form method="POST" action="login_result.php">
          <div class="form-group">
            <label for="username">Username</label>
            <small id="accountTypeHelp" class="form-text-inline text-muted"><span class="text-danger">* Required.</span></small>
            <input type="text" class="form-control" id="username" name ="usernameLogin" placeholder="username">
          </div>
          <div class="form-group">
            <label for="password">Password</label>
            <small id="accountTypeHelp" class="form-text-inline text-muted"><span class="text-danger">* Required.</span></small>
            <input type="password" class="form-control" id="password"  name ="passwordLogin" placeholder="Password">
          </div>
          <div class="form-group">
            <label for="accountType">Logging in as a:</label>
            <div class="col-sm-10">
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="accountType" id="accountBuyer" value="buyer" checked>
                <label class="form-check-label" for="accountBuyer">Buyer</label>
              </div>
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="accountType" id="accountSeller" value="seller">
                <label class="form-check-label" for="accountSeller">Seller</label>
              </div>
            <small id="accountTypeHelp" class="form-text-inline text-muted"><span class="text-danger">* Required.</span></small>
            </div>
            </div>
          <button type="submit" name = "login" class="btn btn-primary form-control">Sign in</button>
        </form>
        <div class="text-center">or <a href="register.php">create an account</a></div>
        </div>
    </div>
 </div>
 </div> <!-- End modal -->
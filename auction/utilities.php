<?php

// Get profile of user in question (all columns from users table)
// Requires header.php (for starting session) and database.php (for send_query)
function user_profile(){
  $profile = $_SESSION['username'];
  $query_user_profile = "SELECT * from Users WHERE username = '$profile'";
  $result_user_profile = send_query($query_user_profile);
  $profile_row = mysqli_fetch_array($result_user_profile);
  return $profile_row;
}

function user_profile_username(){
  $profile = $_SESSION['username'];
  $query_user_profile = "SELECT * from Users WHERE username = '$profile'";
  $result_user_profile = send_query($query_user_profile);
  $profile_row = mysqli_fetch_array($result_user_profile);
  return $profile_row["username"];
}





//Alert message while registering & referal back to registration page
function alert_message_registration($message)
{
  echo "<script>alert('$message');</script>";
  echo "<script>window.location.href='register.php';</script>";
}

//Alert message while logging in
function alert_message_login($message)
{
  echo "<script>alert('$message');</script>";
  echo "<script>window.location.href='login.php';</script>";
}

//Alert message while filling out auction 
function alert_message_auction($message)
{
  echo "<script>alert('$message');</script>";
  echo "<script>window.location.href='create_auction.php';</script>";
}

// display_time_remaining:
// Helper function to help figure out what time to display
function display_time_remaining($interval) {

    if ($interval->days == 0 && $interval->h == 0) {
      // Less than one hour remaining: print mins + seconds:
      $time_remaining = $interval->format('%im %Ss');
    }
    else if ($interval->days == 0) {
      // Less than one day remaining: print hrs + mins:
      $time_remaining = $interval->format('%hh %im');
    }
    else {
      // At least one day remaining: print days + hrs:
      $time_remaining = $interval->format('%ad %hh');
    }

  return $time_remaining;

}

// print_listing_li:
// This function prints an HTML <li> element containing an auction listing
function print_listing_li($item_id, $title, $desc, $price, $num_bids, $end_time, $auctionPicture)
{
  // Truncate long descriptions
  if (strlen($desc) > 250) {
    $desc_shortened = substr($desc, 0, 250) . '...';
  }
  else {
    $desc_shortened = $desc;
  }
  
  // Fix language of bid vs. bids
  if ($num_bids == 1) {
    $bid = ' bid';
  }
  else {
    $bid = ' bids';
  }
  
  // Calculate time to auction end
  $now = new DateTime();
  if ($now > $end_time) {
    $time_remaining = 'This auction has ended';
  }
  else {
    // Get interval:
    $time_to_end = date_diff($now, $end_time);
    $time_remaining = display_time_remaining($time_to_end) . ' remaining';
  }
  
  // Print HTML
  echo('
    <li class="list-group-item d-flex justify-content-between align-items-start">
    <div class="p-2 mr-3">
    <img src="' . $auctionPicture . '" alt="' . $title . ' Image" style=" max-width:100px; max-height:100px;">
  </div>
    <div class="p-2 mr-5"><h5><a href="listing.php?item_id=' . $item_id . '">' . $title . '</a></h5>' . $desc_shortened . '</div>
    <div class="text-center text-nowrap"><span style="font-size: 1.5em">£' . number_format($price, 2) . '</span><br/>' . $num_bids . $bid . '<br/>' . $time_remaining . '</div>
  </li>'
  );
}



function print_mylisting_li($item_id, $title, $desc, $price, $num_bids, $end_time, $auctionPicture)
{
  // Truncate long descriptions
  if (strlen($desc) > 250) {
    $desc_shortened = substr($desc, 0, 250) . '...';
  }
  else {
    $desc_shortened = $desc;
  }
  
  // Fix language of bid vs. bids
  if ($num_bids == 1) {
    $bid = ' bid';
  }
  else {
    $bid = ' bids';
  }
  
  // Calculate time to auction end
  $now = new DateTime();
  if ($now > $end_time) {
    $time_remaining = 'This auction has ended';
  }
  else {
    // Get interval:
    $time_to_end = date_diff($now, $end_time);
    $time_remaining = display_time_remaining($time_to_end) . ' remaining';
  }
  
  // Print HTML
  echo('
    <li class="list-group-item d-flex justify-content-between align-items-start">
      <div class="p-2 mr-3">
        <img src="' . $auctionPicture . '" alt="' . $title . ' Image" style=" max-width:100px; max-height:100px;">
      </div>
      
      <div class="p-2 mr-5 flex-grow-1"><h5><a href="listing.php?item_id=' . $item_id . '">' . $title . '</a></h5>' . $desc_shortened . '</div>
      <div class="text-center text-nowrap"><span style="font-size: 1.5em;">£' . number_format($price, 2) . '</span><br/>' . $num_bids . $bid . '<br/>' . $time_remaining . '</div>
      <form method = "POST" action="seller_delete_auction.php" class="align-self-center">
        <input type="hidden" name="auctionID" value="' . $item_id . '">
        <button type="submit" class="btn btn-danger from-control">Delete</button>
      </form>
    </li>'
  );
}














function print_user_li_titles()
{
    echo('
    <li class="list-group-item user-item">
        <table class="user-info-table">
            <tr>
                <th class="title">User ID</th>
                <th class="spacer">&emsp;</th>
                <th class="title">Username</th>
                <th class="spacer">&emsp;</th>
                <th class="title">Email</th>
                <th class="spacer">&emsp;</th>
                <th class="title">First Name</th>
                <th class="spacer">&emsp;</th>
                <th class="title">Last Name</th>
                <th class="spacer">&emsp;</th>
                <th class="title">User Account Type</th>
                <th class="spacer">&emsp;</th>
                <th class="title">Actions</th>
            </tr>
        </table>
    </li>');
}

function print_user_li($userID, $username, $userEmail, $userFirstName, $userLastName, $userAccountType)
{
    echo('
    <li class="list-group-item user-item">
        <table class="user-info-table">
            <tr>
                <td class="user-info">' . $userID . '</td>
                <td class="spacer">&emsp;</td>
                <td class="user-info">' . $username . '</td>
                <td class="spacer">&emsp;</td>
                <td class="user-info">' . $userEmail . '</td>
                <td class="spacer">&emsp;</td>
                <td class="user-info">' . $userFirstName . '</td>
                <td class="spacer">&emsp;</td>
                <td class="user-info">' . $userLastName . '</td>
                <td class="spacer">&emsp;</td>
                <td class="user-info">' . $userAccountType . '</td>
                <td class="spacer">&emsp;</td>
                <td class="user-info">
                    <div class="button-container">
                        <button class="btn btn-danger">Delete</button>
                        <button class="btn btn-primary">Activate/Deactivate</button>
                    </div>
                </td>
            </tr>
        </table>
    </li>
    ');
}
 
//function to generate a unique randome filename
function generateUniqueFilename($folder, $extention){
  $timestamp = (new DateTime())->format('YmdHisu');
  $uniqueID = substr(md5(uniqid()), 0, 6);
  return $folder .'/'. $timestamp .'_'. $uniqueID . "." . $extention;
}



function sanitise_input($data) {
  $data = trim($data); # removes whitespaces from the beginning and end of user input
  $data = stripslashes($data); #removes backslashes from data
  $data = htmlspecialchars($data); #
  return $data;
}
?>
session_start():


<?php include_once("header.php")?>
<?php require("utilities.php")?>
<?php require('database_connection.php')?>

<div class="container">

<h2 class="my-3">Recommendations for you</h2>

<div id="searchSpecs">
<!-- When this form is submitted, this PHP page is what processes it.
     Search/sort specs are passed to this page through parameters in the URL
     (GET method of passing data to a page). -->


<?php

## NOTES - should we include filtering/ordering? or keep completely blank and couple recommendations only
## sessionID - ok
## what data do we pull from
# find auctions user bid on -> pull other userIDs that bid on the same item -> pull other user purchases?/bids?

# clarify exactly what the idea for data is and then build on existing sql queries



##IMPORTANT
$conn = connect();


# initialising session double check

$userID = isset($_SESSION['userID']) ? $session['userID'] : '';



$curr_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;


$userIdQuery = 'SELECT userID FROM Users';
$resultUserId = send_query($userIdQuery);
if (!$resultUserId){
  die('SQL error idquery'.mysqli_error($conn));
} else {
  $finalUserID = [];
  while($row = mysqli_fetch_assoc($resultUserId)){
    $finalUserID[] = $row;
  }
}


?>


<!-- added name="keyword" to <input type="text" class="form-control border-left-0" -->
  </div> <!-- end search specs bar -->

</div> <!-- end search specs bar -->


             <!-- changing ACTIVE currently updates price, why tf is that the case for stylish black t-shirtrs -->

<?php
  $keyword = isset($_GET['keyword']) ? sanitise_input($_GET['keyword']) : "";
  $AIkeyword = isset($_GET['AIkeyword']) ? sanitise_input($_GET['AIkeyword']) : "";
  

  if (!isset($_GET['cat'])) {
    $category = "Category";
  } else {
    $category = $_GET['cat'];
  }

  if (!isset($_GET['order_by'])) {
    $ordering = 'Price';
  } else {
    $ordering = $_GET['order_by'];
  }
  
  if (isset($_GET['page']) && is_numeric($_GET['page']) && $_GET['page'] > 0) {
    $curr_page = (int) $_GET['page'];
} else {
    $curr_page = 1;
}


  if (!isset($_GET['colour'])) {
    $colour = 'All Colours';
  } else {
    $colour = $_GET['colour'];
  }

  if (!isset($_GET['gender'])) {
    $gender = 'Gender';
  }
  
$results_per_page = 10;
$start_from = ($curr_page - 1) * $results_per_page;


# Possibly this should be updated? currently if user loads default then moves to second page then applies filters, it will jump back to default page
$result = null;
$paginationResult = null;

$currentDateTime = date('Y-m-d H:i:s');

$isFormSubmitted = isset($_GET['keyword']) || isset($_GET['cat']) || isset($_GET['colour']) || isset ($_GET['gender']) || isset($_GET['size']) || isset($_GET['order_by']) || isset($_GET['AIkeyword']);

if ($isFormSubmitted) {
$query ='SELECT 
            a.auctionID, 
            a.auctionName,
            a.auctionStartingPrice,
            a.auctionDescription,
            a.categoryType,
            a.auctionEndDate,
            a.categoryColor,
            a.categoryGender,
            a.categorySize,
            a.auctionStartingPrice,
            mb.highestBid,
            COALESCE(mb.highestBid, a.auctionStartingPrice) as currentPrice,
            COUNT(b.bidID) as numBids
          FROM
            Auctions a
          LEFT JOIN
          Bids b ON a.auctionID = b.auctionID
          LEFT JOIN
            (SELECT 
                auctionID,
                MAX(bidValue) AS highestBid
            FROM
                Bids
            GROUP BY
                auctionID
            ) mb ON a.auctionID = mb.auctionID
            WHERE 1 ';

  
    $countQuery = "SELECT COUNT(DISTINCT a.auctionID) AS total FROM Auctions a LEFT JOIN Bids b ON a.auctionID = b.auctionID LEFT JOIN (SELECT auctionID, MAX(bidValue) AS highestBid FROM Bids GROUP BY auctionID) mb ON a.auctionID = mb.auctionID WHERE 1";

            

### error is with $conn 
  if (!empty($keyword) && !empty($AIkeyword)) {
    $combinedSearchTerm = mysqli_real_escape_string($conn, $keyword . ' ' . $AIkeyword);
    $query .= " AND MATCH (auctionDescription) AGAINST ('" . $combinedSearchTerm . "' IN NATURAL LANGUAGE MODE) ";
    $countQuery .= " AND MATCH (auctionDescription) AGAINST ('" . $combinedSearchTerm . "' IN NATURAL LANGUAGE MODE) ";

  } else {
    if (!empty($AIkeyword)) {
      $AIkeyword = mysqli_real_escape_string($conn, $AIkeyword);
      $query .= " AND MATCH (auctionDescription) AGAINST ('" . $AIkeyword . "' IN NATURAL LANGUAGE MODE) ";
      $countQuery .= " AND MATCH (auctionDescription) AGAINST ('" . $AIkeyword . "' IN NATURAL LANGUAGE MODE) ";

  } 
  if (!empty($keyword)) {
      $keyword = mysqli_real_escape_string($conn, $keyword);
      $query .= " AND MATCH (auctionName) AGAINST ('" . $keyword . "' IN NATURAL LANGUAGE MODE) ";
      $countQuery .= " AND MATCH (auctionName) AGAINST ('" . $keyword . "' IN NATURAL LANGUAGE MODE) ";

  }
}
  

  if ($category != 'all') {
    $category = mysqli_real_escape_string($conn,$category);
    $query .=  " AND categoryType = '" . $category . "' ";
    $countQuery .=  " AND categoryType = '" . $category . "' ";

  }
  if ($colour != 'all') {
    $colour = mysqli_real_escape_string($conn, $colour);
    $query .= " AND categoryColor = '" . $colour . "' ";
    $countQuery .= " AND categoryColor = '" . $colour . "' ";
  }
if ($gender != 'all') {
  $gender = mysqli_real_escape_string($conn, $gender);
  $query .= " AND categoryGender = '" .$gender . "' ";
  $countQuery .= " AND categoryGender =  '" .$gender . "' ";
}
if ($activeListing === 'active') {
  $activeListing = mysqli_real_escape_string($conn, $activeListing);
  $query .= " AND a.auctionEndDate > '" . $currentDateTime . "' ";
  $countQuery .= " AND a.auctionEndDate > '" . $currentDateTime . "' ";

}

if ($size != 'all') {
  $size = mysqli_real_escape_string($conn, $size);
  $query .= " AND categorySize = '" .$size . "' ";
  $countQuery .= " AND categorySize = '" .$size . "' ";
}
$query .= ' GROUP BY a.auctionID, a.auctionName, a.auctionStartingPrice, a.auctionDescription, a.categoryType, a.auctionEndDate, a.categoryColor, a.categoryGender, a.categorySize';
  switch($ordering) {
    case 'pricelow':
      $query .= ' ORDER BY COALESCE (mb.highestBid, a.auctionStartingPrice) ASC ';
      break;
    case 'pricehigh':
      $query .= ' ORDER BY COALESCE (mb.highestBid, a.auctionStartingPrice) DESC ';
      break;
    case 'date':
      $query .= ' ORDER BY auctionEndDate ASC ';
      break;
    }
   
  



    $countQuery .= ' GROUP BY a.auctionID';









# adding condition to count results for pagination at end of script
  $paginationCount = send_query($countQuery);
  if (!$paginationCount){
  die('SQL error: ln 378 paginationresult '.mysqli_error($conn));
  }

  $paginationResult = send_query($query);
  if (!$paginationResult){
      die('SQL error: '.mysqli_error($conn));
  }

  
  

} else {
  $defaultCountQuery = "SELECT COUNT(DISTINCT a.auctionID) AS total FROM Auctions a LEFT JOIN Bids b ON a.auctionID = b.auctionID LEFT JOIN (SELECT auctionID, MAX(bidValue) AS highestBid FROM Bids GROUP BY auctionID) mb ON a.auctionID = mb.auctionID WHERE 1";



  $defaultQuery = 'SELECT 
  a.auctionID, 
  MAX(a.auctionName) as auctionName,
  MAX(a.auctionStartingPrice) as auctionStartingPrice,
  MAX(a.auctionDescription) as auctionDescription,
  MAX(a.categoryType) as categoryType,
  MAX(a.auctionEndDate) as auctionEndDate,
  MAX(a.categoryColor) as categoryColor,
  MAX(a.categoryGender) as categoryGender,
  MAX(a.categorySize) as categorySize,
  MAX(COALESCE(mb.highestBid, a.auctionStartingPrice)) as currentPrice,
  COUNT(b.bidID) as numBids
FROM
  Auctions a
LEFT JOIN
  Bids b ON a.auctionID = b.auctionID
LEFT JOIN
  (SELECT 
    auctionID,
    MAX(bidValue) AS highestBid
  FROM
    Bids
  GROUP BY
    auctionID
  ) mb ON a.auctionID = mb.auctionID
WHERE 1 ';


# important to add here, otherwise will cause errors
$defaultQuery .= ' GROUP BY a.auctionID';
$defaultCountQuery .= ' GROUP BY a.auctionID';
# this section below is causing the error, remove it and everything works

$paginationCount = send_query($defaultCountQuery);
if(!$paginationCount){
  die('Connection failed: ' . $conn->connect_error);
}

$paginationResult = send_query($defaultQuery);
if(!$paginationResult){
  die('connection failed' );
}



}

$num_results = mysqli_num_rows($paginationCount);

$max_page = ceil($num_results / $results_per_page);

?>
<?php
  /* For the purposes of pagination, it would also be helpful to know the
     total number of results that satisfy the above query */
?>

<div class="container mt-5">
  <!-- TODO: If result set is empty, print an informative message. Otherwise... -->
  
<?php
#line below condition should theoretically never be zero, ENSURE LOGIC IS CONSISTENT THROUGHOUT (triple check once complete with all features)
if (mysqli_num_rows($paginationResult)==0) {
  echo '<p> ! ! ! No listings were found under the given criteria ! ! ! </p>';
} else {

  # EXITING PHP TO ADD DIVIDER FOR HTML CONTENT (so while loop appears in the correct place, under html class before next line starting with <?php )
  
  ?>
<ul class="list-group"> 
<!-- re-entering php mode -->

<?php
## have coalesced above, so can replace shorthand condition for $current_price if preferred
  while ($row = mysqli_fetch_assoc($paginationResult)){
    $item_id = $row['auctionID'];
    $title = $row['auctionName'];
    $description = $row['auctionDescription'];
    # logical outlier here for Stylish black T-Shitrt for men - > investigate
    $current_price = isset($row['highestBid']) ? $row['highestBid'] : $row['auctionStartingPrice'];
    $num_bids = $row['numBids'];
    $end_date = new DateTime($row['auctionEndDate']);
    print_listing_li($item_id, $title, $description, $current_price, $num_bids, $end_date);
  }
}
?>

</ul>

<!-- Pagination for results listings -->
<nav aria-label="Search results pages" class="mt-5">
  <ul class="pagination justify-content-center">
  
<?php
  $querystring = "";
  foreach ($_GET as $key => $value) {
    if ($key != "page") {
      $querystring .= urlencode($key) . "=" . urlencode($value) . "&amp;";
    }
  }


  $high_page_boost = max(3 - $curr_page, 0);
  $low_page_boost = max(2 - ($max_page - $curr_page), 0);
  $low_page = max(1, $curr_page - 2 - $low_page_boost);
  $high_page = min($max_page, $curr_page + 2 + $high_page_boost);
  
  if ($curr_page != 1) {
    echo('
    <li class="page-item">
      <a class="page-link" href="browse.php?' . $querystring . 'page=' . ($curr_page - 1) . '" aria-label="Previous">
        <span aria-hidden="true"><i class="fa fa-arrow-left"></i></span>
        <span class="sr-only">Previous</span>
      </a>
    </li>');
  }
    
  for ($i = $low_page; $i <= $high_page; $i++) {
    if ($i == $curr_page) {
      // Highlight the link
      #echo('
    #   <li class="page-item active">');
    echo '<li class="page-item active"><a class="page-link" href="#">' . $i . '</a></li>';
    } else {
      echo '<li class="page-item"><a class="page-link" href="browse.php?' . $querystring . 'page=' . $i . '">' . $i . '</a></li>';
    }
  }
    
  

  if ($curr_page != $max_page) {
    echo('
    <li class="page-item">
      <a class="page-link" href="browse.php?' . $querystring . 'page=' . ($curr_page + 1) . '" aria-label="Next">
        <span aria-hidden="true"><i class="fa fa-arrow-right"></i></span>
        <span class="sr-only">Next</span>
      </a>
    </li>');
  }
?>

  </ul>
</nav>


</div>



<?php include_once("footer.php")?>
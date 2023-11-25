<!-- 
  Below is needed to pull the session id making login element unique to the user. --> 



<?php include_once("header.php")?>
<?php require("utilities.php")?>
<?php require('database_connection.php')?>


<div class="container">

<h2 class="my-3">Recommendations for you</h2>
<p>Welcome to your recommendations page! Here, you'll find a selection of items and offers we think you'll love. Browse through and discover great deals tailored just for you.</p>


<div id="searchSpecs">
<!-- When this form is submitted, this PHP page is what processes it.
     Search/sort specs are passed to this page through parameters in the URL
     (GET method of passing data to a page). -->


<?php

session_start();
## should this be zero or ''
# GPT4
$userID = isset($_SESSION['userID']) ? intval($_SESSION['userID']) : 0;
# debugged, int 4 set so this section is found correctly
# ALSO SEEMS TO WORK WITHOUT session_start() as well? test and deploy?


##IMPORTANT
$conn = connect();

$curr_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

?>

<!-- added name="keyword" to <input type="text" class="form-control border-left-0" -->
  </div> <!-- end search specs bar -->
</div> <!-- end search specs bar -->

             <!-- changing ACTIVE currently updates price, why tf is that the case for stylish black t-shirtrs -->

<?php
  

  if (isset($_GET['page']) && is_numeric($_GET['page']) && $_GET['page'] > 0) {
    $curr_page = (int) $_GET['page'];
} else {
    $curr_page = 1;
}

$curr_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;



  
$results_per_page = 10;
$start_from = ($curr_page - 1) * $results_per_page;


# Possibly this should be updated? currently if user loads default then moves to second page then applies filters, it will jump back to default page
$result = null;
$paginationResult = null;

$currentDateTime = date('Y-m-d H:i:s');

#$defaultCountQuery = "SELECT COUNT(DISTINCT a.auctionID) AS total FROM Auctions a LEFT JOIN Bids b ON a.auctionID = b.auctionID LEFT JOIN (SELECT auctionID, MAX(bidValue) AS highestBid FROM Bids GROUP BY auctionID) mb ON a.auctionID = mb.auctionID WHERE 1";

  ## GPT 4 used for query construction 


$recCountQuery = $countQuery = "SELECT 
COUNT(DISTINCT a.auctionID) AS total
FROM
Auctions a
JOIN 
Bids b ON a.auctionID = b.auctionID
WHERE 
b.buyerID != $userID
AND NOT EXISTS (
 SELECT 1
  FROM Bids b2
  WHERE b2.auctionID = a.auctionID AND b2.buyerID = $userID
)";


$recQuery = "SELECT 
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
COUNT(DISTINCT b.bidID) as numBids
FROM
Auctions a
JOIN 
Bids b ON a.auctionID = b.auctionID
LEFT JOIN (
SELECT 
  auctionID, 
  MAX(bidValue) AS highestBid
FROM 
  Bids 
GROUP BY 
  auctionID
) mb ON a.auctionID = mb.auctionID
WHERE 
b.buyerID != $userID
AND NOT EXISTS (
 SELECT 1
  FROM Bids b2
  WHERE b2.auctionID = a.auctionID AND b2.buyerID = $userID
)
GROUP BY
a.auctionID";



$recQuery .= " LIMIT $start_from, $results_per_page";


$paginationResult = mysqli_query($conn, $recQuery);
if (!$paginationResult) {
  die('SQL error: ' . mysqli_error($conn));
}


$paginationCountResult = mysqli_query($conn, $recCountQuery);
if (!$paginationCountResult) {
  die('SQL error: ' . mysqli_error($conn));
}

// Now you can fetch the number of rows for pagination


# important to add here, otherwise will cause errors
#$recQuery .= ' GROUP BY a.auctionID';
# this section below is causing the error, remove it and everything works


if ($row = mysqli_fetch_assoc($paginationCountResult)) {
  $num_results = $row['total'];
} else {
  die('Error fetching total count: ' . mysqli_error($conn));
}

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
  echo '<p> ! ! ! There currently are no recommendations available. Please check back soon ! ! ! </p>';
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
      <a class="page-link" href="recommendations.php?' . $querystring . 'page=' . ($curr_page - 1) . '" aria-label="Previous">
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
      echo '<li class="page-item"><a class="page-link" href="recommendations.php?' . $querystring . 'page=' . $i . '">' . $i . '</a></li>';
    }
  }
    
  

  if ($curr_page != $max_page) {
    echo('
    <li class="page-item">
      <a class="page-link" href="recommendations.php?' . $querystring . 'page=' . ($curr_page + 1) . '" aria-label="Next">
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
<?php include_once("header.php")?>
<?php require("utilities.php")?>
<?php require('database_connection.php')?>


<div class="container">

<h2 class="my-3">Recommendations for you</h2>
<!-- Generated by GPT4 -->
<p>Welcome to your recommendations page! Here, you'll find a selection of items and offers we think you'll love. Browse through and discover great deals tailored just for you.</p>

<div id="searchSpecs">
<?php

## adapted from browse.php

$userID = isset($_SESSION['userID']) ? intval($_SESSION['userID']) : 0;
$conn = connect();
?>

  </div> 
</div> 


<?php

$curr_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;


$results_per_page = 10;
$start_from = ($curr_page - 1) * $results_per_page;


$result = null;
$currentDateTime = date('Y-m-d H:i:s');

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
a.auctionName, 
a.auctionStartingPrice,
a.auctionDescription,
a.categoryType,
a.auctionEndDate,
a.categoryColor,
a.categoryGender,
a.categorySize,
a.auctionPicture,
mb.highestBid,
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
## end of GPT 4 adaptation


$result = mysqli_query($conn, $recQuery);
if (!$result) {
  die('SQL error: ' . mysqli_error($conn));
}


$paginationCountResult = mysqli_query($conn, $recCountQuery);
if (!$paginationCountResult) {
  die('SQL error: ' . mysqli_error($conn));
}



if ($row = mysqli_fetch_assoc($paginationCountResult)) {
  $num_results = $row['total'];
} else {
  die('Error fetching total count: ' . mysqli_error($conn));
}

$max_page = ceil($num_results / $results_per_page);

?>
<?php
  
?>

<div class="container mt-5">
  
<?php
if (mysqli_num_rows($result)==0) {
  echo '<p> ! ! ! There currently are no recommendations available. Please check back soon ! ! ! </p>';
} else {
?>
<ul class="list-group"> 
<?php
  while ($row = mysqli_fetch_assoc($result)){
    $item_id = $row['auctionID'];
    $title = $row['auctionName'];
    $description = $row['auctionDescription'];
    $current_price = isset($row['highestBid']) ? $row['highestBid'] : $row['auctionStartingPrice'];
    $num_bids = $row['numBids'];
    $end_date = new DateTime($row['auctionEndDate']);
    $auctionPicture = $row['auctionPicture'];
    print_listing_li($item_id, $title, $description, $current_price, $num_bids, $end_date,$auctionPicture );
  }
}
?>

</ul>

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
<?php include_once("header.php")?>
<?php require("utilities.php")?>
<?php require('database_connection.php')?>

<div class="container">

<h2 class="my-3">Recommendations for you</h2>
<p>In this recommendations page you will find auctions that user similar to you have bid</p>

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

# Query to find auctions based on similar biders to the current user who have bid on the same auctions
# Count query is used to determine the number of results for pagination 
$recCountQuery = "SELECT 
COUNT(DISTINCT a.auctionID) AS totalAuctions
FROM
Auctions a
JOIN 
Bids b2 ON a.auctionID = b2.auctionID
WHERE 
b2.buyerID IN (
    SELECT DISTINCT b1.buyerID
    FROM Bids b1
    WHERE b1.auctionID IN (
        SELECT DISTINCT b3.auctionID
        FROM Bids b3
        WHERE b3.buyerID = $userID
    )
)
AND NOT EXISTS (
    SELECT 1
    FROM Bids b4
    WHERE b4.auctionID = a.auctionID AND b4.buyerID = $userID
)
GROUP BY
a.auctionID";

# Select query to extract details of relevant auctions 
$recQuery = "SELECT 
    a.auctionID,
    a.auctionName,
    GREATEST(a.auctionStartingPrice, COALESCE(MAX(b2.bidValue), a.auctionStartingPrice)) AS highestBid,
    a.auctionDescription,
    a.categoryType,
    a.auctionEndDate,
    a.categoryColor,
    a.categoryGender,
    a.categorySize,
    a.auctionPicture,
    COUNT(b2.bidID) AS numBids
FROM
    Auctions a
LEFT JOIN Bids b2 ON a.auctionID = b2.auctionID
WHERE 
    b2.buyerID IN (
        SELECT DISTINCT b1.buyerID
        FROM Bids b1
        WHERE b1.auctionID IN (
            SELECT DISTINCT b3.auctionID
            FROM Bids b3
            WHERE b3.buyerID = $userID
        )
    )
    AND NOT EXISTS (
        SELECT 1
        FROM Bids b4
        WHERE b4.auctionID = a.auctionID AND b4.buyerID = $userID
    )
GROUP BY
    a.auctionID";


#
$recQuery .= " LIMIT $start_from, $results_per_page";

$result = send_query($recQuery);
if (!$result) {
  die('SQL error: ' . mysqli_error($conn));
}


$paginationCountResult = send_query($recCountQuery);
if (!$paginationCountResult) {
  die('SQL error: ' . mysqli_error($conn));
}

$row = mysqli_fetch_assoc($paginationCountResult);


if ($row == 0) {
  $num_results = 0;
} else {
  $num_results = $row['totalAuctions'];
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
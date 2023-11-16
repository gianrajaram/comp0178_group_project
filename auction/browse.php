<?php include_once("header.php")?>
<?php require("utilities.php")?>

<div class="container">

<h2 class="my-3">Browse listings</h2>

<div id="searchSpecs">
<!-- When this form is submitted, this PHP page is what processes it.
     Search/sort specs are passed to this page through parameters in the URL
     (GET method of passing data to a page). -->


<!-- added name="keyword" to <input type="text" class="form-control border-left-0" -->
<form method="get" action="browse.php">
  <div class="row">
    <div class="col-md-5 pr-0">
      <div class="form-group">
        <label for="keyword" class="sr-only">Search keyword:</label>
	    <div class="input-group">
          <div class="input-group-prepend">
            <span class="input-group-text bg-transparent pr-0 text-muted">
              <i class="fa fa-search"></i>
            </span>
          </div>
          <input type="text" class="form-control border-left-0" id="keyword" name="keyword" placeholder="Search for items">
        </div>
      </div>
    </div>
    <div class="col-md-3 pr-0">
      <div class="form-group">
        <label for="cat" class="sr-only">Search within:</label>
        <select class="form-control" id="cat" name="cat">
          <option selected value="all">All categories</option>
          <option value="fill">Fill me in</option>
          <option value="with">with options</option>
          <option value="populated">populated from a database?</option>
        </select>
      </div>
    </div>
    <div class="col-md-3 pr-0">
      <div class="form-inline">
        <label class="mx-2" for="order_by">Sort by:</label>
        <select class="form-control" id="order_by" name="order_by">
          <option selected value="pricelow">Price (low to high)</option>
          <option value="pricehigh">Price (high to low)</option>
          <option value="date">Soonest expiry</option>
        </select>
      </div>
    </div>
    <div class="col-md-1 px-0">
      <button type="submit" class="btn btn-primary">Search</button>
    </div>
  </div>
</form>
</div> <!-- end search specs bar -->


</div>

<?php

## adding DATABASE connection block: TO remove and change to GabiFunction
$servername="localhost";
$username='AuctionProject';
$password='london2023';
$DB_name='website_auction';
$conn = new mysqli($servername,$username,$password,$DB_name);

#GR - function to sanitise user inputted text - Best practice according to chatgpt - possibly add to utilities.php if useful elsewhere?
function sanitise_input($data) {
  $data = trim($data); # removes whitespaces from the beginning and end of user input
  $data = stripslashes($data); #removes backslashes from data
  $data = htmlspecialchars($data); #
  return $data;
}
  
  if ($conn->connect_error) {
  die('Connection failed: '. $conn->connect_error);
  }

  // Retrieve these from the URL
  if (!isset($_GET['keyword'])) {
    $keyword = "";
    // TODO: Define behavior if a keyword has not been specified.
  }
  # cleaning user input to efficiently query the database and return relevant results
  else {
    $keyword = sanitise_input($_GET['keyword']); #implemented a function that will clean the data of projected user errors, resulting in a higher probability of a correct match to database
  }
  if (!isset($_GET['cat'])) {
    $category = "All categories";
    // TODO: Define behavior if a category has not been specified.
  }
  else {
    $category = $_GET['cat'];
  }
  if (!isset($_GET['order_by'])) {
    $ordering = 'pricelow';
    // TODO: Define behavior if an order_by value has not been specified.
  }
  else {
    $ordering = $_GET['order_by'];
  }
  
  if (!isset($_GET['page'])) {
    $curr_page = 1;
  }
  else {
    $curr_page = $_GET['page'];
  }
  
    /* TODO: Use above values to construct a query. Use this query to 
     retrieve data from the database. 
     (If there is no form data entered,
     decide on appropriate default value/default query to make. */
     
# initialising vairbales that will hold the rest of the queries 
$result = null;

$isFormSubmitted = isset($_GET['keyword']) || isset($_GET['cat']) || isset($_GET['order_by']);

if ($isFormSubmitted) {

  $query = 'SELECT auctionID, auctionName, auctionDescription, categoryType, auctionBidCount, auctionEndDate FROM Auctions WHERE 1';
  #doublecheck if countQuery is correct: Current logic <- line below is only changed for one of the 3 filtering conditions - checked, correct, theoretically else will count all rows in auctions table.
  $countQuery = 'SELECT COUNT(*) AS total FROM Auctions WHERE 1';

  if (!empty($keyword)){
    $keyword = mysqli_real_escape_string($conn,$keyword);
    $query .= " AND MATCH (auctionName) AGAINST ('" . $keyword . "' IN NATURAL LANGUAGE MODE) ";
    $baseQueryCount = " AND MATCH (auctionName) AGAINST ('" . $keyword . "' IN NATURAL LANGUAGE MODE) ";
  }
  #Category condition:
  # will work correctly after adding category types to html code top of script. Same iteration for each lower level node. DOUBLE CHECK LOGIC MATCHES.
  if ($category != 'all') {
    $category = mysqli_real_escape_string($conn,$category);
    $query .=  "AND categoryType = '" . $category . "' ";
    $countQuery .=  "AND categoryType '" . $category . "' ";
  }

  ###ERROR IN THIS SECTION - auctionCurrentHighestBid not defined -> pull and match query from Bids table using common column: auctionID
  switch($ordering) {
    case 'pricelow':
      $query .= ' ORDER BY auctionCurrentHighestBid ASC ';
      break;
    case 'pricehigh':
      $query .= ' ORDER BY auctionCurrentHighestBid DESC ';
      break;
    case 'date':
      $query .= ' ORDER BY auctionEndDate ASC ';
      break;
    }
    $result = mysqli_query($conn,$query);
    if (!$result){
      die('SQL error: ln160 !dollaresult '.mysqli_error($conn));
    }
} else {
  $defaultQuery = 'SELECT auctionID, auctionName, auctionDescription, categoryType, auctionBidCount, auctionEndDate FROM Auctions';
  $result = mysqli_query($conn,$defaultQuery);
  if (!$result){
    die('SQL error: ln167 if !dollaresult'.mysqli_error($conn));
  }
}
$countResult = mysqli_query($conn,$countQuery);
if (!countResult) {
  die('SQL error: dollacountResult'.mysqli_error($conn));
}
$totalFilteredRecords = mysqli_fetch_array($countResult);
$num_results = $totalFilteredRecord['total']; // TODO: Calculate me for real
# GR Edit: Added condition for calculating based on each case of the dynamic SQL query.
$results_per_page = 10;
# GR Edit: leave as is
$max_page = ceil($num_results / $results_per_page);
# GR Edit: Leave as is
?>
<?php
  /* For the purposes of pagination, it would also be helpful to know the
     total number of results that satisfy the above query */
?>

<div class="container mt-5">
  <!-- TODO: If result set is empty, print an informative message. Otherwise... -->

  
<?php
#line below condition should theoretically never be zero, ENSURE LOGIC IS CONSISTENT THROUGHOUT (triple check once complete with all features)
if (mysqli_num_rows($result)==0) {
  echo '<p> No listings were found under the given criteria </p>';
} else {

  # EXITING PHP TO ADD DIVIDER FOR HTML CONTENT (so while loop appears in the correct place, under html class before next line starting with <?php )
  
  ?>
<ul class="list-group"> 
<!-- re-entering php mode -->
<!-- TODO: Use a while loop to print a list item for each auction listing retrieved from the query -->


<?php
# TEMPORARY VALUES auctionCurrentHighestBid and auctionBidCount currently null values in database and need to be queried
#PLACEHOLDERS added below

  while ($row = mysqli_fetch_assoc($result)){
    $item_id = $row['auctionID'];
    $title = $row['auctionName'];
    $description = $row['auctionDescription'];
    $current_price = 13;
    $num_bids = 21;
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

  // Copy any currently-set GET variables to the URL.
  $querystring = "";
  foreach ($_GET as $key => $value) {
    if ($key != "page") {
      $querystring .= "$key=$value&amp;";
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
      echo('
    <li class="page-item active">');
    }
    else {
      // Non-highlighted link
      echo('
    <li class="page-item">');
    }
    
    // Do this in any case
    echo('
      <a class="page-link" href="browse.php?' . $querystring . 'page=' . $i . '">' . $i . '</a>
    </li>');
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
  $conn->close();
?>

  </ul>
</nav>


</div>



<?php include_once("footer.php")?>
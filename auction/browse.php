<?php include_once("header.php")?>
<?php require("utilities.php")?>

<div class="container">

<h2 class="my-3">Browse listings</h2>

<div id="searchSpecs">
<!-- When this form is submitted, this PHP page is what processes it.
     Search/sort specs are passed to this page through parameters in the URL
     (GET method of passing data to a page). -->



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
          <input type="text" class="form-control border-left-0" id="keyword" placeholder="Search for items">
        </div>
      </div>
    </div>
    <div class="col-md-3 pr-0">
      <div class="form-group">
        <label for="cat" class="sr-only">Search within:</label>
        <select class="form-control" id="cat">
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
        <select class="form-control" id="order_by">
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
{ #GR - function to sanitise user inputted text
  function sanitise_input($data) {
    $data = trim($data); # removes whitespaces from the beginning and end of user input
    $data = stripslashes($data); #removes backslashes from data
    $data = htmlspecialchars($data); #
    return $data;
  }
  # you added below 5 options as global variables double check if this is correct way of querying/would cause complications for anyone else.
  $servername="localhost";
  $username='AuctionProject';
  $password='london2023';
  $DB_name='website_auction';
  $conn = new mysqli($servername,$username,$password,$DB_name);
  if ($conn->connect_error) {
  die('Connection failed: '. $conn->connect_error);
  }
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
     retrieve data from the database. (If there is no form data entered,
     decide on appropriate default value/default query to make. */
  
  ## adding a query for max bid for each auction in place of auctionCurrentHighestBid / so you need to find with a query the max Bid for each auction and order them accordingly
## target: 1. pull columns from bid table. 2. Join with $query


  ## building a dynamic query based on above conditions:



  $bidQuery = 'SELECT bidValue, buyerID, auctionID FROM Bids';


  $query = 'SELECT auctionID, auctionName, auctionDescription, auctionCurrentHighestBid, auctionBidCount, auctionEndDate FROM Auctions WHERE 1';
  $countQuery = 'SELECT (*) COUNT FROM Auctions WHERE 1';

#$keyword search IN NATURAL LANGUAGE MODE -> CURRENTLY DEPENDS ON FULLTEXT MODE BEING ENABLED
# else: simplify

## adding count conditions -
  if (!empty($keyword)){
    $keyword = mysqli_real_escape_string($conn,$keyword);
    $query .= " AND MATCH (auctionName) AGAINST ('" . $keyword . "' IN NATURAL LANGUAGE MODE) ";
    $baseQueryCount = " AND MATCH (auctionName) AGAINST ('" . $keyword . "' IN NATURAL LANGUAGE MODE) ";
  }
  #Category condition:
  ## adding value of category for now <- because I still need to add loop in <select>(HTML) for exact categories.
  if($category != 'all') {
    $category = $_GET['cat'];
    $query .= "AND '" . $category . "' ";
    $countQuery .= "AND '" . $category . "' ";
    # removed ->CategoryClothsType<- from after AND, resulting in no SQL error
  }

  ###ERROR IN THIS SECTION - auctionCurrentHighestBid not defined
  #ordering -> expand based on all categories of ordering
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

  $countResult = $conn->query($countQuery);
  if (!$countResult){
    die('SQL error: '.myslqi_error($conn));

  }
  }


  
  #$query = 'SELECT auctionID, auctionName,auctionDescription,auctionCurrentHighestBid, auctionBidCount,auctionEndDate FROM Auctions';


  /* For the purposes of pagination, it would also be helpful to know the
     total number of results that satisfy the above query */
  $lineInQuery = $countResult->fetch_row();
  $num_results = $lineInQuery[0]; // TODO: Calculate me for real
  # GR Edit: Added condition for calculating based on each case of the dynamic SQL query.
  $results_per_page = 10;
  # GR Edit: leave as is
  $max_page = ceil($num_results / $results_per_page);
  # GR Edit: Leave as is
?>

<div class="container mt-5">
<?php
if (mysqli_num_rows($result)==0) {
  echo '<p> No listings were found under the given criteria </p>';
}
?>

<!-- TODO: If result set is empty, print an informative message. Otherwise... -->

<ul class="list-group"> <!------------------------------------------------- LISTINGS -->


<!-- TODO: Use a while loop to print a list item for each auction listing retrieved from the query -->

<?php
# Gian Testing:
#executing query
$result = mysqli_query($conn,$query);
#Testing if query returns error
if (!$result){
  die('SQL error: '.mysqli_error($conn));
}

  while($row = mysqli_fetch_assoc($result)) {
    $item_id = $row['auctionID'];
    $title = $row['auctionName'];
    $description = $row['auctionDescription'];
    $current_price = $row['auctionCurrentHighestBid'];
    $num_bids = $row['auctionBidCount'];
    $end_date = new DateTime($row['auctionEndDate']);

    print_listing_li($item_id, $title, $description, $current_price, $num_bids, $end_date);
  }




  // Demonstration of what listings will look like using dummy data.
 # $item_id = "87021";
  #$title = "Dummy title";
 # $desc = "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum eget rutrum ipsum. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Phasellus feugiat, ipsum vel egestas elementum, sem mi vestibulum eros, et facilisis dui nisi eget metus. In non elit felis. Ut lacus sem, pulvinar ultricies pretium sed, viverra ac sapien. Vivamus condimentum aliquam rutrum. Phasellus iaculis faucibus pellentesque. Sed sem urna, maximus vitae cursus id, malesuada nec lectus. Vestibulum scelerisque vulputate elit ut laoreet. Praesent vitae orci sed metus varius posuere sagittis non mi.";
  #$current_price = 30;
  #$num_bids = 1;
  #$end_date = new DateTime('2020-09-16T11:00:00');
  
  // This uses a function defined in utilities.php
 # print_listing_li($item_id, $title, $desc, $current_price, $num_bids, $end_date);
  
 # $item_id = "516";
 # $title = "Different title";
 # $description = "Very short description.";
 # $current_price = 13.50;
 # $num_bids = 3;
  #$end_date = new DateTime('2020-11-02T00:00:00');
  
 # print_listing_li($item_id, $title, $description, $current_price, $num_bids, $end_date);

  #$item_id = "1313";
 # $title = "Gian Test";
  #$description = "desc";
  #$current_price = 21;
  #$num_bids = 2;
  #$end_date = new DateTime('2020-11-02T00:00:00');


  #print_listing_li($item_id, $title, $description, $current_price, $num_bids, $end_date);

  #$servername="localhost";
  #$username='AuctionProject';
  #$password='london2023';
  #$DB_name='website_auction';
  #  $conn = new mysqli($servername,$username,$password,$DB_name);
  #  if ($conn->connect_error) {
   #   die('Connection failed: '. $conn->connect_error);
   # }
    # SQL query in below else statement pending database change -> auctionName to FULLTEXT (index), this is a pre-req for MATCH ... AGAINST in NATURAL LANGUAGE MODE to work. Query should theoretically be correct.
   # else{
  #  $query = 'SELECT auctionID, auctionName,auctionDescription,auctionCurrentHighestBid, auctionBidCount,auctionEndDate FROM Auctions WHERE MATCH($keyword) AGAINST (auctionName) IN NATURAL LANGUAGE MODE';
   # $resultKeyWord = send_query($query);
   # $searchSubset = [];
   # while($rows = mysqli_fetch_assoc($resultKeyWord)) {
  #    $searchSubset[] = $rows;
   # }

   # foreach ($searchSubset as $row) {
  #  $item_id = $row['auctionID'];
   # $title = $row['auctionName'];
   # $description = $row['auctionDescription'];
  #  $current_price = $row['auctionCurrentHighestBid'];
   # $num_bids = $row['auctionBidCount'];
   # $end_date = $row['auctionEndDate'];
   # print_listing_li($item_id,$title,$description,$current_price,$num_bids,$end_date);
#
   # }
   # }

  #  mysqli_close($conn);
   # }
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
?>

  </ul>
</nav>


</div>



<?php include_once("footer.php")?>
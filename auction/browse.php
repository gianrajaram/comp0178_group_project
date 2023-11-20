<?php include_once("header.php")?>
<?php require("utilities.php")?>
<?php require('database_connection.php')?>

<div class="container">

<h2 class="my-3">Browse listings</h2>

<div id="searchSpecs">
<!-- When this form is submitted, this PHP page is what processes it.
     Search/sort specs are passed to this page through parameters in the URL
     (GET method of passing data to a page). -->


<?php
##IMPORTANT
$conn = connect();

## add new button for filtering to only active auctions
## initialisied variable $category which is needed to save user selection during form submission -> implemented within first HTML 
## double check if this is needed here.

if (isset($_GET['cat'])) {
  $category = $_GET['cat']; 
} else {
  $category = "all"; 
}

## initialised $ordering variable needed to save user selection during form submission -> implemented within first HTML section
if (isset($_GET['order_by'])) {
  $ordering = $_GET['order_by'];
} else {
  $ordering = 'Price'; 
  #$ordering = 'pricelow';
}

## initialise variable $colour
if (isset($_GET['colour'])) {
  $colour = $_GET['colour'];
} else {
  $colour = 'all';
}

## initialise variable $gender
if (isset($_GET['gender'])) {
  $gender = $_GET['gender'];
} else {
  $gender = 'all';
}
# initalise variable $size
if (isset($_GET['size'])) {
  $size = $_GET['size'];
} else {
  $size = 'all';
}


## mysqli_query changed to send_query  -> filtering doesn't work? why
## initialised clothingtype variable needed for loop in html script section
## $conn changed to $connection

$clothingQuery = 'SELECT categoryType FROM CategoryClothsType';
$resultClothes = send_query($clothingQuery);
if (!$resultClothes){
  die('SQL error: top of script, clothingtype code '.mysqli_error($conn));
} else {
  $categoriesClothes =[];
  while ($row = mysqli_fetch_assoc($resultClothes)) {
    $categoriesClothes[] = $row;
}
}

$colourQuery = 'SELECT categoryColor FROM CategoryColorType';
$resultColour = send_query( $colourQuery);
if (!$resultColour) {
  die('SQL error: top of script, colourtype initialisation'.mysqli_error($conn));
} else {
  $colourType =[];
  while($row = mysqli_fetch_assoc($resultColour)) {
    $colourType[] = $row;
  }
}

$genderQuery = 'SELECT categoryGender FROM CategoryGenderType';
$resultGender = send_query($genderQuery);
if(!$resultGender) {
  die('SQL error: top of script, gendertype initialisation'.mysqli_error($conn));
} else {
  $genderType =[];
  while($row = mysqli_fetch_assoc($resultGender)) {
    $genderType[] = $row;
  }
}

$sizeQuery = 'SELECT categorySize FROM CategorySizeType';
$resultSize = send_query($sizeQuery);
if(!$resultSize) {
  die('SQL error: top of script, categorysize initialisation'.mysqli_error($conn));
} else {
  $sizeType=[];
  while($row = mysqli_fetch_assoc($resultSize)) {
    $sizeType[] = $row;
  }
}
?>


<!-- added name="keyword" to <input type="text" class="form-control border-left-0" -->
<form method="get" action="browse.php">
   <!-- HIDDEN KEYWORD ELEMENT   -->

   <input type="hidden" name="page" value="<?php echo $curr_page; ?>">

  <div class="row">
    <div class="col-md-2 pr-0">
      <div class="form-group">
        <label for="keyword" class="sr-only">Search keyword:</label>
	    <div class="input-group">
          <div class="input-group-prepend">
            <span class="input-group-text bg-transparent pr-0 text-muted">
              <i class="fa fa-search"></i>
            </span>
          </div>
             <!-- HIDDEN KEYWORD ELEMENT CONTINUED, saves value of keyword from url in case where the form is submitted multiple times eg a user searches 'stylish'    -->
                          <!-- and wants to filter the subset of results by any other drop-down box  -->
          <input type="text" class="form-control border-left-0" id="keyword" name="keyword" placeholder= "Search:" value ="<?php echo isset($_GET['keyword']) ? htmlspecialchars($_GET['keyword']) : ''; ?>">
        </div>
      </div>
    </div>
    <div class="col-md-2 pr-0">
      <div class="form-group">
        <label for="cat" class="sr-only">Search within:</label>
        <select class="form-control" id="cat" name="cat">
          <!-- dynamically retain user selection in form -->
        <option value="all" <?php echo $category === 'all' ? 'selected' : '' ?>>Category</option>
          <!-- REMOVED CODE FOR TESTING: <option value="all">All categories</option> -->
          <?php
              foreach ($categoriesClothes as $buttonCategory) {
                $isSelected = $buttonCategory['categoryType'] === $category ? 'selected' :'';
                echo '<option value="'. $buttonCategory ['categoryType'] .'" '. $isSelected. '>'.$buttonCategory['categoryType'] .'</option>';
              }
              ?>
        </select>
      </div>
    </div>
     <!-- populating more category options below -->

    <div class="col-md-2 pr-0">
  <div class="form-group">
  <label for="colour" class="sr-only">Search within:</label>
    <select class="form-control" id="colour" name="colour">
    <option value="all" <?php echo $colour === 'all' ? 'selected' : '' ?>>Colour</option>
      <!-- double check if $buttonCategory works well-->
      <?php
      foreach ($colourType as $buttonCategory) {
        $isSelected = $buttonCategory['categoryColor'] === $colour ? 'selected' : '';
        echo '<option value="'. $buttonCategory ['categoryColor'] .'" '. $isSelected. '>'.$buttonCategory['categoryColor'] .'</option>';
      }
      ?>
    </select>
  </div>
</div>

<div class="col-md-2 pr-0">
  <div class="form-group">
    <label for="gender" class= "sr-only" >Gender:</label>
    <select class="form-control" id="gender" name="gender">
      <option value="all">Gender</option>
      <?php
      foreach($genderType as $buttonCategory) {
        $isSelected = $buttonCategory['categoryGender'] === $gender ? 'selected' : '';
        echo '<option value="'. $buttonCategory ['categoryGender'] .'" '. $isSelected. '>'.$buttonCategory['categoryGender'] .'</option>';
      }
      ?>
    </select>
  </div>
</div>

<div class="col-md-1 pr-0">
  <div class="form-group">
    <label for="size" class= "sr-only">Size:</label>
    <select class="form-control" id="size" name="size">
      <option value="all">Size</option>
      <?php
      foreach($sizeType as $buttonCategory) {
        $isSelected = $buttonCategory['categorySize'] === $size ? 'selected' : '';
        echo '<option value="'. $buttonCategory ['categorySize'] .'" '.$isSelected. '>'.$buttonCategory['categorySize'] .'</option>';
      }
      ?>
      <!-- PHP code will populate more options here -->
    </select>
  </div>
</div>

    <div class="col-md-2 pr-0">
      <div class="form-group">
        <label  for="order_by" class="sr-only">Sort by:</label>
        <select class="form-control" id="order_by" name="order_by">
        <option value = "Price" >Price</option>
        <option value="pricelow" <?php echo $ordering === 'pricelow' ? 'selected' : '' ?>>Low to High</option>
        <option value="pricehigh" <?php echo $ordering === 'pricehigh' ? 'selected' : '' ?>>High to Low</option>
       <option value="date" <?php echo $ordering === 'date' ? 'selected' : '' ?>>Soonest expiry</option>
        </select>
      </div>
    </div>
    <div class="col-md-1 px-0">
      <button type="submit" class="btn btn-primary">Search</button>
    </div>
  </div>

  <div class="row">
    <div class="col-md-4 pr-0">
  <div class="form-group">
      <div class="form-group">
        <label for="AIkeyword" class="sr-only">Search: </label>
	    <div class="input-group">
          <div class="input-group-prepend">
            <span class="input-group-text bg-transparent pr-0 text-muted">
              <i class="fa fa-search"></i>
            </span>
          </div>
             <!-- HIDDEN KEYWORD ELEMENT CONTINUED, saves value of keyword from url in case where the form is submitted multiple times eg a user searches 'stylish'    -->
                          <!-- and wants to filter the subset of results by any other drop-down box  -->
         <input type="text" class="form-control border-left-0" id="AIkeyword" name="AIkeyword" placeholder= "Explore creatively with AI search:" value ="<?php echo isset($_GET['AIkeyword']) ? htmlspecialchars($_GET['AIkeyword']) : ''; ?>">
        </div>
      </div>
    </div>

</div>

</form>
</div> <!-- end search specs bar -->

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


  $query .= " LIMIT $start_from, $results_per_page";
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

$defaultQuery .=  " LIMIT $start_from, $results_per_page";
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
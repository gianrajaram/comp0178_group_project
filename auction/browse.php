<?php include_once("header.php")?>
<?php require("utilities.php")?>
<?php require('database_connection.php')?>


<div class="container">
<h2 class="my-3">Browse listings</h2>
<div id="searchSpecs">

<!-- GPT4 used primarily for debugging & logic testing throughout the script. I have added specific comments for
any segment of the script where AI generated code appears. 
Specific methods suggested initially by GPT4, then learned and used throughout the script include:
- learning mysqli[...] functions
- php shorthand "if-else" ternary operator eg: $ordering = isset($_GET['order_by']) ? $_GET['order_by'] : 'Price';
- "hiding" variables to URL allowing for dynamic filtering & pagination
- "Where 1" SQL element, allowing for dynamic query construction
- NATURAL LANGUAGE MODE SQL match + database FULLTEXT mode requirement   -->



<?php
$conn = connect();
## initialising variables in PHP script prior to appearing in the first HTML section, because some are used there and would otherwise cause errors.
## if not used in HTML section, variables are needed for $isFormSubmmited IF-ELSE section, or pagination


$keyword = isset($_GET['keyword']) ? sanitise_input($_GET['keyword']) : "";
$AIkeyword = isset($_GET['AIkeyword']) ? sanitise_input($_GET['AIkeyword']) : "";
$category = isset($_GET['cat']) ? $_GET['cat'] : 'all';
$ordering = isset($_GET['order_by']) ? $_GET['order_by'] : 'Price';
$colour = isset($_GET['colour']) ? $_GET['colour'] : 'all';
$gender = isset($_GET['gender']) ? $_GET['gender'] : 'all';
$size = isset($_GET['size']) ? $_GET['size'] : 'all';
$activeListing = isset($_GET['active']) ? $_GET['active'] : 'all';

## resolving logic outlier of user navigating to page 3 then searching
$isFormSubmitted = isset($_GET['keyword']) || isset($_GET['cat']) || isset($_GET['colour']) || isset ($_GET['gender']) || isset($_GET['size']) || isset($_GET['order_by']) || isset($_GET['AIkeyword']);


$curr_page = $isFormSubmitted ? 1 : (isset($_GET['page']) ? (int)$_GET['page'] : 1);


## 



## below 4 queries necessary for foreach HTML section
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


<form method="get" action="browse.php">
   <!-- HIDDEN KEYWORD ELEMENT - necessary for UX, allowing for pagination to work in accordance with submitted form filtering 
  GPT 4-->
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
        
          <input type="text" class="form-control border-left-0" id="keyword" name="keyword" placeholder= "Search:" value ="<?php echo isset($_GET['keyword']) ? htmlspecialchars($_GET['keyword']) : ''; ?>">
        </div>
      </div>
    </div>
    <div class="col-md-2 pr-0">
      <div class="form-group">
        <label for="cat" class="sr-only">Search within:</label>
        <select class="form-control" id="cat" name="cat">
          <!-- PHP ternary operator and below line of code created using GPT4 -->
        <option value="all" <?php echo $category === 'all' ? 'selected' : '' ?>>Category</option>
          <?php
              foreach ($categoriesClothes as $buttonCategory) {
                $isSelected = $buttonCategory['categoryType'] === $category ? 'selected' :'';
                echo '<option value="'. $buttonCategory ['categoryType'] .'" '. $isSelected. '>'.$buttonCategory['categoryType'] .'</option>';
              }
              ?>
        </select>
      </div>
    </div>

    <div class="col-md-2 pr-0">
  <div class="form-group">
  <label for="colour" class="sr-only">Search within:</label>
    <select class="form-control" id="colour" name="colour">
    <option value="all" <?php echo $colour === 'all' ? 'selected' : '' ?>>Colour</option>
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
         <input type="text" class="form-control border-left-0" id="AIkeyword" name="AIkeyword" placeholder= "Explore creatively with AI search:" value ="<?php echo isset($_GET['AIkeyword']) ? htmlspecialchars($_GET['AIkeyword']) : ''; ?>">
        </div>
      </div>
    </div>
</div>

</form>

<div class="col-md-2 pr-0">
        <div class="form-group">
          <label  for="active" class="sr-only">Active listing filter</label>
          <select class="form-control" id="active" name="active">
          <option value = "all" >All listings</option>
          <option value='active' <?php echo $activeListing === 'active' ? 'selected' : '' ?>>Show active listings only </option>
          </select>
        </div>
      </div>
    </div>
  </div> 
</div> <!-- end search specs bar -->

<?php

$result = null;
$results_per_page = 10;

$start_from = ($curr_page - 1) * $results_per_page;
$currentDateTime = date('Y-m-d H:i:s');

# conditional operator || solution borrowed from GPT4


if ($isFormSubmitted) {
  ## GPT 4 used for query construction
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
    $query .= " LIMIT $start_from, $results_per_page";

  $paginationCount = send_query($countQuery);
  if (!$paginationCount){
  die('SQL error: ln 378 paginationresult '.mysqli_error($conn));
  }

  $result = send_query($query);
  if (!$result){
      die('SQL error: '.mysqli_error($conn));
  }
} else {
  ## GPT 4 used query construction.
  $defaultCountQuery = "SELECT COUNT(DISTINCT a.auctionID) AS total FROM Auctions a LEFT JOIN Bids b ON a.auctionID = b.auctionID LEFT JOIN (SELECT auctionID, MAX(bidValue) AS highestBid FROM Bids GROUP BY auctionID) mb ON a.auctionID = mb.auctionID WHERE 1";
  $defaultQuery = "SELECT 
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
  WHERE 1";


$defaultQuery .= ' GROUP BY a.auctionID';
$defaultCountQuery .= ' GROUP BY a.auctionID';
# this section below is causing the error, remove it and everything works
$defaultQuery .= " LIMIT $start_from, $results_per_page";

$paginationCount = send_query($defaultCountQuery);
if(!$paginationCount){
  die('Connection failed: ' . $conn->connect_error);
}

$result = send_query($defaultQuery);
if(!$result){
  die('connection failed' );
}
}



$num_results = mysqli_num_rows($paginationCount);

$max_page = ceil($num_results / $results_per_page);

?>
<?php

?>

<div class="container mt-5">
  
<?php
if (mysqli_num_rows($result)==0) {
  echo '<p> ! ! ! No listings were found under the given criteria ! ! ! </p>';
} else {
  ## exiting PHP mode tp include HTML line below
  ?>
<ul class="list-group"> 
<!-- re-entering PHP -->
<?php
  while ($row = mysqli_fetch_assoc($result)){
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
      # below line adapted from GPT4
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
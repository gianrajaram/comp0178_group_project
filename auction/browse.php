<?php include_once("header.php")?>
<?php require("utilities.php")?>

<div class="container">

<h2 class="my-3">Browse listings</h2>

<div id="searchSpecs">
<!-- When this form is submitted, this PHP page is what processes it.
     Search/sort specs are passed to this page through parameters in the URL
     (GET method of passing data to a page). -->

<!-- adding code to loop through and find all categories from page -->

<?php

$servername="localhost";
$username='AuctionProject';
$password='london2023';
$DB_name='website_auction';
$conn = new mysqli($servername,$username,$password,$DB_name);


# added error handling
if ($conn->connect_error) {
  die('Connection failed: ' . $conn->connect_error);
}

## initialisied variable $category which is needed to save user selection during form submission -> implemented within first HTML 
## DOUBLE CHECK IF && IS NEEDED
# is this first initialisation of the variable causing errors? is this the reason server MAY be overloading causing time lag? explore adding mysqli close after each session within <?php >

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

## initialised clothingtype variable needed for loop in html script section
$clothingQuery = 'SELECT categoryType FROM CategoryClothsType';
$resultClothes = mysqli_query($conn, $clothingQuery);
if (!$resultClothes){
  die('SQL error: top of script, clothingtype code '.mysqli_error($conn));
} else {
  $categoriesClothes =[];
  while ($row = mysqli_fetch_assoc($resultClothes)) {
    $categoriesClothes[] = $row;
}
}

$colourQuery = 'SELECT categoryColor FROM CategoryColorType';
$resultColour = mysqli_query($conn, $colourQuery);
if (!$resultColour) {
  die('SQL error: top of script, colourtype initialisation'.mysqli_error($conn));
} else {
  $colourType =[];
  while($row = mysqli_fetch_assoc($resultColour)) {
    $colourType[] = $row;
  }
}

$genderQuery = 'SELECT categoryGender FROM CategoryGenderType';
$resultGender = mysqli_query($conn,$genderQuery);
if(!$resultGender) {
  die('SQL error: top of script, gendertype initialisation'.mysqli_error($conn));
} else {
  $genderType =[];
  while($row = mysqli_fetch_assoc($resultGender)) {
    $genderType[] = $row;
  }
}

$sizeQuery = 'SELECT categorySize FROM CategorySizeType';
$resultSize = mysqli_query($conn, $sizeQuery);
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

##  Outstanding steps

# 1. Pagination -> implement
# 2. read through 'die' statements to return something more reasonable than info for dev -> debugging
# 3. COUNTQUERY MIGHT NEED updating to match auctions x bids? no idea check
# 4. Added DATABASE connection block: TO remove and change to GabiFunction
# 5. obsolete logic, defining !$isset .... $ category = 'Category not needed. Understand logic to see why and possible take out
#     Above is likely because you shifted these to top of the page
##    YEP tried removing category from this section, entire section can likely be taken out because it has been shifted to top of page but make sure!
#     move keyword to top for clarity?

  
  if ($conn->connect_error) {
  die('Connection failed: '. $conn->connect_error);
  }

  // Retrieve these from the URL

  $keyword = isset($_GET['keyword']) ? sanitise_input($_GET['keyword']) : "";
  $AIkeyword = isset($_GET['AIkeyword']) ? sanitise_input($_GET['AIkeyword']) : "";
  

  if (!isset($_GET['cat'])) {
    $category = "Category";
    // TODO: Define behavior if a category has not been specified.
  } else {
    ### DEFINING BEHAVIOUR IF KEYWORD IS SPECIFIED behaviour if keyword is specified
    $category = $_GET['cat'];
  }

  if (!isset($_GET['order_by'])) {
    $ordering = 'Price';
    // TODO: Define behavior if an order_by value has not been specified.
  } else {
    $ordering = $_GET['order_by'];
  }
  
  if (!isset($_GET['page'])) {
    $curr_page = 1;
  } else {
    $curr_page = $_GET['page'];
  }

  if (!isset($_GET['colour'])) {
    $colour = 'All Colours';
  } else {
    $colour = $_GET['colour'];
  }

  if (!isset($_GET['gender'])) {
    $gender = 'Gender';
  }
  
    /* TODO: Use above values to construct a query. Use this query to 
     retrieve data from the database. 
     (If there is no form data entered,
     decide on appropriate default value/default query to make. */
     
# initialising vairbales that will hold the rest of the queries 
$result = null;

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
## ABOVE LOGIC IS MISSING THE CASE WHERE THERE IS NO BID PLACED


  #doublecheck if countQuery is correct: Current logic <- line below is only changed for one of the 3 filtering conditions - checked, correct, theoretically else will count all rows in auctions table.
  $countQuery = 'SELECT COUNT(*) AS total FROM Auctions WHERE 1';


  if (!empty($keyword) && !empty($AIkeyword)) {
    $combinedSearchTerm = mysqli_real_escape_string($conn, $keyword . ' ' . $AIkeyword);
    $query .= " AND MATCH (auctionDescription) AGAINST ('" . $combinedSearchTerm . "' IN NATURAL LANGUAGE MODE) ";

  } else {
    if (!empty($AIkeyword)) {
      $AIkeyword = mysqli_real_escape_string($conn, $AIkeyword);
      $query .= " AND MATCH (auctionDescription) AGAINST ('" . $AIkeyword . "' IN NATURAL LANGUAGE MODE) ";
  } 
  if (!empty($keyword)) {
      $keyword = mysqli_real_escape_string($conn, $keyword);
      $query .= " AND MATCH (auctionName) AGAINST ('" . $keyword . "' IN NATURAL LANGUAGE MODE) ";
  }
}
  
  
  #Category condition:
  # will work correctly after adding category types to html code top of script. Same iteration for each lower level node. DOUBLE CHECK LOGIC MATCHES.
  # double checked top of script html+php foreach loop - should be pulling the correct data
  if ($category != 'all') {
    $category = mysqli_real_escape_string($conn,$category);
    $query .=  "AND categoryType = '" . $category . "' ";
    $countQuery .=  "AND categoryType '" . $category . "' ";
  }
  if ($colour != 'all') {
    $colour = mysqli_real_escape_string($conn, $colour);
    $query .= "AND categoryColor = '" . $colour . "' ";
    $countQuery .= "AND categoryColor '" . $colour . "' ";
  }
if ($gender != 'all') {
  $gender = mysqli_real_escape_string($conn, $gender);
  $query .= "AND categoryGender = '" .$gender . "' ";
  $countQuery .= "AND categoryGender '" .$gender . "' ";
}

if ($size != 'all') {
  $size = mysqli_real_escape_string($conn, $size);
  $query .= "AND categorySize = '" .$size . "' ";
  $countQuery .= "AND categorySize = '" .$size . "' ";
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
    $result = mysqli_query($conn,$query);
    if (!$result){
      die('SQL error: ln160 !dollaresult '.mysqli_error($conn));
    }

} else {
  $defaultQuery = 'SELECT 
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

$defaultQuery .= ' GROUP BY a.auctionID, a.auctionName, a.auctionStartingPrice, a.auctionDescription, a.categoryType, a.auctionEndDate, a.categoryColor, a.categoryGender, a.categorySize';

  $result = mysqli_query($conn,$defaultQuery);
  if (!$result){
    die('SQL error: ln167 if !dollaresult'.mysqli_error($conn));
  }
}


if (!empty($keyword) && !empty($AIkeyword)) {
  $keyword = "";
  $AIkeyword = "";
} elseif (!empty($AIkeyword)) {
  $AIkeyword = mysqli_real_escape_string($conn,$AIkeyword);
  $defaultQuery  .= " AND MATCH (auctionDescription) AGAINST ('" . $AIkeyword . "' IN NATURAL LANGUAGE MODE) ";
  $countQuery .= " AND MATCH (auctionDescription) AGAINST ('" . $AIkeyword . "' IN NATURAL LANGUAGE MODE) ";
} elseif (!empty($keyword)) {
  $keyword = mysqli_real_escape_string($conn,$keyword);
  $defaultQuery .= " AND MATCH (auctionName) AGAINST ('" . $keyword . "' IN NATURAL LANGUAGE MODE) ";
  $countQuery = " AND MATCH (auctionName) AGAINST ('" . $keyword . "' IN NATURAL LANGUAGE MODE) ";  
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
## have coalesced above, so can replace shorthand condition for $current_price if preferred
  while ($row = mysqli_fetch_assoc($result)){
    $item_id = $row['auctionID'];
    $title = $row['auctionName'];
    $description = $row['auctionDescription'];
    $current_price = isset($row['highestBid']) ? $row['highestBid'] : $row['auctionStartingPrice'];
    $num_bids = $row['numBids'];
    $end_date = new DateTime($row['auctionEndDate']);
    # update $end_date to review pulling correct data
    # 3 categories
    # Pending - if time before start date
    # Active - between start and end date
    # Closed -  

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
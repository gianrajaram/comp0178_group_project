<?php include_once("header.php")?>

<?php
/* (Uncomment this block to redirect people without selling privileges away from this page)
  // If user is not logged in or not a seller, they should not be able to
  // use this page.
  if (!isset($_SESSION['account_type']) || $_SESSION['account_type'] != 'seller') {
    header('Location: browse.php');
  }
*/

require 'database_connection.php';

$clothingtype = "SELECT categoryType FROM `categoryclothstype`";
$resultClothes = send_query($clothingtype);
$categoriesClothes = [];
while ($row = mysqli_fetch_assoc($resultClothes)) {
  $categoriesClothes[] = $row;
}

$colorType = "SELECT categoryColor FROM `categorycolortype`";
$resultColor = send_query($colorType);
$categoriesColor = [];
while ($row = mysqli_fetch_assoc($resultColor)) {
  $categoriesColor[] = $row;
}

$genders = "SELECT categoryGender FROM `categorygendertype`";
$resultGender = send_query($genders);
$categoriesGender = [];
while ($row = mysqli_fetch_assoc($resultGender)) {  
  $categoriesGender[] = $row;
}

$sizequerytype = "SELECT categorySize FROM `categorysizetype`";
$resultSize = send_query($sizequerytype);
$categoriesSize = [];
while ($row = mysqli_fetch_assoc($resultSize)) {
  $categoriesSize[] = $row;
}



?>


<div class="container">

<!-- Create auction form -->
<div style="max-width: 800px; margin: 10px auto">
  <h2 class="my-3">Create new auction</h2>
  <div class="card">
    <div class="card-body">
      <!-- Note: This form does not do any dynamic / client-side / 
      JavaScript-based validation of data. It only performs checking after 
      the form has been submitted, and only allows users to try once. You 
      can make this fancier using JavaScript to alert users of invalid data
      before they try to send it, but that kind of functionality should be
      extremely low-priority / only done after all database functions are
      complete. -->
      <form method="post" action="create_auction_result.php" enctype="multipart/form-data">
        <div class="form-group row">
          <label for="auctionTitle" class="col-sm-2 col-form-label text-right">Title of auction</label>
          <div class="col-sm-10">
            <input type="text" class="form-control" id="auctionTitle" name='auctionTitle' placeholder="e.g. Purple cut-out dress" required>
            <small id="titleHelp" class="form-text text-muted"><span class="text-danger">* Required.</span> A short description of the item you're selling, which will display in listings.</small>
          </div>
        </div>
        <div class="form-group row">
          <label for="auctionDetails" class="col-sm-2 col-form-label text-right">Details</label>
          <div class="col-sm-10">
            <textarea class="form-control" id="auctionDetails" name = 'auctionDetails' rows="4"></textarea>
            <small id="detailsHelp" class="form-text text-muted">Full details of the listing to help bidders decide if it's what they're looking for.</small>
          </div>
        </div>

        <!-- Type of Clothes dropdown -->
        <div class="form-group row">
          <label for="auctionClothCategory" class="col-sm-2 col-form-label text-right">Type of Clothing</label>
          <div class="col-sm-10">
            <select class="form-control" id="auctionClothCategory" name="auctionClothCategory" required>
              <option disabled selected>Choose...</option>
              <?php
              foreach( $categoriesClothes as $category ) {
                echo '<option value="'. $category['categoryType'] .'">'. $category['categoryType'] .'</option>';
              }
              ?>
            </select>
            <small id="categoryClothHelp" class="form-text text-muted"><span class="text-danger">* Required.</span> Select a category for this item.</small>
          </div>
        </div>

        <!-- Type of Color dropdown -->
        <div class="form-group row">
          <label for="auctionColorCategory" class="col-sm-2 col-form-label text-right">Color of Item</label>
          <div class="col-sm-10">
            <select class="form-control" id="auctionColorCategory" name="auctionColorCategory" required>
              <option disabled selected>Choose...</option>
              <?php
              foreach( $categoriesColor as $category ) {
                echo '<option value="'. $category['categoryColor'] .'">'. $category['categoryColor'] .'</option>';
              }
              ?>
            </select>
            <small id="categoryColorHelp" class="form-text text-muted"><span class="text-danger">* Required.</span> Select the color for this item.</small>
          </div>
        </div>

        <!-- Type of Gender of Clothing -->
        <div class="form-group row">
          <label for="auctionGenderCategory" class="col-sm-2 col-form-label text-right">Gender</label>
          <div class="col-sm-10">
            <select class="form-control" id="auctionGenderCategory" name="auctionGenderCategory" required>
              <option disabled selected>Choose...</option>
              <?php
              foreach( $categoriesGender as $category ) {
                echo '<option value="'. $category['categoryGender'] .'">'. $category['categoryGender'] .'</option>';
              }
              ?>
            </select>
            <small id="categoryGenderHelp" class="form-text text-muted"><span class="text-danger">* Required.</span> Select from the list above.</small>
          </div>
        </div>

        <!-- Type of Size of Clothing -->
        <div class="form-group row">
          <label for="auctionSizeCategory" class="col-sm-2 col-form-label text-right">Size of Item</label>
          <div class="col-sm-10">
            <select class="form-control" id="auctionSizeCategory" name="auctionSizeCategory" required>
              <option disabled selected>Choose...</option>
              <?php
              foreach( $categoriesSize as $category ) {
                echo '<option value="'. $category['categorySize'] .'">'. $category['categorySize'] .'</option>';
              }
              ?>
            </select>
            <small id="categorySizeHelp" class="form-text text-muted"><span class="text-danger">* Required.</span> Select a size for this item.</small>
          </div>
        </div>

        <div class="form-group row">
          <label for="auctionStartPrice" class="col-sm-2 col-form-label text-right">Starting price</label>
          <div class="col-sm-10">
	        <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text">£</span>
              </div>
              <input type="number" class="form-control" id="auctionStartPrice" name='auctionStartPrice' required>
            </div>
            <small id="startBidHelp" class="form-text text-muted"><span class="text-danger">* Required.</span> Initial bid amount.</small>
          </div>
        </div>
        <div class="form-group row">
          <label for="auctionReservePrice" class="col-sm-2 col-form-label text-right">Reserve price</label>
          <div class="col-sm-10">
            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text">£</span>
              </div>
              <input type="number" class="form-control" id="auctionReservePrice" name='auctionReservePrice'>
            </div>      <p id="selectedFileName" class="mb-2"></p>
             <p id="uploadStatus" class="mb-0"></p>
            </select>
            <small id="reservePriceHelp" class="form-text text-muted">Optional. Auctions that end below this price will not go through. This value is not displayed in the auction listing.</small>
          </div>
        </div>
        <div class="form-group row">
          <label for="auctionStartDate" class="col-sm-2 col-form-label text-right">Start date</label>
          <div class="col-sm-10">
            <input type="datetime-local" class="form-control" id="auctionStartDate" name = 'auctionStartDate' required>
            <small id="startDateHelp" class="form-text text-muted"><span class="text-danger">* Required.</span> Day for the auction to start.</small>
          </div>
        </div>
        <div class="form-group row">
          <label for="auctionEndDate" class="col-sm-2 col-form-label text-right">End date</label>
          <div class="col-sm-10">
            <input type="datetime-local" class="form-control" id="auctionEndDate" name='auctionEndDate' required>
            <small id="endDateHelp" class="form-text text-muted"><span class="text-danger">* Required.</span> Day for the auction to end.</small>
          </div>
        </div>
        
        <div class="form-group row">
          <label for="auctionImage" class="col-sm-2 col-form-label text-right">Upload Image</label>
          <div class="col-sm-10">
             <div class="input-group">
               <div class="custom-file">
                 <input type="file" class="custom-file-input" id="auctionImage" name="auctionImage" accept="image/*" onchange="updateFileName()">
                 <label class="custom-file-label" for="auctionImage">Choose file</label>
               </div>
               <div class="input-group-append">
                 <button class="btn btn-primary" type="button" onclick="document.getElementById('auctionImage').click()">Upload</button>
               </div>
             </div>
             <small id="imageHelp" class="form-text text-muted">Optional. Upload an image of the item you're selling.</small>
           </div>
        </div>
        <script>
        function updateFileName() {
          var input = document.getElementById('auctionImage');
          var fileName = input.files[0].name;
          var label = document.querySelector('.custom-file-label');
          label.innerHTML = fileName;
        }
        </script>



        <div class="form-group row">
           <label class="col-sm-2 col-form-label"></label>
           <div class="col-sm-10">
       
        <button type="submit" class="btn btn-primary form-control">Create Auction</button>
      </form>
    </div>
  </div>
</div>

</div>


<?php include_once("footer.php")?>

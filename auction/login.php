<?php include_once("header.php")?>

<div class="container">
<h2 class="my-3">Logging in your account</h2>

<!-- Create login form -->
<form method="POST" action="login_result.php">
  <div class="form-group row">
    <label for="usernameLogin" class="col-sm-2 col-form-label text-right">Username</label>
	<div class="col-sm-10">
      <input type="text" class="form-control" id="usernameLogin" name ="usernameLogin" placeholder="Username">
      <small id="usernameLoginHelp" class="form-text text-muted"><span class="text-danger">* Required.</span></small>
	</div>
  </div>
  <div class="form-group row">
    <label for="passwordLogin" class="col-sm-2 col-form-label text-right">Password</label>
    <div class="col-sm-10">
      <input type="password" class="form-control" id="passwordLogin" name = "passwordLogin" placeholder="Password">
      <small id="passwordLoginHelp" class="form-text text-muted"><span class="text-danger">* Required.</span></small>
    </div>
  </div>
  <div class="form-group row">
    <label for="accountType" class="col-sm-2 col-form-label text-right">Logging in as a:</label>
	<div class="col-sm-10">
	  <div class="form-check form-check-inline">
        <input class="form-check-input" type="radio" name="accountType" id="accountBuyer" value="Buyer">
        <label class="form-check-label" for="accountBuyer">Buyer</label>
      </div>
      <div class="form-check form-check-inline">
        <input class="form-check-input" type="radio" name="accountType" id="accountSeller" value="Seller">
        <label class="form-check-label" for="accountSeller">Seller</label>
      </div>
      <div class="form-check form-check-inline">
        <input class="form-check-input" type="radio" name="accountType" id="accountSameAsLastSession" value="Same as last session">
        <label class="form-check-label" for="accountSameAsLastSession">Same as last session</label>
      </div>
      <small id="accountTypeHelp" class="form-text-inline text-muted"><span class="text-danger">* Required.</span></small>
	</div>
  </div>
    <button type="submit" name ="login" class="btn btn-primary form-control">Login</button>
  </div>
</form>

<div class="text-center">Don't have an account? <a href="register.php">Register</a>
</div>

<?php include_once("footer.php")?>
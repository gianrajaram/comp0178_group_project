<?php include_once("header.php")?>

<div class="container">
<h2 class="my-3">Register new account</h2>

<!-- Create auction form -->
<form method="POST" action="process_registration.php">
  <div class="form-group row">
    <label for="email" class="col-sm-2 col-form-label text-right">Email</label>
	<div class="col-sm-10">
      <input type="text" class="form-control" id="email" placeholder="Email">
      <small id="emailHelp" class="form-text text-muted"><span class="text-danger">* Required.</span></small>
	</div>
  </div>
  <div class="form-group row">
    <label for="password" class="col-sm-2 col-form-label text-right">Password</label>
    <div class="col-sm-10">
      <input type="password" class="form-control" id="password" placeholder="Password">
      <small id="passwordHelp" class="form-text text-muted"><span class="text-danger">* Required.</span></small>
    </div>
  </div>
  <div class="form-group row">
    <label for="passwordConfirmation" class="col-sm-2 col-form-label text-right">Repeat password</label>
    <div class="col-sm-10">
      <input type="password" class="form-control" id="passwordConfirmation" placeholder="Enter password again">
      <small id="passwordConfirmationHelp" class="form-text text-muted"><span class="text-danger">* Required.</span></small>
    </div>
  </div>
  <div class="form-group row">
    <label for="firstName" class="col-sm-2 col-form-label text-right">First name</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="firstName" placeholder="Your first name">
    </div>
  </div>
  <div class="form-group row">
    <label for="lastName" class="col-sm-2 col-form-label text-right">Last name</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="lastName" placeholder="Your last name">
    </div>
  </div>
  <div class="form-group row">
    <label for="Address" class="col-sm-2 col-form-label text-right">Address</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="Address" placeholder="Your address">
    </div>
  </div>
  <div class="form-group row">
    <label for="Telephone" class="col-sm-2 col-form-label text-right">Telephone</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="Telephone" placeholder="Your telephone">
    </div>
  </div>
  <div class="form-group row">
    <label for="Gender" class="col-sm-2 col-form-label text-right">Gender</label>
	<div class="col-sm-10">
	  <div class="form-check form-check-inline">
        <input class="form-check-input" type="radio" name="Gender" id="GenderFermale" value="Female" checked>
        <label class="form-check-label" for="GenderFermale">Female</label>
      </div>
      <div class="form-check form-check-inline">
        <input class="form-check-input" type="radio" name="Gender" id="GenderMale" value="Male">
        <label class="form-check-label" for="GenderMale">Male</label>
      </div>
      <div class="form-check form-check-inline">
        <input class="form-check-input" type="radio" name="Gender" id="GenderOther" value="Other">
        <label class="form-check-label" for="GenderOther">other</label>
      </div>
      <div class="form-check form-check-inline">
        <input class="form-check-input" type="radio" name="Gender" id="GenderNotSay" value="Prefer not to say">
        <label class="form-check-label" for="GenderMale">Prefer not to say</label>
      </div>
	</div>
  </div>
    <button type="submit" class="btn btn-primary form-control">Register</button>
  </div>
</form>

<div class="text-center">Already have an account? <a href="" data-toggle="modal" data-target="#loginModal">Login</a>

</div>

<?php include_once("footer.php")?>
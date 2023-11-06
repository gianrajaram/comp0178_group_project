<?php include_once("header.php")?>
<?php include_once("database_connection.php")?>
<?php include_once("utilities.php")?>

<div class="container">
<h2 class="my-3">My profile</h2>


<?php
// Extract current profile attributes
$profile_row = user_profile();
?>

<form method="POST" action="profile_update.php">
  <div class="form-group row">
    <label for="emailProfile" class="col-sm-2 col-form-label text-right">Email</label>
	<div class="col-sm-10">
      <input type="text" class="form-control" id="emailProfile" name ="emailProfile" value="<?= $profile_row['userEmail'] ?>" disabled>
	</div>
  </div>
  <div class="form-group row">
    <label for="usernameProfile" class="col-sm-2 col-form-label text-right">Username</label>
	<div class="col-sm-10">
      <input type="text" class="form-control" id="usernameProfile" name ="usernameProfile" value="<?= $profile_row['username'] ?>" disabled>
	</div>
  </div>
  <div class="form-group row">
    <label for="firstNameProfile" class="col-sm-2 col-form-label text-right">First name</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="firstNameProfile" name="firstNameProfile" value="<?= $profile_row['userFirstName'] ?>">
    </div>
  </div>
  <div class="form-group row">
    <label for="lastNameProfile" class="col-sm-2 col-form-label text-right">Last name</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="lastNameProfile" name="lastNameProfile" value="<?= $profile_row['userLastName'] ?>">
    </div>
  </div>
  <div class="form-group row">
    <label for="AddressProfile" class="col-sm-2 col-form-label text-right">Address</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="AddressProfile" name= "AddressProfile" value="<?= $profile_row['userAddress'] ?>">
    </div>
  </div>
  <div class="form-group row">
    <label for="TelephoneProfile" class="col-sm-2 col-form-label text-right">Telephone</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="TelephoneProfile" name = "TelephoneProfile" value="<?= $profile_row['userTel'] ?>">
    </div>
  </div>
  <div class="form-group row">
    <label for="GenderProfile" class="col-sm-2 col-form-label text-right">Gender</label>
	<div class="col-sm-10">
	  <div class="form-check form-check-inline">
        <input class="form-check-input" type="radio" name="GenderProfile" id="GenderFemale" value="Female" <?php if ($profile_row['userGender'] === 'Female') echo 'checked'; ?>>
        <label class="form-check-label" for="GenderFemale">Female</label>
      </div>
      <div class="form-check form-check-inline">
        <input class="form-check-input" type="radio" name="GenderProfile" id="GenderMale" value="Male" <?php if ($profile_row['userGender'] === 'Male') echo 'checked'; ?>>
        <label class="form-check-label" for="GenderMale">Male</label>
      </div>
      <div class="form-check form-check-inline">
        <input class="form-check-input" type="radio" name="GenderProfile" id="GenderOther" value="Other" <?php if ($profile_row['userGender'] === 'Other') echo 'checked'; ?>>
        <label class="form-check-label" for="GenderOther">Other</label>
      </div>
      <div class="form-check form-check-inline">
        <input class="form-check-input" type="radio" name="GenderProfile" id="GenderNotSay" value="Prefer not to say" <?php if ($profile_row['userGender'] === 'Prefer not to say') echo 'checked'; ?>>
        <label class="form-check-label" for="GenderNotSay">Prefer not to say</label>
      </div>
	</div>
  </div>
    <button type="submit" name ="user_profile_change" class="btn btn-primary form-control">Change</button>
  </div>
</form>

<?php include_once("footer.php")?>
<!DOCTYPE html>
<html lang="en-us">

<head>
  <meta charset="utf-8">
  <title>Signup</title>
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

  <!-- ** CSS Plugins Needed for the Project ** -->

  <!-- Bootstrap -->
  <link rel="stylesheet" href="plugins/bootstrap/bootstrap.min.css">
  <!-- themefy-icon -->
  <link rel="stylesheet" href="plugins/themify-icons/themify-icons.css">
  <!--Favicon-->
  <link rel="icon" href="images/favicon.png" type="image/x-icon">
  <!-- fonts -->
  <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700&display=swap" rel="stylesheet">
  <!-- Main Stylesheet -->
  <link href="assets/style.css" rel="stylesheet" media="screen" />
</head>

<body>
	<div class="banner bg-cover" style="background-image: url(./images/background.jpg); background-size: cover;" >
		<br><br>
<section class="section">
  <div class="row">
    <div class="col-md-6 mx-auto my-5">
      <div class="card">
    <div class="card-header">
		<h2>Signup Form</h2>
		<form action="files/login_register.php" method="post" class="submitForm">
			<div class="form-group">
        <input type="name" id="name" name="name" placeholder="Enter name" class="form-control mb-4 shadow rounded-0">
			</div>
      <div class="form-group">
        <input type="email" id="email" name="email" placeholder="Enter email" class="form-control mb-4 shadow rounded-0">
			</div>
			<div class="form-group">
	       <input type="password" id="pwd" name="password" placeholder="Enter password" class="form-control mb-4 shadow rounded-0"   >
			</div>
			<div class="form-group">
	       <input type="password" id="pwd" name="confirm_pass" placeholder="Enter confirm password" class="form-control mb-4 shadow rounded-0"   >
			</div>

      <div class="row">
      <div class="col-md-5"></div>
			<button type="submit" class="btn btn-primary" >Register</button><br>
		</form>
	</div>

	<center><div class="alertError"></div></center>
	 <p class="text-center text-muted mt-5 mb-0">Have already an account? <a href="login.php"
        class="fw-bold text-body"><u>Login here</u></a></p>
</div>
</div>
</div>
</div>
</section>

<?php include "files/jsLinks.php" ?>
</body>
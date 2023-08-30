<!DOCTYPE html>
<html lang="en-us">

<head>
	<meta charset="utf-8">
	<title>E diary</title>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
  
  <!-- theme meta -->
  <meta name="theme-name" content="appointment management system" />

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
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
</head>

<body>
	<!-- header -->
	<header class="banner  bg-cover" style="background-image: url(./images/background.jpg); background-size: cover;">
		<nav class="navbar navbar-expand-md navbar-dark">
			<div class="container">
				<a class="navbar-brand px-2" href="index.php">E Diary</a>
				<button class="navbar-toggler border-0" type="button" data-toggle="collapse" data-target="#navigation"
					aria-controls="navigation" aria-expanded="false" aria-label="Toggle navigation">
					<span class="navbar-toggler-icon"></span>
				</button>

				<div class="collapse navbar-collapse text-center" id="navigation">
					<ul class="navbar-nav ml-auto">
						<li class="nav-item">
							<a class="nav-link text-dark" href="index.php">Home</a>
						</li>
						
						 <?php
			  session_start();
				if(!isset($_SESSION['user_login']) AND empty($_SESSION['user_login'][0]))
				{			  
			    ?>
				  <li class="nav-item">
					<a class="nav-link text-dark" href="login.php">Login</a>
				  </li>

				  <li class="nav-item">
					<a class="nav-link text-dark" href="signup.php">SignUp</a>
				  </li>
				<?php } else { ?>
					<li class="nav-item">
					   <a class="nav-link text-dark" href="dashboard.php">Dashboard</a>
				  </li>

				  <li class="nav-item">
					  <a href="#logoutID" data-toggle="modal" data-target="#logoutID" class="nav-link text-dark"> Logout</a>
				  </li>
				<?php } ?>

						<li class="nav-item">
							<a class="nav-link text-dark" href="contact.php">Contact us</a>
						</li>
						<li class="nav-item">
							<!-- <a class="nav-link text-dark" href=""><img src="./images/mail.png" width="25%" alt=""></a> -->
						</li>
					</ul>
				</div>
				
			</div>
		</nav>
		<!-- banner -->
		<div class="container section">
			<div class="row">
				<div class="col-lg-8 text-center mx-auto">
					<h1 class="text-white mb-3">Make an Appointment</h1>
				<h1 style="color: white; font-size: 18px; line-height: 1.4; margin-bottom: 20px;">Effortlessly manage your appointments with eDiary. Never miss an appointment again with our automated reminders. Simplify your scheduling process with our user-friendly platform.</h1>




					 <div class="position-relative">
						
					</div><br>
					
					

				</div>
			</div>
		</div>
		<!-- /banner -->
	</header>
	<!-- /header -->

	<!-- topics -->
	<section class="section">
		<div class="container">
			<div class="row justify-content-center">
				<div class="col-12 text-center">
					<h2 class="section-title">Pages</h2>
				</div>
				<!-- appointment pages -->
				
				<div class="col-lg-4 col-sm-6 mb-4">
					<a href="dashboard.php" class="px-4 py-5 bg-white shadow text-center d-block match-height">
						<img src="./images/booking.png" width="45%" alt="">
						<h3 class="mb-3 mt-0">Make Appointment</h3>
						<p class="mb-0">Schedule your appointments effortlessly.<br>
						Book your appointments with ease.<br>
						</p>
					</a>
				</div>
				<div class="col-lg-4 col-sm-6 mb-4">
					<a href="dashboard.php" class="px-4 py-5 bg-white shadow text-center d-block match-height">
						<img src="./images/dashboard (1).png" width="45%" alt="">
						<h3 class="mb-3 mt-0">Dashboard</h3>
						<p class="mb-0">Efficiently organize appointments and updates in your dashboard.<br>Seamlessly control and update appointments with your personalized dashboard.</p><br>
					</a>
				</div>
				<div class="col-lg-4 col-sm-6 mb-4">
					<a href="contact.php" class="px-4 py-5 bg-white shadow text-center d-block match-height">
						<img src="./images/google-contacts.png" width="45%
						" alt="">
						<h3 class="mb-3 mt-0">Contact us</h3>
						<p class="mb-0">If you have any problem or question just click on it </p>
					</a>
				</div>
			</div>
		</div>
	</section>
	<!-- /appointment pages -->

	<footer class="section pb-4">
    <div class="container">
      <div class="row align-items-center">
        <div class="col-md-8 text-md-left text-center">
          <p class="mb-md-0 mb-4">Copyright © 2023 Designed and Developed by <a
              href="">Ubaid Khan</a></p>
        </div>
        <div class="col-md-4 text-md-right text-center">
          <ul class="list-inline">
            <li class="list-inline-item"><a class="text-color d-inline-block p-2" href="#"><i
                  class="ti-facebook"></i></a></li>
            <li class="list-inline-item"><a class="text-color d-inline-block p-2" href="#"><i
                  class="ti-twitter-alt"></i></a></li>
            <li class="list-inline-item"><a class="text-color d-inline-block p-2" href="#"><i class="ti-github"></i></a>
            </li>
            <li class="list-inline-item"><a class="text-color d-inline-block p-2" href="#"><i
                  class="ti-linkedin"></i></a></li>
          </ul>
        </div>
      </div>
    </div>
  </footer> 


	
 <?php include "files/jsLinks.php" ?>
	

</html>

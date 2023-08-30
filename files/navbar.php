<header class="banner overlay bg-cover" data-background="">
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

			  <!--<li class="nav-item">
				<a class="nav-link text-dark" href="Dashboard.php">Dashboard</a>
			  </li>-->

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
				<a class="nav-link text-dark" href="contact.php">contact us</a>
			  </li>
			  
			</ul>
		  </div>
		</div>
	  </nav>
</header>
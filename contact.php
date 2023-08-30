<!DOCTYPE html>
<html lang="en-us">

<head>
  <meta charset="utf-8">
  <title>E-Diary</title>
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

  <!-- header -->
  <?php include "files/navbar.php"; ?>
  <!-- /header -->

  <!-- contact -->
  <div class="banner bg-cover" style="background-image: url(./images/background.jpg); background-size: cover;" >
		<br><br>>
  <section class="section">
    <div class="container">
      <div class="row">
        <div class="col-6 mx-auto my-5">
          <div class="card">
            <div class="card-header">
              <h2>contact us</h2>
              <p>Anything to ask </p>

               <form action="files/contact.php" method="post" class="submitForm">
                <input type="text" id="name" name="c_name" placeholder="Name" class="form-control mb-4 shadow rounded-0">
                <input type="email" id="mail" name="c_email" placeholder="Email"
                  class="form-control mb-4 shadow rounded-0">
                <input type="text" id="mail" name="c_phoneNumber" placeholder="Enter phone number"
                  class="form-control mb-4 shadow rounded-0">
                <input type="text" id="subject" name="c_subject" placeholder="subject"
                  class="form-control mb-4 shadow rounded-0">
                <textarea name="c_message" id="message" placeholder="Message"
                  class="form-control mb-4 shadow rounded-0"></textarea>
                <div class="row">
                  <div class="col-5"></div>
                  <div class="col-2"><button type="submit" value="send" class="btn btn-primary">Send</button></div>
                  <div class="col-5"></div>
                </div>
              </form>
                                       <center><div class="alertError"></div></center>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  </section>
  <!-- /contact -->

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

</body>

</html>
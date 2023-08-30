<!DOCTYPE html>
<html lang="en-us">

<head>
  <meta charset="utf-8">
  <title>Forget password</title>
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
  <style>
    body {
      background-color: #f8f9fa;
    }

    .container {
      max-width: 500px;
      margin-top: 100px;
      background-color: white;
      padding: 20px;
      border-radius: 5px;
      box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.2);
    }

    h2 {
      text-align: center;
      margin-bottom: 30px;
    }

    .form-control {
      border-radius: 0;
    }

    .btn {
      border-radius: 0;
    }
  </style>
</head>


<body>
  <div class="banner bg-cover" style="background-image: url(./images/background.jpg); background-size: cover;" >
		<br><br>
  <section class="section">
    <div class="row">
      <div class="col-md-6 mx-auto my-5">
        <div class="card">
          <div class="card-header">
            <h2>Forget password</h2>
            <form action="files/login_register.php" method="post" class="submitForm">
              <div class="form-group">
                <input type="email" id="email" name="forgot_email" placeholder="Enter email"
                  class="form-control mb-4 shadow rounded-0">
              </div>

              <div class="row">
                <div class="col-5"></div>
                <div class=""><button type="submit" class="btn btn-primary">Reset</button></div>

              </div>

              <div class="alertError"></div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>

<?php include "files/jsLinks.php" ?>

</body>
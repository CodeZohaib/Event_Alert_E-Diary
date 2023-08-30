<?php 
include "files/function.php";
if(!isset($_SESSION['user_login']) AND empty($_SESSION['user_login'][0]) AND empty($_SESSION['user_login'][1]))
{
    header('location:login.php');
}
$userEmail=$_SESSION['user_login'][0];
$userID=$_SESSION['user_login'][1];
$userData=getUser($userEmail);
$appointmentData=getUserAllAppointments($userID);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- theme meta -->
    <meta name="theme-name" content="focus" />
    <title>Dashboard</title>
    <!-- ================= Favicon ================== -->
    <!-- Standard -->
    <link rel="shortcut icon" href="http://placehold.it/64.png/000/fff">
    <!-- Retina iPad Touch Icon-->
    <link rel="apple-touch-icon" sizes="144x144" href="http://placehold.it/144.png/000/fff">
    <!-- Retina iPhone Touch Icon-->
    <link rel="apple-touch-icon" sizes="114x114" href="http://placehold.it/114.png/000/fff">
    <!-- Standard iPad Touch Icon-->
    <link rel="apple-touch-icon" sizes="72x72" href="http://placehold.it/72.png/000/fff">
    <!-- Standard iPhone Touch Icon-->
    <link rel="apple-touch-icon" sizes="57x57" href="http://placehold.it/57.png/000/fff">
    <!-- Styles -->
    <link href="css/lib/calendar2/pignose.calendar.min.css" rel="stylesheet">
    <link href="css/lib/chartist/chartist.min.css" rel="stylesheet">
    <link href="css/lib/font-awesome.min.css" rel="stylesheet">
    <link href="css/lib/themify-icons.css" rel="stylesheet">
    <link href="css/lib/owl.carousel.min.css" rel="stylesheet" />
    <link href="css/lib/owl.theme.default.min.css" rel="stylesheet" />
    <link href="css/lib/weather-icons.css" rel="stylesheet" />
    <link href="css/lib/menubar/sidebar.css" rel="stylesheet">
    <link href="css/lib/bootstrap.min.css" rel="stylesheet">
    <link href="css/lib/helper.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">

</head>

<body>

    <div class="sidebar sidebar-hide-to-small sidebar-shrink sidebar-gestures">
        <div class="nano">
            <div class="nano-content">
                <ul class="dashboardNavbar">
                    <div class="logo">
                        <a href="index.php"><span>E-Diary</span></a>
                    </div>                   
                    <li><a href="dashboard.php">All Event</a></li>  
                    <li><a href="email_send_data.php">Reminder Data</a></li> 
                     <li><a href="change_password.php">Change Password</a></li> 
                    <li><a href="contact.php">Contact us</a></li>   
                    <li><a href="#logoutID" data-toggle="modal" data-target="#logoutID"> Logout</a></li>     
                </ul>
            </div>
        </div>
    </div>
    <!-- /# sidebar -->

    <div class="header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="float-left">
                        <div class="hamburger sidebar-toggle">
                            <span class="line"></span>
                            <span class="line"></span>
                            <span class="line"></span>
                        </div>
                    </div>
                    <div class="float-right">
                        <div class="dropdown dib">
                            <div class="header-icon" data-toggle="dropdown">
                                <span class="user-avatar"> <?php echo ucwords($userData['name']); ?>
                                    <i class="ti-angle-down f-s-10"></i>
                                </span>
                                <div class="drop-down dropdown-profile dropdown-menu dropdown-menu-right">
                                    
                                    <div class="dropdown-content-body">
                                        <ul class="adminLink">
                                            
                                            <li>
                                                <a href="#logoutID" data-toggle="modal" data-target="#logoutID">
                                                    <i class="ti-power-off"></i>
                                                    <span>Logout</span>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
   

          
	<div class="content-wrap">
        <div class="main"></div>
		<div class="card">
			<div class="card-header">
               <center>
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#appointment">
                        Add New Appointment
                      </button>
                </center> <br><br>
		<!-- <h1 class="text-center mb-4">ubaid appointment</h1> -->
		<form class="d-flex" role="search">
			<input class="form-control me-2 searchAppointment" type="search" placeholder="Search" aria-label="Search">
			
		  </form>
		  <br>
		<div class="table-responsive">
			<?php 
              if(is_array($appointmentData))
              {
            ?>
			<table class="table table-bordered table-striped">
				<thead>
					<tr> <th>id</th>
						<th>Events</th>
						<th>Member</th>
						<th>Venue</th>
						<th>Date</th>
						<th>From</th>
						<th>To</th>
						<th>Recurrence</th>
                        <th>Remainder</th>
						<th>Status</th>
                        <th>Action</th>
                        
				</thead>
				<tbody class="allAppointment">

                    <?php 
                     $i=count($appointmentData);
                      foreach ($appointmentData as $key => $value) 
                      {

                        if($value['remainder']=='custom')
                        {

                            $remainder=date("d-m-Y", strtotime($value['r_custom_date']))." ".$value['r_custom_time'];
                        }
                        else
                        {
                            $remainder=ucwords($value['remainder'])." Before";
                        }


                        if($value['status']=='complete')
                        {
                             $editBtn='<button class="btn btn-primary btn-sm updateBtn" data-toggle="modal" data-target="#notEdit">
                                <i class="fa fa-pencil"></i></button>';
                            

                            $status='<span class="badge" style="background:#3067EF; font-size:9px">Complete</span>';
                        }
                        else 
                        {
                           $editBtn='<button class="btn btn-primary btn-sm updateBtn" data-toggle="modal" data-target="#updateAppointment" appointmentID="'.$value['id'].'">
                                <i class="fa fa-pencil"></i></span></button>';

                            $status='<a href="#appointmentComplete" data-toggle="modal"> <span class="badge appointmentStatusComplete" style="background:#6fd96f; font-size:10px" appointmentID="'.$value['id'].'">Pending</span></a>';
                        }

                         echo '<tr>
                            <td>'.$i.'</td>
                            <td>'.ucwords($value['event_name']).'</td>
                            <td>'.$value['member'].'</td>
                            <td>'.ucwords($value['venue']).'</td>
                            <td>'.date("d-m-Y", strtotime($value['event_date'])).'</td>
                            <td>'.$value['from_time'].'</td>
                            <td>'.$value['to_time'].'</td>
                            <td>'.ucwords($value['recurrence']).'</td>
                            <td>'.$remainder.'</td>
                            <td>'.$status.'</td>
                            <td>
                              <div class="btn-group" role="group" aria-label="Button group">
                                
                                '.$editBtn.'
                                <button class="btn btn-danger btn-sm deleteBtnAppointment" data-toggle="modal" data-target="#appointmentDelete" appointmentID="'.$value['id'].'"><i class="fa fa-trash"></i></button>
                              </div>
                            </td>

                        </tr>';

                        $i--;
                      }
                    ?>
				</tbody>
			</table>
           <?php 
              } 
              else
              {
                echo "<br><br><br><center><div class='alert alert-danger'>Event Appointment Cannot Be Create......!</div></center>";
              }
           ?>
			
                
					  
<div class="modal fade" id="appointment" tabindex="-1" role="dialog" aria-labelledby="appointmentLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content" style="margin-top:150px">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Add New Appointment</h5>
            <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
			<div class="col-md-12">
               <form action="files/appointment.php" method="post" class="submitForm">
					<div class="form-group">
                        <b>Event Name</b>
					  <input type="text" id="Events" name="event_name" placeholder="Enter Event Name....!"
						class="form-control mb-4 shadow rounded-0" required>
					</div>
					<div class="form-group">
                        <b>Member</b>
					  <input type="number" id="No Of Members" name="no_member" placeholder="no of members"
						class="form-control mb-4 shadow rounded-0" required>
					</div>
	  
					<div class="form-group">
                        <b>Venue</b>
					  <input type="text" id="venue" name="venue" placeholder="Enter Venue"
						class="form-control mb-4 shadow rounded-0" required>
					</div>
					<div class="form-group">
                        <b>Event Date</b>
					  <input type="date" id="date" name="event_date" name="name" class="form-control mb-4 shadow rounded-0" required>
					</div>
					<div class="form-group">
                        <b>From Time</b>
					  <input type="time" id="time" name="from_time" placeholder="from time"
						class="form-control mb-4 shadow rounded-0" required>
					</div>
					<div class="form-group">
					  <b>To Time</b>
					  <input type="time"  id="time" name="to_time" placeholder="to time" class="form-control mb-4 shadow" required>
					</div>

                   <div class="form-group form-group-xls">
                        <b>Recurrence Type </b> (Optional)
                      <select class="form-select  form-select-lg mb-4 shadow form-control" style="font-size: medium;" name="recurrence" required>
                        <option value="no" selected>Select</option>
                        <option value="daily">Daily</option>
                        <option value="Weekly">Weekly</option>
                        <option value="Monthly">Monthly</option>
                        <option value="yearly">yearly</option>
                      </select>
                    </div>

                    <div class="form-group form-group-xls">
                          <b>Remainder</b>
                          <select class="form-select form-select-lg mb-4 shadow form-control" style="font-size: medium;" name="remainder" required>
                            <option value="">Select Remainder</option>
                            <option value="5 minutes">5 Minutes Before</option>
                            <option value="10 minutes">10 Minutes Before</option>
                            <option value="15 minutes">15 Minutes Before</option>
                            <option value="20 minutes">20 Minutes Before</option>
                            <option value="30 minutes">30 Minutes Before</option>
                            <option value="1 hour">1 hour Before</option>
                            <option value="1 day">1 day Before</option>
                            <option value="custom">Custom</option>
                          </select>

                          <div class="custom-options" style="display: none;">
                            <label for="custom-date">Custom Date:</label>
                            <input type="date" name="custom-date" class="form-control custom-date">

                            <label for="custom-time">Custom Time:</label>
                            <input type="time" name="custom-time" class="form-control custom-time">
                          </div>
                        </div>

					 <button type="submit" class="btn btn-primary">Add Appointment</button>

                     <div class="alertError"></div>
				  </form>
                </div>
          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            
          </div>
        </div>
    </div>
</div>

<div class="modal fade" id="updateAppointment" tabindex="-1" role="dialog" aria-labelledby="updateAppointment" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content" style="margin-top:175px">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Update Appointment</h5>
            <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="col-md-12">
               <form action="files/appointment.php" method="post" class="submitForm appointmentUpdatedData">

               </form>
            </div>
          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            
          </div>
        </div>
    </div>
</div>


<div class="modal fade" id="logoutID" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">

       <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Logout ID</h5>
                        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                      </div>

        <div class="modal-body">
          <p><strong>Are You Sure You Want To Logout ID.?</p>
        </div>
      
       <div class="modal-footer text-center">
         <button type="button" class="btn btn-success btn-sm idLogOut">Yes</button>
         <button type="close" class="btn btn-danger btn-sm" data-dismiss="modal">No</button>
       </div>

       <div class="alertModelError"></div>
    </div>
   </div>
 </div>


 <div class="modal fade" id="appointmentComplete" tabindex="-1" aria-labelledby="appointmentComplete" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">

       <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Appointment Complete</h5>
            <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
          </div>

        <div class="modal-body">
          <p><strong>Are You Sure You Want To Change Status Appointment.?</p>
        </div>
      
       <div class="modal-footer text-center">
         <button type="button" class="btn btn-success btn-sm yesChangeStatus">Yes</button>
         <button type="close" class="btn btn-danger btn-sm" data-dismiss="modal">No</button>
       </div>

       <div class="alertModelError"></div>
    </div>
   </div>
 </div>





 <div class="modal fade" id="notEdit" tabindex="-1" aria-labelledby="appointmentDelete" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">

       <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Appointment</h5>
                        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                      </div>

        <div class="modal-body">
          <p><strong>Appointment is not Edit. Because appointment is complete...!</p>
        </div>
      
       <div class="modal-footer text-center">
         <button type="close" class="btn btn-danger btn-sm" data-dismiss="modal">Close</button>
       </div>

       <div class="alertModelError"></div>
    </div>
   </div>
 </div>


 <div class="modal fade" id="appointmentDelete" tabindex="-1" aria-labelledby="appointmentDelete" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">

       <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Appointment Delete</h5>
                        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                      </div>

        <div class="modal-body">
          <p><strong>Are You Sure You Want To Delete Appointment.?</p>
        </div>
      
       <div class="modal-footer text-center">
         <button type="button" class="btn btn-success btn-sm yesDeleteAppointment">Yes</button>
         <button type="close" class="btn btn-danger btn-sm" data-dismiss="modal">No</button>
       </div>

       <div class="alertModelError"></div>
    </div>
   </div>
 </div>



	
    <!-- jquery vendor -->
    <script src="js/lib/jquery.min.js"></script>
    <script src="js/lib/jquery.nanoscroller.min.js"></script>
    <!-- nano scroller -->
    <script src="js/lib/menubar/sidebar.js"></script>
    <script src="js/lib/preloader/pace.min.js"></script>
    <!-- sidebar -->

    <script src="js/lib/bootstrap.min.js"></script>
    <script src="js/scripts.js"></script>
    <!-- bootstrap -->

    <script src="js/lib/calendar-2/moment.latest.min.js"></script>
    <script src="js/lib/calendar-2/pignose.calendar.min.js"></script>
    <script src="js/lib/calendar-2/pignose.init.js"></script>


    <script src="js/lib/weather/jquery.simpleWeather.min.js"></script>
    <script src="js/lib/weather/weather-init.js"></script>
    <script src="js/lib/circle-progress/circle-progress.min.js"></script>
    <script src="js/lib/circle-progress/circle-progress-init.js"></script>
    <script src="js/lib/chartist/chartist.min.js"></script>
    <script src="js/lib/sparklinechart/jquery.sparkline.min.js"></script>
    <script src="js/lib/sparklinechart/sparkline.init.js"></script>
    <script src="js/lib/owl-carousel/owl.carousel.min.js"></script>
    <script src="js/lib/owl-carousel/owl.carousel-init.js"></script>
    <!-- scripit init-->
    <script src="js/dashboard2.js"></script>
    <script src="js/custom.js"></script>


</body>

</html>

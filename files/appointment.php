<?php 

include "function.php";

if(!isset($_SESSION['user_login']) AND empty($_SESSION['user_login'][0]) AND empty($_SESSION['user_login'][1]))
{
    header('location:login.php');
}


if(isset($_POST['event_name']) AND isset($_POST['no_member']) AND isset($_POST['venue']) AND isset($_POST['event_date']) AND isset($_POST['from_time']) AND isset($_POST['to_time']) AND isset($_POST['recurrence']) AND isset($_POST['remainder']) AND isset($_POST['custom-date']) AND isset($_POST['custom-time']))
{

	$userEmail=$_SESSION['user_login'][0];
	$userID=$_SESSION['user_login'][1];

	$event_name=strtolower($_POST['event_name']);
	$no_member=$_POST['no_member'];
	$venue=strtolower($_POST['venue']);
	$event_date=$_POST['event_date'];
	$from_time=$_POST['from_time'];
	$to_time=$_POST['to_time'];
	$recurrence=strtolower($_POST['recurrence']);
	$reminder=strtolower($_POST['remainder']);
	$custom_date=$_POST['custom-date'];	
	$custom_time=$_POST['custom-time'];

	$from_time_obj = new DateTime($from_time);
	$startTime = $from_time_obj->format('h:i A');
	$to_time_obj = new DateTime($to_time);
	$endTime = $to_time_obj->format('h:i A');

	$custom_time_obj = new DateTime($custom_time);
	$custom_time = $custom_time_obj->format('h:i A');

	$currentDate = new DateTime();
    $eventDate = new DateTime($_POST['event_date']);

    // Calculate the event date and time
	$eventDateTime = new DateTime($_POST['event_date'] . ' ' . $_POST['from_time']);


	if ($eventDateTime < $currentDate) 
	{
	    return MsgDisplay('error', 'Invalid event date or time. Please select a future date and time.');
	}

	if ($_POST['from_time'] > $_POST['to_time']) 
	{
	    return MsgDisplay('error', 'Please select an end time that is after the start time of the event........');
	}

	if($reminder=='custom')
	{
	    $newEventData=new DateTime($event_date);
	    $reminder_date=new DateTime($_POST['custom-date']);

	    if ($reminder_date > $newEventData) 
	    {
		    return MsgDisplay('error', 'Invalid reminder date. Please select a date between the event date and the reminder date....!');
		} 
		else if($reminder_date == $newEventData)
		{
			// Convert the time strings to DateTime objects
			$eventStartDateTime = DateTime::createFromFormat('H:i', $_POST['from_time']);
			$reminderDateTime = DateTime::createFromFormat('H:i', $_POST['custom-time']);

			if($reminderDateTime >= $eventStartDateTime)
			{
				return MsgDisplay('error', 'Invalid reminder time. Please select a valid time earlier than the event start time....!');
			}
		}  
	}
	else
	{
		$custom_time='';
		$custom_date='';
	}

	$appointmentData=getUserAppointments($userID);
	if(is_array($appointmentData))
	{
		foreach ($appointmentData as $key => $timing) 
		{
			$dbEventDate = new DateTime($timing['event_date']);
			$newEventData=new DateTime($event_date);

		    $dbStartTime = $timing['from_time'];
		    $dbEndTime   = $timing['to_time'];

	        if ($newEventData == $dbEventDate) 
	        {
		        if (($startTime >= $dbStartTime && $startTime < $dbEndTime) || ($endTime > $dbStartTime && $endTime <= $dbEndTime) || ($startTime <= $dbStartTime && $endTime >= $dbEndTime)) 
		        {
		            return MsgDisplay('error', 'The selected start and end times overlap with an existing appointment timing on the same date...!');
		        }
		    }
		}
	}

     // Calculate the reminder time based on the selected option
	  if ($_POST['remainder'] === 'custom') 
	  {
	    $customDate = $_POST['custom-date'];
	    $customTime = $_POST['custom-time'];
	    $reminderDateTime = new DateTime($customDate . ' ' . $customTime);
	  } 
	  else 
	  {
	    $optionValue = intval(explode(' ', $_POST['remainder'])[0]); // Extract the numeric value from the option
	    $optionUnit = explode(' ', $_POST['remainder'])[1]; // Extract the unit of time from the option

	    $reminderDateTime = clone $eventDateTime;

	    if ($optionUnit === 'minutes') {
	      $reminderDateTime->sub(new DateInterval("PT{$optionValue}M"));
	    } elseif ($optionUnit === 'hour') {
	      $reminderDateTime->sub(new DateInterval("PT{$optionValue}H"));
	    } elseif ($optionUnit === 'day') {
	      $reminderDateTime->sub(new DateInterval("P{$optionValue}D"));
	    }
	  }

	$formattedReminderDate = $reminderDateTime->format('d-m-Y h:i A');
	$checkReminder=new DateTime($formattedReminderDate);
	if($checkReminder<=$currentDate)
	{
		return MsgDisplay('error', 'Invalid reminder time select....!');
	}


	$run=$con->prepare('INSERT INTO `appointment`(`user_id`, `event_name`, `venue`, `member`, `event_date`, `from_time`, `to_time`, `recurrence`, `remainder`,`r_custom_date`,`r_custom_time`,`status`) VALUES (?,?,?,?,?,?,?,?,?,?,?,?)');

	if(is_object($run))
	{
		$run->bindParam(1,$userID,PDO::PARAM_INT);
		$run->bindParam(2,$event_name,PDO::PARAM_STR);
		$run->bindParam(3,$venue,PDO::PARAM_STR);
		$run->bindParam(4,$no_member,PDO::PARAM_INT);
		$run->bindParam(5,$event_date,PDO::PARAM_STR);
		$run->bindParam(6,$startTime,PDO::PARAM_STR);
		$run->bindParam(7,$endTime,PDO::PARAM_STR);
		$run->bindParam(8,$recurrence,PDO::PARAM_STR);
		$run->bindValue(9,$reminder,PDO::PARAM_STR);
		$run->bindValue(10,$custom_date,PDO::PARAM_STR);
		$run->bindValue(11,$custom_time,PDO::PARAM_STR);
		$run->bindValue(12,'pending',PDO::PARAM_STR);

		if($run->execute())
		{
			$a_id=$con->lastInsertId();

			$run=$con->prepare('INSERT INTO `email_send`(`u_id`, `a_id`, `remainder_date`, `send_status`) VALUES (?,?,?,?)');
			if(is_object($run))
			{
				$run->bindParam(1,$userID,PDO::PARAM_INT);
		        $run->bindParam(2,$a_id,PDO::PARAM_INT);
		        $run->bindParam(3,$formattedReminderDate,PDO::PARAM_STR);
		        $run->bindValue(4,'pending',PDO::PARAM_STR);

		        if($run->execute())
		        {
		        	return MsgDisplay('refersh','Appointment added successfully......!');
		        }
			}
			
		}
	}

	return MsgDisplay('error', 'Something Was Problem Please Try Again...!');

}
else if(isset($_POST['appointmentID']) AND isset($_POST['updateAppointment']))
{
	$appointmentID=$_POST['appointmentID'];

	if(is_numeric($appointmentID))
	{
		$data=getAppointments($appointmentID);
		if(is_array($data))
		{
			$recurrence=$data['recurrence'];
			$reminder=$data['remainder'];

			$fromTimeObj = DateTime::createFromFormat('h:i A', $data['from_time']);
			$from_time = $fromTimeObj->format('H:i'); 

			$toTimeObj = DateTime::createFromFormat('h:i A', $data['to_time']);
			$to_time = $toTimeObj->format('H:i'); 

			if($reminder=='custom')
			{
				$customTimeObj = DateTime::createFromFormat('h:i A', $data['r_custom_time']);
			    $custom_time = $customTimeObj->format('H:i'); 

				$custom='<div class="custom-options">
                        <label for="custom-date">Custom Date:</label>
                        <input type="date" value="'.$data['r_custom_date'].'" name="u_custom-date" class="form-control custom-date">

                        <label for="custom-time">Custom Time:</label>
                        <input type="time" value="'.$custom_time.'" name="u_custom-time" class="form-control custom-time">
                      </div>';
			}
			else{
				$custom='<div class="custom-options" style="display: none;">
                        <label for="custom-date">Custom Date:</label>
                        <input type="date" name="u_custom-date" class="form-control custom-date">

                        <label for="custom-time">Custom Time:</label>
                        <input type="time" name="u_custom-time" class="form-control custom-time">
                      </div>';
			}

			
		   $html='
		       <div class="form-group">
                    <b>Event Name</b>
                  <input type="text" id="Events" value="'.$data['event_name'].'" name="u_event_name" placeholder="Enter Event Name....!"
                    class="form-control mb-4 shadow rounded-0" required>
                    
                </div>
                <div class="form-group">
                    <b>Member</b>
                  <input type="number" id="No Of Members" value="'.$data['member'].'" name="u_no_member" placeholder="no of members"
                    class="form-control mb-4 shadow rounded-0" required>
                </div>
  
                <div class="form-group">
                    <b>Venue</b>
                  <input type="text" id="venue" value="'.$data['venue'].'" name="u_venue" placeholder="Enter Venue"
                    class="form-control mb-4 shadow rounded-0" required> 
                </div>
                <div class="form-group">
                    <b>Event Date</b>
                  <input type="date" id="date" value="'.$data['event_date'].'" name="u_event_date" class="form-control mb-4 shadow rounded-0" required>
                </div>


                <div class="form-group">
                    <b>From Time</b>
                    <input type="number" name="update_id" value="'.$data['id'].'" hidden>
                  <input type="time" id="time" value="'.$from_time.'" name="u_from_time" placeholder="from time"
                    class="form-control mb-4 shadow rounded-0" required>

                </div>
                <div class="form-group">
                  <b>To Time</b>
                  <input type="time"  id="time" value="'.$to_time.'" name="u_to_time" placeholder="to time" class="form-control mb-4 shadow" required>
                </div>

               <div class="form-group form-group-xls">
                    <b>Recurrence Type </b> (Optional)
                 <select class="form-select form-select-lg mb-4 shadow form-control" style="font-size: medium;" name="u_recurrence" required>
                 <option value="no" ' . ($recurrence === 'no' ? 'selected' : '') . '>Select</option>
                 <option value="daily" ' . ($recurrence === 'daily' ? 'selected' : '') . '>Daily</option>
				<option value="Weekly" ' . ($recurrence === 'weekly' ? 'selected' : '') . '>Weekly</option>
				<option value="Monthly" ' . ($recurrence === 'monthly' ? 'selected' : '') . '>Monthly</option>
				<option value="yearly" ' . ($recurrence === 'yearly' ? 'selected' : '') . '>Yearly</option>
				</select>
                </div>

                <div class="form-group form-group-xls">
                      <b>Remainder</b>
						<select class="form-select form-select-lg mb-4 shadow form-control" style="font-size: medium;" name="u_remainder" required>
						<option value="">Select Remainder</option>
						<option value="5 minutes" ' . ($reminder === '5 minutes' ? 'selected' : '') . '>5 Minutes Before</option>
						<option value="10 minutes" ' . ($reminder === '10 minutes' ? 'selected' : '') . '>10 Minutes Before</option>
						<option value="15 minutes" ' . ($reminder === '15 minutes' ? 'selected' : '') . '>15 Minutes Before</option>
						<option value="20 minutes" ' . ($reminder === '20 minutes' ? 'selected' : '') . '>20 Minutes Before</option>
						<option value="30 minutes" ' . ($reminder === '30 minutes' ? 'selected' : '') . '>30 Minutes Before</option>
						<option value="1 hour" ' . ($reminder === '1 hour' ? 'selected' : '') . '>1 hour Before</option>
						<option value="1 day" ' . ($reminder === '1 day' ? 'selected' : '') . '>1 day Before</option>
						<option value="custom" ' . ($reminder === 'custom' ? 'selected' : '') . '>Custom</option>
						</select>

						'.$custom.'
                    </div>

                 <button type="submit" class="btn btn-primary">Update Appointment</button>

                 <div class="alertError"></div>
              ';

           return MsgDisplay('success',$html);
		}
	}
	
}
else if(isset($_POST['u_event_name']) AND isset($_POST['u_no_member']) AND isset($_POST['u_venue']) AND isset($_POST['u_event_date']) AND isset($_POST['u_from_time']) AND isset($_POST['u_to_time']) AND isset($_POST['u_recurrence']) AND isset($_POST['u_remainder']) AND isset($_POST['u_custom-date']) AND isset($_POST['u_custom-time']) AND isset($_POST['update_id']))
{
	$appointmentID=$_POST['update_id'];
	if(is_numeric($appointmentID))
	{
		$a_Data=getAppointments($appointmentID);
		if(is_array($a_Data))
		{
			

			$userEmail=$_SESSION['user_login'][0];
			$userID=$_SESSION['user_login'][1];

			$event_name=strtolower($_POST['u_event_name']);
			$no_member=$_POST['u_no_member'];
			$venue=strtolower($_POST['u_venue']);
			$event_date=$_POST['u_event_date'];
			$from_time=$_POST['u_from_time'];
			$to_time=$_POST['u_to_time'];
			$recurrence=strtolower($_POST['u_recurrence']);
			$reminder=strtolower($_POST['u_remainder']);
			$custom_date=$_POST['u_custom-date'];	
			$custom_time=$_POST['u_custom-time'];

			$from_time_obj = new DateTime($from_time);
			$startTime = $from_time_obj->format('h:i A');
			$to_time_obj = new DateTime($to_time);
			$endTime = $to_time_obj->format('h:i A');

			$custom_time_obj = new DateTime($custom_time);
			$custom_time = $custom_time_obj->format('h:i A');

			$currentDate = new DateTime();
		    $eventDate = new DateTime($_POST['u_event_date']);


		    // Calculate the event date and time
			$eventDateTime = new DateTime($_POST['u_event_date'] . ' ' . $_POST['u_from_time']);



			if ($eventDateTime <= $currentDate) 
			{
			    return MsgDisplay('error', 'Invalid event date or time. Please select a future date and time.');
			}

			if ($_POST['u_from_time'] > $_POST['u_to_time']) 
			{
			    return MsgDisplay('error', 'Please select an end time that is after the start time of the event........');
			}


			if($reminder=='custom')
			{
			    $newEventData=new DateTime($event_date);
			    $reminder_date=new DateTime($_POST['u_custom-date']);

			    if ($reminder_date > $newEventData) 
			    {
				    return MsgDisplay('error', 'Invalid reminder date. Please select a date between the event date and the reminder date....!');
				} 
				else if($reminder_date == $newEventData)
				{
					// Convert the time strings to DateTime objects
					$eventStartDateTime = DateTime::createFromFormat('H:i', $_POST['u_from_time']);
					$reminderDateTime = DateTime::createFromFormat('H:i', $_POST['u_custom-time']);

					if($reminderDateTime >= $eventStartDateTime)
					{
						return MsgDisplay('error', 'Invalid reminder time. Please select a valid time earlier than the event start time....!');
					}
				}  
			}
			else
			{
				$custom_time='';
				$custom_date='';
			}


			$appointmentData=getUserAppointments($userID);
			if(is_array($appointmentData))
			{
				foreach ($appointmentData as $key => $timing) 
				{
					if($timing['id']==$a_Data['id'])
					{
						continue;
					}
					$dbEventDate = new DateTime($timing['event_date']);
					$newEventData=new DateTime($event_date);

				    $dbStartTime = $timing['from_time'];
				    $dbEndTime   = $timing['to_time'];

			        if ($newEventData == $dbEventDate) 
			        {
				        if (($startTime >= $dbStartTime && $startTime < $dbEndTime) || ($endTime > $dbStartTime && $endTime <= $dbEndTime) || ($startTime <= $dbStartTime && $endTime >= $dbEndTime)) 
				        {
				            return MsgDisplay('error', 'The selected start and end times overlap with an existing appointment timing on the same date...!');
				        }
				    }
				}
			}


	     // Calculate the reminder time based on the selected option
		  if ($_POST['u_remainder'] === 'custom') 
		  {
		    $customDate = $_POST['u_custom-date'];
		    $customTime = $_POST['u_custom-time'];
		    $reminderDateTime = new DateTime($customDate . ' ' . $customTime);
		  } 
		  else 
		  {
		    $optionValue = intval(explode(' ', $_POST['u_remainder'])[0]); // Extract the numeric value from the option
		    $optionUnit = explode(' ', $_POST['u_remainder'])[1]; // Extract the unit of time from the option

		    $reminderDateTime = clone $eventDateTime;

		    if ($optionUnit === 'minutes') {
		      $reminderDateTime->sub(new DateInterval("PT{$optionValue}M"));
		    } elseif ($optionUnit === 'hour') {
		      $reminderDateTime->sub(new DateInterval("PT{$optionValue}H"));
		    } elseif ($optionUnit === 'day') {
		      $reminderDateTime->sub(new DateInterval("P{$optionValue}D"));
		    }
		  }

			$formattedReminderDate = $reminderDateTime->format('d-m-Y h:i A');

			$checkReminder=new DateTime($formattedReminderDate);
			if($checkReminder<=$currentDate)
			{
				return MsgDisplay('error', 'Invalid reminder time select....!');
			}


			$run=$con->prepare('UPDATE `appointment` SET `event_name`=?,`venue`=?,`member`=?,`event_date`=?,`from_time`=?,`to_time`=?,`recurrence`=?,`remainder`=?,`r_custom_date`=?,`r_custom_time`=?,`status`=? WHERE id=? AND user_id=?');

			if(is_object($run))
			{
				$run->bindParam(1,$event_name,PDO::PARAM_STR);
				$run->bindParam(2,$venue,PDO::PARAM_STR);
				$run->bindParam(3,$no_member,PDO::PARAM_INT);
				$run->bindParam(4,$event_date,PDO::PARAM_STR);
				$run->bindParam(5,$startTime,PDO::PARAM_STR);
				$run->bindParam(6,$endTime,PDO::PARAM_STR);
				$run->bindParam(7,$recurrence,PDO::PARAM_STR);
				$run->bindValue(8,$reminder,PDO::PARAM_STR);
				$run->bindValue(9,$custom_date,PDO::PARAM_STR);
				$run->bindValue(10,$custom_time,PDO::PARAM_STR);
				$run->bindValue(11,'pending',PDO::PARAM_STR);
				$run->bindParam(12,$appointmentID,PDO::PARAM_INT);
				$run->bindParam(13,$userID,PDO::PARAM_INT);
				

				if($run->execute())
				{
					$run=$con->prepare('DELETE FROM `email_send` WHERE u_id=? AND a_id=? AND send_status=?');
					if(is_object($run))
					{
						$run->bindParam(1,$userID,PDO::PARAM_INT);
				        $run->bindParam(2,$appointmentID,PDO::PARAM_INT);
				        $run->bindValue(3,'pending',PDO::PARAM_STR);

				        if($run->execute())
				        {
				        	$run=$con->prepare('INSERT INTO `email_send`(`u_id`, `a_id`, `remainder_date`, `send_status`) VALUES (?,?,?,?)');
							if(is_object($run))
							{
								$run->bindParam(1,$userID,PDO::PARAM_INT);
						        $run->bindParam(2,$appointmentID,PDO::PARAM_INT);
						        $run->bindParam(3,$formattedReminderDate,PDO::PARAM_STR);
						        $run->bindValue(4,'pending',PDO::PARAM_STR);

						        if($run->execute())
						        {
						        	return MsgDisplay('refersh','Appointment Updated successfully......!');
						        }
							}
				        }
					}
				}
			}
		}
		else
		{
			return MsgDisplay('error', 'Invalid Appointment Update.....!');
		}
	}
	else
	{
		return MsgDisplay('error', 'Invalid Appointment Update.....!');
	}

	return MsgDisplay('error', 'Something Was Problem Please Try Again...!');

}
else if(isset($_POST['deleteAppointment']) AND isset($_POST['deleteID']))
{
	if(is_numeric($_POST['deleteID']))
	{
	    $userID=$_SESSION['user_login'][1];
		$deleteID=$_POST['deleteID'];
		
		$run=$con->prepare('SELECT * FROM `appointment` WHERE id=?');
		$run->bindParam(1,$deleteID,PDO::PARAM_INT);
		if($run->execute())
		{
			if($run->rowCount()>0)
			{
				$run=$con->prepare("DELETE FROM `appointment` WHERE user_id=? AND id=?");
				$run->bindParam(1,$userID,PDO::PARAM_INT);
				$run->bindParam(2,$deleteID,PDO::PARAM_INT);

				if($run->execute())
				{
					$run=$con->prepare("DELETE FROM `email_send` WHERE u_id=? AND a_id=?");
					$run->bindParam(1,$userID,PDO::PARAM_INT);
					$run->bindParam(2,$deleteID,PDO::PARAM_INT);

					if($run->execute())
					{
						return MsgDisplay('success', 'Appointment Deleted Successfully...!');
					}
				}

			}
			else
			{
				return MsgDisplay('error', 'Invalid Appointment Delete...!');
			}
		}
	}
	else
	{
		return MsgDisplay('error', 'Invalid Appointment Delete...!');
	}


	return MsgDisplay('error', 'Something Was Problem Please Try Again...!');
}
else if(isset($_POST['completeAppointment']) AND isset($_POST['appointmentID']))
{

	$appointmentID=$_POST['appointmentID'];
	if(!empty($appointmentID) AND is_numeric($appointmentID))
	{
		$userID=$_SESSION['user_login'][1];
		$data=getAppointments($appointmentID);
		if(is_array($data))
		{
			$run=$con->prepare('UPDATE `appointment` SET `status`=? WHERE user_id=? AND id=?');
			$run->bindValue(1,'complete',PDO::PARAM_STR);
			$run->bindParam(2,$userID,PDO::PARAM_INT);
			$run->bindParam(3,$appointmentID,PDO::PARAM_INT);
			if($run->execute())
			{
				$run=$con->prepare('DELETE FROM `email_send` WHERE u_id=? AND a_id=? AND send_status=?');
			    $run->bindParam(1,$userID,PDO::PARAM_INT);
			    $run->bindParam(2,$appointmentID,PDO::PARAM_INT);
			    $run->bindValue(3,'pending',PDO::PARAM_STR);

			    if($run->execute())
			    {
			    	return MsgDisplay('success', 'Appointment Status Change Successfully...!');
			    }
			}
		}
		else
		{
			return MsgDisplay('error', 'Invalid Appointment Change...!');
		}
	}
	else
	{
		return MsgDisplay('error', 'Invalid Appointment Change...!');
	}

	return MsgDisplay('error', 'Something Was Problem Please Try Again...!');
}



?>
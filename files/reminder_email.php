<?php 
include "function.php";


if(is_object($con))
{

	$run=$con->prepare('SELECT * FROM `email_send` WHERE send_status=?');
	$run->bindValue(1,'pending',PDO::PARAM_STR);
	if($run->execute())
	{
		if($run->rowCount()>0)
		{
			$data=$run->fetchALL();
			foreach ($data as $key => $value) 
			{ 
				$data=getAppointments($value['a_id']);
				// Fetch date and time from the database
				$dbDateTime = $value['remainder_date'];
				// Format current date and time
				$currentDateTime = date('d-m-Y h:i A');
				// Compare the dates

				if (strtotime($currentDateTime) == strtotime($dbDateTime)) 
				{

					$data=getAppointments($value['a_id']);
			        $userData=getUser($value['u_id']);

					$to = $userData['email'];
				    $headers = "From: ediary935@gmail.com\r\n";
					$headers .= "Reply-To: ediary935@gmail.com\r\n";
					$headers .= "X-Mailer: PHP/" . phpversion();
					$subject="Subject: Reminder: [".ucwords($data['event_name'])."] - [".date("d-m-Y", strtotime($data['event_date']))."]";

$message_txt = "Dear ".ucwords($userData['name']).", 
This email is to remind you about the upcoming ".ucwords($data['event_name'])." scheduled on ".date("d-m-Y", strtotime($data['event_date']))." at ".$data['from_time']." to ".$data['to_time']." at ".ucwords($data['venue']).". We want to ensure that you don't miss out on this event, so please take note of the details below:

Event Name: ".ucwords($data['event_name'])."
Member: ".$data['member']."
Venue: ".ucwords($data['venue'])."
Event Date: ".date("d-m-Y", strtotime($data['event_date']))."
Time: ".$data['from_time']." to ".$data['to_time']."
Recurrence: ".ucwords($data['recurrence'])."

Please make sure to mark your calendar and be prepared to attend this event. If there are any changes or updates, please log in to your account and update your event accordingly.

Thank you for your participation, and we look forward to seeing you at the event!

Best regards,

[eDiary]
[".$currentDateTime."]
					";

					$eventDateTime = DateTime::createFromFormat('d-m-Y h:i A', $value['remainder_date']);
					$selectedOption = $data['recurrence'];

					if ($selectedOption === 'weekly') {
					    $reminderDateTime = clone $eventDateTime;
					    $reminderDateTime->add(new DateInterval('P7D')); // Subtract 7 days for a weekly recurrence
					    $formattedReminderDate = $reminderDateTime->format('d-m-Y h:i A');
					}else if ($selectedOption === 'monthly') {
					    $reminderDateTime = clone $eventDateTime;
					    $reminderDateTime->add(new DateInterval('P1M')); // Subtract 1 month for a monthly recurrence
					    $formattedReminderDate = $reminderDateTime->format('d-m-Y h:i A');
					}else if ($selectedOption === 'yearly') {
					    $reminderDateTime = clone $eventDateTime;
					    $reminderDateTime->add(new DateInterval('P1Y')); // Subtract 1 year for a yearly recurrence
					    $formattedReminderDate = $reminderDateTime->format('d-m-Y h:i A');
					}else if ($selectedOption === 'daily') {
					    $reminderDateTime = clone $eventDateTime;
					    $reminderDateTime->add(new DateInterval('P1D')); // Add 1 day for a daily recurrence
					    $formattedReminderDate = $reminderDateTime->format('d-m-Y h:i A');
					}else {

						$run=$con->prepare("UPDATE `appointment` SET `status`=? WHERE user_id=? AND id=?");
						$run->bindValue(1,'complete',PDO::PARAM_STR);
						$run->bindValue(2,$value['u_id'],PDO::PARAM_INT);
						$run->bindValue(3,$value['a_id'],PDO::PARAM_INT);
						$run->execute();

					    $formattedReminderDate='complete';
					}

					$send_id=$value['send_id'];
					$run=$con->prepare("UPDATE `email_send` SET `send_status`=? WHERE send_id=?");
				    $run->bindValue(1,'complete',pdo::PARAM_STR);
				    $run->bindParam(2,$send_id,pdo::PARAM_INT);

				    if ($run->execute()) 
				    {
				    	if($formattedReminderDate!='complete')
				    	{

				    		$run=$con->prepare('INSERT INTO `email_send`(`u_id`, `a_id`, `remainder_date`, `send_status`) VALUES (?,?,?,?)');
				    		$run->bindParam(1,$value['u_id'],PDO::PARAM_INT);
				    		$run->bindParam(2,$value['a_id'],PDO::PARAM_INT);
				    		$run->bindParam(3,$formattedReminderDate,PDO::PARAM_STR);
				    		$run->bindValue(4,'pending',PDO::PARAM_STR);
				    		$run->execute();


                            $newDataEvent = date("Y-m-d", strtotime($formattedReminderDate));


				    		$run=$con->prepare("UPDATE `appointment` SET `event_date`=?,`r_custom_date`=? WHERE user_id=? AND id=?");
							$run->bindValue(1,$newDataEvent,PDO::PARAM_STR);
							$run->bindValue(2,$newDataEvent,PDO::PARAM_STR);
							$run->bindValue(3,$value['u_id'],PDO::PARAM_INT);
							$run->bindValue(4,$value['a_id'],PDO::PARAM_INT);
							$run->execute();


				    	}
				    	
				    	mail('ediary935@gmail.com', $subject, $message_txt, $headers);
				    } 
				} 
			}
		}
	}
}

?>
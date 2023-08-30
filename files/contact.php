<?php

include "function.php";

if(isset($_POST['c_name']) && isset($_POST['c_email']) && isset($_POST['c_phoneNumber']) && isset($_POST['c_subject']) && isset($_POST['c_message']))
{
	if(!empty($_POST['c_name']) && !empty($_POST['c_email']) && !empty($_POST['c_phoneNumber']) && !empty($_POST['c_subject']) && !empty($_POST['c_message']))
	{
	    $name = $_POST['c_name'];
		$email = $_POST['c_email'];
		$phoneNumber = $_POST['c_phoneNumber'];
		$subject = $_POST['c_subject'];
		$message = $_POST['c_message'];

		$message_txt = $message . "<br><p>Name " . ucwords($name) . "...!<br>Phone Number: " . $phoneNumber . "</p><br><hr>";

		$header = "From: " . $email . "\r\n";
		$header .= "Cc: " . $email . "\r\n";
		$header .= "Reply-To: " . $email . "\r\n"; // Add the reply-to address

		$header .= "Content-type: text/html\r\n";

		@mail('ediary935@gmail.com', $subject, $message_txt, $header);
		return MsgDisplay('success', 'Email sent to the admin. A reply will be sent to your email address.', '');

	}
	else
	{
		return MsgDisplay('error', 'All Field Are Mandatory.....!', '');
	}
}

?>
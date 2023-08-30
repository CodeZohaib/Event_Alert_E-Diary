<?php 

include "function.php";

if(isset($_POST['name']) AND isset($_POST['email']) AND isset($_POST['password']) AND isset($_POST['confirm_pass']))
{
	if(!empty($_POST['name']) AND !empty($_POST['email']) AND !empty($_POST['password']) AND !empty($_POST['confirm_pass']))
	{
		$name=strtolower($_POST['name']);
		$email=strtolower($_POST['email']);
		$password=$_POST['password'];
		$confirm_pass=$_POST['confirm_pass'];

		if($password!=$confirm_pass)
		{
			MsgDisplay('error','Missmatch Password AND Confirm Password...... !','');
		}
		else if(password_method($_POST['password'])===false)
		{
			return MsgDisplay('error','Password Required<br><br> 1=Minimum 8 characters<br> 2=One uppercase letter<br> 3=One lowercase letter,<br> 4=One number,');
		}
		else if(is_array(getUser($_POST['email'])))
		{
			return MsgDisplay('error','Email Address Already Exist. Please Use Anthor Email.....!');
		}
		else
		{
			$run=$con->prepare("INSERT INTO `users`(`name`, `email`, `password`) VALUES (?,?,?)");
			if(is_object($run))
			{
				$run->bindParam(1,$name,PDO::PARAM_STR);
				$run->bindParam(2,$email,PDO::PARAM_STR);
				$run->bindParam(3,$password,PDO::PARAM_STR);
				if($run->execute())
				{
					$_SESSION['user_login']=[
		          	  $email,
		          	  $con->lastInsertId(),
                    ];

					$url=BASEURL."/dashboard.php";
					MsgDisplay('success','Account Created Successfully...... !',$url);
				}
			}
		}
	}
	else
	{
		MsgDisplay('error','All Fields Are Mandatory ...... !','');
	}
}
else if(isset($_POST['login_email']) AND isset($_POST['login_pass']) ) 
{
	$login_email=$_POST['login_email'];
	$login_pass=$_POST['login_pass'];

	if(empty($login_email))
	{
		return MsgDisplay('error','Please Enter Email.....!');
	}
	else if(empty($login_pass))
	{
		return MsgDisplay('error','Please Enter Password.....!');
	}
	else if(password_method($_POST['login_pass'])===false)
	{
		return MsgDisplay('error','Invalid Password.....!');
	}
	else
	{
		$userData=getUser(strtolower($login_email));
		if(is_array($userData))
		{
			if($userData['password']==$login_pass)
			{
				$_SESSION['user_login']=[
		          	  $userData['email'],
		          	  $userData['id'],
                    ];

					$url=BASEURL."/dashboard.php";
					MsgDisplay('success','User Login Successfully...... !',$url);
			}
			else
			{
				return MsgDisplay('error','Invalid Password.....!');
			}
			
		}
		else
		{
			return MsgDisplay('error','Invalid Email.....!');
		}
		
	}
	
}
else if(isset($_POST['adminLogout']) AND !empty($_POST['adminLogout']))
{
	session_destroy();
	unset($_SESSION['adminLogin']);
	return MsgDisplay('success','ID LogOut Successfully.....!',BASEURL."/login.php");
}
else if(isset($_POST['forgot_email'])) 
{
	if(!empty($_POST['forgot_email'])) 
	{
		$email=strtolower($_POST['forgot_email']);
		$userData=getUser($email);
		if(is_array($userData))
		{
		    $subject='Forgot Password';
            $message="<p>Hello ".ucwords($userData['name'])."...!<br>Your Password is :-  ".$userData['password']." </p><br><hr>
            <p>If You Think You Did Not Make This Request, Just ignore this email</p>";
            
	        @mail($email, $subject, $message,"Content-type: text/html\r\n");
	        return MsgDisplay('success','Password Send Your Email Address Please Check Your Email.....!','');
	        
		}
		else
		{
			return MsgDisplay('error','Invalid Email Address.....!','');
		}
	}
	else
	{
		return MsgDisplay('error','Please Enter Your Email Address.....!','');
	}
}
else if(isset($_POST['old_password']) AND isset($_POST['new_password']) AND isset($_POST['confirm_password']))
{
	if(!isset($_SESSION['user_login']) AND empty($_SESSION['user_login'][0]) AND empty($_SESSION['user_login'][1]))
	{
	    header('location:login.php');
	}
	else
	{
		$o_pass=$_POST['old_password'];
		$n_pass=$_POST['new_password'];
		$c_pass=$_POST['confirm_password'];

		if(!empty($o_pass) AND !empty($n_pass) AND !empty($c_pass)) 
		{
			if(password_method($o_pass)===false OR password_method($n_pass)===false OR password_method($c_pass)===false)
			{
				return MsgDisplay('error','Invalid Old Password.....!');
			}
			else if(password_method($n_pass)===false OR password_method($c_pass)===false)
			{
				return MsgDisplay('error','Password Required<br><br> 1=Minimum 8 characters<br> 2=One uppercase letter<br> 3=One lowercase letter,<br> 4=One number,');
			}
			else if($n_pass!=$c_pass)
			{
				MsgDisplay('error','Missmatch New Password AND Confirm Password...... !','');
			}
			else
			{
				$userEmail=$_SESSION['user_login'][0];
				$userID=$_SESSION['user_login'][1];
				$userData=getUser($userEmail);

				if($o_pass!=$userData['password'])
				{
					return MsgDisplay('error','Invalid Old Password.....!');
				}
				else
				{
					$run=$con->prepare('UPDATE `users` SET `password`=? WHERE id=?');

					$run->bindParam(1,$n_pass,PDO::PARAM_STR);
					$run->bindParam(2,$userID,PDO::PARAM_INT);

					if($run->execute())
					{
						return MsgDisplay('success','Password Change Successfully.....!');
					}
				}
				
			}

			MsgDisplay('error','Something Was Wrong Please Again...... !','');
		}
	}


}

//check($_POST);

?>
<?php
date_default_timezone_set("Asia/Karachi"); 
session_start();
  global $con;
  $con = connection();

  define("BASEURL","http://localhost/ediary");

  function connection()
  {
  	try
  	{
	    $db=new PDO("mysql:host=localhost;dbname=ediary","root","");
	    $db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
	    return $db;
	  }

	  catch(PDOException $e)
	  {
	    echo "Sorry database connection error:-".$e->getMessage();
	    exit();
	  }

  }


  function check($array)
  {
  	echo "<pre>";
  	print_r($array);
  	exit();
  }

 function password_method($password)
 {
	$uppercase = preg_match('@[A-Z]@',$password);
  $lowercase = preg_match('@[a-z]@',$password);
  $number    = preg_match('@[0-9]@',$password);

    if(!$uppercase || !$lowercase || !$number || strlen($password) < 8) 
    {
      return false;
    }
    else
    {
    	return true;
    }
}

function formatDate($date)
{
      return date('F j, Y, g:i A',strtotime($date));
}


function getUser($val)
{
	global $con;
	if(isset($val) AND !empty($val))
	{
		if(is_numeric($val))
		{
			$run=$con->prepare("SELECT * FROM `users` WHERE id=?");
		}
		else
		{
			$run=$con->prepare("SELECT * FROM `users` WHERE email=?");
		}
		
		if(is_object($run))
		{
			$run->bindParam(1,$val,PDO::PARAM_STR);

			if($run->execute())
			{
				if($run->rowCount()>0)
				{
					return $run->fetch(PDO::FETCH_ASSOC);
				}
			}
		}
	}

	return false;
}



function getUserAppointments($id)
{
	global $con;
	$run=$con->prepare('SELECT * FROM `appointment` WHERE user_id=? AND status=? ORDER BY id DESC');
	$run->bindParam(1,$id,PDO::PARAM_INT);
	$run->bindValue(2,'pending',PDO::PARAM_STR);
	if($run->execute())
	{
		if($run->rowCount()>0)
		{
			return $run->fetchALL(PDO::FETCH_ASSOC);
		}
	}

	return false;
}


function getSendEmailData($userId,$aId)
{
	global $con;
	$run=$con->prepare('SELECT * FROM `email_send` WHERE u_id=? AND a_id=? ORDER BY send_id DESC LIMIT 1');
	$run->bindParam(1,$userId,PDO::PARAM_INT);
	$run->bindParam(2,$aId,PDO::PARAM_INT);
	if($run->execute())
	{
		if($run->rowCount()>0)
		{
			return $run->fetch(PDO::FETCH_ASSOC);
		}
	}

	return false;
}


function getUserAllAppointments($id)
{
	global $con;
	$run=$con->prepare('SELECT * FROM `appointment` WHERE user_id=? ORDER BY id DESC');
	$run->bindParam(1,$id,PDO::PARAM_INT);
	if($run->execute())
	{
		if($run->rowCount()>0)
		{
			return $run->fetchALL(PDO::FETCH_ASSOC);
		}
	}

	return false;
}

function getUserEmailData($id)
{
	global $con;
	$run=$con->prepare('SELECT * FROM `email_send` WHERE u_id=? ORDER BY send_id DESC');
	$run->bindParam(1,$id,PDO::PARAM_INT);
	if($run->execute())
	{
		if($run->rowCount()>0)
		{
			return $run->fetchALL(PDO::FETCH_ASSOC);
		}
	}

	return false;
}

function getAppointments($id)
{
	global $con;
	$run=$con->prepare('SELECT * FROM `appointment` WHERE id=? AND status=?');
	$run->bindParam(1,$id,PDO::PARAM_INT);
	$run->bindValue(2,'pending',PDO::PARAM_STR);
	if($run->execute())
	{
		if($run->rowCount()>0)
		{
			return $run->fetch(PDO::FETCH_ASSOC);
		}
	}

	return false;
}
 

function MsgDisplay($status,$msg,$url=NULL)
{
	if ($status==='success' AND !empty($url)) 
	{
		echo json_encode([
			'success'=>'success',
			'message'=>$msg,
			'url'=>$url,
			
		]);
	}
	else if ($status==='success' AND empty($url)) 
	{

		echo json_encode([
			'success'=>'success',
			'message'=>$msg,
	    ]);
	}
	else if ($status==='error' AND empty($url)) 
	{
		echo json_encode([
			'error'=>'error',
			'message'=>$msg,
	    ]);
	}
    else if ($status==='refersh') 
      {
        echo json_encode([
            'success'=>'success',
            'message'=>$msg,
            'signout'=>1,
        ]);
      }
}
?>
<?php
// New profile after sign in activate_profile !!!
function NewProfile($Email, $Code)
{
	include '../../../wp-config.php';
	
	$Arr;
	$isv_db = @new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
	
	//  id, username, email, lastname
	$stmt1 = $isv_db->prepare("SELECT id, username, activate_profile FROM users WHERE email = ? LIMIT 1");
	$stmt1->bind_param('s', $Email);
	$stmt1->execute();
	$stmt1->store_result();
	$stmt1->bind_result($Id, $username, $HashCode);
	while($stmt1->fetch())
	{
		if(password_verify($Code, $HashCode))
		{
			//echo "JAAA";
			// CHECK IF PROFILE ALREADY Exists
			$iD = $Id;
			$stmtt = $isv_db->prepare("SELECT NULL FROM `user_profile` WHERE user_id = ? LIMIT 1");
			$stmtt->bind_param('s',$iD);
			$stmtt->execute();
			$stmtt->store_result();
			$stmtt->fetch();
			$countr = $stmtt->num_rows();
			$stmtt->close();
			
			// echo $countr;
				// Check if row exists and/or create new profile
				if($countr > 0)
				{
					exit();
				}
				else
				{
				// INSERT AFTER CHECK WITH Button Click after Sign In
				$stmt = $isv_db->prepare("INSERT INTO `user_profile`(`id`, `user_id`, `titel`, `phone`, `profile_pic`, `cover_photo`, `linkedin`, `omschr`) VALUES (NULL,?,'','','','','','')");
				$stmt->bind_param('i', $iD);
				$stmt->execute();
				$stmt->close();
	
				//header('Location: isv_pages/profile.php');
				}
		}
		else
		{
			echo "U heeft een verkeerd nummer....probeer het nogmaals";
		}
	}
	$stmt1->close();
}

// Load Member in memberspart
function LoadMember($Id)
{
	$isv_db = @new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
	
	$stmt = $isv_db->prepare("SELECT u.username, u.email, u.pwd, u.level, u.lastname, pro.user_id, pro.titel, pro.phone, pro.profile_pic, pro.linkedin, pro.omschr, pro.email_yn, sta.soc_status, sta.work_status, sta.occ_group FROM users u, user_profile pro, user_status sta WHERE u.status = 1 AND  u.id = ? AND pro.user_id = ? AND sta.user_id = ? LIMIT 1"); // u.level = 1 AND 
	$stmt->bind_param('iii', $Id,$Id,$Id);
	$stmt->execute();
	$stmt->store_result();
	$stmt->bind_result($username, $email, $pwd, $level, $lastname, $user_id, $titel, $phone, $profile_pic, $linkedin, $omschr, $emailYN, $socstatus,$workstatus,$occid);
	$countr = $stmt->num_rows;
	// echo $countr;
	
	if($countr == null)
	{
		$Arry[]=array();
	}
	else
	{
		while($stmt->fetch())
		{
			$Arry[] = array(
			'user_name' => $username, 
			'email' =>$email, 
			'pasw' =>$pwd, 
			'visible' =>$level, 
			'lastname' =>$lastname, 
			'user_id' =>$user_id, 
			'title' =>$titel, 
			'phone' =>$phone, 
			'photo' =>$profile_pic, 
			'linkedin' =>$linkedin, 
			'omschrijving' =>$omschr,
			'email_yn'=>$emailYN,
			'soc_status'=>$socstatus,
			'work_status'=>$workstatus,
			'occupy_id'=>$occid
			);
		}
		
	}
	$stmt->close();
	return $Arry;
}
?>
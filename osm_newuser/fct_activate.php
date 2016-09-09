<?php
/*
	Plugin Name: Activation Process
	Plugin Uri: guidoleen.nl
	Author: Guido Leen
	Version: 1.0
*/


function gu_activation_process()
{
if(isset($_POST['SubMNewStatus']))
{
	if( $_POST['inputCode'] == "" && $_POST['inputEmail'] == "" && filter_var($_POST['inputEmail'], FILTER_VALIDATE_EMAIL) )
	{
		handlError('Voer een juiste code of emailadres in');
		exit();
	}
	else
	{
		$isv_db = @new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
		
		$code = $_POST['inputCode'];
		$email = $_POST['inputEmail'];
		
		$stmt = $isv_db->prepare("SELECT id, activate_profile FROM users WHERE email = '$email'");
		$stmt->execute();
		$stmt->store_result();
		$stmt->bind_result($Id, $CodeDb);
		while($stmt->fetch())
		{
			$ArrID = array(
			'ID' => $Id,
			'Code' => $CodeDb
			);
		}
		$stmt->close();
		
		if(password_verify($code, $CodeDb))
		{
			try
			{
				$stmt = $isv_db->prepare("INSERT INTO user_profile(id, user_id, titel, phone, profile_pic, cover_photo, linkedin, omschr, email_yn) VALUES (NULL,?,'','','','','','',0)");
				$stmt->bind_param('i', $Id);
				$stmt->execute();
				$stmt->close();
			
				$stmt = $isv_db->prepare("INSERT INTO user_status(status_id, user_id, soc_status, work_status, occ_group) VALUES (NULL,?,0,0,0)");
				$stmt->bind_param('i', $Id);
				$stmt->execute();
				$stmt->close();
			
				$stmt = $isv_db->prepare("UPDATE users SET status=1, level=1, reg_date=?,activate_profile='1' WHERE id=$Id");
				$stmt->bind_param('d', date("Y-m-d h:i:sa"));
				$stmt->execute();
				$stmt->close();
				
				toLogin();
				exit();
			}
			catch(Exception $ee)
			{
				die("Oeps, er is iets mis gegaan. Probeer het nog een keer om te activeren....");
			}
		}
		else
		{
			handlError('Voer een juiste code of emailadres in');
			exit();
		}
	}
}
}
add_action('init','gu_activation_process');
?>

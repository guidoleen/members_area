<?php
	// TODO
	// inputEmail1
	
include '../../../wp-config.php';
	
	// Load the Encode Decode Class
	if(!class_exists('gu_encryption'))
	{
		include plugins_url() .  '/osm_cls/cls_encrypt_decrypt.php';
	}

// From the profile.php input:
if(isset($_POST['SubMUpdate']))
{
	// Check if token is equal
	if($_POST['profile_token'] != $_SESSION['profile_token'])
	{
		gu_goto_home();
		exit();
	}
	if(!gu_login_check())
	{
		gu_goto_home();
		exit();
	}
	else
	{
		// Re-initialise the session token
		$_SESSION['profile_token'] = "";
	
		// Call Encryption Class
		$Encryption = new gu_encryption();
	
		// MemId with decoded method from Encryption
		$MemId = $Encryption->decode($_GET['memId']);
		if($MemId == null)
		{
			handlError("Helaas kun je niet de gegevens updaten. Neem contact op met de beheerder...");
			exit();
		}
		else
		{
			$name = Esc($_POST['inputNaam']);
			$lastName = Esc($_POST['inputLastNaam']);
			$Titel = Esc($_POST['inputTitel']);
			$Tel =  Esc($_POST['inputTelefoon']);
			$Pwd = $_POST['inputPassword'];
			$omsch = Esc($_POST['inputOmschr']);
			if( $_POST['inputLinkedin'] === $_POST['inputLinkedinHide'] )
			{
				$Linkedin = Esc($_POST['inputLinkedinHide']);
			}
			else
			{
				$Linkedin = LinHttp(Esc($_POST['inputLinkedin']));
			}
			$occgroup = (int)($_POST['occ_group'] +1);

				$provisible = 0;
				switch($_POST['provisible'])
				{
					case "ja":
					$provisible = 1;
					break;
					case "nee":
						$provisible = 0;
					break;
				}
				
				$mail_yn = 0;
				switch($_POST['mail_yn'])
				{
					case "ja":
					$mail_yn = 1;
					break;
					case "nee":
						$mail_yn = 0;
					break;
				}
				
				$socstat = 0;
				switch($_POST['soc_status'])
				{
					case "0":
						$socstat = 0;
					break;
					case "1":
						$socstat = 1;
					break;
				}
				
				$socwork = 0;
				switch($_POST['work_status'])
				{
					case "0":
						$socwork = 0;
					break;
					case "1":
						$socwork = 1;
					break;
					case "2":
						$socwork = 2;
					break;
				}
				
				// Call the WP DB
				$isv_db = @new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

				if($Pwd == "")
				{
					$stmt = $isv_db->prepare("UPDATE user_profile pro, users u, user_status sta SET u.username=?, u.lastname=?, pro.titel=?, pro.phone=?, pro.linkedin=?, pro.omschr=?, u.level=?, pro.email_yn = ?, sta.soc_status = ?, sta.work_status = ?, sta.occ_group = ? WHERE  pro.user_id = $MemId AND u.id = $MemId AND sta.user_id = $MemId");
					$stmt->bind_param('ssssssiiiii', $name, $lastName, $Titel,$Tel,$Linkedin,$omsch,$provisible,$mail_yn,$socstat,$socwork,$occgroup);
				}
				else
				{
					$pwdhash = password_hash($Pwd, PASSWORD_DEFAULT);
					$stmt = $isv_db->prepare("UPDATE user_profile pro, users u, user_status sta SET u.username=?, u.lastname=?, pro.titel=?, pro.phone=?, pro.linkedin=?, pro.omschr=?, u.level=?, pro.email_yn = ?, u.pwd=?, sta.soc_status = ?, sta.work_status = ?, sta.occ_group = ? WHERE  pro.user_id = $MemId AND u.id = $MemId AND sta.user_id = $MemId");
					$stmt->bind_param('ssssssiisiii', $name, $lastName, $Titel,$Tel,$Linkedin,$omsch,$provisible,$mail_yn,$pwdhash,$socstat,$socwork,$occgroup);
				}
			$stmt->execute();
			$stmt->store_result();
			$stmt->close();
			
			// FileUpload Image
			if($_FILES['file_upload']['name'] == null)
			{
				// form fct_functions.php:
				gu_goto_memberp($_GET['memId']);
				exit();
			}
			else
			{
				// Call the update class
				if(!class_exists('uploadclss'))
				{
					include ABSPATH.'/wp-content/plugins/osm_cls/cls_upload_file.php';
				}
				$ObjFile = $_FILES['file_upload'];
			
				// Instantiate the class upload
				$ProfUpdate = new uploadclss();
				$ProfUpdate->getusrid($MemId);
				$ProfUpdate->toupload($ObjFile, ABSPATH . '/wp-content/osm_uploads/imgusr/', 300, 2);
			}
			// Klaar Met de update form fct_functions.php:
			gu_goto_memberp($_GET['memId']);
			exit();
		}
	}
}

// Function Linkedin http
function LinHttp($strLin)
{
	$strLin = strtolower($strLin);
	
	return $strLin;
}
?>
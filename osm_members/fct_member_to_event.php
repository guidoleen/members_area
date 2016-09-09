<?php
/*
	Plugin Name: Member Subscribe to Event
	Plugin Uri: guidoleen.nl
	Author: Guido Leen
	Version: 1.0
*/

function gu_member_to_event()
{
	if(isset($_POST['subMemberEvent']))
	{
		$mem_id = $_SESSION['Id'];
		$eventid = $_POST['event_id'];

			if(!class_exists('gu_encryption'))
    			{
    				include 'cls_encrypt_decrypt.php';
    			}
    			$EnDycrypt = new gu_encryption();
			$mem_id =  $EnDycrypt->decode($mem_id);
			
			try
			{
				$wp_db = @new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
				$stmt = $wp_db->prepare("SELECT user_id FROM users_event WHERE post_id = ?");
				$stmt->bind_param('i', $eventid);
				$stmt->execute();
				$stmt->store_result();
				$stmt->bind_result($UsrId);
				
				if($stmt->num_rows != null)
				{
					$iCount = 0;
					while( $stmt->fetch() )
					{
						if($UsrId == $mem_id)
						{
							$iCount = 1;
						}
					}
					
					if($iCount == 1)
					{
						handlError("Ahh, jij bent al aangemeld voor dit café....");
						exit();
					}
				}
				if($eventid == 0)
				{
					handlError("Probeer je opnieuw aan te melden voor dit café....");
					exit();
				}
				else
				{
					$stmt = $wp_db->prepare("INSERT INTO users_event (post_id, user_id) VALUES (?,?)");
					$stmt->bind_param('ii', $eventid, $mem_id);
					$stmt->execute();
				}
				
				$stmt->close();
			}
			catch(Exception $ee)
			{
				handlError($ee);
			}
	}
}
add_action('init', 'gu_member_to_event');

// function show members in event
function gu_showmembers_inevent($event_id)
{
	$arrResult;
	$SqlQuery = "SELECT u.username, pro.profile_pic, u.id FROM users u, user_profile pro, 
		(SELECT user_id FROM users_event WHERE post_id = ?) AS usrid
		WHERE u.id = usrid.user_id AND pro.user_id = usrid.user_id LIMIT 6";
	try
	{
		$wp_db = @new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
		$stmt = $wp_db->prepare($SqlQuery);
		$stmt->bind_param('i', $event_id);
		$stmt->execute();
		$stmt->store_result();
		$stmt->bind_result($UsrName,$UsrPhoto,$UsrId);
		
		if($stmt->num_rows == 0)
		{
			$arrResult = "";
			return $arrResult;
		}
		else
		{
			if(!class_exists('gu_encryption'))
			{
				include plugins_url() . '/osm_cls/cls_encrypt_decrypt.php';
			}
			$EnDycrypt = new gu_encryption();

			while( $stmt->fetch() )
			{
				$arrResult[] = array(
					'user_name'=>$UsrName,
					'user_photo'=>$UsrPhoto,
					'user_id'=>$EnDycrypt->encode($UsrId)
				);
			}
			return $arrResult;
		}
	}
	catch(Exception $ee)
	{
		die($ee);
	}
}
add_action('init', 'gu_showmembers_inevent');

?>
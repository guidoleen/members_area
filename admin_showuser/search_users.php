<?php
include '../wp-config.php';
define('IMAGE_MAP', content_url() . '/osm_uploads/imgusr/');

// Search Function
if(isset($_POST['userSub']))
{
	$Search = $_POST['searchuser'];
	$strSearch = "SELECT u.username, u.lastname, u.id, pro.profile_pic FROM users AS u JOIN user_profile AS pro ON pro.user_id=u.id WHERE u.username LIKE '$Search%' OR u.lastname LIKE '$Search%' OR u.id LIKE '$Search'";
	
	try
	{
		$dbConn = @new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
		$result = mysqli_query($dbConn,$strSearch);
		
		$arr_result;
		while($row = mysqli_fetch_array($result))
		{
			$arr_result = array(
				'username' => $row['username'],
				'lastname' => $row['lastname'],
				'id' => $row['id'],
				'photo_pic' => $row['profile_pic']
			);
		}
		if(!$arr_result == NULL)
		{
			echo screen_write_member($arr_result['id'],$arr_result['username'],$arr_result['lastname'],$arr_result['photo_pic']);
		}
		
		else if($arr_result == NULL)
		{
			// Member without profileinput
			try
			{
				$strSearch = "SELECT username, lastname, id FROM users WHERE username LIKE '$Search%' OR lastname LIKE '$Search%' OR id LIKE '$Search'";
				$result = mysqli_query($dbConn,$strSearch);
				while($row = mysqli_fetch_array($result))
				{
					$arr_result = array(
					'username' => $row['username'],
					'lastname' => $row['lastname'],
					'id' => $row['id']);
				}
				if(!$arr_result == NULL)
				{
					echo screen_write_member($arr_result['id'],$arr_result['username'],$arr_result['lastname'],"");
				}
			}
			catch(Exception $ee)
			{
				echo "Helaas even geen verbinding met de database";
			}
		}
		
		else
		{
			echo "<span style='color:#ff0000'>Helaas geen members gevonden...</span>";
		}
	}
	catch(Exception $ee)
	{
		echo "Helaas even geen verbinding met de database";
	}
}

// function write member to screen
function screen_write_member($id,$username,$lastname,$photopic)
{
	$strTotal = "<br><a href='index.php?usrid=" . $id . "'><div class='membershow'>";
	
	if($photopic == "")
	{
		$photopic = "usr_0.jpg";
	}
	$strTotal .= "<img src='" . IMAGE_MAP . $photopic . "' style='border-radius: 50%; width:100px'><br>";
	$strTotal .= $username . " " . $lastname;
	$strTotal .= "</div></a>";
	
	return $strTotal;
}

?>
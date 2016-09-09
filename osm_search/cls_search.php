<?php
class gu_search
{
	function __construct()
	{
	}

	function find($Term,$SqlTotal)
	{
		$isv_db = @new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
		
		$term = $Term;
		if($term != "")
		{
			$term = preg_replace("#[^a-z0-9]#i","",$term);
		}
		
		if($SqlTotal == 0)
		{
			$stmp = $isv_db->prepare("SELECT u.id, u.username, u.lastname, pro.titel, pro.linkedin, pro.profile_pic FROM users AS u INNER JOIN user_profile AS pro ON u.id = pro.user_id WHERE  u.status = 1 AND u.level = 1 AND u.username LIKE '$term%'");
		}
		else
		{
			$stmp = $isv_db->prepare("SELECT u.id, u.username, u.lastname, pro.titel, pro.linkedin, pro.profile_pic FROM users AS u INNER JOIN user_profile AS pro ON u.id = pro.user_id WHERE  u.status = 1 AND u.level = 1 AND u.username LIKE '$term%' OR u.lastname LIKE '$term%'");
		}
		$stmp->execute();
		$stmp->store_result();
		$stmp->bind_result($id,$username,$lastname,$titel,$linkedin,$photo);
		$rcount = $stmp->num_rows();
		
			if($rcount > 0)
			{
				while($stmp->fetch())
				{
					$resultName[] = array('id' => $id, 'user_name' => $username, 'last_name' => $lastname, 'titel' => $titel, 'linkedin' => $linkedin, 'photo' => $photo);
				}
			}
			else
			{
				return false;
				// $resultName[] = array();
			}
	return $resultName;
	}
}
?>

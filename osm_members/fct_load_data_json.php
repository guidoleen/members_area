<?php
	include '../../../wp-config.php';
	$isv_db = @new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
	
	// Call Encryption Class
	$Encryption = new gu_encryption();
	
	// the params van $.ajax()
	$limit = $_GET['limit'];
	$from = $_GET['from'];
	
	// counter for user aantal EVENTUEEL TE GEBRUIKEN
	// $counter = mysqli_query($isv_db, 'SELECT NULL FROM `users`');
	// $countr = mysqli_num_rows($counter);
	
	if(!isset($_GET['limit']))
	{
		die('Helaas geen database connectie...');
	}
	else
	{
		// connect / select db met LIMIT
		$res = mysqli_query($isv_db, ('SELECT u.*, pro.titel, pro.linkedin, pro.profile_pic FROM users AS u INNER JOIN user_profile AS pro ON u.id = pro.user_id WHERE u.status = 1 AND u.level = 1 ORDER BY u.username ASC LIMIT ' . $limit . ' OFFSET ' . $from));
		while ($row = mysqli_fetch_array($res)) 
		{
			$arr[] = array('id'=>$Encryption->encode($row['id']), 'username'=>$row['username'], 'last_name'=>$row['lastname'], 'title'=>$row['titel'], 'linkedin'=>$row['linkedin'], 'photo'=>$row['profile_pic']);
		}
	}

// encode de array naar JSON voor ajax met ECHO
echo json_encode($arr);
?>  
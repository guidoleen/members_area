<?php
/*	Plugin Name: Members Lijst Centre
	Plugin URI: http://guidoleen.nl
	Desciption: ListBuilder
	Version: 1.0
	Author: Guido Leen
*/

// TODO > show members from location > SELECT FROM WHERE ....
// Show the widget
function wddp_showfrom_db()
{
	echo wddp_search_form();
	echo "<br>De gebruikers die eventueel aan te passen zijn... <BR>";
	
	//// Db connection vanuit WP
	$dbConn = @new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
	
	if($dbConn->connect_errno)
	{
		echo "Helaas geen connectie mogelijk. Probeer het later nog eens....";
		exit();
	}
	else
	{
		try
		{
			$stmt = $dbConn->prepare("SELECT id, username FROM users ORDER BY username LIMIT 5 OFFSET 0");
			$stmt->execute();
			$stmt->bind_result($Id,$username);
			$stmt->store_result();
			
			while($stmt->fetch())
			{
				echo "<a href='index.php?usrid=" . $Id . "'>";
				echo $username . '</a><br>';
			}
			$stmt->close();
		}
		catch(Exception $e)
		{
			echo "er ging iets mis...";
		}
	}
	
	//// Connection and show member with ID
	// id waarde van delete input
	$Inputval="Id waarde";
	
	// The Post
	if(isset($_GET['usrid']))
	{
		$id = $_GET['usrid'];
		try
		{
			$stmt = $dbConn->prepare("SELECT u.username, u.lastname, pro.profile_pic FROM users AS u JOIN user_profile AS pro ON pro.user_id=u.id WHERE u.id = $id");
			$stmt->execute();
			$stmt->bind_result($username, $lastname, $photo);
			$stmt->store_result();
			
			define('IMAGE_MAP', content_url() . '/osm_uploads/imgusr/');
			while($stmt->fetch())
			{
				if($photo == NULL)
				{
					$strSet = "<img src='" . IMAGE_MAP . "usr_0.jpg' style='width:100px;border-radius:50%'>";
				}
				else
				{	
					$strSet = "<img src='" . IMAGE_MAP . $photo . "' style='width:100px;border-radius:50%'>";
				}
				$strSet .= "<br><a href='index.php?usrid=" . $Id . "'>";
				$strSet .= "<span class='first b b-posts'>" . $username . '</span></a><br>';
				
				echo $strSet;
			}
			
			// Show in inputfield Delete
			$Inputval = $id;
		}
		catch(Exception $e)
		{
			echo "er ging iets mis...";
		}

	}
	
	// Calling the Search Function
	include 'search_users.php';
	
	// Calling the Delete form
	include 'delete_user.php';
	
}
// Form create search form
function wddp_search_form()
{
 	$SearchStr = "<form method='post' action='index.php'>";
 	$SearchStr .= "<input type='text' name='searchuser'>";
 	$SearchStr .= "<input type='submit' name='userSub' value='Vind member'>";
  	$SearchStr .= "</form>";
	
	return $SearchStr;
}
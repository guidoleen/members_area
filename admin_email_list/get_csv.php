<?php
/*
	Plugin Name: Create Email CSV List for MailChimp
	Author: Guido Leen
	Plugin Uri: guidoleen.nl
	Version: 1.0
*/

// Show in Dashboard
function gu_make_cvs_list()
{
	echo make_theform();
}

// function make form and call the make .php file
function make_theform()
{
	// Get post in array from category in users_event
	$wpDb = @new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
	$stmt = $wpDb->prepare("SELECT DISTINCT post_id FROM users_event ORDER BY post_id");
	$stmt->execute();
	$stmt->store_result();
	$stmt->bind_result($postId);
	
	$Qposts = array();
	while( $stmt->fetch() )
	{
		array_push($Qposts, $postId);
	}
	$stmt->close();
	
	$OptionVal = "";
	$icount = count($Qposts);
	if($Qposts != null)
	{
		// include the post function
		include "fct_csv.php";
		
		$i = 0;
		while($i < $icount)
		{
			$OptionVal .= "<option value='" . preg_replace("/ /", "", $Qposts[$i] ) . "'>" . gu_get_theTitle( $Qposts[$i] ) . "</option>";
			$i++;
		}
	}
	
	// Combine result in option form
	return $strTheForm = "<form method='post' action='" . plugins_url() .  "/admin_email_list/make_csv.php' enctype='multipart/form-data'><select name='Locatie'><option selected='true'>Selecteer....</option>". $OptionVal . "</select><input type='submit' name='sub_csv_file' value='Maak CSV file aan'></form>";
}

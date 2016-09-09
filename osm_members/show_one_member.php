<?php
/*
	Plugin Name: Show 1 Member
	Plugin URI: guidoleen.nl
	Author: Guido Leen
	Version: 1.0
*/
include 'fct_member_profile.php';

function gu_show_one_member()
{
	$ArrMember;
	if(isset($_GET['memId']))
	{
		$Id = $_GET['memId'];
		$IdMail = $_GET['memId'];
	
		// Call Encryption Class
		$Encryption = new gu_encryption();
	
		// Call the Member Function with decode ID From 
		$ArrMember = LoadMember($Encryption->decode($Id));
	}
return $ArrMember;
}
add_action('init', 'gu_show_one_member');
?>

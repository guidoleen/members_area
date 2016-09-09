<?php
/*
	Plugin Name: Show Error
	Plugin URI: guidoleen.nl
	Author: Guido Leen
	Version: 1.0
*/

function gu_show_error()
{
	$Error = "";
	if(isset($_GET['err']))
	{
		$Error = $_GET['err'];
	}
return $Error;
}
add_action('init', 'gu_show_error');
?>

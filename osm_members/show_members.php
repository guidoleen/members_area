<?php
/*
Plugin Name: Show Members
Plugin URI:  http://guidoleen.nl
Version:     1.0
Author:      Guido Leen
*/

function gu_wp_show_members()
{
	//// Db connection vanuit WP
	$dbConn = @new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
	
	if($dbConn->connect_errno)
	{
		echo "Helaas geen connectie mogelijk. Probeer het later nog eens....";
		exit();
	}
	
	// See if class already exists
	if(!class_exists('rowBuilder'))
	{
		include(ABSPATH."/wp-content/plugins/osm_cls/cls_rowbuilder.php");
	}
	
	// instantiate class rowbuilder and build rows from class first time while loading page
	$rowbuild = new rowBuilder();
	$_StrShowStart = $rowbuild->dbRowFirtsTime($dbConn);
	
return $_StrShowStart;
}
add_action('init', 'gu_wp_show_members');
	
?>
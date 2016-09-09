<?php
/* 
	Plugin Name: Login System
	Plugin URI: guidoleen.nl
	Author: Guido Leen
	Version: 1.0
*/

function gu_login_member()
{
	// Handle the loginform
	if(isset($_POST['subLogin']))
	{
		if(!class_exists("Login"))
		{
			include ABSPATH.'/wp-content/plugins/osm_cls/cls_login.php';
		}
		$clsLogin = new Login();

			if($clsLogin->isLoggedin())
			{
				$usrId = $_SESSION['Id'];
				header("Location: " . home_url() . '/member/?memId=' . $usrId);
				exit();
			}
			else
			{
				header("HTTP/1.1 403 Forbidden");
				$clsLogin->ShowErrors();
			}
	}
}
add_action('init','gu_login_member');
?>
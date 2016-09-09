<?php
/*
	Plugin Name: Forgot Email
	Plugin Uri: guidoleen.nl
	Author: Guido Leen
	Version: 1.0
*/

 

function gu_password_forgot()
{
if (isset($_POST['ButtForgotPassw'])) 
{
	$email = $_POST['forgotEmail'];

	if($_POST['forgttoken'] != $_SESSION['forgot_token'])
	{
		exit();
	}
	
	if(emailOrNot($email) && EmailExist($email)) 
   	{
    		try
    		{
       	 		// New password random
       	 		if(!class_exists('gu_forgot_password'))
			{
				include ABSPATH.'/wp-content/plugins/osm_cls/cls_forgot_pwd.php';
			}
        		$randpwd = randomPwd();
		
			// class aanmaken en aanroepen
        		$chpasw = new gu_forgot_password();
        		$chpasw->setNewPassw($email,$randpwd);
        
       	 		$mailtext = "Dit is een passwoord dat tijdelijk maar 2 uur gebruikt kan worden: <BR>";
       			$mailtext .= $randpwd;
       	 
       	 		// call the mailer method;
       	 		////////// TODO >> DE EMAIL FUNCTIE >>> UPDATEN WHEN ON SERVER!!!!
			include ABSPATH . '/wp-content/plugins/osm_newuser/mailer_weg/fct_mailform.php';
       	 		mailer($email, "Hier een nieuw password om in te loggen...", $mailtext);
        
        		// header('Location: ' . ABSPATH);
        		handlError("Jouw nieuwe code is verstuurd via email. Verander nà inloggen deze in een nieuwe code.");
        	}
        	catch(Exeption $EE)
       		{
        		handlError("Helaas is er momenteel geen verbinding mogelijk om een password aan te maken... Probeer het later nog eens.");
        	}
    	} 
    	else 
    	{
       		// Login failed 
       		handlError("fout password, probeer nog een keer");
    	}
}
}
add_action('init', 'gu_password_forgot');
?>

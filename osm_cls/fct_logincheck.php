<?php
/* 
	Plugin Name: Login Check System
	Plugin URI: guidoleen.nl
	Author: Guido Leen
	Version: 1.0
*/

function gu_login_check() 
{
	///////  SET SESSION ///////
 	ini_set('session.cookie_httponly', 1); // reduce js identity theft
	ini_set('session.use_only_cookies', 1);
	ini_set('session.cookie_lifetime', 0); 
	ini_set('session.entropy_file', '/dev/urandom');
	ini_set('session.hash_bits_per_character', 6);
	// ini_set('session.cookie_secure', 1); // ONLY WHEN HTTPS
	
	session_start();
	
	// session_regenerate_id();
	/////// END SET SESSION ///////
	
    // Check if all session variables are set 
    if (isset($_SESSION['Id'], $_SESSION['email'], $_SESSION['token'], $_SESSION['HTTP_USER_AGENT'])) 
    {
    	if(!class_exists('gu_encryption'))
    	{
    		include 'cls_encrypt_decrypt.php';
    	}
    	$EnDycrypt = new gu_encryption();
    	
        $user_id = $EnDycrypt->decode($_SESSION['Id']);
        $token = $_SESSION['token'];
        $email = $_SESSION['email'];
        $usragent = $_SESSION['HTTP_USER_AGENT'];
        
        // Get the user-agent string of the user.
        $user_browser = $_SERVER['HTTP_USER_AGENT'];
 
	 $wp_db = @new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        if ($stmt = $wp_db->prepare("SELECT token_id, user_agent  FROM sessions WHERE user_id = ? LIMIT 1")) 
        {
            // Bind "$user_id" to parameter. 
          	$stmt->bind_param('i', $user_id);
            	$stmt->execute();
           	$stmt->store_result();
 
            if ($stmt->num_rows == 1) {
                // If the user exists get variables from result.
                $stmt->bind_result($_token, $_useragent);
                $stmt->fetch();
                 
                if (($_token == $token) && ($_useragent == $usragent))
                {
                	    // Logged In!!!! 
                   	return true;
                } else {
                    // Not logged in 
                    return false;
                }
            } else {
                // Not logged in 
                return false;
            }
        } else {
            // Not logged in 
            return false;
        }
    } else {
        // Not logged in 
        return false;
    }
}
add_action('init', 'gu_login_check');

function stopSession()
{
	if(isset($_GET['logout_mem']))
	{
		if($_GET['logout_mem'] == 1)
		{
			$_SESSION = array();
			$_SESSION['Id'] = "";
			
			// session_start();
			// session_destroy();
		}
	}
}
add_action('init', 'stopSession');

function show_start_member($id)
{
	if(!class_exists('gu_encryption'))
    	{
    		include 'cls_encrypt_decrypt.php';
    	}
    	$EnDycrypt = new gu_encryption();
	$Uid = $EnDycrypt->decode($id);
	
	$resultArr = "";
	try
	{
		$wp_db = @new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
		$stmt = $wp_db->prepare("SELECT u.username, pro.profile_pic FROM users AS u, user_profile AS pro WHERE u.id = ? AND pro.user_id = ? LIMIT 1");
		$stmt->bind_param('ii', $Uid, $Uid);
		$stmt->execute();
		$stmt->store_result();
		$stmt->bind_result($usrname, $picture);
	
		while($stmt->fetch())
		{
			$resultArr[] = array(
				'username'=> $usrname,
				'picture'=> $picture
			);
		}
	}
	catch(Exception $ee)
	{
		die("helaas geen database verbinding: " . $ee);
	}
	
	return $resultArr;
}
add_action('init', 'show_start_member');
?>
<?php
if(!class_exists('gu_encryption'))
{
	include 'cls_encrypt_decrypt.php';
}
 
class Login
{
	private $_Id;
	private $_email;
	private $_password;
	private $_hashpwd;
	private $_sessionid;
	private $_acces;
	
	public $_usragent;
	private $_login;
	private $_token;
	private $_errors;
	
	public function __construct()
	{
		$this->_sessionid = session_id();
		$this->_errors = array();
		$this->_login = isset($_POST['subLogin']) ? 1 : 0;
		$this->_acces = 0;
		$this->_usragent = md5($_SERVER['HTTP_USER_AGENT']);
		$this->_password = ($this->_login) ? $this->filter($_POST['pwdval']) : '';
		$this->_email = ($this->_login) ? $_POST['emailval'] : $this->filter($_SESSION['emailval']);
		$this->_hashpwd = ($this->_login) ? password_hash($this->_password, PASSWORD_DEFAULT) : '';
		$this->_token = $_POST['tokenval']; // token from hidden field
		$this->_usragent = $_POST['usragentval']; // usagent from hidden field
		$this->_Id = 0;
		// $this->_usrid = $this->GetUsrId();
	}
	
	function isLoggedin()
	{
		($this->_login) ? $this->veryfiPostData() : $this->veryfiSessionData();
		return $this->_acces;
	}
	
	function filter($var)
	{
		return preg_replace('/[^a-zA-Z0-9]/','', $var);
	}
	
	function veryfiPostData()
	{
		try
		{
			if(!$this->isTokenValid())
			{
				throw new Exception("Ben je op de juiste computer aan het inloggen....");
				UnSetSession();
			}
				
			if(!$this->isUsrAgentValid())
			{
				throw new Exception("Gebruik je dezelfde browser...");
				UnSetSession();
			}
				
			if(!$this->emailOrNot($this->_email))
			{
				throw new Exception("Geen juist emailadres ingevoerd. Check of het juist is....");
				UnSetSession();
			}
				
			if(!$this->veryfiDataBase())
			{
				throw new Exception("Geen juist password/email....");
				UnSetSession();
			}
			
			else
			{
				$this->_acces = 1;
				$this->RegisterSession();
				$this->sessionInDb();
			}
		}
		catch(Exception $ee)
		{
			$this->_errors[] = $ee->getMessage();
		}
	}
	
	function veryfiSessionData()
	{
		if($this->veryfiDataBase() && $this->sessionExits())
		{
			$this->_acces = 1;
		}
	}
	
	function veryfiDataBase()
	{
		$wp_db = @new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
		$stmt = $wp_db->prepare("SELECT id, email, pwd FROM users WHERE email = ? LIMIT 1");
		$stmt->bind_param('s', $this->_email);
		$stmt->execute();
		$stmt->store_result();
		$stmt->bind_result($usrid, $email, $pwd);
		$stmt->fetch();

		if($stmt->num_rows == 1)
		{
			if ($this->checkbrute($usrid, $wp_db) == true) 
             		{
             			echo "Je hebt te vaak een poging gedaan om in te loggen. Probeer het over een uur weer...";
	             		 return false;
           		}
           		else if(password_verify($this->_password, $pwd))
           		{
           			$this->_Id = $usrid;
           			return true;
           		}
           		else
           		{
           			// Password is not correct
                  		// We record this attempt in the database, time() in number of seconds after 1970...
                   		$nowatt = time();
                    		$wp_db->query("INSERT INTO login_attempts(user_id, time) VALUES ('$usrid', '$nowatt')");
                    		return false;
           		}
		}
		else
		{
			return false;
		}
		$wp_db->close();
	}
	
	function isTokenValid()
	{
		return (!isset($_SESSION['tokenval']) || $this->_token != $_SESSION['tokenval']) ? 0 : 1;
	}
	
 	function isUsrAgentValid()
 	{
		return (!isset($_SESSION['HTTP_USER_AGENT']) || $this->_usragent != $_SESSION['HTTP_USER_AGENT']) ? 0 : 1;
 	}
	
	function RegisterSession()
	{
		$Encrypt = new gu_encryption();
		$_SESSION['Id'] = $Encrypt->encode($this->_Id);
		$_SESSION['email'] = $Encrypt->encode($this->_email);
		$_SESSION['token'] = $this->_token;
	}
	
	function UnSetSession()
	{
		// Unset sessions
		$_SESSION = array();
		
		// get session parameters 
		$params = session_get_cookie_params();
		
		// Delete the actual cookie.
		setcookie(session_name(),
        	'', time() - 42000, 
       		$params["path"], 
        	$params["domain"], 
        	$params["secure"], 
        	$params["httponly"]);
		
		// Destroy session
		session_destroy();
	}
	
	function sessionExits()
	{
		return(isset($_SESSION['email']) && isset($_SESSION['Id'])) ? 1 : 0;
	}
	
	public function ShowErrors()
	{
		echo "<div id='loginpart'><h3>Oeps, gaat iets mis: </h3>";
		
		foreach($this->_errors as $key=>$value)
		{
			echo $value;
		}
		
		echo "</div>";
	}
	
// 	function GetUsrId()
// 	{
// 		
// 	}
	
	function emailOrNot($value)
	{
		if(filter_var($value, FILTER_VALIDATE_EMAIL)) 
		{
       	 		return true;
    		} 
  		else 
    		{
        		return false;
    		}
	}
	
 	function checkbrute($user_id, $mysqli) 
 	{
    		 $now = time();
  
    		// All login attempts are counted from the past 1 hours. 
    		 $valid_attempts = $now - (1 * 60 * 60);

    		if ($stmt = $mysqli->prepare("SELECT time FROM login_attempts WHERE user_id = ? AND time > '$valid_attempts'")) 
    	 	{	
      	  		$stmt->bind_param('i', $user_id);
  
       	 		// Execute the prepared query. 
      	 		$stmt->execute();
       	 		$stmt->store_result();
 		
        	// If there have been more than 5 failed logins 
         		if ($stmt->num_rows > 4) 
       			{
           			return true;
         		}	 
        		else 
         		{
             			return false;
         		}
		}
	}
	
	function sessionInDb()
	{
		$lastact = time();
		
		$wp_db = @new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
		
		$stmt = $wp_db->prepare("DELETE FROM sessions WHERE user_id = ?");
		$stmt->bind_param('i', $this->_Id);
		$stmt->execute();

		$stmt = $wp_db->prepare("INSERT INTO sessions (user_id, token_id, user_agent, last_activity) VALUES  (?, ?, ?, ?)");
		$stmt->bind_param('isss', $this->_Id, $this->_token, $this->_usragent, $lastact);
		$stmt->execute();
		
		$wp_db->close();
	}
}

?>

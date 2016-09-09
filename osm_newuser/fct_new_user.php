<?php
/*
	Plugin Name: Create New User
	Plugin Uri: guidoleen.nl
	Author: Guido Leen
	Version: 1.0
*/

function gu_new_user()
{ 
if(isset($_POST["submitNewUsr"]))
{
	// Check if Session is ON >> MAYBE TODO
// 	if(!isset($_COOKIE['testifcookie']))
// 	{
// 		handlError("Deze site gebruikt cookies om je te kunnen aanmelden. Het is dus belangrijk om te zorgen dat jouw browser cookies accepteert...");
// 		exit();
// 	}

	if($_POST['username'] == "" && $_POST['lastname'] == "" && $_POST['email'] == "" && $_POST['pwd'] == "" && $_POST['pwd2'] == "" && $_POST['captcha'] == "")
	{
		handlError("Graag alle velden invoeren...");
		exit();
	}
	if($_POST['newusr_token'] != $_SESSION['newusr_token'])
	{
		handlError("Geen geldige browser om mee in te loggen. Probeer het nog een keer...");
		exit();
	}
	else
	{
		// First Check if email exists
		$isv_db = @new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

		$checkmail = $_POST["email"];
		$stmtt = $isv_db->prepare("SELECT NULL FROM users WHERE email = ? LIMIT 1");
		$stmtt->bind_param('s',$checkmail);
		$stmtt->execute();
		$stmtt->store_result();
		$stmtt->fetch();
		$countr = $stmtt->num_rows();
		$stmtt->close();
		if($countr > 0)
		{	
			handlError("Email bestaat al reeds....");
			exit();
		}
		else
		{
			// captaCheck
			if($_POST["captcha"]!=$_SESSION["code"])
			{
				handlError("Geen geldige captcha code ingevoerd....");
				exit();
			}
			else
			{
				try
				{
					// Activate pwd for profile making LET OP: ID KUN JE WIJZIGEN
					$iiRand = rand();
					$ArrRandSign = RandomSign($iiRand);
		
					// Database insert into
					$isv_db = @new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

					$RegDate = date('Y-m-d H:i:s');
					$stmt = $isv_db->prepare ("INSERT INTO `users` (`id`, `username`, `email`, `pwd`, `status`, `level`, `reg_date`, `activate_profile`, `lastname`) VALUES (NULL,?,?,?,0,0,?,?,?)");
 					$stmt->bind_param('ssssss', $username, $email, $userpwd, $RegDate, $ActivateProf, $LastName);
					$username = EscHtml($_POST["username"]);
					$email = EscHtml(filter_var($_POST["email"], FILTER_VALIDATE_EMAIL));
					$userpwd = password_hash($_POST["pwd"], PASSWORD_BCRYPT);
					$userpwd2 = $_POST["pwd2"];
					$ActivateProf = $ArrRandSign['hashed_string'];
					$LastName = EscHtml($_POST["lastname"]);

					if(password_verify($userpwd2, $userpwd) AND !$username =="" AND !$email =="")
					{
						$stmt->execute();
						$stmt->close();
			
						////////// DE EMAIL FUNCTIE >>> UPDATEN WHEN ON SERVER!!!!
						$strUrl =  home_url() . '/activatemember/';
						
						// Message construct + code
						$strText = "Kopieer hier jouw persoonlijke code en plak 'm in de aanmeldpagina: <br> <a href=";
						$strText .= $strUrl . ">Meld je hier aan...</a><br><br>";
						$strText .= "Jouw aanmeld code: <br><br>" . $ArrRandSign['plain_string'];
						$strText .= "<br> Na het succesvol aanmelden kun je direct naar de inlogpagina om met jouw ingegeven password en email adres in te loggen.";
						
						handlError("Welkom bij inBetween <br>" . $strText);
						exit();
					}
					else
					{
						// Password is not correct
                  				// We record this attempt in the database, time() in number of seconds after 1970...
                   				$nowatt = time();
                    				$wp_db->query("INSERT INTO login_attempts(user_id, time) VALUES ('$usrid', '$nowatt')");
                    				
						handlError("Geen password match....");
						exit();
					}
				}
				catch(Exeption $EE)
				{
					handlError("Helaas ging er iets mis bij het aanmelden, probeer het later nog eens....");
					exit();
				}
			}
		}
	}

	// Naar Activate gedeelte >> TODO
	// header('Location: ' . home_url() . '/activatemember/');
}
}
add_action('init', 'gu_new_user');

// Function create ranbdom password after email
function RandomSign($Id)
{
	// construct the passSignIn
	date_default_timezone_set("Europe/Amsterdam");
	$date = date('Y-m-d H:i:s');
	$pwdforSign = $Id . $date;
	$pwdforSign = str_replace(' ', '', $pwdforSign);
	
		$pwdforSign = substr($pwdforSign, 5, 13);
	
	// hashed passSignIn
	$pwdSign = password_hash($pwdforSign, PASSWORD_BCRYPT);
	
	$DateArr = array(
		'hashed_string'=> $pwdSign,
		'plain_string'=> $pwdforSign
	);
	return $DateArr;
}

// Basic Characterset for purefi-ing
function EscHtml($value)
{
	return htmlspecialchars($value, ENT_QUOTES, 'utf-8');
}

?>
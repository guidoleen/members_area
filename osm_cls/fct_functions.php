<?php
// Error Handling
function handlError($Err)
{
	return header('Location: ' . home_url() . '/error/?err=' . $Err); 
}

// To inlog Page
function toLogin()
{
	return header('Location: ' . home_url() . '/login/'); 
}

// Basic Characterset for purefi-ing MAYBE HTML PUREFIER!!!!
function Esc($value)
{
	return htmlspecialchars($value, ENT_QUOTES, 'utf-8');
}

// Got ToHome
function gu_goto_home()
{
	return header('Location: ' . home_url());
}

// Go To Member Page Show
function gu_goto_memberp($id)
{
	return header("Location: " . home_url() . "/member?memId=" . $id);
}

// Function Event or Not
function gu_check_ifevent()
{
	$GetTerms = get_the_category();
	
	$TermsArr = array();
	foreach($GetTerms as $term)
	{
		$TermsArr[] = $term->name;
	}
	if($TermsArr == null)
	{
		break;
	}

	for($i=0; $i<count($TermsArr);$i++)
	{
		if($TermsArr[$i] === "In Between Café")
		{
			return true;
			break;
		}
		else
		{
			return false;
		}
	}
}

// Email or Not
function emailOrNot($email)
{
	return filter_var($email, FILTER_VALIDATE_EMAIL) ? 1 : 0;
}

// Email Exist or Not
function EmailExist($email)
{
	$wp_db = @new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
	$stmt = $wp_db->prepare("SELECT NULL FROM users WHERE email = ?");
	$stmt->bind_param('s', $email);
	$stmt->execute();
	$stmt->store_result();

	if($stmt->num_rows > 0)
	{
		return true;
	}
	else
	{
		return false;
	}
	
	$stmt->close();
}

////////// Change Password after inlogfailure /////////
function randomPwd() 
{
    $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
    $pass = array(); //remember to declare $pass as an array
    
    $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
    
    for ($i = 0; $i < 8; $i++) {
        $n = rand(0, $alphaLength);
        $pass[] = $alphabet[$n];
    }
    return implode($pass); //turn the array into a string
}

// Show Occupation from list
function ShowOccupationArr()
{
	$isvdb = @new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

	$stmt = $isvdb->prepare("SELECT occ_id, occ_name FROM occ_group");
	$stmt->execute();
	$stmt->store_result();
	$stmt->bind_result($occId,$OccName);
	
	$ArrResult;
	
	while($stmt->fetch())
	{
		$ArrResult[] = array(
			'occ_id'=>$occId,
			'occ_name'=>$OccName
		);
	}
	return $ArrResult;
}

// Function only in In Between Event
function IsOnlyBetweenEvent($strVal)
{
	$postCat = get_the_category();
	$arrStr['cat_name'] = array();
	foreach( $postCat as $Cat )
	{
		array_push( $arrStr['cat_name'], $Cat->name );
	}
	
	$n = count( $arrStr['cat_name'] );
	for( $i=0; $i<$n; $i++ )
	{
		if( $arrStr['cat_name'][$i] == $strVal )
		{
			return true;
			break;
		}
	}
	return false;
}

?>
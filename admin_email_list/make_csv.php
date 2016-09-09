<?php
include '../../../wp-config.php';

// If Submitted the Button
$StrDirName = 'fupload';
	
	if(isset($_POST['sub_csv_file']))
	{
		var_dump($_POST['Locatie']);
		if($_POST['Locatie'] !== 'Selecteer....')
		{
			$iLocId = $_POST['Locatie'];
			
			// include the post function for retrieving the option name
			include "fct_csv.php";
		
			$StrPlace = gu_get_theTitle( $iLocId );
			$StrPlace = strtolower(str_replace( " ", "", $StrPlace ));
			$StrPlace = $StrDirName . '/' . $StrPlace . '.csv';
	
			if(get_email_tocsv($iLocId, $StrPlace, $StrDirName) == true)
			{
				echo "Gelukt: " . header('Location: ' . $StrPlace);
			}
		}
		else
		{
			echo "U moet een Locatie selecteren..... <a href='" . admin_url() . "'>Terug naar Dashboard</a>";
		}
	}
	
// Function Get email to CSV - Construction >> TODO: SELECT FROM WHERE locationId = .....
function get_email_tocsv($iLocId, $LocName, $StrDirName)
{
	try
	{
		$conn = @new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
		$Sql = "SELECT usr.email, usr.username FROM users usr, (SELECT user_id FROM users_event WHERE post_id =" . $iLocId . ") AS usrID WHERE usr.id= usrID.user_id";
		
		$result = mysqli_query($conn, $Sql);
		
		// First create array from db rows
		$arr[] = array();
		while($row = mysqli_fetch_assoc($result))
		{
			$arr[] = array('email'=>$row['email'],
			'username'=>$row['username']
			);
		}
		
		if(!file_exists($StrDirName))
		{
			mkdir($StrDirName, 0744);
		}
		
		// To file write....
		$StrWriter = fopen($LocName,'w');
			// fwrite($StrWriter,implode("", $arr));
			
			// Create foreachloop from array to fputcsv
			foreach($arr as $arrCsv)
			{
				fputcsv($StrWriter, $arrCsv);
			}
		fclose($StrWriter);
		
	return true;	
	}
	catch(Exception $EE)
	{
		die("Probeer het nogmaals of neem contact op met de beheerder....");
	return false;
	}
}


?>

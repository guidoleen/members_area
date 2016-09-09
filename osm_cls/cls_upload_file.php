<?php
// function upload
class uploadclss
{
	function __construct()
	{
	}
	
/////// TODO User uit de Database na klikken > vanuit Session[]
private $UsrId;
private $UsrIdName;	
public $NewFileName;
public $OldFileName;

///// Handle error
function handlerror($Err)
{
	return header('Location: 404.php?error=' . $Err); 
}

///// userId uit de database
function getusrid($usrId)
{
	$this->setphotoname($usrId);
	
	$this->UsrId = $usrId;
	return $this->UsrId;
}
function setphotoname($usrid)
{
	$this->UsrIdName = "usr_" . $usrid;
	return $this->UsrIdName;
}

// Functie upload	
function toupload($fileInput, $FileDir, $Ratio, $MaxFSize)
{
	$MaxFilesize = $MaxFSize * 1024 * 1024;
	if($fileInput == null)
	{
		exit();
	}
	
	try
	{
	if($_FILES['file_upload']["size"] == null)
	{  
		$this->handlerror("Een foto toevoegen a.u.b.");
	}
	else if($_FILES['file_upload']["size"] > $MaxFilesize) // groter dan ..mb
	{  
		$this->handlerror("Jouw file is te groot om te uploaden naar onze server. Maak 'm a.u.b. kleiner dan 2mb.");
		exit();
	}
	else
	{	// user name open.....
		$UsrIdName = $this->UsrIdName;
			
		// file + directory		
		$FileInput = $UsrIdName;
		$FileInput2 = $UsrIdName . "_s";
		$FileType = pathinfo($_FILES['file_upload']["name"], PATHINFO_EXTENSION);
		$FileType = strtolower($FileType);
		$FileName = basename($_FILES['file_upload']["name"]); 
					
		if($FileType == "jpeg" || $FileType == "png" || $FileType == "gif" || $FileType == "jpg")
		{
			if($FileType == "jpeg" || $FileType == "jpg")
			{
				$FileInput .= ".jpg";
				$FileInput2 .= ".jpg";
			}
			if($FileType == "png")
			{
				$FileInput .= ".png";
				$FileInput2 .= ".png";
			}
			if($FileType == "gif")
			{
				$FileInput .= ".gif";
				$FileInput2 .= ".gif";
			}
			
			// De Filename in the DB
			if($this->dbPhotoInset($this->UsrId,$FileInput))
			{
				// echo "Geladen";
			}
			
			// De fileupload
			if(move_uploaded_file($_FILES['file_upload']["tmp_name"], $FileDir . $FileInput))
			{
				// CopyFile
				$this->OldFileName = $FileDir . $FileInput;
				$this->NewFileName = $FileDir . $FileInput2;
				// echo "Gelukt"  . $FileType;

				if($this->fileresizenow($FileType, $FileDir . $FileInput, $Ratio, $FileDir . $FileInput))
				{
					header('Location: index');
					exit();
				}
				else
				{
					unlink($FileDir . $FileInput);
					$this->handlerror("niet goed gegaan");
				}
			}
	}	
	}
	}
	catch(Exception $e)
	{
		$this->handlerror($e);
	}
}

/// Copy file 2nd time
function copyfile($Ratio)
{
	copy($this->OldFileName,$this->NewFileName);
	
	// Making smaller or larger
	$FileType = pathinfo($this->OldFileName, PATHINFO_EXTENSION);
	$this->fileresizenow($FileType, $this->NewFileName, $Ratio, $this->NewFileName);
}

//// Resize function ////
function fileresizenow($filetype, $fileinput, $ratio, $filedir)
{
	//// Resize image //// 
	$FileSource;
	try
	{
		switch($filetype)
		{
		case "jpg":
		$FileSource = imagecreatefromjpeg($fileinput);
		if(!$FileSource)
		{
			$this->handlerror("File kleiner maken");
			unlink($fileinput);
		}
		break;

		case "jpeg":
		$FileSource = imagecreatefromjpeg($fileinput);
		break;

		case "png":
		$FileSource = imagecreatefrompng($fileinput);
		break;

		case "gif":
		$FileSource = imagecreatefromgif($fileinput);
		break;

		default:
		handlerror("U heeft geen jpg, png of gif gedownload");
		}
	}
	catch(Exception $Ee)
	{
		$this->handlerror("File kleiner maken" . $Ee);
		// exit();
	}
	
	if($FileSource == null)
	{

	break;
	}
	else
	{
	
	$sourx = imagesx($FileSource);
	$soury = imagesy($FileSource);

	// Set the newwidth/height and proportion x/y
	$DestFileX = $ratio;
	$DestFileY = $ratio/$sourx;
	$DestFileY = round($DestFileY * $soury);
	
	// setup new image
	$FileDest = imagecreatetruecolor($DestFileX, $DestFileY);

	// Create new image
	imagecopyresampled($FileDest, $FileSource, 0,0,0,0, $DestFileX, $DestFileY, $sourx, $soury);
	
	// put new resized file in dir
	// Set the new Directory name
	$NewDir = $filedir;
	switch($filetype)
	{
		case "jpg" || "jpeg":
		imagejpeg($FileDest, $NewDir);
		break;

		case "png":
		imagepng($FileDest, $NewDir);
		break;

		case "gif":
		imagegif($FileDest, $NewDir);
		break;
	}
	}
imagedestroy($FileSource);
imagedestroy($FileDest);
return true;
//// end resize image ////
}

// db connnect
	function dbPhotoInset($id,$Photoname)
	{
		global $isv_db;
		
		$stmt = $isv_db->prepare("UPDATE user_profile SET profile_pic = ? WHERE user_id = ?");
		$stmt->bind_param('si',$Photoname,$id);
		$stmt->execute();
		$stmt->store_result();
		$stmt->close();
		
		return true;
	}
}

?>
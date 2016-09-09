<?php
if (!class_exists('gu_encryption')) 
{
        include 'cls_encrypt_decrypt.php';
}

class rowBuilder
{
	public $StrShowStart;
	public $rij;

	public function __construct()
	{
	}
	
	// RijBouwer 1st time
	public function rijBouwerPhp($Id, $name, $lastname, $functie, $photo, $linkdin)
	{
		$rij =  "<div class='col-md-4'><li class='media basic_backclr' style='padding:2px;'><span class='pull-left' href=''>";
		$rij .= "<a href='";
		$rij .= "member?memId=" . $Id;
		$rij .= "'>";
		$rij .= "<img class='img-circle memimg' alt='' src='";
		
		if($photo == "")
		{
			$rij .= content_url() . "/osm_uploads/imgusr/usr_0.jpg'";
		}
		else
		{
			$rij .= content_url() . "/osm_uploads/imgusr/" . $photo ."'";
		}

		$rij .= ">";
		$rij .= "</a></span><div class='media-body'><span class='text-muted pull-right'>";
	
		if($linkdin == 1)
		{
			$rij .= "<a href=''><i class='align-right'><img src='";
			$rij .= content_url() . "/osm_uploads/site/linkedin40.png'></i></a>";
		}

		$rij .= "</span><h3>";
		$rij .= $name . " " . $lastname;
		$rij .= "</h3><p>";
        	$rij .= "<a href='";
        	$rij .= "member?memId=" . $Id;
        	$rij .= "'>";
        	$rij .=  $functie;
        	$rij .=  "</a>";
		$rij .= "</p></div></li><hr></div>";

		return $rij;
	}
	
	private $Encryption; 
	// First time Build Row at Start
	public function dbRowFirtsTime($dbrowbuild)
	{
		// Calling Encryption
		$this->Encryption = new gu_encryption();
		
		// $res = mysqli_query($dbrowbuild, 'SELECT u.username, u.lastname, u.id, pro.titel, pro.linkedin, pro.profile_pic FROM users u, user_profile pro WHERE u.status = 1 AND u.level = 1 AND pro.user_id = u.id ORDER BY u.username ASC LIMIT 6 '); 
		$res = mysqli_query($dbrowbuild, 'SELECT u.username, u.lastname, u.id, pro.titel, pro.linkedin, pro.profile_pic 
		FROM users AS u INNER JOIN user_profile AS pro ON u.id=pro.user_id WHERE u.status = 1 AND u.level = 1 ORDER BY u.username ASC LIMIT 6 '); 
    		while ($row = mysqli_fetch_array($res)) 
    		{
    			if($row['linkedin'] == "")
    			{
    				$LinkIn = 0;
    			}
    			else
    			{
    				$LinkIn = 1;
    			}
    			$this->StrShowStart .= $this->rijBouwerPhp($this->Encryption->encode($row['id']), $row['username'], $row['lastname'], $row['titel'], $row['profile_pic'], $LinkIn);
       		 }
       		 return $this->StrShowStart;
       }
}
?>
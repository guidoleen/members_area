<?php
	//// Db connection vanuit WP
	$isv_dbadm = @new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
		
if(isset($_POST['SubSave']))
{
	if($_POST['dropselect'] == "pwd")
	{
		$dbquery = "UPDATE users SET "; 
		$dbquery .= "pwd='" . password_hash($_POST['inputVal'], PASSWORD_BCRYPT);
		$dbquery .= "' WHERE id = " . $_POST['inId'];
	}
	
	if($_POST['dropselect'] == "activate_profile")
	{
		$dbquery = "UPDATE users SET "; 
		$dbquery .= "activate_profile='" . password_hash($_POST['inputVal'], PASSWORD_BCRYPT);
		$dbquery .="' WHERE id = " . $_POST['inId'];
	}

	$stmt = $isv_dbadm->prepare($dbquery);
	$stmt->execute();
	$stmt->close();
}
//////// BEFORE DELET MEMBER ///////
if(isset($_POST['DeleteMemr']))
{
	//session_start();
	//session_regenerate_id();
	$_SESSION['idMem'] = $_POST['inId'];
	
	echo "<div id='cancel_ok' style='background-color:#ddd;left:0;top:0;width:100%;height:50px;position:absolute;padding:5px;'>Weet je zeker dat je deze member wilt verwijderen: <form method='post' action='index.php' enctype='multipart/form-data'> <input type='submit' id='Delete_Memr' name='Delete_Memr' value='Ja'><input type='submit' id='NODelete_Memr' name='NODelete_Memr' value='Nee'></form></div>";
	
	// session_destroy();
}

//////// NO DELETE MEMBER //////
if(isset($_POST['NODelete_Memr']))
{
}

/////// DELETE MEMBER /////////
if(isset($_POST['Delete_Memr']))
{
	$idMem = $_SESSION['idMem'];
	echo "Ja " . $idMem;
	
	if($idMem == "")
	{
		echo "Geen (geldige) ID";
		// exit();
	}
	
	// Chek if id is in DB
	if(CheckIdTrue($idMem))
	{
			$Id = $idMem;

			$sql = "DELETE users, users_event, user_status, user_profile FROM users INNER JOIN
					users_event INNER JOIN
					user_status INNER JOIN
					user_profile WHERE users.id = ? 
					AND users_event.user_id = users.id
					AND user_status.user_id = users.id
					AND user_profile.user_id = users.id";

			$stmt = $isv_dbadm->prepare($sql);
			$stmt->bind_param('i', $Id);
			$stmt->execute();
			$stmt->close();
			
			echo "User deleted....";
	}
	else
	{
		echo "Geen bestaand ID in de Data Base....";
	}
}

// ID Checkerr
function CheckIdTrue($IdChk)
{	
	$dbadm = @new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

	$stmt = $dbadm->prepare("SELECT id FROM users");
	$stmt->execute();
	$stmt->store_result();
	$stmt->bind_result($id);
	$contr = $stmt->num_rows();
	$result="";
	while($stmt->fetch())
	{
		$result[] = $id;
	}
	$stmt->close();
	
	foreach($result as $strcheck)
	{
		if($strcheck == $IdChk)
		{
			return true;
		}
	}
	return false;
}

?>
<div class="container"><!--/.RIJEN Members Start -->
<hr>
  <div class="row">
    <div class="col-md-12">
      <div class="media basic_backclr" style="padding:2px;">
        <div class="media-body" style="padding:10px">

          <label class="col-lg-2 control-label">
	Toesturen van nieuw password of nieuwe activatiecode,<br>
	of het deleten van een member uit het bestand.
          </label>  <br> <br> 
          
<!--/.Form Data Input -->
                  
<form class="form-horizontal" role="form" method="post" action="index.php" enctype="multipart/form-data">
  
   <div class="form-group">
 <label class="col-lg-2 control-label">Id</label>
 <div class="col-lg-10">
  <input type="text" class="form-control" id="inId" name="inId" value="<?php echo $Inputval ?>">
 </div>
 </div>
 
  <br>
 <div class="form-group">
 <label class="col-lg-2 control-label">Wat wil je doen?</label>
 <div class="col-lg-10">
   <!-- <input type="text" class="form-control" id="inputCol" name="inputCol" placeholder="<?php echo 'De colummm' ?>"> -->
   
    	<select class="form-control" id="dropselect" name="dropselect">
  		<option value="pwd">Password veranderen</option>
  		<option value="activate_profile">Nieuwe activatiecode sturen</option>
	</select> 
 </div>
 </div>
 
 <br>
  <div class="form-group">
 <label class="col-lg-2 control-label">Welke password/waarde</label>
 <div class="col-lg-10">
   <input type="text" class="form-control" id="inputVal" name="inputVal" value="Waarde">
 </div>
 </div>
           
  <br>
 
 <div class="form-group">
 <div class="col-lg-10">
   <input type="submit" name="SubSave" id="SubSave" class="btn btn-danger" value="Bewaar in de DB...">
   <input type="submit" name="DeleteMemr" id="DeleteMemr" class="btn btn-danger" value="Delete Member...">
 </div>
 </div>
 
</form>
                  
                  
    	</div>
	</div>
	</div>
 </div>
</div>
<hr>

<?php
	// include '../isv_pages/ovr/m_footer.php';
?>

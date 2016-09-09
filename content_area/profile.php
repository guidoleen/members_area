<?php
/*
	Template Name: profile
*/

	if(gu_login_check())
	{
		$Id = $_GET['memId'];
                if($_GET['memId'] != $_SESSION['Id'])
              	{
              		echo "<h2>Oeps....... dit is niet jouw profiel......</h2>";
              		exit();
              	}

		$_ArrMember = gu_show_one_member();
	
		if($_ArrMember[0]['photo'] != "")
		{
			$StrPhoto = content_url() . '/osm_uploads/imgusr/' . $_ArrMember[0]['photo'];
		}
		else
		{
			$StrPhoto = content_url() . '/osm_uploads/imgusr/usr_0.jpg';
		}
	}
	else
	{
		header("Location: " . site_url());
		exit();
	}
	
	$profileToken = $_SESSION['profile_token'] = md5(uniqid(rand(0,5)));
?>
<?php
	get_header();
?>
<div class="container"><!--/.RIJEN Members Start -->
  	<hr>

  	<div class="row">
    <div class="col-md-12">
      <div class="media basic_backclr" style="padding:2px;">
        <div class="media-body" style="padding:10px">

         <a href="<?php echo 'member/?memId=' .$Id ?>"><img class="img-circle" alt="" src="<?php echo $StrPhoto ?>" style="width:100px; height: 100px; margin-top:3px;margin-left:5px"></a>
          <br>
          <label class="col-lg-2 control-label">

				<h3>Hoi, <?php echo $_ArrMember[0]['user_name'] . " " . $_ArrMember[0]['lastname']?></h3>
         		<p>
          		 Mocht je het nodig vinden, hier kun je jouw gegevens aanpassen:
         		</p>
          </label>  <br> <br> 
          
<!--/.Form Data Input -->
                  
<form class="form-horizontal" role="form" method="post" action="<?php echo plugins_url() .  '/osm_members/fct_profile_update.php?memId='. $Id ?>" enctype="multipart/form-data">
  
<div class="form-group">
<label class="col-lg-2 control-label"></label>
 <div class="col-lg-10">
   
       <span class="btn btn-default btn-file">Pas hier jouw foto aan
         <input type="file" id="file_upload" name="file_upload">
    </span>
 </div>
 </div>
  
  
   <div class="form-group">
 <label class="col-lg-2 control-label">Naam</label>
 <div class="col-lg-10">
 	<div class="row">
 		<div class="col-xs-6 col-md-6">
  		<input type="text" class="form-control" id="inputNaam" name="inputNaam" value="<?php echo $_ArrMember[0]['user_name']?>"> 
  		</div>
  		<div class="col-xs-6 col-md-6">
  		<input type="text" class="form-control" id="inputNaam" name="inputLastNaam" value="<?php echo $_ArrMember[0]['lastname']?>">
  		</div>
  	</div>
 </div>
 </div>
  
   <div class="form-group">
     <label class="col-lg-2 control-label">Titel/functie</label>
 <div class="col-lg-10">
  <input type="text" class="form-control" id="inputTitel" name="inputTitel" value="<?php echo $_ArrMember[0]['title'] ?>">
 </div>
 </div>
 
   <div class="form-group">
 <label class="col-lg-2 control-label">Email</label>
 <div class="col-lg-10">
  <input type="text" class="form-control" id="inputEmail1" name="inputEmail1" value="<?php echo $_ArrMember[0]['email'] ?>">
 </div>
 </div>
  
  
 <div class="form-group">
 <label class="col-lg-2 control-label">Telefoon</label>
 <div class="col-lg-10">
   <input type="text" class="form-control" id="inputTelefoon" name="inputTelefoon" value="<?php echo $_ArrMember[0]['phone'] ?>">
 </div>
 </div>
 <div class="form-group">
 <label class="col-lg-2 control-label">Verander Password</label>
 <div class="col-lg-10">
   <input type="password" class="form-control" id="inputPassword" name="inputPassword" placeholder="Password">
 </div>
 </div>
 <div class="form-group">
 <label class="col-lg-2 control-label">Korte omschrijving</label>
 <div class="col-lg-10">
    <textarea class="form-control" rows="5" id="inputOmschr" name="inputOmschr" value="Korte omschrijving"><?php echo $_ArrMember[0]['omschrijving'] ?></textarea>
 </div>
 </div>
 <div class="form-group">
 <label class="col-lg-2 control-label">Plak hier jouw LinkedIn adres:</label>
 <div class="col-lg-10">
 <input type="hidden" name="inputLinkedinHide" value="<?php echo $_ArrMember[0]['linkedin'] ?>">
  <input type="text" class="form-control" id="inputLinkedin" name ="inputLinkedin" value="<?php echo $_ArrMember[0]['linkedin'] ?>">
 </div>
 </div>
  
<div class="form-group">
 	<label class="col-lg-2 control-label">Jouw Profiel zichtbaar?</label>
 	<div class="col-lg-10">
  	<div class="radio">
  	
  	<?php  
  	if($_ArrMember[0]['visible'] == 1)
  	{
  		$StrRadButt = "<label ><input type='radio' name='provisible' value='ja' checked='checked'>Ja</label><br>";
  		$StrRadButt .= "<label><input type='radio' name='provisible' value='nee'>Nee</label>";
  	}
  	else
  	{
		$StrRadButt = "<label ><input type='radio' name='provisible' value='ja'>Ja</label><br>";
  		$StrRadButt .= "<label><input type='radio' name='provisible' value='nee' checked='checked'>Nee</label>";  	
  	}
  	echo $StrRadButt;
  	?>
	</div>
	</div>
 </div>
 
  <hr noshade>
  
<div class="form-group">
  <label class="col-lg-2 control-label">Kunnen anderen jou mailen?</label>
 	<div class="col-lg-10">
  	<div class="radio">
  	<?php  
  	if($_ArrMember[0]['email_yn'] == 1)
  	{
  		$StrRadButt = "<label ><input type='radio' name='mail_yn' value='ja' checked='checked'>Ja</label><br>";
  		$StrRadButt .= "<label><input type='radio' name='mail_yn' value='nee'>Nee</label>";
  	}
  	else
  	{
		$StrRadButt = "<label ><input type='radio' name='mail_yn' value='ja'>Ja</label><br>";
  		$StrRadButt .= "<label><input type='radio' name='mail_yn' value='nee' checked='checked'>Nee</label>";  	
  	}
  	echo $StrRadButt;
  	?>
	</div>
 	</div>
 </div>
 
  <hr noshade>
  
<div class="form-group">
  <label class="col-lg-2 control-label">Jouw "status" op dit moment:</label>
 	<div class="col-lg-10">
  	<div class="radio">
  	<?php  
  	if($_ArrMember[0]['soc_status'] == 0)
  	{
  		$StrRadButt = "<label ><input type='radio' name='soc_status' value='0' checked='checked'>Werkzoekend</label><br>";
  		$StrRadButt .= "<label><input type='radio' name='soc_status' value='1'>ZZP'er</label>";
  	}
  	else
  	{
		$StrRadButt = "<label ><input type='radio' name='soc_status' value='0'>Werkzoekend</label><br>";
  		$StrRadButt .= "<label><input type='radio' name='soc_status' value='1' checked='checked'>ZZP'er</label>";  	
  	}
  	echo $StrRadButt;
  	?>
	</div>
 	</div>
 </div>
 
 <hr noshade>
 
<div class="form-group">
  <label class="col-lg-2 control-label">Jouw "status" op dit moment:</label>
 	<div class="col-lg-10">
  	<div class="radio">
  	<?php
  		$strinval = array();
  		switch($_ArrMember[0]['work_status'])
  		{
  			case 0 :
  				$strinval[0] = "checked='checked'";
  			break;
  			
  			case 1 :
  				$strinval[1] = "checked='checked'";
  			break;
  			
  			case 2 :
  				$strinval[2] = "checked='checked'";
  			break;
  			
  			default :
  				 $n = count($strinval);
  				for($i=0; $i<n; $i++)
  				{
  					$strinval[$i] = "";
  				}
  			break;
  		}
  	?>
  		<label><input type='radio' name='work_status' value='0' <?php echo $strinval[0] ?>>Uwv</label><br />
    		<label><input type='radio' name='work_status' value='1' <?php echo $strinval[1] ?>> Bijstand</label><br />
		<label><input type='radio' name='work_status' value='2' <?php echo $strinval[2] ?>> Geen uitkering</label>
	</div>
 	</div>
 </div>
 
  <hr noshade>
  
     <div class="form-group">
     <?php
     	$arrOcc = ShowOccupationArr(); 
     ?>
     	
     <label class="col-lg-2 control-label">Jouw beroepsgroep:
     	<?php 
     		$ioccnr = ($_ArrMember[0]['occupy_id'])-1;
   		echo $arrOcc[$ioccnr]['occ_name'];
    	 ?>
     </label>
 	<div class="col-lg-10">
 	<select class="input-large" id="occ_group" name="occ_group">
 		<?php
 			$n = count($arrOcc);
 			$stroption = "<option class='form-control' value='00'>Selecteer een beroepsgroep</option></br>";
 			for($i=0;$i<$n;$i++)
 			{
 				$stroption .= "<option class='form-control' value='" . $i . "'> " . $arrOcc[$i]['occ_name'] . "</option></br>";
 			}
 			echo $stroption;
 		?>
 	</select>
 	</div>
 </div>
         
  <br>
 
 <div class="form-group">
 <div class="col-lg-offset-2 col-lg-10">
 <input type="hidden" name="profile_token" value="<?php echo $profileToken ?>">
   <button type="submit" name="SubMUpdate" id="SubMUpdate" class="btn btn-danger">Bewaar</button>
 </div>
 </div>
</form>
                  
                  
    </div>
 </div>
<hr>

<?php
	get_footer();
?>

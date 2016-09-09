<?php
/*
	Template Name: member
*/
	// Load the Encode Decode Class
	if(!class_exists('gu_encryption'))
	{
		include plugins_url() .  '/osm_cls/cls_encrypt_decrypt.php';
	}
?>
<?php
	// Call the show one member function
	$_ArrMember = gu_show_one_member();
	
	// Check if Photo or Not
	$strErr = "Helaas geen member gevonden....";
	if($_ArrMember[0] == NULL)
	{
		handlError($strErr);
	}
	else
	{
		if($_ArrMember[0]['photo'] != "")
		{
			$StrPhoto = content_url() . '/osm_uploads/imgusr/' . $_ArrMember[0]['photo'];
		}
		else
		{
			$StrPhoto = content_url() . '/osm_uploads/imgusr/usr_0.jpg';
		}
	}
	
	if($_ArrMember[0]['visible'] == "")
	{
		handlError($strErr);
	}
?>
<?php
	get_header();
?>
<div class="container"><!--/.MEMBER Profile Start -->
  	<hr>

  	<div class="row">
    <div class="col-md-12">
      
      <div class="media basic_backclr" style="padding:2px;">
        <div class="media-body" style="padding:10px">

         <a href=""><div class="img-circle" style="overflow:hidden;width:150px;height:150px;"><img style="width:150px; margin-top:0px;margin-left:0px" src="<?php echo $StrPhoto ?>" style="width:150px; height: 150px; margin-top:3px;margin-left:5px"></div></a>
          <br>
          <div class="row">
          <label class="col-lg-2 control-label">
            <h3><?php echo $_ArrMember[0]['user_name'] . " " . $_ArrMember[0]['lastname']?></h3> 
          </label>
          
          <label class="col-lg-6 control-label">
            <p>
            <?php echo $_ArrMember[0]['title'] ?>
            </p><br>
             <p class="plain_text">
         	<?php echo $_ArrMember[0]['omschrijving'] ?>
            </p>
            <?php 
            	if($_ArrMember[0]['linkedin'] != "")
            	{
          	 echo "<a href='" . $_ArrMember[0]['linkedin'] . "'><img class='img-circle' alt='' src='" . content_url() . "/osm_uploads/site/linkedin.png' style='width:50px;height:50px;margin-top:3px;margin-left:5px'></a>";
		}
		if($_ArrMember[0]['phone'] != "")
            	{
          	echo  "<a href='tel:" . $_ArrMember[0]['phone'] . "'><img class='img-circle' alt='' src='" . content_url() . "/osm_uploads/site/telef.png' style='width:50px; height: 50px; margin-top:3px;margin-left:5px'></a>";
          	 }
           ?>
           </label>
          </div>	 
 
          
          
        </div>
      </div>
</div>      
      
<div class="container">        
<!--/.Form Mail Hans -->
      <hr>
      <div class="media basic_backclr" style="padding:2px;">
        <div class="media-body" style="padding:10px">
                  
<!--  Mail Area -->
<?php 
           if(gu_login_check() && $_ArrMember[0]['email_yn'] == 1)
           {
           	include 'member-mail.php';
          }   
?>
<!--  END Mail Area -->
                  
    </div>
 </div>
<hr>

<?php get_footer(); ?>
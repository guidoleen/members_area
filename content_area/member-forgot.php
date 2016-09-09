<!-- LOGIN FORM -->
<?php
/* 
	Template Name: forgot
*/
	$usragent = $_SESSION['HTTP_USER_AGENT'] = md5($_SERVER['HTTP_USER_AGENT']);
	$forgttoken = $_SESSION['forgot_token'] = md5(uniqid(rand(0,5)));

	// get_header(); 
?>

<div class="container"><!--/.RIJEN Members Start -->
  	<hr>

  	<div class="row">
    <div class="col-md-12">
      <div class="media basic_backclr" style="padding:2px;">
        <div class="media-body" style="padding:10px">

         <a href=""><img class="img-circle" alt="" src="<?php echo content_url() . '/osm_uploads/hans.jpg'?>" style="width:100px; height: 100px; margin-top:3px;margin-left:5px"></a>
          <br>
          <label class="col-lg-2 control-label">

				<h3>Jouw password vergeten?</h3>
         		<p>
          		 Geef jouw email adres op en wij sturen jou een nieuw password toe:
         		</p>
          </label>  <br> <br> 
          
<!--/.Form Data Input -->
<form action="<?php $_SERVER['PHP_SELF'] ?>" name="forgotform" method="post" enctype="multipart/form-data" class="form-horizontal">
<div class="form-group">
 <label class="col-lg-2 control-label">Jouw Email</label>
 <div class="col-lg-10">
  <input type="text" class="form-control" id="forgotEmail" name="forgotEmail" value="Jouw Email">
 </div>
 </div>
              
  <br>
 
 <div class="form-group">
 <div class="col-lg-offset-2 col-lg-10">
 <input type="hidden" value="<?php echo $forgttoken ?>" name="forgttoken">
   <button type="submit" name="ButtForgotPassw" class="btn btn-danger">Verstuur nieuw password</button>
 </div>
 </div>
</form>

    </div>
 </div>
<hr>

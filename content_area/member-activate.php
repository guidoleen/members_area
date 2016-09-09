<?php
/*
	Template Name: memberactivate
*/
	get_header();
?>
<div class="container"><!--/.RIJEN Members Start -->
<hr>
  <div class="row">
    <div class="col-md-12">
      <div class="media basic_backclr" style="padding:2px;">
        <div class="media-body" style="padding:10px">

          <label class="col-lg-12 control-label">
	<h1 class="grey_color">Welkom en maak hier jouw members profiel verder af:</h1>
          </label>  <br> <br> 
          
<!--/.Form Data Input -->
                  
<form class="form-horizontal" role="form" method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>" enctype="multipart/form-data">
  
   <div class="form-group">
 <label class="col-lg-2 control-label">Email</label>
 <div class="col-lg-10">
  <input type="text" class="form-control" id="inputEmail" name="inputEmail" value="Jouw email">
 </div>
 </div>
  
 <div class="form-group">
 <label class="col-lg-2 control-label">Plak hier jouw code (die je via email hebt ontvangen)</label>
 <div class="col-lg-10">
   <input type="password" class="form-control" id="inputCode" name="inputCode" value="Jouw Code">
 </div>
 </div>
           
  <br>
 
 <div class="form-group">
 <div class="col-lg-10">
   <button type="submit" name="SubMNewStatus" id="SubMNewStatus" class="btn btn-danger">Maak profiel aan...</button>
 </div>
 </div>
</form>
                  
                  
    	</div>
	</div>
	</div>
 </div>
</div>
<hr>

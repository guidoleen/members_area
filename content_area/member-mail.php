<form class="form-horizontal" role="form" action="<?php echo ISVIPI_FUNCTIONS_BASE . 'fct_mail.php?mem=' . $IdMail?>" method="post" enctype="multipart/form-data">
  
   <div class="form-group">
     <label class="col-lg-2 control-label">Stuur <?php echo $_ArrMember[0]['user_name'] ?> een bericht:</label>
 <div class="col-lg-10">
  <input type="text" class="form-control" id="inputMijnMail" name="inputMijnMail" placeholder="Mijn Email adres">
 </div>
 </div>
  
 
 <div class="form-group">
   <label class="col-lg-2 control-label">Mijn bericht:</label>
 <div class="col-lg-10">
    <textarea class="form-control" rows="5" id="inputOmschr" placeholder="..zoiets van..." name="textmessage" id="textmessage"></textarea>
 </div>
 </div>

 
 <div class="form-group">
 <div class="col-lg-offset-2 col-lg-10">
   <button type="submit" class="btn btn-danger" name="SubMailing" id="SubMailing">Stuur bericht</button>
 </div>
 </div>
</form>
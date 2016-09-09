<!-- LOGIN FORM -->
<?php
/* 
	Template Name: login
*/
	$token = $_SESSION['tokenval'] = md5(uniqid(rand(0,15)));
	$usragent = $_SESSION['HTTP_USER_AGENT'] = md5($_SERVER['HTTP_USER_AGENT']);
	
	$nwusrtoken = $_SESSION['newusr_token'] = md5(uniqid(rand(0,5)));

	get_header(); 
?>
<br />
<div class="intro_text">
	<h1 class="grey_color">Hoi, log hier in om het members gedeelte binnen te komen: </h1><br /><br />
</div>

<div class="login_box">
	<div class="col-xs-12 well signup-div">
	<form action="<?php $_SERVER['PHP_SELF'] ?>" name="loginform" method="post" enctype="multipart/form-data">
		Email:<input type="text" name="emailval"><br />
		Password:<input type="password" name="pwdval"><br />
		<input type="hidden" name="tokenval" value=<?php echo $token ?>><br />
		<input type="hidden" name="usragentval" value=<?php echo $usragent ?>><br />
		<button type="submit" name="subLogin" class="btn btn-danger">login</button><br /><br />
	</form>
	<a href="<?php echo site_url() . '/forgot' ?>">Jouw password vergeten?</a>
	</div>
</div>

<!-- NEW User Form -->
<div class="login_box">
                    <div class="col-xs-12 well signup-div">
                        <legend><i class="fa fa-user-plus"></i> Nog geen lid?</legend>
                        <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post" class="form" role="form" name="NewUserForm"  id="NewUserForm">
                        <div class="row">
                            <div class="col-xs-6 col-md-6">
                                <input class="form-control" name="username" placeholder="Jouw naam*" type="text" />
                            </div>
                            <div class="col-xs-6 col-md-6">
                                <input class="form-control" name="lastname" placeholder="Achternaam*" type="text"/>
                            </div>
                        </div><br>
                        <input class="form-control" name="email" placeholder="Email*" type="email"/><br>
                        <input class="form-control" name="pwd" placeholder="Password*" type="password"/><br>
                        <input class="form-control" name="pwd2" placeholder="Re-enter Password*" type="password"/><br>
                    <div class="row">
                        <div class="col-xs-6 col-md-6">
                        	<input class="form-control" name="captcha" placeholder="captchaCode*" type="text"/>
                        </div>
                        <div class="col-xs-6 col-md-6">
                   		<img src="<?php echo plugins_url() . '/osm_newuser/fct_code_maker.php' ?>" >
                   	</div>
                    </div>
                       <!--  <div class="alert error nodisplay" id="error"></div> -->
                        <div class="alert success nodisplay" id="success"></div>

                     <div class="row">
                         <div class="col-xs-6 col-md-6">
                         <input type="hidden" name="newusr_token" value="<?php echo $nwusrtoken ?>">
                        	<button type="submit" name="submitNewUsr" class="btn btn-danger">Kom erbij!</button>
                       </div>
                       </div>
                      </form>
                    </div>
                </div>
  </div>
<!-- END NEW User Form -->
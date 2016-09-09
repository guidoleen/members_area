<?php
/**
 * The template for displaying the header
 *
 * Displays all of the head element and everything up until the "site-content" div.
 *
 * @package WordPress
 * @subpackage Twenty_Sixteen
 * @since Twenty Sixteen 1.0
 */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    	<meta charset="utf-8">
    	<meta property='og:locale' content='nl_NL'/>
	<meta http-equiv="language" content="NL">
    	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	
	<?php wp_head(); ?>
	
	<!-- core CSS -->
    	<!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css"> -->
	<link href="<?php echo get_template_directory_uri() . '/css/bootstrap.css'?>" rel="stylesheet">
    	<link href="<?php echo get_template_directory_uri() . '/css/osm.css'?>" rel="stylesheet">
    
    <!-- BOOTSTRAP -->
    <script src="<?php echo get_template_directory_uri() . '/js/jquery.js'?>"></script>
    <script src="<?php echo get_template_directory_uri() . '/js/bootstrap.js'?>"></script>
	
</head>

<body <?php body_class(); ?>>

<nav class="navbar navbar-default">
  <!-- <div class="container-fluid"> -->
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="<?php echo home_url() . '/'?>"><img src="<?php echo content_url() . '/osm_uploads/site/logobtween.png'?>" alt="InBetween"></a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
        <li><br></li>
        
        <?php
        ////////// SHOW the MEMBER ///////
        $strHoi = "";
	if(gu_login_check())
	{
		echo "<li><a href='" . site_url() .  "/?logout_mem=1'>Afmelden</a></li>";
		
		$ArrResult = show_start_member($_SESSION['Id']);
		$strHoi = "<a href='profile?memId=" . $_SESSION['Id']. "'>";
		$strHoi .= "Hoi: ". $ArrResult[0]['username'];
		$strHoi .= "<img src='" . content_url() . "/osm_uploads/imgusr/" . $ArrResult[0]['picture'] . "' alt='' class='img-circle memimg_sm'></a> &nbsp;";
	}
	else
	{
		echo "<li><a href='" . site_url() .  "/login/ '>Login</a></li>";
		$strHoi = "";
	}
	////////// END SHOW the MEMBER ///////
	?>
      </ul>
       <div class="gu_search">
      		<form class="navbar-form navbar-left" role="search" action="<?php echo home_url() . '/searchmembers/' ?>" method="post">
          		<input type="text" class="form-control" placeholder="Zoek een member" name="SearchInput">
          		<div class="btn-space">  </div>
          		<button type="submit" name="SearchMember" id="SearchMember" class="btn btn-default">Zoek Member</button> 
      		</form>
      		<form role="search" method="get" class="navbar-form navbar-left" action="<?php echo esc_url( home_url( '/' ) ); ?>">
			<input type="text" class="form-control" placeholder="Zoek algemeen" value="<?php echo get_search_query(); ?>" name="s" />
			<div class="btn-space">   </div>
	 		<button type="submit" class="btn btn-default"  value="Zoek">Zoek Algemeen</button>
	 	</form>
	</div>
	
      
    </div><!-- /.navbar-collapse -->
   <div id="hoi">
   	<div style="float:left;margin-top:6px;"><?php echo $strHoi ?></div>
   
   	<div class="menu_navigate">
   	
<!-- JQuery For Menu Open -->
  	 <script>
  	 var WINWIDT = 900;
  	 var MENUTOP = $("#hoi").height();
  	 
  	 $(window).load(function()
  	 {
  	 	if($(window).width() < WINWIDT)
  	 	{
  	 		var objHeadMenu = $("#site-header-menu");
  	 		objHeadMenu.hide();
  	 		objHeadMenu.css("top",MENUTOP);
  	 	}
  	 });
  	 
  	 $(document).ready(function()
  	 {
  	 	var objHeadMenu = $("#site-header-menu");
  	 	
  	 	$("#sub_menu_butt").click(function()
  	 	{
  	 		objHeadMenu.toggle();
  	 		$("#menu-toggle").show();
  	 	});
  	 	
  	 	window.onresize=function()
  	 	{
  	 		if( objHeadMenu.css("display") == "none" )
  	 		{
  	 			if ($(window).width() > WINWIDT) 
  	 			{
  	 				objHeadMenu.show();
				}
				else
				{
					objHeadMenu.hide();
				}
			}
		}
  	 });
  	 </script>
<!-- END JQuery For Menu Open -->
      
   	<?php if ( has_nav_menu( 'primary' ) ) : ?>
   				<button id="sub_menu_butt" class="dropdown-toggle" aria-expanded="false"></button>
					<!-- WP BUTTON <button id="menu-toggle" class="menu-toggle"><?php _e( 'Menu', 'twentysixteen' ); ?><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span></button> -->
					 <center>
					<div id="site-header-menu" class="site-header-menu">
						<?php if ( has_nav_menu( 'primary' ) ) : ?>
							<nav id="site-navigation" class="main-navigation" role="navigation">
								<?php
// 									wp_nav_menu( array(
// 										'theme_location' => 'primary'
// 									 ) );
									 
									 wp_nav_menu( array(
									 	'menu_class'     => 'primary-menu'
									 	) );

								?>
							</nav><!-- .main-navigation -->
						<?php endif; ?>
					</div><!-- .site-header-menu -->
					</center>
		<?php endif; ?>
		</div>
	</div>
</nav>
<!-- END NAVIGATION -->
		<div id="content" class="site-content">

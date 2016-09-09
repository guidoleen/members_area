<?php
/*
	 Template Name: errorpage
 */

	get_header(); 

$strErr = gu_show_error();
?>
	
<!-- NEW AREA -->
<center>

	<h1 style='color:#4e4e4e'><?php echo $strErr ?></h1><br>

	<a href="<?php echo 'javascript:history.go(-1)' ?>" class="btn btn-default"><< Terug </a>
	<a href="<?php echo site_url() ?>" class="btn btn-default">Naar Home </a><br><br>

</center>
<!-- END NEW AREA -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>

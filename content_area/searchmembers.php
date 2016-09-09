<?php
/*
	Template Name: searchmembers
*/
?>
<?php
	$rowBuildr = "";
	$findterm = "";

		if(isset($_POST['SearchMember']))
		{
			$findterm = $_POST['SearchInput'];
		}
		if(isset($_GET['id']))
		{
			$findterm = $_GET['id'];
		}
		if($findterm == "")
		{
			handlError("Helaas hebben we geen members kunnen vinden....");
			exit();
		}
		
		// Call the Plugin Function Search and this calls the cls_search.php
		$_rowBuildr = gu_wp_search_member($findterm);
?>
<?php
	get_header();
?>
<div class="container"><!--/.MEMBER Profile Start -->
<br>
<?php
	// echo the output
	echo $_rowBuildr;
?>

<?php get_footer(); ?>
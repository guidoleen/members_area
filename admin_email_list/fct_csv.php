<?php
// function get the title from id
function gu_get_theTitle($id)
{
	$page = get_post($id);
	return $page->post_title;
}
?>

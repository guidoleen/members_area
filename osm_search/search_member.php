<?php
/*
	Plugin Name: Search Member
	Author: Guido Leen
	Version: 1.0
	Plugin Uri: guidoleen.nl
*/

include  'cls_search.php';
include ABSPATH.'/wp-content/plugins/osm_cls/fct_functions.php';

function gu_wp_search_member($findterm)
{
	// See if class RowBuilder already exists
	if(!class_exists('rowBuilder'))
	{
		include(ABSPATH."/wp-content/plugins/osm_cls/cls_rowbuilder.php");
	}
			// Instantiëren van de resulaten
			$search = new gu_search();
			if(isset($_GET['alfa']))
			{
				// if Alfabet is selected
				$strresult = $search->find($findterm,0);
			}
			else
			{
				$strresult = $search->find($findterm,1);
			}
	
			// Row Builder 
			$rowBuild = new rowBuilder();
	
			if($strresult == "")
			{
				handlError("Helaas hebben we geen members kunnen vinden....");
				exit();
			}
			else
			{
				// Call 2nd encryption class
				$Encryption2 = new gu_encryption();
				// de resultaten van de search:
					for($i=0; $i<count($strresult); $i++)
					{
					if($strresult[$i]['linkedin'] == "")
    					{
    						$LinkIn = 0;
    					}
    					else
    					{
    						$LinkIn = 1;
    					}
    					
				// De rijBouwerPhp($name, $functie, $linkdin)
				$rowBuildr .= $rowBuild->rijBouwerPhp($Encryption2->encode($strresult[$i]['id']), $strresult[$i]['user_name'], $strresult[$i]['last_name'], $strresult[$i]['titel'], $strresult[$i]['photo'], $LinkIn) ;
			}
	}
	return $rowBuildr;
}
add_action('init', 'gu_wp_search_member');
?>

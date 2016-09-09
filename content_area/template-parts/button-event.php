<?php
	$EventToken = $_SESSION['event_token'] = md5(uniqid(rand(0,5)));
	$id_post = get_the_id();
?>

<form method="post" action="<?php $_SERVER['PHP_SELF'] ?>" enctype="multipart/form-data">
	<div class="form-group">
	<input type="hidden" id="event_token" name="event_token" value="<?php echo $EventToken ?>">
	<input type="hidden" id="event_id" name="event_id" value="<?php echo $id_post ?>">
          <button class="btn btn-danger" id="MemberEvent" name="subMemberEvent">Ik kom ook</button>
        </div>
</form>

<hr>

<?php
	$arrBuildMem = gu_showmembers_inevent($id_post);

	if($arrBuildMem == "")
	{
		exit();
	}
	else
	{
		echo "<div style='margin:10px'><h2>Wie gaan ook?</h2><ul class='ul-mem-sm'>";
		
		$icount = (count($arrBuildMem));
		$strBuild;
		for($i=0;$i<$icount;$i++)
		{
			echo build_members_items($arrBuildMem[$i]['user_id'],$arrBuildMem[$i]['user_photo'],$arrBuildMem[$i]['user_name']);
		}
		echo "</ul></div>";
	}
	
// Bouwer van Memebers 
$strbuildMem = "";		
function build_members_items($Id,$photo,$Name)
{
	$strbuildMem .= "<li><center><a href='member?memId=" . $Id ."'><img class='img-circle memimg_sm' alt='' src='";
	
		if($photo == "")
		{
			$strbuildMem .= content_url() . "/osm_uploads/imgusr/usr_0.jpg'";
		}
		else
		{
			$strbuildMem .= content_url() . "/osm_uploads/imgusr/" . $photo ."'";
		}
			
	$strbuildMem .= "'><div>" . $Name . "</div></a></center></li>";
	
	return $strbuildMem;
}
?>
<br/><br/><br/><br/>

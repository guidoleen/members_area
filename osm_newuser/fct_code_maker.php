<?php

	$code = codemaker();
	session_start();
		$_SESSION["code"]= $code;

	$im = imagecreatetruecolor(100, 24);
	$bg = imagecolorallocate($im, 92, 184, 92);
	$fg = imagecolorallocate($im, 255, 255, 255);
	imagefill($im, 0, 0, $bg);
	imagestring($im, 5, 5, 5, $code, $fg);
	
	imagepng($im);
	imagedestroy($im);
	

function codemaker()
{
	$code=rand(1000,9999);
	$strwords = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVW";
	
	$iresult = 0;
	for($i=0;$i<4;$i++)
	{
		$irand=rand(0,strlen($strwords)-1);
		$iresult .= char_at($strwords, (string)$irand);
	}
	return $code .$iresult;
}
	
function char_at($str, $pos)
{
 	return $str{$pos};
}
?>
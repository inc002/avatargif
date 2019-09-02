<?php
function scan_dir($folderGIF) {
	$ignored = array('.', '..', '.svn', '.htaccess');
	$files = array();
	$i=0;
	foreach (scandir($folderGIF) as $file) {
		if (in_array($file, $ignored)) continue;
		$files[$file] = filemtime($folderGIF.$file);
		$i++;
	}
	arsort($files);
	$files = array_keys($files);
	return ($files) ? $files : false;
}


function getLastGIF($files,$folderGIF,$limit=50){
	$returnGIF = "<h3>Last generated GIF with <3 by you</h3>";
	for($i=0;$i<count($files);$i++){
		$tabFileProp = stat($folderGIF.$files[$i]);
		$tabOrigin = explode('-',$files[$i]);
		$origin = '';
		if (isset($tabOrigin[1])){
			$origin = 'from '.$tabOrigin[1]." ";
		}
		$returnGIF .= '<div><img width="128" height="128" src="GIF/'.$files[$i].'"><br /><a href="GIF/'.$files[$i].'" target="_blank">Creation date : '.date ("d.m.Y à H:i:s", $tabFileProp['mtime']).' '.$origin.'</a></div>';
		if ($i==$limit){
			break;
		}
	}
	return $returnGIF;
}
?>

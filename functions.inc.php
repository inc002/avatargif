<?php
function scan_dir($folderGIF,$limit=50) {
	$ignored = array('.', '..', '.svn', '.htaccess');
	$files = array();
	$i=0;
	foreach (scandir($folderGIF) as $file) {
		if (in_array($file, $ignored)) continue;
		$files[$file] = filemtime($folderGIF.$file);
		$i++;
		if ($i==$limit){
			break;
		}
	}
	arsort($files);
	$files = array_keys($files);
	return ($files) ? $files : false;
}


function getLastGIF($files,$folderGIF){
	$returnGIF = "<h3>Last GIF generated with love by you</h3>";
	for($i=0;$i<count($files);$i++){
		$tabFileProp = stat($folderGIF.$files[$i]);
		$tabOrigin = explode('-',$files[$i]);
		$origin = '';
		if (isset($tabOrigin[1])){
			$origin = 'from '.$tabOrigin[1]." ";
		}
		$returnGIF .= '<div><img width="128" height="128" src="GIF/'.$files[$i].'"><br /><a href="GIF/'.$files[$i].'">Creation date : '.date ("d.m.Y à H:i:s", $tabFileProp['mtime']).' '.$origin.'</a></div>';
	}
	return $returnGIF;
}
?>

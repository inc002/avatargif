<?php

function pre($var){
	echo "<pre>";
	var_dump($var);
	echo "</pre>";
}

function writeLogGIF($txt,$filename='logGIF.txt'){
	$file = fopen($filename, "a") or die("Unable to open file!");
	fwrite($file, $txt."\n");
	fclose($file);
}

function readLogGIF($filename='logGIF.txt'){
	$file = fopen($filename, "r") or die("Unable to open file!");
	while(!feof($file)){
		$tabFile[] = fgets($file);
	}
	fclose($file);
	return $tabFile;
}

function accountFormat($account,$tabSN,$idSN){
	for ($i=0;$i<count($account);$i++){
		$listAccount['url'] .= '<a href="'.$tabSN[$idSN]['url'].$account[$i].'">@'.$account[$i].'</a> ';
		$listAccount['at'].= '@'.$account[$i].' ';
	}
	return $listAccount;
}

/*function scan_dir($folderGIF) {
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
}*/


function getLastGIF($tabLines,$limit=50){
	$returnGIF = "<h3>Last generated GIF with <3 by you</h3>";
	$tabFileRecent = array_reverse($tabLines);
	for($i=0;$i<count($tabFileRecent);$i++){
		$tabLines=explode('|',$tabFileRecent[$i]);
		$tabFileProp = stat(trim($tabLines[1]));
		$tabOrigin = explode('-',$tabLines[1]);
		$origin = '';
		if (isset($tabOrigin[1])){
			$origin = '<br />'.$tabLines[0].'<br />from '.$tabOrigin[1]."<hr />";
			$returnGIF .= '<div><img width="128" height="128" src="'.$tabLines[1].'"><br /><a href="'.$tabLines[1].'" target="_blank">'.date ("d.m.Y - H:i:s", $tabFileProp['mtime']).' '.$origin.'</a></div>';
		}
		if ($i==$limit){
			break;
		}
	}
	return $returnGIF;
}

function getWords($sentenceBrut){
	$sentencePlus = str_replace(' ','+',$sentenceBrut);
	$tabWordsBrut = explode('+',$sentencePlus);
	return $tabWordsBrut;
}



function getTop50TwitterAccount(){
	//wikitable sortable jquery-tablesorter
	$url = 'https://en.wikipedia.org/wiki/List_of_most-followed_Twitter_accounts';
	$html = file_get_html($url);
	if ($html){
		foreach($html->find('"table.wikitable sortable" tr') as $element){
			foreach($element->find('td') as $cell) {
				// push the cell's text to the array
				if (substr($cell->plaintext,0,1)==='@'){
					$tabTwittos[] = $cell->plaintext;
				} 
			}
		}
		return $tabTwittos;
	}
	return false;
}

function randomGIF($tabName){
	$nbRandom = rand(2,8);
	$nbTabName = count($tabName);	
	$string = '';
	for ($i=0;$i<$nbRandom;$i++){
		$valRandom = rand($i,$nbTabName);
		$string .= $tabName[$valRandom].' ';
	}
	return $string;
}


?>

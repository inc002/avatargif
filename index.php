<?php
include('resources/simple_html_dom.php');


// INIT 

//twitter : ProfileAvatar-image 
//telegram : tgme_page_photo_image

$tabSN = array ();
$tabSN[0]['name'] = 'twitter';
$tabSN[0]['class_img'] = 'ProfileAvatar-image';
$tabSN[0]['url'] = 'https://twitter.com/';
$tabSN[1]['name'] = 'telegram';
$tabSN[1]['class_img'] = 'tgme_page_photo_image';
$tabSN[1]['url'] = 'https://t.me/';
$folderGIF = 'GIF/';
$error = false;
if (!isset ($_GET['n']) OR empty($_GET['n']) OR !is_numeric($_GET['n']) OR $_GET['n']>1){
//choose ID of Social Network 
// 0 : twitter
// 1 : telegram
	$idSN = 0;
}else{
	$idSN = $_GET['n'];
}

function getWords($sentenceBrut){
	$sentencePlus = str_replace(' ','+',$sentenceBrut);
	$tabWordsBrut = explode('+',$sentencePlus);
	return $tabWordsBrut;
}

function pre($var){
	echo "<pre>";
	var_dump($var);
	echo "</pre>";
}

function printOut($urlGIF,$idSN,$tabSN){
	$out = 
		'<html>
			<header>
			</header>
			<body>
				<div align="center">
					<br /><a href="'.$urlGIF.'">Download GIF (right click : Save target as...)</a>
					<br /><br /><img src="'.$urlGIF.'">
					<br /><br />Images from '.$tabSN[$idSN]['name'].'
					</div>
			</body>
		</html>';
		return $out;
} 

$nameGIF = $folderGIF.md5($_GET['s'].$_GET['n']).".gif";
$startUrl = !empty($_SERVER['HTTPS']) ? 'https://' : 'http://' ;
$urlGIF = $startUrl.$_SERVER['SERVER_NAME'].'/avatargif/'.$nameGIF;

if (!file_exists($nameGIF)){
	//Get url of avatar pictures
	$resTabWords = getWords($_GET['s']);
	$nbWords = count($resTabWords);
	for ($i=0;$i<$nbWords;$i++){
		// Create DOM from URL or file
		$url = $tabSN[$idSN]['url'].$resTabWords[$i];
		if (!empty($resTabWords[$i])){
			$html = file_get_html($url);
			if ($html){
				foreach($html->find('img.'.$tabSN[$idSN]['class_img']) as $element){
					$frames[] = file_get_contents($element->src);
				}
			}
		}
	}
	//Create GIF
	$nbFrames = count($frames);
	if ($nbFrames > 1){
		include ('create_gif.php');
		$error = false;
	}else{
		echo "Error : Only one frame";
		$error = true;
	}
	
}
if (!$error) echo printOut($urlGIF,$idSN,$tabSN);







?>

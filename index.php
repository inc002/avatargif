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

function printOut($urlGIF,$idSN,$tabSN,$s,$error){
	$display = '	<br /><a href="'.$urlGIF.'">Download GIF (right click : Save target as...)</a>
					<br /><br /><img src="'.$urlGIF.'">
					<br /><br />Images from '.$tabSN[$idSN]['name'].
					'<br /><br /><a href="#" onclick="window.history.back();">Back</a>';
	
	if (empty($s) or !isset($s)){
		$display = '
		<h1>Create a GIF with avatars of social network people</h1>
		<h3>Just write some nicknames from social network, separated by spaces</h3>
		<form><input type="text" name="s" id="s"><br />
		<br/>
		<input type="radio" name="n" id="n0" value="0" checked>
		<label for="s1">Twitter</label>	
		<input type="radio" name="n" id="n1" value="1">
		<label for="s1">Telegram</label>
		<input type="submit"></form>'; 
	}
	if ($error){
		$display = $error;
	}
	$out = 
		'<html>
			<header>
			</header>
			<body>
				<div align="center">
				'.$display.'
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
	}elseif(isset($_GET['s'])){
		$error = "Error : No GIF available";
	}
	
}
echo printOut($urlGIF,$idSN,$tabSN,$_GET['s'],$error);







?>

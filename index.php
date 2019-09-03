<?php
/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/
include('resources/simple_html_dom.php');
include('functions.inc.php');

// INIT 

//twitter : ProfileAvatar-image 
//telegram : tgme_page_photo_image
//instagram : _6q-tv
//facebook : _1glk _6phc img

$tabSN = array ();
$tabSN[0]['name'] = 'twitter';
$tabSN[0]['class_img'] = 'ProfileAvatar-image';
$tabSN[0]['url'] = 'https://twitter.com/';
$tabSN[1]['name'] = 'telegram';
$tabSN[1]['class_img'] = 'tgme_page_photo_image';
$tabSN[1]['url'] = 'https://t.me/';
$tabSN[2]['name'] = 'instagram';
$tabSN[2]['class_img'] = '_6q-tv';
$tabSN[2]['url'] = 'https://www.instagram.com/';
$tabSN[3]['name'] = 'facebook';
$tabSN[3]['class_img'] = '_1glk _6phc img';
$tabSN[3]['url'] = 'https://www.facebook.com/public/';


$folderGIF = 'GIF/';
$error = false;
if (!isset ($_GET['n']) OR empty($_GET['n']) OR !is_numeric($_GET['n']) OR $_GET['n']>3){
//choose ID of Social Network 
// 0 : twitter
// 1 : telegram
	$idSN = 0;
}else{
	$idSN = $_GET['n'];
}

//pre(randomGIF(getTop50TwitterAccount()));
if (isset($_GET['r']) AND is_numeric($_GET['r']))	{
	$_GET['s'] = randomGIF(getTop50TwitterAccount());
	$_GET['n'] = $_GET['r'];
}


function printOut($urlGIF,$idSN,$tabSN,$s,$listAccount,$error,$folderGIF,$nameGIF){
	$tabFileListGIF = readLogGIF();
	//pre($tabFileListGIF);
	$lastGIF = getLastGIF($tabFileListGIF);
	
	$tabListAccount = accountFormat($listAccount,$tabSN,$idSN);
	
	if (empty($listAccount)){	
		$account = explode(' ',$s);
	}else{
		$account = $listAccount;
	}
	
	
		//
		//$listAccount = '@'.$listAccount;
	$text= 'This GIF has been generated with #PP of '.$tabListAccount['at'].'from '.$tabSN[$idSN]['name'];
	$shareTwitter = '<a href="https://twitter.com/intent/tweet?url='.urlencode($urlGIF).'&text='.urlencode($text).'&via=avatargif">Share on twitter</a>';
	$shareFacebook = '<a href="https://www.facebook.com/sharer/sharer.php?u='.urlencode($urlGIF).'">Share on facebook</a>';
	/*
	 * <div class="fb-share-button" data-href="https://murcier.fr/avatargif/GIF/330cf88fbca6c47efeff83bff0baf3a1-twitter-.gif" 
	 * data-layout="button_count" data-size="small"><a target="_blank" 
	 * href="https://www.facebook.com/sharer/sharer.php?u=https%3A%2F%2Fmurcier.fr%2Favatargif%2FGIF%2F330cf88fbca6c47efeff83bff0baf3a1-twitter-.gif&amp;src=sdkpreparse" class="fb-xfbml-parse-ignore">Partager</a></div>
	 * 
	 * */
	
	$display = '	<br /><a href="'.$urlGIF.'">Download GIF (right click : Save target as...)</a>
					<br /><br /><img src="'.$urlGIF.'">
					<br /><br />Images from '.$tabSN[$idSN]['name'].
					'<br /><br />Avatar of : '.$tabListAccount['url'].'&nbsp;
					<br /><br />'.$shareTwitter.' '.$shareFacebook.'
					<br /><br /><a href="#" onclick="window.history.back();">Back</a>&nbsp;<a href="index.php">Home</a>';
	
	if (empty($s) or !isset($s)){
		$display = '
		<h1>Create a GIF with avatars of social network people</h1>
		<h3>Just write some nicknames from social network, separated by space</h3>
		<form><input type="text" name="s" id="s" size="30" placeholder="madonna bieber trudeau shakira"><br />
		<br/>
		<input type="radio" name="n" id="n0" value="0" checked>
		<label for="s1">Twitter</label>	
		<input type="radio" name="n" id="n1" value="1">
		<label for="s1">Telegram</label>
		<input type="radio" name="n" id="n2" value="2">
		<label for="s1">Instagram (slow...)</label>
		<!-- <input type="radio" name="n" id="n3" value="3">
		<label for="s1">Facebook</label> -->
		<br />
		<br />
		<input type="submit"></form>
		<form>
		<a href="?r=0">Random Top 50 twitter account</a>
		</form>
		'; 
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
				<div align="center">
				'.$lastGIF.'
				</div>
			</body>
		</html>';
	
		return $out;
} 

$nameGIF = $folderGIF.md5($_GET['s'].$_GET['n'])."-".$tabSN[$_GET['n']]['name']."-.gif";
$startUrl = !empty($_SERVER['HTTPS']) ? 'https://' : 'http://' ;
$urlGIF = $startUrl.$_SERVER['SERVER_NAME'].'/avatargif/'.$nameGIF;
	



if (!file_exists($nameGIF)){
	//Get url of avatar pictures
	$resTabWords = getWords($_GET['s']);
	$nbWords = count($resTabWords);
	$listAccount = array();
	for ($i=0;$i<$nbWords;$i++){
		// Create DOM from URL or file
		$url = $tabSN[$idSN]['url'].$resTabWords[$i].'';
		//pre($url);
		if (!empty($resTabWords[$i])){
			$html = file_get_html($url);
			//pre($html);
			if ($html){
				$resTabWords[$i] = str_replace('@','',$resTabWords[$i]);
				$listAccount[] = $resTabWords[$i];
				if ($idSN == 2){
					//meta property="og:image"
					foreach($html->find('meta[property=og:image]') as $element){
						$frames[] = file_get_contents($element->content);
					}
				}
				//facebook
				if ($idSN == 3){
					foreach($html->find('div.clearfix') as $element){
						$frames[] = file_get_contents($element->href);
					}
					pre($frames);
				}else{
					foreach($html->find('img.'.$tabSN[$idSN]['class_img']) as $element){
						$frames[] = file_get_contents($element->src);
					}
				}
			}
		}
	}
	//Create GIF
	//pre($listAccount); 
	$nbFrames = count($frames);
	if ($nbFrames > 1){
		include ('create_gif.php');
		$error = false;
		
	}elseif(isset($_GET['s'])){
		$error = 'Error : No GIF available<br /><br /><a href="#" onclick="window.history.back();">Back</a>';
	}
	
}
//pre($idSN);
if (isset($nameGIF) AND !empty($listAccount) AND isset($tabSN)){
	//backup pp used for this GIF in file
	$tabAccount = accountFormat($listAccount,$tabSN,$idSN);
	writeLogGIF($tabAccount['at']."| ".$nameGIF);
}
echo printOut($urlGIF,$idSN,$tabSN,$_GET['s'],$listAccount,$error,$folderGIF,$nameGIF);







?>
